<?php

namespace App\Http\Controllers\Book;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use Illuminate\Routing\Controller;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);

        $books = Book::query()->paginate(10);

        return view('book.books', compact('books'));
    }
}
