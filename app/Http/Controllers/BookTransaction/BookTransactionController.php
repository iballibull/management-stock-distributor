<?php

namespace App\Http\Controllers\BookTransaction;

use App\Models\Book\Book;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\BookStock\BookStockBatch;
use App\Models\BookTransaction\Semester;
use App\Models\BookTransaction\BookTransaction;
use App\Models\BookTransaction\BookTransactionItem;
use App\Http\Requests\BookTransaction\BookStockInRequest;

class BookTransactionController extends Controller
{
    public function createIn()
    {
        $semesters = Semester::orderBy('name', 'desc')
            ->pluck('name', 'id');

        $books = Book::with(['category', 'educationLevel', 'curriculum'])
            ->orderBy('title')
            ->get();

        return view('book-transaction.book-in-create', compact('semesters', 'books'));
    }

    public function storeIn(BookStockInRequest $request)
    {
        $request->validated();
        $semesterId = $request->input('semester_id');
        $books = $request->input('books', []);

        try {
            DB::beginTransaction();

            $bookItems = [];
            $now = now();
            $user = auth()->user();

            $totalQuantity = collect($books)->sum('quantity');
            $totalValue = collect($books)->sum(function ($book) {
                return $book['quantity'] * $book['unit_price'];
            });

            $bookTransaction = BookTransaction::create([
                'user_id' => $user->id,
                'semester_id' => $semesterId,
                'transaction_type_id' => 1,  // untuk buku kedatangan (buku masuk)
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
                'status' => $user->role_id == 1 ? 'approved' : 'pending',
                'approved_by' => $user->role_id == 1 ? $user->id : null,
                'approved_at' => $user->role_id == 1 ? $now : null,
                'rejection_reason' => null,
            ]);

            foreach ($books as $book) {
                $bookId = $book['book_id'];
                $quantity = $book['quantity'];
                $unitPrice = $book['unit_price'];
                $totalPrice = $quantity * $unitPrice;
                $returnPercentage = $book['return_percentage'];

                $bookItems[] = [
                    'book_transaction_id' => $bookTransaction->id,
                    'book_id' => $bookId,
                    'book_stock_batch_id' => null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'return_percentage' => $returnPercentage,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            BookTransactionItem::insert($bookItems);

            if ($bookTransaction->status === 'approved') {
                $bookItems = collect($bookItems)->map(function ($item) use ($semesterId, $now) {
                    $maxReturn = $item['return_percentage'] / 100 * $item['quantity'];

                    return [
                        'book_id' => $item['book_id'],
                        'semester_id' => $semesterId,
                        'purchase_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'remaining_quantity' => $item['quantity'],
                        'return_percentage' => $item['return_percentage'],
                        'max_return_quantity' => $maxReturn,
                        'remaining_return_quantity' => $maxReturn,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                });

                BookStockBatch::insert($bookItems->toArray());
            }

            DB::commit();
            return back()->with('success', 'Stok buku berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('failed', 'Gagal menambahkan stok buku: ' . $th->getMessage());
        }
    }

    public function cancel($transactionId)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Cari transaksi berdasarkan ID dan user yang login
            $transaction = BookTransaction::with(['transactionType', 'bookTransactionItems'])
                ->where('user_id', $user->id)
                ->findOrFail($transactionId);

            // Validasi apakah user dapat membatalkan transaksi ini
            if (($user->role->name === 'Sales' || $user->role->name === 'Admin') && $transaction->user_id !== $user->id) {
                abort(403, 'Anda hanya dapat membatalkan transaksi Anda sendiri.');
            }

            // Validasi bahwa transaksi dapat dibatalkan
            if (in_array($transaction->status, ['approved', 'rejected', 'cancelled'])) {
                throw new \Exception('Hanya transaksi dengan status menunggu yang dapat dibatalkan.');
            }

            // Update status transaksi menjadi cancelled
            $transaction->update([
                'status' => 'cancelled',
            ]);

            // Jika transaksi adalah PENGAMBILAN rollback stok
            if ($transaction->transactionType->name === "PENGAMBILAN") {

                // Loop melalui setiap item transaksi
                foreach ($transaction->bookTransactionItems as $transactionItem) {

                    // Kembalikan quantity ke stock batch yang sesuai
                    BookStockBatch::where('id', $transactionItem->book_stock_batch_id)
                        ->increment('remaining_quantity', $transactionItem->quantity);
                }
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return back()->with('success', "Transaksi #{$transaction->id} berhasil dibatalkan dan stok telah dikembalikan.");

        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            return back()->with('failed', 'Terjadi kesalahan saat membatalkan transaksi: ' . $th->getMessage());
        }
    }

    public function reject(Request $request, $transactionId)
    {
        // Mulai transaksi database untuk memastikan atomicity
        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Cari transaksi berdasarkan ID dengan eager loading
            $transaction = BookTransaction::with(['transactionType', 'bookTransactionItems'])
                ->findOrFail($transactionId);

            // Validasi bahwa transaksi dapat ditolak
            if (in_array($transaction->status, ['approved', 'rejected', 'cancelled'])) {
                throw new \Exception('Hanya transaksi dengan status menunggu yang dapat ditolak.');
            }

            // Validasi input rejection reason
            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            // Update status transaksi menjadi rejected
            $transaction->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now(),
                'rejected_by' => $user->id,
            ]);

            // Jika transaksi adalah PENGAMBILAN, rollback stok ke batch yang tepat
            if ($transaction->transactionType->name === "PENGAMBILAN") {

                // Loop melalui setiap item transaksi
                foreach ($transaction->bookTransactionItems as $transactionItem) {

                    // Kembalikan quantity ke stock batch yang sesuai
                    BookStockBatch::where('id', $transactionItem->book_stock_batch_id)
                        ->increment('remaining_quantity', $transactionItem->quantity);
                }
            }


            // Commit transaksi jika semua berhasil
            DB::commit();

            return back()->with('success', "Transaksi #{$transaction->id} berhasil ditolak dan stok telah dikembalikan.");

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            return back()->with('failed', 'Terjadi kesalahan saat menolak transaksi: ' . $e->getMessage());
        }
    }

