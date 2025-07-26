<?php

namespace App\Http\Controllers\Transaction;

use App\Models\BookTransaction\TransactionType;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;

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
}
