<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookTransaction\BookTransaction;
use App\Models\Transaction\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $roleId = auth()->user()->role_id;
        $userId = auth()->id();

        if ($roleId == 1 || $roleId == 2) {
            // ADMIN & OWNER - Dashboard lengkap
            return $this->getAdminOwnerDashboard($roleId);
        } else {
            // SALES - Dashboard personal
            return $this->getSalesDashboard($roleId, $userId);
        }
    }

    private function getAdminOwnerDashboard($roleId)
    {
        // Semua pending transactions
        $transactions = BookTransaction::with(['user', 'transactionType'])
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        // Chart data omzet - 12 bulan terakhir
        $year = date('Y');
        $chartData = Transaction::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Format data untuk Chart.js
        $months = [];
        $amounts = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = $monthNames[$i - 1];
            $monthData = $chartData->where('month', $i)->first();
            $amounts[] = $monthData ? $monthData->total : 0;
        }

        // Summary statistics
        $totalOmzet = Transaction::whereYear('created_at', $year)->sum('total_amount');
        $totalTransactions = Transaction::whereYear('created_at', $year)->count();
        $remainingPaid = Transaction::where('status', '!=', 'PAID')->sum('remaining_amount');
        $monthlyOmzet = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', date('m'))
            ->sum('total_amount');

        // Pendapatan metrics
        $totalPendapatan = Transaction::whereIn('status', ['PAID', 'INSTALLMENT'])->sum('amount_paid');
        $pendapatanBulanIni = Transaction::whereIn('status', ['PAID', 'INSTALLMENT'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', date('m'))
            ->sum('amount_paid');

        return view('dashboard', compact(
            'transactions',
            'months',
            'amounts',
            'totalOmzet',
            'totalTransactions',
            'remainingPaid',
            'monthlyOmzet',
            'totalPendapatan',
            'pendapatanBulanIni',
            'roleId'
        ));
    }

    private function getSalesDashboard($roleId, $userId)
    {
        // Hanya pending transactions milik sales yang login saat ini
        $transactions = BookTransaction::with(['transactionType'])
            ->where('status', 'PENDING')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Sisa bayar dari transaksi sales yang login saat ini
        $remainingPaid = Transaction::whereHas('bookTransaction', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', '!=', 'PAID')
            ->sum('remaining_amount');

        // Personal performance metrics
        $myTotalOmzet = Transaction::whereHas('bookTransaction', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');

        $myMonthlyOmzet = Transaction::whereHas('bookTransaction', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('total_amount');

        $myTotalTransactions = Transaction::whereHas('bookTransaction', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereYear('created_at', date('Y'))
            ->count();

        $myPaidAmount = Transaction::whereHas('bookTransaction', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereIn('status', ['PAID', 'INSTALLMENT'])
            ->sum('amount_paid');

        return view('dashboard', compact(
            'transactions',
            'remainingPaid',
            'myTotalOmzet',
            'myMonthlyOmzet',
            'myTotalTransactions',
            'myPaidAmount',
            'roleId'
        ));
    }
}