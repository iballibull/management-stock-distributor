<?php
// filepath: /Users/iballibull/Documents/Kuliah/Laravel/management-stock-distributor/app/Http/Controllers/BookTransaction/BookActivityController.php

namespace App\Http\Controllers\BookTransaction;

use App\Models\BookTransaction\BookTransaction;
use App\Models\BookTransaction\Semester;
use App\Models\BookTransaction\TransactionType;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BookTransaction::with([
            'user:id,name,email',
            'semester:id,name',
            'transactionType:id,name',
            'approvedBy:id,name'
        ]);

        switch ($user->role->name) {
            case 'Admin':
            case 'Owner':
                // Owner dan Admin bisa melihat dan mengubah semua transaksi
                // Tidak ada filter tambahan
                break;

            default:
                // Default: hanya transaksi sendiri
                $query->where('user_id', $user->id);
                break;
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type_id', $request->transaction_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by user (hanya untuk admin dan owner)
        if ($request->filled('user_id') && in_array($user->role->name, ['Admin', 'Owner'])) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }

        // Pagination
        $bookTransactions = $query
            ->sortable()
            ->latest()
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Data untuk dropdowns
        $transactionTypes = collect();

        $users = collect();
        if (in_array($user->role->id, [1, 2])) {
            $users = User::withTrashed()->pluck('name', 'id');
            $transactionTypes = TransactionType::pluck('name', 'id');
        } else {
            $users = User::where('id', $user->id)->pluck('name', 'id');
            $transactionTypes = TransactionType::where('name', 'KEDATANGAN')->pluck('name', 'id');
        }
        $semesters = Semester::withTrashed()->pluck('name', 'id');

        return view('book-transaction.book-activity', compact(
            'bookTransactions',
            'transactionTypes',
            'semesters',
            'users',
            'user'
        ));
    }

    public function detail($transactionId)
    {
        $user = auth()->user();
        $role = $user->role->name;
        $bookTransaction = BookTransaction::with([
            'user:id,name,email',
            'semester:id,name,year,semester_number',
            'transactionType:id,name',
            'bookTransactionItems.book',
            'approvedBy:id,name',
        ])->findOrFail($transactionId);

        // Cek akses
        if ($role === 'Sales' && $bookTransaction->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk transaksi ini.');
        }

        return view('book-transaction.book-activity-detail', compact('bookTransaction', 'role', 'user'));
    }
}