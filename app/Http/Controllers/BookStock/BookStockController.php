<?php

namespace App\Http\Controllers\BookStock;

use App\Models\Book\Book;
use App\Models\BookTransaction\BookTransactionItem;
use Illuminate\Http\Request;
use App\Models\Book\Category;
use App\Models\Book\Curriculum;
use Illuminate\Support\Facades\DB;
use App\Models\Book\EducationLevel;
use App\Http\Controllers\Controller;
use App\Models\BookStock\BookStockBatch;
use App\Models\BookTransaction\Semester;
use App\Models\BookTransaction\BookTransaction;

class BookStockController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role->name;

        $query = Book::with('category:id,name', 'curriculum:id,name', 'educationLevel:id,name')
            ->withSum('bookStockBatches', 'remaining_quantity');

        if ($role !== 'Sales') {
            $query->withSum('bookStockBatches', 'remaining_return_quantity')
                ->withSum('bookStockBatches', 'remaining_mutation_quantity');

            // Get current semester based on academic calendar
            $currentDate = now()->subMonth();
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

    public function order(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'books' => 'required|array',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'transaction_type_id' => 'exists:transaction_types,id',
        ]);

        $books = $request->input('books');

        // Tentukan tipe transaksi berdasarkan role user (Sales = 2, Owner dari input)
        $transactionTypeId = auth()->user()->role_id == 3 ? 2 : $request->input('transaction_type_id');
        $user = auth()->user();

        // Mulai transaksi database 
        DB::beginTransaction();

        try {
            // Pre-validasi: Cek ketersediaan stok sebelum memproses apapun
            foreach ($books as $bookOrder) {
                $availableStock = BookStockBatch::where('book_id', $bookOrder['book_id'])
                    ->sum('remaining_quantity');

                // Jika stok tidak mencukupi, throw exception
                if ($availableStock < $bookOrder['quantity']) {
                    $book = Book::select('id', 'title')->find($bookOrder['book_id']);
                    throw new \Exception(
                        "Stok tidak mencukupi untuk buku '{$book->title}'. " .
                        "Tersedia: {$availableStock}, Diminta: {$bookOrder['quantity']}"
                    );
                }
            }

            // Hitung total nilai dan quantity dengan benar
            $totalValue = 0;
            $totalQuantity = collect($books)->sum('quantity');

            foreach ($books as $bookOrder) {
                $book = Book::find($bookOrder['book_id']);
                $totalValue += $book->price * $bookOrder['quantity'];
            }

            // Dapatkan semester aktif saat ini
            $currentSemesterId = Semester::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->value('id');

            // Buat record transaksi buku utama
            $bookTransaction = BookTransaction::create([
                'user_id' => $user->id,
                'transaction_type_id' => $transactionTypeId,
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
                'status' => 'pending',
                'semester_id' => $currentSemesterId,
            ]);

            // Proses setiap order buku
            foreach ($books as $bookOrder) {
                $bookId = $bookOrder['book_id'];
                $requestedQuantity = $bookOrder['quantity'];
                $book = Book::find($bookId);

                // Ambil batch stok yang tersedia, diurutkan berdasarkan tanggal masuk (FIFO)
                $stockBatches = BookStockBatch::where('book_id', $bookId)
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'desc') // Sort sekunder untuk konsistensi
                    ->lockForUpdate() // Lock baris untuk mencegah race condition
                    ->get();

                $remainingQuantity = $requestedQuantity;

                // Proses pengurangan stok dengan metode FIFO (First In, First Out)
                foreach ($stockBatches as $batch) {
                    // Jika quantity sudah terpenuhi, keluar dari loop
                    if ($remainingQuantity <= 0)
                        break;

                    // Tentukan jumlah yang akan diambil dari batch ini
                    $quantityToTake = min($remainingQuantity, $batch->remaining_quantity);

                    // Buat record batch item transaksi untuk tracking
                    BookTransactionItem::create([
                        'book_transaction_id' => $bookTransaction->id,
                        'book_stock_batch_id' => $batch->id,
                        'book_id' => $bookId,
                        'quantity' => $quantityToTake,
                        'unit_price' => $book->price,
                        'total_price' => $book->price * $quantityToTake,
                    ]);

                    // Update stok batch (kurangi remaining quantity)
                    $batch->decrement('remaining_quantity', $quantityToTake);

                    // Kurangi quantity yang masih dibutuhkan
                    $remainingQuantity -= $quantityToTake;
                }

                // Final check - seharusnya tidak terjadi karena sudah pre-validasi
                if ($remainingQuantity > 0) {
                    throw new \Exception(
                        "Stok tidak mencukupi untuk buku '{$book->title}' saat pemrosesan. " .
                        "Kekurangan: {$remainingQuantity} unit"
                    );
                }
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return redirect()->route('book.stock.index')
                ->with('success', "Order buku berhasil diproses. ID Transaksi: #{$bookTransaction->id}");

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            return redirect()->back()
                ->withErrors(['failed' => 'Gagal memproses order buku: ' . $e->getMessage()])
                ->withInput(); // Simpan input untuk ditampilkan kembali
        }
    }
}