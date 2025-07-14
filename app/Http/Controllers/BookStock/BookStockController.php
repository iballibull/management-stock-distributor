<?php

namespace App\Http\Controllers\BookStock;

use App\Models\Book\Book;
use App\Models\BookTransaction\Semester;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use App\Models\Book\EducationLevel;
use App\Http\Controllers\Controller;

class BookStockController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role->name;

        $query = Book::with('category:id,name', 'curriculum:id,name', 'educationLevel:id,name')
            ->withSum('bookStockBatches', 'remaining_quantity');

        if ($role !== 'Sales') {
            $currentDate = now();
            $currentSemesterId = Semester::where('start_date', '<=', $currentDate)
                ->where('end_date', '>=', $currentDate)->value('id');

            if ($currentSemesterId) {
                $query->withSum([
                    'bookStockBatches as current_semester_return' => function ($query) use ($currentSemesterId) {
                        $query->where('semester_id', $currentSemesterId);
                    }
                ], 'remaining_return_quantity')
                    ->withSum([
                        'bookStockBatches as current_semester_mutation' => function ($query) use ($currentSemesterId) {
                            $query->where('semester_id', $currentSemesterId);
                        }
                    ], 'remaining_mutation_quantity');
            }
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan kurikulum
        if ($request->filled('curriculum_id')) {
            $query->where('curriculum_id', $request->curriculum_id);
        }

        // Filter berdasarkan jenjang pendidikan
        if ($request->filled('education_level_id')) {
            $query->where('education_level_id', $request->education_level_id);
        }

        // Filter berdasarkan kelas
        if ($request->filled('grade_number')) {
            $query->where('grade_number', $request->grade_number);
        }

        // Filter berdasarkan semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('book_stock_batches_sum_remaining_quantity', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('book_stock_batches_sum_remaining_quantity', 'desc');
                    break;
                default:
                    $query->orderBy('title', 'asc');
            }
        } else {
            $query->orderBy('title', 'asc');
        }

        $books = $query->paginate(12)->withQueryString();

        // Data untuk filter dropdown
        $categories = Category::orderBy('name')->get();
        $curriculums = Curriculum::orderBy('name')->get();
        $educationLevels = EducationLevel::orderBy('name')->get();
        $grades = Book::distinct()->orderBy('grade_number')->pluck('grade_number');
        $semesters = [1, 2];

        return view('stock.index', compact(
            'books',
            'categories',
            'curriculums',
            'educationLevels',
            'grades',
            'semesters',
            'role'
        ));
    }
}