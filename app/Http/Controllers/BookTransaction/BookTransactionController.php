<?php

namespace App\Http\Controllers\BookTransaction;

use App\Models\Book\Book;
use App\Models\BookStockBatch;
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
                    return [
                        'book_id' => $item['book_id'],
                        'semester_id' => $semesterId,
                        'purchase_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'remaining_quantity' => $item['quantity'],
                        'mutation_percentage' => $item['mutation_percentage'],
                        'return_percentage' => $item['return_percentage'],
                        'max_mutation_quantity' => $item['mutation_percentage'] / 100 * $item['quantity'],
                        'max_return_quantity' => $item['return_percentage'] / 100 * $item['quantity'],
                        'used_return_quantity' => 0,
                        'used_mutation_quantity' => 0,
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
}
