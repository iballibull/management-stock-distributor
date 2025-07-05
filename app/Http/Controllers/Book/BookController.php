<?php

namespace App\Http\Controllers\Book;

use App\Models\Book\Book;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use App\Models\Book\EducationLevel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk dropdown
        $categories = Category::withTrashed()->pluck('name', 'id');
        $curriculums = Curriculum::withTrashed()->pluck('name', 'id');
        $educationLevels = EducationLevel::withTrashed()->pluck('name', 'id');

        // Query builder untuk books
        $query = Book::with([
            'category:id,name',
            'curriculum:id,name',
            'educationLevel:id,name'
        ])->sortable();

        // Search
        if ($request->input('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori 
        if ($request->input('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan kurikulum
        if ($request->input('curriculum_id')) {
            $query->where('curriculum_id', $request->curriculum_id);
        }

        // Filter berdasarkan tingkat pendidikan
        if ($request->input('education_level_id')) {
            $query->where('education_level_id', $request->education_level_id);
        }

        // Filter berdasarkan harga
        if ($request->input('price')) {
            $query->where('price', '<=', $request->price);
        }

        // Filter berdasarkan kelas
        if ($request->input('grade_number')) {
            $query->where('grade_number', 'like', '%' . $request->grade_number . '%');
        }

        // Filter berdasarkan semester
        if ($request->input('semester')) {
            $query->where('semester', $request->semester);
        }

        // Pagination
        $books = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('book.books', compact(
            'books',
            'categories',
            'curriculums',
            'educationLevels'
        ));
    }

    public function create(Request $request)
    {
        $categories = Category::pluck('name', 'id');
        $curriculums = Curriculum::pluck('name', 'id');
        $educationLevels = EducationLevel::pluck('name', 'id');


        return view('book.create-books', compact('categories', 'curriculums', 'educationLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:288',
                Rule::unique('books')->where(function ($query) use ($request) {
                    return $query
                        ->where('title', 'like', '%' . $request->title . '%')
                        ->where('category_id', $request->category_id)
                        ->where('education_level_id', $request->education_level_id)
                        ->where('curriculum_id', $request->curriculum_id)
                        ->where('grade_number', $request->grade_number)
                        ->where('semester', $request->semester)
                        ->whereNull('deleted_at');
                }),
            ],
            'category_id' => 'required|exists:categories,id',
            'education_level_id' => 'required|exists:education_levels,id',
            'curriculum_id' => 'required|exists:curriculums,id',
            'price' => 'required|numeric|min:0',
            'grade_number' => 'required|string|in:1,2,3,4,5,6,7,8,9,10,11,12,BESAR,KECIL',
            'semester' => 'required|in:1,2',
            'image' => 'required|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle input gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = now()->format('Ymd_His') . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('bookImages', $imageName, 'public');
        }

        // Cek apakah buku dengan kriteria tersebut ada di trash
        $existingBook = Book::withTrashed()
            ->where('title', 'like', '%' . $request->title . '%')
            ->where('category_id', $request->category_id)
            ->where('education_level_id', $request->education_level_id)
            ->where('curriculum_id', $request->curriculum_id)
            ->where('grade_number', $request->grade_number)
            ->where('semester', $request->semester)
            ->whereNotNull('deleted_at')
            ->first();

        if ($existingBook) {
            // hapus gambar sebelumnya 
            if ($existingBook->image && Storage::disk('public')->exists($existingBook->image)) {
                Storage::disk('public')->delete($existingBook->image);
            }

            // update dan restore buku yang ada di trash
            $existingBook->update([
                'price' => $request->price,
                'image' => $imagePath,
                'deleted_at' => null,
                'created_at' => now()
            ]);

        } else {
            // Buat buku baru ketika kriteria tidak ada di trash
            Book::create([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'education_level_id' => $request->education_level_id,
                'curriculum_id' => $request->curriculum_id,
                'price' => $request->price,
                'grade_number' => $request->grade_number,
                'semester' => $request->semester,
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambah');
    }

    public function destroy($bookId)
    {
        try {
            $book = Book::findOrFail($bookId);
            $book->delete();

            // Hapus gambar dari book
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }

            return back()->with('success', 'Buku berhasil dihapus');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Gagal menghapus buku: ' . $th->getMessage());
        }
    }

    public function edit($bookId)
    {
        $book = Book::with(['category', 'curriculum', 'educationLevel'])->findOrFail($bookId);
        $categories = Category::pluck('name', 'id');
        $curriculums = Curriculum::pluck('name', 'id');
        $educationLevels = EducationLevel::pluck('name', 'id');

        return view('book.edit-books', compact('book', 'categories', 'curriculums', 'educationLevels'));
    }

    public function update(Request $request, $bookId)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:288',
                Rule::unique('books')->ignore($bookId)->where(function ($query) use ($request) {
                    return $query
                        ->where('title', 'like', '%' . $request->title . '%')
                        ->where('category_id', $request->category_id)
                        ->where('education_level_id', $request->education_level_id)
                        ->where('curriculum_id', $request->curriculum_id)
                        ->where('grade_number', $request->grade_number)
                        ->where('semester', $request->semester)
                        ->whereNull('deleted_at');
                }),
            ],
            'category_id' => 'required|exists:categories,id',
            'education_level_id' => 'required|exists:education_levels,id',
            'curriculum_id' => 'required|exists:curriculums,id',
            'price' => 'required|numeric|min:0',
            'grade_number' => 'required|string|in:1,2,3,4,5,6,7,8,9,10,11,12,BESAR,KECIL',
            'semester' => 'required|in:1,2',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        $book = Book::findOrFail($bookId);

        // Handle input gambar
        $imagePath = $book->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }

            $image = $request->file('image');
            $imageName = now()->format('Ymd_His') . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('bookImages', $imageName, 'public');
        }

        // Cek apakah buku dengan kriteria tersebut ada di trash
        $existingBook = Book::withTrashed()
            ->where('id', '!=', $bookId)
            ->where('title', 'like', '%' . $request->title . '%')
            ->where('category_id', $request->category_id)
            ->where('education_level_id', $request->education_level_id)
            ->where('curriculum_id', $request->curriculum_id)
            ->where('grade_number', $request->grade_number)
            ->where('semester', $request->semester)
            ->whereNotNull('deleted_at')
            ->first();

        if ($existingBook) {
            // Hapus gambar dari existing book yang ada di trash
            if ($existingBook->image && Storage::disk('public')->exists($existingBook->image)) {
                Storage::disk('public')->delete($existingBook->image);
            }

            // Restore existing book dengan data baru
            $existingBook->restore(); // Restore dari soft delete
            $existingBook->update([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'education_level_id' => $request->education_level_id,
                'curriculum_id' => $request->curriculum_id,
                'price' => $request->price,
                'grade_number' => $request->grade_number,
                'semester' => $request->semester,
                'image' => $imagePath,
                'created_at' => now()
            ]);

            // Update data yang berelasi dengan book ini
            try {
                // TODO

                // Hapus book yang sedang di-edit (soft delete)
                $book->delete();
            } catch (\Exception $e) {
                // Jika ada error saat update relasi, rollback
                return redirect()->route('books.index')
                    ->with('failed', 'Gagal mengupdate data terkait: ' . $e->getMessage());
            }

            return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate');
        }

        // Update buku
        $book->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'education_level_id' => $request->education_level_id,
            'curriculum_id' => $request->curriculum_id,
            'price' => $request->price,
            'grade_number' => $request->grade_number,
            'semester' => $request->semester,
            'image' => $imagePath,
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate');
    }
}