    public function approve($transactionId)
    {
        try {
            DB::beginTransaction();

            $bookTransaction = BookTransaction::findOrFail($transactionId);

            $now = now();
            $user = auth()->user();

            $bookTransaction->update([
                'approved_by' => $user->id,
                'approved_at' => $now,
                'status' => 'approved',
            ]);

            if ($bookTransaction->transaction_type_id !== 2) { // Jika bukan PENGAMBILAN

                $bookItems = collect($bookTransaction->bookTransactionItems)->map(function ($item) use ($bookTransaction, $now) {
                    $maxReturn = $item['return_percentage'] / 100 * $item['quantity'];

                    return [
                        'book_id' => $item['book_id'],
                        'semester_id' => $bookTransaction->semester_id,
                        'purchase_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'remaining_quantity' => $item['quantity'],
                        'return_percentage' => $item['return_percentage'],
                        'max_return_quantity' => $maxReturn,
                        'remaining_return_quantity' => $maxReturn,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                    ;
                });

                BookStockBatch::insert($bookItems->toArray());

                DB::commit();
                return back()->with('success', 'Transaksi berhasil di setujui dan stok buku berhasil ditambahkan.');
            } else {
                $totalPurchase = BookTransactionItem::where('book_transaction_id', $bookTransaction->id)
                    ->join('book_stock_batches', 'book_transaction_items.book_stock_batch_id', '=', 'book_stock_batches.id')
                    ->sum(DB::raw('book_transaction_items.quantity * book_stock_batches.purchase_price'));

                $totalValue = $bookTransaction->total_value;
                $profitAmount = $totalValue - $totalPurchase;

                Transaction::create([
                    'book_transaction_id' => $bookTransaction->id,
                    'user_id' => $bookTransaction->user_id,
                    'total_amount' => $totalValue,
                    'status' => 'UNPAID',
                    'remaining_amount' => $totalValue,
                    'amount_paid' => 0,
                    'profit_amount' => $profitAmount,
                ]);


                DB::commit();
                return back()->with('success', 'Transaksi berhasil di setujui.');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('failed', 'Gagal menyetujui: ' . $th->getMessage());
        }
    }
}
