<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;


class TransactionController extends Controller
{
    public function omzet(Request $request)
    {
        $year = $request->get('year', date('Y')); // Default tahun sekarang
        $transactionType = $request->get('transaction_type', 'all'); // Filter tipe transaksi

        // Base query untuk filter
        $baseQuery = Transaction::whereHas('bookTransaction', function ($query) use ($transactionType) {
            $query->whereIn('transaction_type_id', $transactionType == 'all' ? [2, 3, 4] : [$transactionType]);
        })->whereYear('created_at', $year);

        // Chart data - omzet per bulan
        $chartData = (clone $baseQuery)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Format data untuk Chart.js (pastikan 12 bulan)
        $months = [];
        $amounts = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = $monthNames[$i - 1];
            $monthData = $chartData->where('month', $i)->first();
            $amounts[] = $monthData ? $monthData->total : 0;
        }

        // Summary statistics
        $totalOmzet = (clone $baseQuery)->sum('total_amount');
        $totalTransactions = (clone $baseQuery)->count();
        $avgDaily = $totalTransactions > 0 ? $totalOmzet / 365 : 0; // Per hari dalam setahun

        // Estimasi keuntungan
        $totalProfit = (clone $baseQuery)->sum('profit_amount');

        // Recent transactions untuk tabel
        $recentTransactions = Transaction::with(['bookTransaction.transactionType', 'bookTransaction.user'])
            ->whereHas('bookTransaction', function ($query) {
                $query->whereIn('transaction_type_id', [2, 3, 4]);
            })
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->simplePaginate(10)
            ->withQueryString();

        // List tahun yang tersedia berdasarkan data
        $availableYears = Transaction::selectRaw('YEAR(created_at) as year')
            ->whereHas('bookTransaction', function ($query) {
                $query->whereIn('transaction_type_id', [2, 3, 4]);
            })
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Jika tidak ada data, tambahkan tahun sekarang
        if (empty($availableYears)) {
            $availableYears = [date('Y'), date('Y') - 1, date('Y') - 2];
        }

        return view('transaction.omzet', compact(
            'months',
            'amounts',
            'totalOmzet',
            'totalTransactions',
            'avgDaily',
            'totalProfit',
            'recentTransactions',
            'availableYears',
            'year'
        ));
    }

    public function revenue()
    {
        return view('transaction.revenue');
    }
}
