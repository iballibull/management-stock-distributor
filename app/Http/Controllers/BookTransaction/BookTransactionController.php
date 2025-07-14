<?php

namespace App\Http\Controllers\BookTransaction;

use App\Models\Book\Book;
use App\Models\BookStock\BookStockBatch;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
                $mutationPercentage = $book['mutation_percentage'];
                $returnPercentage = $book['return_percentage'];

                $bookItems[] = [
                    'book_transaction_id' => $bookTransaction->id,
                    'book_id' => $bookId,
                    'book_stock_batch_id' => null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'mutation_percentage' => $mutationPercentage,
                    'return_percentage' => $returnPercentage,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            BookTransactionItem::insert($bookItems);

            if ($bookTransaction->status === 'approved') {
                $bookItems = collect($bookItems)->map(function ($item) use ($semesterId, $now) {
                    $maxReturn = $item['return_percentage'] / 100 * $item['quantity'];
                    $maxMutation = $item['mutation_percentage'] / 100 * $item['quantity'];

                    return [
                        'book_id' => $item['book_id'],
                        'semester_id' => $semesterId,
                        'purchase_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'remaining_quantity' => $item['quantity'],
                        'mutation_percentage' => $item['mutation_percentage'],
                        'return_percentage' => $item['return_percentage'],
                        'max_mutation_quantity' => $maxMutation,
                        'max_return_quantity' => $maxReturn,
                        'remaining_return_quantity' => $maxReturn,
                        'remaining_mutation_quantity' => $maxReturn,
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

    // TODO: Jika itu adalah PENGAMBILAN maka rollback stok
    public function cancel($transactionId)
    {
        try {
            $user = auth()->user();
            $transaction = BookTransaction::where('user_id', $user->id)->findOrFail($transactionId);

            // Check if user can cancel this transaction
            if (($user->role->name === 'Sales' || $user->role->name === 'Admin') && $transaction->user_id !== $user->id) {
                abort(403, 'Anda hanya dapat membatalkan transaksi Anda sendiri.');
            }

            // Validate that transaction can be cancelled
            if (in_array($transaction->status, ['approved', 'rejected', 'cancelled'])) {
                throw new \Exception('Hanya transaksi dengan status menunggu yang dapat dibatalkan.');
            }

            $transaction->update([
                'status' => 'cancelled',
            ]);

            return back()->with('success', "Transaksi #{$transaction->id} berhasil dibatalkan.");
        } catch (\Throwable $th) {
            return back()->with('failed', 'Terjadi kesalahan saat membatalkan transaksi: ' . $th->getMessage());
        }

    }

    // TODO: Jika itu adalah PENGAMBILAN maka rollback stok
    public function reject(Request $request, $transactionId)
    {
        try {
            $transaction = BookTransaction::findOrFail($transactionId);

            // Validate that transaction can be rejected
            if (in_array($transaction->status, ['approved', 'rejected', 'cancelled'])) {
                throw new \Exception('Hanya transaksi dengan status menunggu yang dapat ditolak.');
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $transaction->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            return back()->with('success', "Transaksi #{$transaction->id} berhasil ditolak.");

        } catch (\Exception $e) {
            return back()->with('failed', 'Terjadi kesalahan saat menolak transaksi: ' . $e->getMessage());
        }
    }

    public function approveIn($transactionId)
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

            $bookItems = collect($bookTransaction->bookTransactionItems)->map(function ($item) use ($bookTransaction, $now) {
                $maxReturn = $item['return_percentage'] / 100 * $item['quantity'];
                $maxMutation = $item['mutation_percentage'] / 100 * $item['quantity'];

                return [
                    'book_id' => $item['book_id'],
                    'semester_id' => $bookTransaction->semester_id,
                    'purchase_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'remaining_quantity' => $item['quantity'],
                    'mutation_percentage' => $item['mutation_percentage'],
                    'return_percentage' => $item['return_percentage'],
                    'max_mutation_quantity' => $maxMutation,
                    'max_return_quantity' => $maxReturn,
                    'remaining_return_quantity' => $maxReturn,
                    'remaining_mutation_quantity' => $maxReturn,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
                ;
            });

            BookStockBatch::insert($bookItems->toArray());

            DB::commit();
            return back()->with('success', 'Transaksi berhasil di setujui dan stok buku berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('failed', 'Gagal menyetujui: ' . $th->getMessage());
        }
    }
}
