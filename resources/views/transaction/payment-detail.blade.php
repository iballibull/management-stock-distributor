@extends('layouts.app', ['metaTitle' => 'Detail Pembayaran', 'parentSection' => 'transaction', 'elementName' => 'Pembayaran'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('payment.index') }}">
                    <span class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600">Transaksi</span>
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('payment.index') }}">
                    <span class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600">Pembayaran</span>
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 truncate">Detail Pembayaran</span>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        <div class="space-y-6">
            <!-- Transaction Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Pembayaran</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-green-400">
                        Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Sudah Dibayar</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($transaction->remaining_amount, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Sisa Pembayaran</div>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID Transaksi</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe
                            Transaksi</label>
                        @php
                            $transactionType = $transaction->bookTransaction->transactionType->name ?? 'Unknown';
                            $badgeClass = match ($transactionType) {
                                'PENGAMBILAN' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'MUTASI' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                'RETUR' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                            };
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-medium {{ $badgeClass }} rounded-full">
                            {{ $transactionType }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        @php
                            $statusText = match ($transaction->status) {
                                'UNPAID' => 'BELUM DIBAYAR',
                                'PAID' => 'LUNAS',
                                'INSTALLMENT' => 'DICICIL',
                                default => 'Unknown',
                            };
                            $statusBadge = match ($transaction->status) {
                                'UNPAID' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                'PAID' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'INSTALLMENT'
                                    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                            };
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-medium {{ $statusBadge }} rounded-full">
                            {{ $statusText }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                            Transaksi</label>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ Illuminate\Support\Carbon::parse($transaction->bookTransaction->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detail</label>
                        <a href="{{ route('book.activity.detail', ['transactionId' => $transaction->bookTransaction->id]) }}"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Form (Only if not fully paid) -->
            @if ($transaction->remaining_amount > 0 && auth()->user()->role_id == 2)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tambah Pembayaran</h3>
                    <form action="{{ route('payment.store', $transaction->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="amount"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="amount" name="amount"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Masukkan jumlah pembayaran" max="{{ $transaction->remaining_amount }}"
                                    min="1" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maksimal: Rp
                                    {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label for="payment_method"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select id="payment_method" name="payment_method"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="TRANSFER" {{ old('payment_method') == 'TRANSFER' ? 'selected' : '' }}>
                                        Transfer Bank</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Catatan (Opsional)
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Tambahkan catatan pembayaran...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Tambah Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Payment History -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Metode</th>
                                <th scope="col" class="px-6 py-3">Catatan</th>
                                <th scope="col" class="px-6 py-3">Di Validasi Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transaction->payments as $key => $payment)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $key + 1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Illuminate\Support\Carbon::parse($payment->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                            {{ $payment->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $payment->notes ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $payment->validator->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada pembayaran yang tercatat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto calculate remaining amount
            const amountInput = document.getElementById('amount');
            const remainingAmount = {{ $transaction->remaining_amount }};

            if (amountInput) {
                amountInput.addEventListener('input', function() {
                    const value = parseInt(this.value) || 0;
                    if (value > remainingAmount) {
                        this.value = remainingAmount;
                    }
                });
            }
        });
    </script>
@endpush
