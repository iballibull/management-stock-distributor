<?php

namespace App\Http\Controllers\Book;

use App\Models\Book\Book;
use Exception;
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
            ->sortable()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()
            ->paginate(10)->appends($request->query());

        return view('book.category', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            // Cek apakah nama sudah digunakan (termasuk yang soft deleted)
            $existing = Category::withTrashed()
                ->where('name', 'LIKE', '%' . $request->name . '%')
                ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    // Restore dan update waktu
                    $existing->restore();
                    $existing->update([
                        'name' => $request->name,
                        'created_at' => now()
                    ]);
                } else {
                    // Sudah ada dan aktif
                    throw new \Exception('Nama kategori sudah digunakan.');
                }
            } else {
                // Data benar-benar baru
                Category::create(['name' => $request->name]);
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

            // Cek apakah nama sudah digunakan oleh kategori lain
            $existingCategory = Category::withTrashed()
                ->where('name', $request->name)
                ->where('id', '!=', $categoryId)
                ->first();

            if ($existingCategory) {
                if ($existingCategory->deleted_at) {
                    // Jika ada kategori yang soft deleted dengan nama yang sama
                    // Pindahkan semua books ke kategori yang sudah ada (restore)
                    Book::where('category_id', $category->id)
                        ->update(['category_id' => $existingCategory->id]);

                    // Restore kategori yang sudah ada
                    $existingCategory->restore();

                    // Hapus kategori yang sedang di-update
                    $category->delete();

                    return back()->with('success', 'Kategori berhasil diupdate dan digabung dengan kategori yang sudah ada');
                } else {
                    // Jika ada kategori aktif dengan nama yang sama
                    throw new Exception('Nama kategori sudah digunakan oleh kategori lain yang aktif.');
                }
            } else {
                // Jika tidak ada konflik nama, update normal
                $category->update([
                    'name' => $request->name
                ]);

                return back()->with('success', 'Kategori berhasil diupdate');
            }
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal mengupdate kategori: ' . $th->getMessage());
        }
    }
}
