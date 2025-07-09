<?php

namespace App\Http\Controllers\BookTransaction;

use App\Http\Requests\BookTransaction\BookStockInRequest;
use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookTransaction\Semester;

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

        return back()->with('success', 'Stok buku berhasil ditambahkan.');
    }
}
