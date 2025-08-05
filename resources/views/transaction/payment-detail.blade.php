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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
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
                                @if (auth()->user()->role_id == 2)
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transaction->payments as $key => $payment)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $key + 1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Illuminate\Support\Carbon::parse($payment->updated_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}
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
                                    @if (auth()->user()->role_id == 2)
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <button type="button"
                                                    data-modal-target="edit-payment-modal{{ $payment->id }}"
                                                    data-modal-toggle="edit-payment-modal{{ $payment->id }}"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button type="button"
                                                    data-modal-target="delete-payment-modal{{ $payment->id }}"
                                                    data-modal-toggle="delete-payment-modal{{ $payment->id }}"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>

                                        <!-- Edit payment Modal -->
                                        <div class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/30 overflow-x-hidden overflow-y-auto"
                                            id="edit-payment-modal{{ $payment->id }}">
                                            <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                    <!-- Modal header -->
                                                    <div
                                                        class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700 border-gray-200">
                                                        <h3 class="text-xl font-semibold dark:text-white">
                                                            Edit Pembayaran
                                                        </h3>
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                            data-modal-toggle="edit-payment-modal{{ $payment->id }}">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="p-6">
                                                        <form
                                                            action="{{ route('payment.update', ['paymentId' => $payment->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="flex justify-center">
                                                                <div class="w-full max-w-md">
                                                                    <div class="grid grid-cols-1 gap-6">
                                                                        <div>
                                                                            <label for="amount"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                                Jumlah <span class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="number" id="amount"
                                                                                placeholder="Jumlah pembayaran"
                                                                                name="amount"
                                                                                value="{{ number_format($payment->amount, 0, ',', '') }}"
                                                                                max="{{ $transaction->remaining_amount + $payment->amount }}"
                                                                                min="1" autocomplete="off"
                                                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                        </div>
                                                                        <div>
                                                                            <label for="add_name"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                                Metode pembayaran <span
                                                                                    class="text-red-500">*</span>
                                                                            </label>
                                                                            <select id="payment_method"
                                                                                name="payment_method"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                required>
                                                                                <option value="">Pilih metode
                                                                                    pembayaran</option>
                                                                                <option value="CASH"
                                                                                    {{ $payment->payment_method == 'CASH' ? 'selected' : '' }}>
                                                                                    Cash
                                                                                </option>
                                                                                <option value="TRANSFER"
                                                                                    {{ $payment->payment_method == 'TRANSFER' ? 'selected' : '' }}>
                                                                                    Transfer Bank</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label for="notes"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                                Nama <span class="text-red-500">*</span>
                                                                            </label>
                                                                            <textarea id="notes" name="notes" rows="3"
                                                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                                placeholder="Tambahkan catatan pembayaran...">{{ $payment->notes }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div
                                                        class="flex justify-end items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                                                        <button
                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                            type="submit">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete payment Modal -->
                                        <div class="fixed inset-0 z-50 flex items-center justify-center min-h-screen hidden overflow-x-hidden overflow-y-auto"
                                            id="delete-payment-modal{{ $payment->id }}">
                                            <div class="relative w-full max-w-md px-4">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                    <!-- Modal header -->
                                                    <div class="flex justify-end p-2">
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                            data-modal-hide="delete-payment-modal{{ $payment->id }}">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Modal  body -->
                                                    <div class="p-6 pt-0 text-center">
                                                        <form
                                                            action="{{ route('payment.destroy', ['paymentId' => $payment->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')

                                                            <svg class="w-16 h-16 mx-auto text-red-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>

                                                            <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">
                                                                Yakin ingin menghapus pembayaran dengan jumlah
                                                                <b>Rp
                                                                    {{ number_format($payment->amount, 0, ',', '.') }}</b>
                                                                dan metode
                                                                <b>{{ $payment->payment_method }}</b>?
                                                            </h3>

                                                            <!-- Tombol Submit -->
                                                            <button type="submit"
                                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800">
                                                                Ya, saya yakin
                                                            </button>

                                                            <!-- Tombol Batal -->
                                                            <button type="button"
                                                                class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                                                                data-modal-hide="delete-payment-modal{{ $payment->id }}">
                                                                Tidak, batal
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
