<?php

namespace App\Http\Controllers\BookStock;

use App\Models\Book\Book;
use App\Models\BookTransaction\BookTransactionItem;
use App\Models\Transaction\Transaction;
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
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'transaction_type_id' => 'nullable|exists:transaction_types,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $books = $request->input('books');
        $user = auth()->user();

        // Tentukan tipe transaksi - Sales hanya bisa melakukan PENGAMBILAN (tipe 2)
        $transactionTypeId = $user->role_id == 3 ? 2 : $request->input('transaction_type_id');

        // Mulai transaksi database untuk atomicity
        DB::beginTransaction();

        try {
            // ===== PRE-VALIDASI: CEK KETERSEDIAAN STOK =====
            // Validasi ketersediaan stok untuk setiap buku berdasarkan tipe transaksi
            foreach ($books as $bookOrder) {
                $bookId = $bookOrder['book_id'];
                $requestedQuantity = $bookOrder['quantity'];

                // Tentukan kolom stok yang akan dicek berdasarkan tipe transaksi
                $availableStock = 0;
                if ($transactionTypeId == 2) {
                    $availableStock = BookStockBatch::where('book_id', $bookId)->sum('remaining_quantity');
                } elseif ($transactionTypeId == 3) {
                    $availableStock = BookStockBatch::where('book_id', $bookId)->sum('remaining_return_quantity');
                } elseif ($transactionTypeId == 4) {
                    $availableStock = BookStockBatch::where('book_id', $bookId)->sum('remaining_mutation_quantity');
                }

                // Jika stok tidak mencukupi, lempar exception
                if ($availableStock < $requestedQuantity) {
                    $book = Book::select('id', 'title')->find($bookId);
                    $stockType = 'Stok';
                    if ($transactionTypeId == 3) {
                        $stockType = 'Stok retur';
                    } elseif ($transactionTypeId == 4) {
                        $stockType = 'Stok mutasi';
                    }

                    throw new \Exception(
                        "{$stockType} tidak mencukupi untuk buku '{$book->title}'. " .
                        "Tersedia: {$availableStock}, Diminta: {$requestedQuantity}"
                    );
                }
            }

            // ===== INISIALISASI VARIABEL PERHITUNGAN =====
            $totalValue = 0;
            $totalPurchase = 0;
            $totalQuantity = collect($books)->sum('quantity');

            // Dapatkan semester aktif saat ini
            $currentSemesterId = Semester::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->value('id');

            if (!$currentSemesterId) {
                throw new \Exception('Semester aktif tidak ditemukan. Pastikan ada semester yang sudah diatur.');
            }

            // ===== BUAT RECORD TRANSAKSI BUKU UTAMA =====
            // Dibuat dengan total_value = 0, akan diupdate setelah memproses semua item
            $bookTransaction = BookTransaction::create([
                'user_id' => $user->id,
                'transaction_type_id' => $transactionTypeId,
                'total_quantity' => $totalQuantity,
                'total_value' => 0, // Akan dihitung selama pemrosesan
                'status' => $user->role_id == 1 ? 'approved' : 'pending', // Owner otomatis approved
                'semester_id' => $currentSemesterId,
                'approved_by' => $user->role_id == 1 ? $user->id : null,
                'approved_at' => $user->role_id == 1 ? now() : null,
                'notes' => $request->input('notes'),
            ]);

            // ===== PROSES SETIAP PESANAN BUKU MENGGUNAKAN METODE FIFO =====
            foreach ($books as $bookOrder) {
                $bookId = $bookOrder['book_id'];
                $requestedQuantity = $bookOrder['quantity'];
                $book = Book::find($bookId);

                // Ambil batch stok yang tersedia diurutkan berdasarkan FIFO
                $stockBatchesQuery = BookStockBatch::where('book_id', $bookId);

                // Filter batch berdasarkan tipe transaksi
                if ($transactionTypeId == 2) {
                    $stockBatchesQuery->where('remaining_quantity', '>', 0);
                } elseif ($transactionTypeId == 3) {
                    $stockBatchesQuery->where('remaining_return_quantity', '>', 0);
                } elseif ($transactionTypeId == 4) {
                    $stockBatchesQuery->where('remaining_mutation_quantity', '>', 0);
                }

                $stockBatches = $stockBatchesQuery
                    ->orderBy('created_at', 'asc') // FIFO: First In, First Out
                    ->orderBy('id', 'desc') // Sort sekunder untuk konsistensi
                    ->lockForUpdate() // Mencegah race condition
                    ->get();

                $remainingQuantity = $requestedQuantity;

                // ===== PROSES PENGURANGAN STOK MENGGUNAKAN METODE FIFO =====
                foreach ($stockBatches as $batch) {
                    if ($remainingQuantity <= 0)
                        break;

                    // Tentukan jumlah yang akan diambil dari batch ini berdasarkan tipe transaksi
                    $quantityToTake = 0;
                    if ($transactionTypeId == 2) {
                        $quantityToTake = min($remainingQuantity, $batch->remaining_quantity);
                    } elseif ($transactionTypeId == 3) {
                        $quantityToTake = min($remainingQuantity, $batch->remaining_return_quantity);
                    } elseif ($transactionTypeId == 4) {
                        $quantityToTake = min($remainingQuantity, $batch->remaining_mutation_quantity);
                    }

                    // Tentukan harga unit berdasarkan tipe transaksi
                    $unitPrice = 0;
                    if ($transactionTypeId == 2) {
                        // PENGAMBILAN menggunakan harga jual
                        $unitPrice = $book->price;
                    } else {
                        // RETUR/MUTASI menggunakan harga beli dari batch
                        $unitPrice = $batch->purchase_price;
                    }

                    $itemTotalPrice = $unitPrice * $quantityToTake;
                    $totalValue += $itemTotalPrice;
                    $totalPurchase += $quantityToTake * $batch->purchase_price;

                    // Buat record item transaksi untuk tracking
                    BookTransactionItem::create([
                        'book_transaction_id' => $bookTransaction->id,
                        'book_stock_batch_id' => $batch->id,
                        'book_id' => $bookId,
                        'quantity' => $quantityToTake,
                        'unit_price' => $unitPrice,
                        'total_price' => $itemTotalPrice,
                    ]);

                    // Update stok batch berdasarkan tipe transaksi
                    $batch->decrement('remaining_quantity', $quantityToTake);
                    if ($transactionTypeId == 3) {
                        $batch->decrement('remaining_return_quantity', $quantityToTake);
                    } elseif ($transactionTypeId == 4) {
                        $batch->decrement('remaining_mutation_quantity', $quantityToTake);
                    }

                    $remainingQuantity -= $quantityToTake;
                }

                // Validasi akhir - seharusnya tidak terjadi karena sudah ada pre-validasi
                if ($remainingQuantity > 0) {
                    throw new \Exception(
                        "Stok tidak mencukupi untuk buku '{$book->title}' saat pemrosesan. " .
                        "Kekurangan: {$remainingQuantity} unit"
                    );
                }
            }

            // ===== UPDATE TOTAL VALUE PADA BOOK TRANSACTION =====
            $bookTransaction->update(['total_value' => $totalValue]);

            // ===== BUAT TRANSAKSI KEUANGAN JIKA BOOK TRANSACTION DISETUJUI =====
            if ($bookTransaction->status === 'approved') {
                if ($bookTransaction->transactionType->name === 'PENGAMBILAN') {
                    // Buat transaksi belum bayar untuk pengambilan/penjualan customer
                    Transaction::create([
                        'book_transaction_id' => $bookTransaction->id,
                        'user_id' => $user->id,
                        'total_amount' => $totalValue,
                        'status' => 'UNPAID',
                        'remaining_amount' => $totalValue,
                        'amount_paid' => 0,
                        'profit_amount' => $totalValue - $totalPurchase,
                    ]);
                } elseif (in_array($bookTransaction->transactionType->name, ['RETUR', 'MUTASI'])) {
                    // Buat transaksi lunas untuk retur/mutasi (tracking internal)
                    Transaction::create([
                        'book_transaction_id' => $bookTransaction->id,
                        'user_id' => $user->id,
                        'total_amount' => $totalValue,
                        'status' => 'UNPAID',
                        'remaining_amount' => $totalValue,
                        'amount_paid' => 0,
                        'profit_amount' => 0, // Tidak ada profit untuk retur/mutasi
                    ]);
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