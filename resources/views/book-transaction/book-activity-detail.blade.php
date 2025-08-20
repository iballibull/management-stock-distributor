@extends('layouts.app', ['metaTitle' => 'Detail Aktivitas Transaksi Buku', 'parentSection' => 'bookTransaction', 'elementName' => 'bookActivity'])

@section('content')
    {{-- Breadcrumb Navigation --}}
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <a href="{{ route('book.activity.index') }}"
                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                    Transaksi Buku
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <a href="{{ route('book.activity.index') }}"
                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                    Aktivitas Transaksi Buku
                </a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Detail Transaksi</span>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        <div class="space-y-6">
            {{-- Header Section --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        {{-- Header Left - Back Button & Title --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('book.activity.index', request()->query()) }}"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 me-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                            <div>
                                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Detail Transaksi #{{ $bookTransaction->id }}
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ Illuminate\Support\Carbon::parse($bookTransaction->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Header Right - Action Buttons --}}
                        @if ($bookTransaction->status == 'pending')
                            @if ($role === 'Owner')
                                <div class="flex gap-2">
                                    <button type="button" data-modal-target="approve-modal"
                                        data-modal-toggle="approve-modal"
                                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                        <svg class="w-4 h-4 me-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Setujui
                                    </button>
                                    <button type="button" data-modal-target="reject-modal" data-modal-toggle="reject-modal"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                        <svg class="w-4 h-4 me-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            @endif
                            @if ($user->id === $bookTransaction->user_id)
                                <div class="flex gap-2">
                                    <button type="button" data-modal-target="cancel-modal" data-modal-toggle="cancel-modal"
                                        class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 me-2 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Batalkan
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Transaction Summary Card --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Transaksi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Status --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span class="text-sm font-semibold">
                                @if ($bookTransaction->status === 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif ($bookTransaction->status === 'approved')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                        Disetujui
                                    </span>
                                @elseif ($bookTransaction->status === 'rejected')
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-900 dark:text-gray-300">
                                        {{ ucfirst($bookTransaction->status) }}
                                    </span>
                                @endif
                            </span>
                        </div>

                        {{-- Transaction Type --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Jenis Transaksi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $bookTransaction->transactionType->name }}
                            </span>
                        </div>

                        {{-- User --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat oleh</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $bookTransaction->user->name }}
                            </span>
                        </div>

                        {{-- Approved By --}}
                        @if ($bookTransaction->approved_by)
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Disetujui oleh</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $bookTransaction->approvedBy->name }}
                                </span>
                            </div>
                        @endif

                        {{-- Total Quantity --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Kuantitas</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($bookTransaction->total_quantity) }} item
                            </span>
                        </div>

                        {{-- Total Value --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Nilai</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($bookTransaction->total_value, 2, ',', '.') }}
                            </span>
                        </div>

                        {{-- Total Book Types --}}
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Jenis Buku</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $bookTransaction->bookTransactionItems->count() }} jenis
                                @if (isset($bookTransaction->bookTransactionItems->first()->batch_count) &&
                                        $bookTransaction->bookTransactionItems->sum('batch_count') > $bookTransaction->bookTransactionItems->count())
                                    <span class="text-xs text-gray-400">
                                        ({{ $bookTransaction->bookTransactionItems->sum('batch_count') }} batch)
                                    </span>
                                @endif
                            </span>
                        </div>

                        {{-- Semester --}}
                        @if ($bookTransaction->semester)
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Semester</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $bookTransaction->semester->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Notes --}}
                    @if ($bookTransaction->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Catatan:</span>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $bookTransaction->notes }}</p>
                        </div>
                    @endif

                    {{-- Rejection Reason --}}
                    @if ($bookTransaction->rejection_reason)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-red-500 dark:text-red-400">Alasan Penolakan:</span>
                            <p
                                class="text-sm text-red-700 dark:text-red-300 mt-1 bg-red-50 dark:bg-red-900/20 p-3 rounded-lg">
                                {{ $bookTransaction->rejection_reason }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Book Details Table --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Detail Buku</h3>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 text-center">No</th>
                                    <th class="px-6 py-3">Judul Buku</th>
                                    <th class="px-6 py-3 text-center">Kategori</th>
                                    <th class="px-6 py-3 text-center">Tingkat</th>
                                    <th class="px-6 py-3 text-center">Kelas</th>
                                    <th class="px-6 py-3 text-center">Semester</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
                                    @if ($bookTransaction->transactionType->name === 'KEDATANGAN')
                                        <th class="px-6 py-3 text-center">Persentase Retur</th>
                                        <th class="px-6 py-3 text-center">Harga Beli</th>
                                    @else
                                        <th class="px-6 py-3 text-center">Harga</th>
                                    @endif
                                    <th class="px-6 py-3 text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($bookTransaction->bookTransactionItems as $key => $item)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        {{-- Number --}}
                                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">
                                            {{ $key + 1 }}
                                            @if (isset($item->batch_count) && $item->batch_count > 1 && ($user->role_id === 1 || $user->role_id === 2))
                                                <div class="text-xs text-blue-500 mt-1">
                                                    {{ $item->batch_count }} batch
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Book Title --}}
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ Illuminate\Support\Str::title($item->book->title) }}
                                            </div>
                                            @if (isset($item->price_variations) && $item->price_variations)
                                                <div class="text-xs text-orange-500 mt-1">
                                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Harga bervariasi
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Category --}}
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $item->book->category->name }}
                                            </span>
                                        </td>

                                        {{-- Education Level --}}
                                        <td class="px-6 py-4 text-center">{{ $item->book->educationLevel->name }}</td>

                                        {{-- Grade --}}
                                        <td class="px-6 py-4 text-center">{{ $item->book->grade_number }}</td>

                                        {{-- Semester --}}
                                        <td class="px-6 py-4 text-center">{{ $item->book->semester }}</td>

                                        {{-- Quantity --}}
                                        <td class="px-6 py-4 text-center font-semibold">
                                            {{ number_format($item->quantity) }}
                                            @if (isset($item->batch_count) && $item->batch_count > 1 && ($user->role_id === 1 || $user->role_id === 2))
                                                <button type="button"
                                                    class="ml-2 text-blue-600 hover:text-blue-800 text-xs"
                                                    onclick="toggleBatchDetails('{{ $item->book_id ?? $item->book->id }}')">
                                                    <svg class="w-3 h-3 inline transition-transform duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    Detail
                                                </button>
                                            @endif
                                        </td>

                                        {{-- Percentages for KEDATANGAN --}}
                                        @if ($bookTransaction->transactionType->name === 'KEDATANGAN')
                                            <td class="px-6 py-4 text-center">
                                                {{ number_format($item->return_percentage ?? 0, 1) }}%
                                            </td>
                                        @endif

                                        {{-- Unit Price --}}
                                        <td class="px-6 py-4 text-center">
                                            @if (isset($item->price_variations) && $item->price_variations)
                                                <span class="text-orange-600"
                                                    title="Harga rata-rata dari {{ $item->batch_count ?? 1 }} batch">
                                                    Rp {{ number_format($item->unit_price, 2, ',', '.') }}
                                                    <span class="text-xs">*</span>
                                                </span>
                                            @else
                                                Rp {{ number_format($item->unit_price, 2, ',', '.') }}
                                            @endif
                                        </td>

                                        {{-- Total Price --}}
                                        <td class="px-6 py-4 text-center font-semibold">
                                            Rp {{ number_format($item->total_price, 2, ',', '.') }}
                                        </td>
                                    </tr>

                                    {{-- Batch Details Row (Hidden by default) --}}
                                    @if (isset($item->batch_count) && $item->batch_count > 1 && isset($item->individual_items))
                                        <tr id="batch-details-{{ $item->book_id ?? $item->book->id }}"
                                            class="hidden bg-gray-50 dark:bg-gray-600">
                                            <td colspan="12" class="px-6 py-3">
                                                <div class="text-sm">
                                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Detail
                                                        Batch:</h5>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                        @foreach ($item->individual_items as $batchIndex => $batch)
                                                            <div class="bg-white dark:bg-gray-700 p-3 rounded border">
                                                                <div class="text-xs text-gray-600 dark:text-gray-300">
                                                                    <div
                                                                        class="font-medium text-gray-800 dark:text-gray-200 mb-1">
                                                                        Batch {{ $batchIndex + 1 }}
                                                                    </div>
                                                                    <div><strong>Qty:</strong>
                                                                        {{ number_format($batch->quantity) }}</div>
                                                                    <div><strong>Harga:</strong> Rp
                                                                        {{ number_format($batch->unit_price, 2, ',', '.') }}
                                                                    </div>
                                                                    <div><strong>Total:</strong> Rp
                                                                        {{ number_format($batch->total_price, 2, ',', '.') }}
                                                                    </div>
                                                                    @if ($bookTransaction->transactionType->name === 'KEDATANGAN')
                                                                        <div><strong>Retur:</strong>
                                                                            {{ $batch->return_percentage ?? 0 }}%</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>

                            {{-- Table Footer with Totals --}}
                            <tfoot
                                class="text-medium text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th colspan="6" class="px-6 py-3 text-right">Total:</th>
                                    <th class="px-6 py-3 text-center font-bold">
                                        {{ number_format($bookTransaction->total_quantity) }}
                                    </th>
                                    @if ($bookTransaction->transactionType->name === 'KEDATANGAN')
                                        <th class="px-6 py-3"></th>
                                    @endif
                                    <th class="px-6 py-3"></th>
                                    <th class="px-6 py-3 text-center font-bold">
                                        Rp {{ number_format($bookTransaction->total_value, 2, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @if ($bookTransaction->status == 'pending')
            {{-- Approve Modal --}}
            <div id="approve-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <button type="button"
                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="approve-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-green-400 w-12 h-12 dark:text-green-200" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                Apakah Anda yakin ingin menyetujui transaksi ini?
                            </h3>
                            <form
                                action="{{ route('book.transaction.approve.in', ['transactionId' => $bookTransaction->id]) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button data-modal-hide="approve-modal" type="submit"
                                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                    Ya, Setujui
                                </button>
                                <button data-modal-hide="approve-modal" type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Batal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cancel Modal --}}
            <div id="cancel-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <button type="button"
                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="cancel-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                Apakah Anda yakin ingin membatalkan transaksi ini?
                            </h3>
                            <form
                                action="{{ route('book.transaction.cancel', ['transactionId' => $bookTransaction->id]) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button data-modal-hide="cancel-modal" type="submit"
                                    class="text-white bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                    Ya, Batalkan
                                </button>
                                <button data-modal-hide="cancel-modal" type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Batal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div id="reject-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tolak Transaksi</h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="reject-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form action="{{ route('book.transaction.reject', ['transactionId' => $bookTransaction->id]) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="p-4 md:p-5 space-y-4">
                                <label for="rejection_reason"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Masukkan alasan penolakan..." required></textarea>
                            </div>
                            <div
                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Tolak Transaksi
                                </button>
                                <button data-modal-hide="reject-modal" type="button"
                                    class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Toggle detail batch untuk buku dengan multiple batch
         * Menampilkan atau menyembunyikan detail batch individual
         * @param {string} bookId - ID buku yang akan ditoggle detail batchnya
         */
        function toggleBatchDetails(bookId) {
            const detailRow = document.getElementById(`batch-details-${bookId}`);
            const button = event.target.closest('button');
            const icon = button.querySelector('svg');

            if (!detailRow || !button || !icon) {
                console.error('Element tidak ditemukan untuk bookId:', bookId);
                return;
            }

            if (detailRow.classList.contains('hidden')) {
                // Tampilkan detail batch
                detailRow.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                button.innerHTML = button.innerHTML.replace('Detail', 'Tutup');

                // Smooth scroll ke detail jika diperlukan
                setTimeout(() => {
                    detailRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            } else {
                // Sembunyikan detail batch
                detailRow.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                button.innerHTML = button.innerHTML.replace('Tutup', 'Detail');
            }
        }

        /**
         * Inisialisasi halaman detail transaksi
         * Menambahkan event listener dan konfigurasi awal
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts setelah 5 detik
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 5000);
            });

            // Tambahkan loading state untuk form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        const originalText = submitButton.textContent;
                        submitButton.textContent = 'Memproses...';

                        // Reset setelah 10 detik jika tidak redirect
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.textContent = originalText;
                        }, 10000);
                    }
                });
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // ESC untuk menutup modal
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('[data-modal-hide]');
                    modals.forEach(modal => {
                        const modalId = modal.getAttribute('data-modal-hide');
                        const modalElement = document.getElementById(modalId);
                        if (modalElement && !modalElement.classList.contains('hidden')) {
                            modal.click();
                        }
                    });
                }
            });
        });
    </script>
@endpush
