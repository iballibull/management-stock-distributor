@extends('layouts.app', ['metaTitle' => 'Dashboard', 'parentSection' => '', 'elementName' => ''])

@section('content')
    @component('layouts.headers.breadcrumbs')
    @endcomponent
    <div class="col-span-full">
        <div class="space-y-6">
            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                {{-- ADMIN & OWNER DASHBOARD --}}

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Omzet Tahun Ini</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 ">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-blue-400">
                            Rp {{ number_format($monthlyOmzet, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Omzet Bulan Ini</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-red-400">
                            Rp {{ number_format($remainingPaid, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Sisa Pembayaran</div>
                    </div>
                </div>

                <!-- Chart Omzet -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Grafik Omzet {{ date('Y') }}
                    </h3>
                    <div class="relative" style="height: 400px;">
                        <canvas id="omzetChart"></canvas>
                    </div>
                </div>

                <!-- Pending Transactions -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Transaksi Menunggu Persetujuan ({{ $transactions->count() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Pengaju</th>
                                    <th scope="col" class="px-6 py-3">Tipe</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions as $transaction)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $transaction->transactionType->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                PENDING
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('book.activity.detail', $transaction->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada transaksi yang menunggu persetujuan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- SALES DASHBOARD --}}

                <!-- Personal Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($myTotalOmzet, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Omzet Saya Tahun Ini</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-blue-400">
                            Rp {{ number_format($myMonthlyOmzet, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Omzet Bulan Ini</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-green-400">
                            Rp {{ number_format($myPaidAmount, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Sudah Terbayar</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-red-400">
                            Rp {{ number_format($remainingPaid, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Sisa Pembayaran Saya</div>
                    </div>
                </div>

                <!-- My Pending Transactions -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Transaksi Saya yang Menunggu Persetujuan ({{ $transactions->count() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Tipe</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions as $transaction)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $transaction->transactionType->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                PENDING
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('book.activity.detail', $transaction->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada transaksi Anda yang menunggu persetujuan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
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
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Omzet: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
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
@endif
