<?php

namespace App\Http\Controllers\Book;

use App\Models\Book\Book;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use Illuminate\Routing\Controller;
use App\Models\Book\EducationLevel;

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
        $books = $query->paginate(10)->withQueryString();

        return view('book.books', compact(
            'books',
            'categories',
            'curriculums',
            'educationLevels'
        ));
    }
}
