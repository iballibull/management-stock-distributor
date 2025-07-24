@extends('layouts.app', ['metaTitle' => 'Omzet', 'parentSection' => 'transaction', 'elementName' => 'Omzet'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('transaction.omzet.index') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600"
                        aria-current="page">Transaksi</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('transaction.omzet.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Omzet</span></a>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Omzet</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($totalTransactions ?? 0) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($avgDaily ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Harian</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalProfit ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Estimasi Keuntungan</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <form method="GET" action="{{ route('transaction.omzet.index') }}"
                    class="flex flex-col sm:flex-row sm:items-end sm:space-x-4 space-y-4 sm:space-y-0 w-full">

                    <div class="flex flex-col w-full sm:w-1/3">
                        <label for="year"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                        <select name="year" id="year"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach ($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col w-full sm:w-1/3">
                        <label for="transaction_type"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Transaksi</label>
                        <select name="transaction_type" id="transaction_type"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="all" {{ request('transaction_type') == 'all' ? 'selected' : '' }}>Semua
                                Transaksi</option>
                            <option value="2" {{ request('transaction_type') == '2' ? 'selected' : '' }}>Pengambilan
                            </option>
                            <option value="3" {{ request('transaction_type') == '3' ? 'selected' : '' }}>Retur
                            </option>
                            <option value="4" {{ request('transaction_type') == '4' ? 'selected' : '' }}>Mutasi
                            </option>
                        </select>
                    </div>

                    <div class="flex justify-end w-full sm:w-auto">
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                            Filter
                        </button>
                    </div>
                </form>
            </div>


            <!-- Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                    Grafik Omzet Tahun {{ $year }}
                </h3>
                <div class="relative" style="height: 400px;">
                    <canvas id="omzetChart"></canvas>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Transaksi</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Nama</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Tanggal</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Tipe</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Total</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($recentTransactions as $transaction)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                        {{ $transaction->bookTransaction->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $transaction->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $transactionType =
                                                $transaction->bookTransaction->transactionType->name ?? 'Unknown';
                                            $badgeClass = match ($transactionType) {
                                                'PENGAMBILAN'
                                                    => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                'MUTASI'
                                                    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                'RETUR'
                                                    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                default
                                                    => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium {{ $badgeClass }} rounded-full">
                                            {{ $transactionType }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('book.activity.detail', ['transactionId' => $transaction->book_transaction_id]) }}"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data transaksi untuk tahun {{ $year }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $recentTransactions->links('pagination::simple-tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('omzetChart');

            if (ctx) {
                const months = @json($months);
                const amounts = @json($amounts);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Omzet',
                            data: amounts,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#3B82F6',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return 'Omzet: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                    color: 'rgba(156, 163, 175, 0.1)'
                                },
                                ticks: {
                                    color: '#6B7280'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: 'rgba(156, 163, 175, 0.1)'
                                },
                                ticks: {
                                    color: '#6B7280',
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
