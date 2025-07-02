<?php

namespace App\Http\Controllers\Book;

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

        $categories = Category::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()
            ->paginate(10)->appends($request->query());

        return view('book.books', compact('categories'));
    }
}
