<?php

namespace App\Http\Controllers\Transaction;

use DB;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Transaction\Payment;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use Illuminate\Validation\ValidationException;
use App\Models\BookTransaction\TransactionType;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'transaction_type' => 'nullable|exists:transaction_types,id',
            'status' => 'nullable|in:UNPAID,PAID,INSTALLMENT',
        ]);

        $roleId = auth()->user()->role_id;

        // Filter berdasarkan input parameter
        $query = Transaction::query();
        if ($request->filled('user_id') && $roleId == 2) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('transaction_type')) {
            $query->whereHas('bookTransaction', function ($q) use ($request) {
                $q->where('transaction_type_id', $request->input('transaction_type'));
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Hanya admin yang bisa melihat semua pembayaran
        // Sisanya hanya bisa melihat pembayaran mereka sendiri
        if ($roleId != 2) {
            $query->where('user_id', auth()->id());
        }

        // Sorting
        $query->sortable(['created_at' => 'desc']);

        // Total pembayaran
        $totalPayment = $query->sum('total_amount');
        $totalRemainingPaid = $query->sum('remaining_amount');
        $totalPaid = $query->sum('amount_paid');

        // Paginasi
        $transactions = $query->with(['bookTransaction.transactionType', 'user'])
            ->paginate(10)
            ->withQueryString();

        $transactionTypes = TransactionType::where('name', '!=', 'KEDATANGAN')->pluck('name', 'id');

        $status = [
            'UNPAID' => 'BELUM DIBAYAR',
            'PAID' => 'SUDAH DIBAYAR',
            'INSTALLMENT' => 'DI CICIL'
        ];

        $users = User::withTrashed()->where('role_id', '!=', '2')->pluck('name', 'id');

        return view('transaction.payment', compact('transactions', 'users', 'transactionTypes', 'status', 'roleId', 'totalPayment', 'totalRemainingPaid', 'totalPaid'));
    }

    public function detail($transactionId)
    {
        $roleId = auth()->user()->role_id;

        // Build query untuk transaction detail
        $query = Transaction::with(['payments', 'bookTransaction.transactionType', 'user']);

        // Jika bukan admin, hanya bisa lihat transaksi sendiri
        if ($roleId != 2) {
            $query->where('user_id', auth()->id());
        }

        // Find transaction
        $transaction = $query->findOrFail($transactionId);

        return view('transaction.payment-detail', compact('transaction'));
    }

    public function store(Request $request, $transactionId)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:CASH,TRANSFER',
                'notes' => 'nullable|string|max:255',
            ]);

            // Cek apakah transaksi ada dan user berhak mengaksesnya
            $transaction = Transaction::findOrFail($transactionId);
            $remainingAmount = $transaction->remaining_amount;

            if ($remainingAmount <= 0 || $remainingAmount < $request->input('amount')) {
                throw ValidationException::withMessages([
                    'amount' => ['Jumlah pembayaran tidak valid.'],
                ]);
            }

            DB::beginTransaction();
            // Buat pembayaran baru
            Payment::insert([
                'transaction_id' => $transaction->id,
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method'),
                'notes' => $request->input('notes'),
                'payment_date' => now(),
                'validate_by' => auth()->id(),
            ]);

            $transaction->update([
                'status' => $remainingAmount - $request->input('amount') == 0 ? 'PAID' : 'INSTALLMENT',
                'amount_paid' => $transaction->amount_paid + $request->input('amount'),
                'remaining_amount' => max(0, $transaction->remaining_amount - $request->input('amount')),
            ]);

            DB::commit();

            return redirect()->route('payment.detail', $transaction->id)->with('success', 'Pembayaran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('payment.detail', $transaction->id)->with('failed', 'Terjadi kesalahan saat menambahkan pembayaran.');
        }
    }

    public function update(Request $request, $paymentId)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:CASH,TRANSFER',
                'notes' => 'nullable|string|max:255',
            ]);

            // Temukan pembayaran berdasarkan ID
            $payment = Payment::findOrFail($paymentId);
            $transaction = $payment->transaction;

            $amountPaid = $request->input('amount');
            $remainingAmount = $transaction->remaining_amount + $payment->amount;

            // Validasi jumlah pembayaran
            if ($amountPaid < 0 || $amountPaid > $remainingAmount) {
                throw ValidationException::withMessages([
                    'amount' => ['Jumlah pembayaran tidak valid.'],
                ]);
            }

            DB::beginTransaction();
            // Update pembayaran
            $payment->update([
                'amount' => $amountPaid,
                'payment_method' => $request->input('payment_method'),
                'notes' => $request->input('notes'),
                'validate_by' => auth()->user()->id,
            ]);

            $amountPaidTransaction = $transaction->payments->sum('amount');
            $remainingAmount = $transaction->total_amount - $amountPaidTransaction;

            // Update transaksi
            $transaction->update([
                'amount_paid' => $amountPaidTransaction,
                'remaining_amount' => $remainingAmount,
                'status' => $remainingAmount == 0 ? 'PAID' : 'INSTALLMENT',
            ]);

            DB::commit();

            return redirect()->route('payment.detail', $transaction->id)->with('success', 'Pembayaran berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('payment.detail', $transaction->id)->with('failed', 'Terjadi kesalahan saat mengupdate pembayaran: ' . $th->getMessage());
        }
    }

    public function destroy($paymentId)
    {
        try {
            // Temukan pembayaran berdasarkan ID
            $payment = Payment::findOrFail($paymentId);
            $transaction = $payment->transaction;

            DB::beginTransaction();

            $remainingAmount = $transaction->remaining_amount + $payment->amount;
            $amountPaid = $transaction->amount_paid - $payment->amount;

            // Hapus pembayaran
            $payment->delete();

            $status = match (true) {
                $remainingAmount == 0 => 'PAID',
                $remainingAmount == $transaction->total_amount => 'UNPAID',
                default => 'INSTALLMENT',
            };

            // Update transaksi
            $transaction->update([
                'amount_paid' => $amountPaid,
                'remaining_amount' => $remainingAmount,
                'status' => $status,
            ]);

            DB::commit();

            return redirect()->route('payment.detail', $transaction->id)->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('payment.detail', $transaction->id)->with('failed', 'Terjadi kesalahan saat menghapus pembayaran: ' . $th->getMessage());
        }
    }
}
