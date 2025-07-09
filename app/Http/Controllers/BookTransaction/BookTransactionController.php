<?php

namespace App\Http\Controllers\BookTransaction;

use App\Models\Book\Book;
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

            $now = now();
            $uuids = [];
            $itemData = [];

            // 1. Siapkan data untuk bulk insert dengan UUID
            foreach ($books as $data) {
                if ($data['quantity'] > 0 && $data['unit_price'] > 0) {
                    $uuid = Str::uuid();
                    $uuids[] = $uuid;

                    $itemData[] = [
                        'uuid' => $uuid,
                        'book_stock_batch_id' => null,
                        'quantity' => $data['quantity'],
                        'unit_price' => $data['unit_price'],
                        'total_price' => $data['quantity'] * $data['unit_price'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // 2. Insert semua item sekaligus
            BookTransactionItem::insert($itemData);

            // 3. Ambil kembali berdasarkan UUID (anti bentrok)
            $insertedItems = BookTransactionItem::whereIn('uuid', $uuids)
                ->orderBy('created_at')
                ->get();

            // 4. Siapkan data transaksi
            $transactions = [];
            foreach ($insertedItems as $item) {
                $transactions[] = [
                    'user_id' => auth()->id(),
                    'semester_id' => $semesterId,
                    'transaction_type_id' => 1,
                    'book_transaction_item_id' => $item->id,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 5. Insert transaksi sekaligus
            BookTransaction::insert($transactions);

            DB::commit();
            return back()->with('success', 'Stok buku berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan stok buku: ' . $th->getMessage());
        }
    }
}
