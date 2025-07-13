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
        ;

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

    public function updateStatus(Request $request, BookTransaction $transaction)
    {
        $user = auth()->user();

        // Validasi akses berdasarkan role
        $this->authorizeStatusUpdate($user, $transaction, $request->status);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $transaction->status;

        // Update transaction
        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes ?? $transaction->notes,
        ];

        // Set approval fields untuk owner
        if ($user->role === 'owner') {
            if ($request->status === 'approved') {
                $updateData['approved_by'] = $user->id;
                $updateData['approved_at'] = now();
            } elseif (in_array($request->status, ['rejected', 'cancelled'])) {
                $updateData['approved_by'] = null;
                $updateData['approved_at'] = null;
            }
        }

        $transaction->update($updateData);

        // Log activity
        \Log::info('Transaction Status Updated', [
            'transaction_id' => $transaction->id,
            'batch_number' => $transaction->batch_number,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'updated_by' => $user->id,
            'user_role' => $user->role,
        ]);

        $statusLabels = [
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan'
        ];

        return back()->with(
            'success',
            "Status transaksi {$transaction->batch_number} berhasil diperbarui menjadi {$statusLabels[$request->status]}."
        );
    }

    /**
     * Authorize status update berdasarkan role
     */
    private function authorizeStatusUpdate($user, $transaction, $newStatus)
    {
        switch ($user->role) {
            case 'owner':
                // Owner bisa mengubah ke status apapun
                return true;

            case 'admin':
                // Admin hanya bisa cancel transaksi apapun
                if ($newStatus !== 'cancelled') {
                    abort(403, 'Admin hanya dapat membatalkan transaksi.');
                }
                return true;

            case 'sales':
                // Sales hanya bisa cancel transaksi mereka sendiri
                if ($transaction->user_id !== $user->id) {
                    abort(403, 'Anda hanya dapat mengubah transaksi Anda sendiri.');
                }
                if ($newStatus !== 'cancelled') {
                    abort(403, 'Sales hanya dapat membatalkan transaksi.');
                }
                return true;

            default:
                abort(403, 'Unauthorized to update transaction status.');
        }
    }

    public function cancel(Request $request, BookTransaction $transaction)
    {
        $user = auth()->user();

        // Check if user can cancel this transaction
        if ($user->role === 'sales' && $transaction->user_id !== $user->id) {
            abort(403, 'Anda hanya dapat membatalkan transaksi Anda sendiri.');
        }

        if (!in_array($user->role, ['sales', 'admin', 'owner'])) {
            abort(403, 'Unauthorized to cancel transaction.');
        }

        // Validate that transaction can be cancelled
        if (in_array($transaction->status, ['approved', 'cancelled'])) {
            return back()->with('error', 'Transaksi yang sudah disetujui atau dibatalkan tidak dapat dibatalkan lagi.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'cancelled',
            'notes' => $request->cancellation_reason,
            'cancelled_by' => $user->id,
            'cancelled_at' => now(),
        ]);

        \Log::info('Transaction Cancelled', [
            'transaction_id' => $transaction->id,
            'batch_number' => $transaction->batch_number,
            'cancelled_by' => $user->id,
            'reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', "Transaksi {$transaction->batch_number} berhasil dibatalkan.");
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