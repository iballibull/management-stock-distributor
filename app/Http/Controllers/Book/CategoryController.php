<?php

namespace App\Http\Controllers\Book;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
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
            ->paginate(10);

        return view('book.category', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $name = $request->input('name');
            $upperName = Str::upper($name);

            // Cari termasuk yang soft deleted
            $existing = Category::withTrashed()->where('name', $upperName)->first();

            // Pulihkan jika data ada sebelumnya
            if ($existing) {
                $existing->restore();
                $existing->update([
                    'name' => $upperName,
                    'created_at' => now()
                ]);
            } else {
                Category::create(['name' => $upperName]);
            }

            return back()->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menambah kategori: ' . $th->getMessage());
        }
    }

    public function destroy($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $category->delete();

            return back()->with('success', 'Kategori berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menghapus kategori: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $categoryId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $category = Category::findOrFail($categoryId);

            $category->update([
                'name' => Str::upper($request->input('name'))
            ]);

            return back()->with('success', 'Kategori berhasil diupdate');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal mengupdate kategori: ' . $th->getMessage());
        }
    }
}
