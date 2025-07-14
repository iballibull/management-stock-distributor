@extends('layouts.app', ['metaTitle' => 'Stok Buku', 'parentSection' => 'stock', 'elementName' => 'index'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Stok Buku</span>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <!-- Header & Search -->
            <div class="mb-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stok Buku</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih buku yang ingin dipesan, lalu
                                klik "Pesan Sekarang"</p>
                        </div>

                        <!-- Search -->
                        <form method="GET" class="flex gap-2">
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                            <input type="hidden" name="curriculum_id" value="{{ request('curriculum_id') }}">
                            <input type="hidden" name="education_level_id" value="{{ request('education_level_id') }}">
                            <input type="hidden" name="grade_number" value="{{ request('grade_number') }}">
                            <input type="hidden" name="semester" value="{{ request('semester') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">

                            <div class="relative min-w-64">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari judul buku..."
                                    class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                            </div>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Cari
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Filters & Sort -->
            <div class="mb-6 flex flex-wrap gap-3 items-center">
                <button data-modal-toggle="filterModal" data-modal-target="filterModal" type="button"
                    class="flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                    </svg>
                    Filter
                    @if (request()->hasAny(['category_id', 'curriculum_id', 'education_level_id', 'grade_number', 'semester']))
                        <span
                            class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-600 rounded-full">
                            {{ collect(request()->only(['category_id', 'curriculum_id', 'education_level_id', 'grade_number', 'semester']))->filter()->count() }}
                        </span>
                    @endif
                </button>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <button id="sortDropdown" data-dropdown-toggle="sortDropdownMenu" type="button"
                        class="flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4v16M7 4l3 3M7 4 4 7m9-3h6l-6 6h6m-6.5 10 3.5-7 3.5 7M14 18h4" />
                        </svg>
                        Urutkan
                        <svg class="w-4 h-4 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="sortDropdownMenu"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-48 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title_asc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'title_asc' || !request('sort') ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Judul A-Z
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title_desc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'title_desc' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Judul Z-A
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'price_asc' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Harga Terendah
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'price_desc' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Harga Tertinggi
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock_desc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'stock_desc' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Stok Terbanyak
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock_asc']) }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white {{ request('sort') == 'stock_asc' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    Stok Tersedikit
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if (request()->hasAny([
                        'category_id',
                        'curriculum_id',
                        'education_level_id',
                        'grade_number',
                        'semester',
                        'search',
                        'sort',
                    ]))
                    <a href="{{ route('book.stock.index') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hapus Filter
                    </a>
                @endif
            </div>

            <!-- Selection Controls - Always show when books exist -->
            @if ($books->count() > 0 && $role !== 'Admin')
                <div class="mb-6">
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="selectAllBooks"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                        Semua</span>
                                </label>
                                <span id="selectedInfo" class="text-sm text-gray-500 dark:text-gray-400">0 item
                                    dipilih</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button id="orderBtn" disabled
                                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed dark:disabled:bg-gray-600 transition-colors"
                                    onclick="showOrderModal()">
                                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Pesan Sekarang
                                </button>

                                <button id="clearSelectionBtn" disabled
                                    class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg disabled:bg-gray-50 disabled:cursor-not-allowed dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 dark:disabled:bg-gray-700 transition-colors"
                                    onclick="clearAllSelection()">
                                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batal Pilihan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Books Grid -->
            @if ($books->count() > 0)
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                    @foreach ($books as $book)
                        <div class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:shadow-lg hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600 book-card relative"
                            data-book-id="{{ $book->id }}" data-book-price="{{ $book->price }}"
                            data-book-title="{{ $book->title }}"
                            data-book-stock="{{ $book->book_stock_batches_sum_remaining_quantity ?? 0 }}">

                            <!-- Checkbox - Always show for available books -->
                            @if (($book->book_stock_batches_sum_remaining_quantity ?? 0) > 0 && $role !== 'Admin')
                                <div class="absolute top-3 left-3 z-10">
                                    <input type="checkbox"
                                        class="book-checkbox w-5 h-5 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 shadow-sm"
                                        data-book-id="{{ $book->id }}" onchange="updateSelection()">
                                </div>
                            @endif

                            <!-- Image Container -->
                            <div class="relative h-48 w-full mb-4 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-700">
                                <img class="h-full w-full object-cover object-center transition-transform duration-200 group-hover:scale-105"
                                    src="{{ asset('storage/' . ($book->image ?? 'bookImages/default.jpg')) }}"
                                    alt="Foto Buku {{ $book->title }}" loading="lazy" />

                                <!-- Stock Badge -->
                                <div class="absolute top-3 right-3">
                                    <div class="flex flex-col gap-1">
                                        <!-- Primary Stock Badge (Most Important) -->
                                        @if (($book->book_stock_batches_sum_remaining_quantity ?? 0) > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $book->book_stock_batches_sum_remaining_quantity }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Habis
                                            </span>
                                        @endif

                                        <!-- Secondary Info - Only show if there are values -->
                                        @php
                                            $hasReturn = ($book->current_semester_return ?? 0) > 0;
                                            $hasMutation = ($book->current_semester_mutation ?? 0) > 0;
                                        @endphp

                                        @if ($hasReturn || $hasMutation)
                                            <div class="flex flex-col gap-1">
                                                @if ($hasReturn)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 shadow-sm">
                                                        <svg class="w-2.5 h-2.5 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        R: {{ $book->current_semester_mutation }}
                                                    </span>
                                                @endif

                                                @if ($hasMutation)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 shadow-sm">
                                                        <svg class="w-2.5 h-2.5 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        M: {{ $book->current_semester_mutation }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <!-- Category -->
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $book->category->name }}
                                    </span>
                                </div>

                                <!-- Title  -->
                                <h3
                                    class="text-lg font-semibold leading-tight text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400 truncate">
                                    {{ Str::title($book->title) }}
                                </h3>

                                <!-- Curriculum -->
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    {{ $book->curriculum->name }}
                                </p>

                                <!-- Details -->
                                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $book->educationLevel->name }}
                                    </span>
                                    <span>•</span>
                                    <span>Kelas {{ $book->grade_number }}</span>
                                    <span>•</span>
                                    <span>Sem {{ $book->semester }}</span>
                                </div>

                                <!-- Price -->
                                <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Harga</span>
                                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Coba ubah filter atau kata kunci pencarian Anda untuk menemukan buku yang tersedia.
                    </p>
                    @if (request()->hasAny([
                            'category_id',
                            'curriculum_id',
                            'education_level_id',
                            'grade_number',
                            'semester',
                            'search',
                            'sort',
                        ]))
                        <a href="{{ route('book.stock.index') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                            Reset semua filter
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Order Modal -->
        <div id="orderModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Pesanan</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            onclick="closeOrderModal()">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>

                    {{-- {{ route('order.store') }} --}}
                    <form action="" method="POST">
                        @csrf
                        <div class="p-4 md:p-5 space-y-4">
                            <div class="max-h-60 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-600 sticky top-0">
                                        <tr>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                Buku</th>
                                            <th
                                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                Qty</th>
                                            <th
                                                class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                Subtotal</th>
                                            <th
                                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedBooksTable" class="divide-y divide-gray-200 dark:divide-gray-600">
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">Total:</span>
                                    <span id="orderTotal" class="text-xl font-bold text-blue-600">Rp 0</span>
                                </div>

                                <div>
                                    <label for="orderNotes"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan
                                        (Opsional)</label>
                                    <textarea id="orderNotes" name="notes" rows="3"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Tambahkan catatan untuk pesanan..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Buat Pesanan
                            </button>
                            <button type="button" onclick="closeOrderModal()"
                                class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filter Modal -->
        <div id="filterModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Buku</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="filterModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('book.stock.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <div class="p-4 md:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Category Filter -->
                                <div>
                                    <label for="category_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                    <select id="category_id" name="category_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Curriculum Filter -->
                                <div>
                                    <label for="curriculum_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kurikulum</label>
                                    <select id="curriculum_id" name="curriculum_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        <option value="">Semua Kurikulum</option>
                                        @foreach ($curriculums as $curriculum)
                                            <option value="{{ $curriculum->id }}"
                                                {{ request('curriculum_id') == $curriculum->id ? 'selected' : '' }}>
                                                {{ $curriculum->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Education Level Filter -->
                                <div>
                                    <label for="education_level_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenjang
                                        Pendidikan</label>
                                    <select id="education_level_id" name="education_level_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        <option value="">Semua Jenjang</option>
                                        @foreach ($educationLevels as $level)
                                            <option value="{{ $level->id }}"
                                                {{ request('education_level_id') == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Grade Filter -->
                                <div>
                                    <label for="grade_number"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                    <select id="grade_number" name="grade_number"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade }}"
                                                {{ request('grade_number') == $grade ? 'selected' : '' }}>
                                                Kelas {{ $grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Semester Filter -->
                                <div class="md:col-span-2">
                                    <label for="semester"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
                                    <select id="semester" name="semester"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        <option value="">Semua Semester</option>
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester }}"
                                                {{ request('semester') == $semester ? 'selected' : '' }}>
                                                Semester {{ $semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('book.stock.index') }}"
                                class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Reset
                            </a>
                            <button type="button" data-modal-hide="filterModal"
                                class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // LocalStorage keys
            const STORAGE_KEY = 'book_selection';
            const STORAGE_EXPIRY = 'book_selection_expiry';

            // Selection storage with expiry (24 hours)
            function saveSelection() {
                const data = Array.from(selectedBooks.values());
                const expiry = Date.now() + (24 * 60 * 60 * 1000); // 24 hours

                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
                localStorage.setItem(STORAGE_EXPIRY, expiry.toString());
            }

            function loadSelection() {
                try {
                    const expiry = localStorage.getItem(STORAGE_EXPIRY);
                    if (expiry && Date.now() > parseInt(expiry)) {
                        // Expired, clear storage
                        localStorage.removeItem(STORAGE_KEY);
                        localStorage.removeItem(STORAGE_EXPIRY);
                        return [];
                    }

                    const data = localStorage.getItem(STORAGE_KEY);
                    return data ? JSON.parse(data) : [];
                } catch (e) {
                    console.error('Error loading selection:', e);
                    return [];
                }
            }

            function clearStoredSelection() {
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(STORAGE_EXPIRY);
            }

            let selectedBooks = new Map();

            function updateSelection() {
                const checkboxes = document.querySelectorAll('.book-checkbox:checked');
                const selectAllBtn = document.getElementById('selectAllBooks');
                const orderBtn = document.getElementById('orderBtn');
                const clearSelectionBtn = document.getElementById('clearSelectionBtn');
                const selectedInfo = document.getElementById('selectedInfo');

                // Start with stored selections from other pages
                const storedSelection = loadSelection();
                selectedBooks.clear();

                // Add stored selections (from other pages)
                storedSelection.forEach(book => {
                    selectedBooks.set(book.id, book);
                });

                // Remove all selected classes first
                document.querySelectorAll('.book-card').forEach(card => {
                    card.classList.remove('selected');
                });

                // Process current page checkboxes
                document.querySelectorAll('.book-checkbox').forEach(checkbox => {
                    const bookCard = checkbox.closest('.book-card');
                    const bookId = checkbox.dataset.bookId;

                    if (checkbox.checked) {
                        // Add or update book selection
                        if (!selectedBooks.has(bookId)) {
                            const bookData = {
                                id: bookId,
                                title: bookCard.dataset.bookTitle,
                                price: parseInt(bookCard.dataset.bookPrice),
                                stock: parseInt(bookCard.dataset.bookStock),
                                quantity: 1 // Default quantity
                            };
                            selectedBooks.set(bookId, bookData);
                        }
                        bookCard.classList.add('selected');
                    } else {
                        // Remove book from selection if unchecked
                        selectedBooks.delete(bookId);
                    }
                });

                // Save updated selection to localStorage
                saveSelection();

                // Update UI
                const count = selectedBooks.size;
                const currentPageSelected = checkboxes.length;

                if (count !== currentPageSelected) {
                    selectedInfo.innerHTML =
                        `<span class="font-medium">${count} item dipilih</span> <span class="text-xs">(${currentPageSelected} di halaman ini)</span>`;
                } else {
                    selectedInfo.textContent = `${count} item dipilih`;
                }

                orderBtn.disabled = count === 0;
                clearSelectionBtn.disabled = count === 0;

                // Update button text
                if (count === 1) {
                    orderBtn.innerHTML = `<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>Pesan 1 Item`;
                } else if (count > 1) {
                    orderBtn.innerHTML = `<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>Pesan ${count} Item`;
                } else {
                    orderBtn.innerHTML = `<svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>Pesan Sekarang`;
                }

                // Update select all checkbox
                const availableCheckboxes = document.querySelectorAll('.book-checkbox:not([disabled])').length;
                selectAllBtn.checked = currentPageSelected === availableCheckboxes && availableCheckboxes > 0;
                selectAllBtn.indeterminate = currentPageSelected > 0 && currentPageSelected < availableCheckboxes;
            }

            function toggleSelectAll() {
                const selectAll = document.getElementById('selectAllBooks');
                const checkboxes = document.querySelectorAll('.book-checkbox:not([disabled])');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });

                updateSelection();
            }

            function clearAllSelection() {
                // Clear current page checkboxes
                document.querySelectorAll('.book-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Clear stored selection
                clearStoredSelection();
                selectedBooks.clear();

                updateSelection();
            }

            function showOrderModal() {
                if (selectedBooks.size === 0) return;

                const tableBody = document.getElementById('selectedBooksTable');
                const orderTotal = document.getElementById('orderTotal');

                tableBody.innerHTML = '';
                let total = 0;

                selectedBooks.forEach(book => {
                    const subtotal = book.price * book.quantity;
                    total += subtotal;

                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-600';
                    row.id = `book-row-${book.id}`;
                    row.innerHTML = `
                    <td class="px-3 py-2">
                        <input type="hidden" name="books[${book.id}][book_id]" value="${book.id}">
                        <input type="hidden" name="books[${book.id}][quantity]" value="${book.quantity}">
                        <div class="font-medium text-gray-900 dark:text-white">${book.title}</div>
                        <div class="text-gray-500 text-xs">Stok: ${book.stock}</div>
                    </td>
                    <td class="px-3 py-2 text-center">
                        <input type="number" 
                               class="w-16 p-1 text-center border rounded text-sm focus:ring-2 focus:ring-blue-500" 
                               value="${book.quantity}" 
                               min="1" 
                               max="${book.stock}"
                               onchange="updateBookQuantity('${book.id}', this.value)">
                    </td>
                    <td class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white" id="subtotal-${book.id}">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" 
                                onclick="removeBookFromOrder('${book.id}')"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                title="Hapus item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                `;
                    tableBody.appendChild(row);
                });

                orderTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Show modal
                document.getElementById('orderModal').classList.remove('hidden');
                document.getElementById('orderModal').classList.add('flex');

                // Clear notes
                document.getElementById('orderNotes').value = '';
            }

            function closeOrderModal() {
                document.getElementById('orderModal').classList.add('hidden');
                document.getElementById('orderModal').classList.remove('flex');
            }

            function removeBookFromOrder(bookId) {
                // Remove from selectedBooks
                selectedBooks.delete(bookId);

                // Update localStorage
                saveSelection();

                // Remove from current page checkbox if exists
                const checkbox = document.querySelector(`.book-checkbox[data-book-id="${bookId}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                    const bookCard = checkbox.closest('.book-card');
                    if (bookCard) {
                        bookCard.classList.remove('selected');
                    }
                }

                // Remove row from table
                const row = document.getElementById(`book-row-${bookId}`);
                if (row) {
                    row.remove();
                }

                // Update total
                let total = 0;
                selectedBooks.forEach(book => {
                    total += book.price * book.quantity;
                });
                document.getElementById('orderTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Update selection UI
                updateSelection();

                // Close modal if no items left
                if (selectedBooks.size === 0) {
                    closeOrderModal();
                }
            }

            function updateBookQuantity(bookId, quantity) {
                const book = selectedBooks.get(bookId);
                if (book) {
                    book.quantity = parseInt(quantity);
                    const subtotal = book.price * book.quantity;
                    document.getElementById(`subtotal-${bookId}`).textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

                    // Update hidden input
                    document.querySelector(`input[name="books[${bookId}][quantity]"]`).value = quantity;

                    // Save to localStorage
                    saveSelection();

                    // Recalculate total
                    let total = 0;
                    selectedBooks.forEach(b => {
                        total += b.price * b.quantity;
                    });
                    document.getElementById('orderTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
            }

            // Add click handler for book cards to toggle selection
            function handleBookCardClick(event) {
                // Don't trigger if clicking on checkbox directly
                if (event.target.type === 'checkbox') return;

                const bookCard = event.currentTarget;
                const checkbox = bookCard.querySelector('.book-checkbox');

                if (checkbox && !checkbox.disabled) {
                    checkbox.checked = !checkbox.checked;
                    updateSelection();
                }
            }

            // Initialize page
            function initializePage() {
                // Load stored selection and check appropriate checkboxes
                const storedSelection = loadSelection();
                const storedIds = storedSelection.map(book => book.id);

                document.querySelectorAll('.book-checkbox').forEach(checkbox => {
                    const bookId = checkbox.dataset.bookId;
                    if (storedIds.includes(bookId)) {
                        checkbox.checked = true;
                    }
                });

                // Add event listeners
                const selectAllBtn = document.getElementById('selectAllBooks');
                if (selectAllBtn) {
                    selectAllBtn.addEventListener('change', toggleSelectAll);
                }

                // Add click handlers to book cards
                document.querySelectorAll('.book-card').forEach(card => {
                    const checkbox = card.querySelector('.book-checkbox');
                    if (checkbox) {
                        card.addEventListener('click', handleBookCardClick);
                        card.style.cursor = 'pointer';
                    }
                });

                // Add escape key listener to close modal
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeOrderModal();
                    }
                });

                // Add click outside modal to close
                document.getElementById('orderModal').addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeOrderModal();
                    }
                });

                // Initial selection update
                updateSelection();
            }

            // Clear selection after successful order
            function clearSelectionAfterOrder() {
                clearStoredSelection();
                selectedBooks.clear();
                document.querySelectorAll('.book-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelection();
            }

            // Handle successful order submission
            document.addEventListener('DOMContentLoaded', function() {
                initializePage();

                // Listen for successful order submission
                @if (session('success'))
                    clearSelectionAfterOrder();
                @endif
            });
        </script>
    @endpush
@endsection
