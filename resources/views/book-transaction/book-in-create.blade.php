@extends('layouts.app', ['metaTitle' => 'Tambah Stok Buku', 'parentSection' => 'bookTransaction', 'elementName' => 'bookTransactionCreate'])

@push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: white !important;
            border: 1px solid rgb(209 213 219) !important;
            border-radius: 0.5rem !important;
            height: 42px !important;
            font-size: 0.875rem !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(17 24 39) !important;
            line-height: 1 !important;
            padding-left: 12px !important;
            padding-right: 60px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            max-width: calc(100% - 70px) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 12px !important;
            top: 1px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            color: rgb(107 114 128) !important;
            cursor: pointer !important;
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-weight: bold !important;
            font-size: 18px !important;
            line-height: 1 !important;
            width: 20px !important;
            height: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 2 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: rgb(75 85 99) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgb(156 163 175) !important;
            font-size: 0.875rem !important;
            line-height: 1 !important;
            padding-left: 0 !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .select2-dropdown {
            border: 1px solid rgb(209 213 219) !important;
            border-radius: 0.5rem !important;
            z-index: 9999 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid rgb(209 213 219) !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(59 130 246) !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: rgb(59 130 246) !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
            outline: none !important;
        }
    </style>
@endpush
@section('content')
    {{-- Breadcrumb navigation untuk menunjukkan posisi halaman --}}
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('book.stock.in.create') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Transaksi Buku</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('book.stock.in.create') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Tambah
                        Stok Buku</span></a>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        {{-- Section Header untuk pemilihan semester --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-6">
            <div class="p-6">
                <div class="flex flex-col space-y-4">
                    <h1 class="font-bold text-xl">Tambah Stok Buku</h1>

                    {{-- Form pilihan semester dengan Select2 --}}
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Pilih Semester <span class="text-red-500">*</span>
                        </label>

                        {{-- Select dengan Select2 (sama seperti pilih buku) --}}
                        <select id="semester_id" name="semester_id" class="select2" required>
                            <option value=""></option>
                            @foreach ($semesters as $id => $name)
                                <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Section - Hanya muncul setelah memilih semester --}}
        <div id="book-form-section"
            class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 hidden">
            <div class="p-6">
                {{-- Form utama untuk transaksi buku --}}
                <form id="book-transaction-form" action="{{ route('book.stock.in.store') }}" method="POST">
                    @csrf
                    @method('POST')

                    {{-- Hidden input untuk menyimpan ID semester yang dipilih --}}
                    <input type="hidden" name="semester_id" value="">

                    {{-- Header dengan tombol tambah buku --}}
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Buku</h2>
                        {{-- Tombol untuk menambah item buku baru --}}
                        <button type="button" id="add-book-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    {{-- Container untuk menampung multiple item buku --}}
                    <div id="book-items-container" class="space-y-4">
                        {{-- Item buku akan ditambahkan secara dinamis via JavaScript --}}
                    </div>

                    {{-- Tombol submit untuk menyimpan transaksi --}}
                    <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Tambah Stok Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Placeholder yang ditampilkan ketika belum memilih semester --}}
        <div id="placeholder-section"
            class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    {{-- Icon buku --}}
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pilih Semester Terlebih Dahulu</h3>
                    <p>Silakan pilih semester untuk mulai menambah stok buku</p>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi element
            const semesterSelect = document.getElementById('semester_id');
            const selectedSemesterInput = document.querySelector('input[name="semester_id"]');
            const bookFormSection = document.getElementById('book-form-section');
            const placeholderSection = document.getElementById('placeholder-section');
            const addBookBtn = document.getElementById('add-book-btn');
            const bookItemsContainer = document.getElementById('book-items-container');

            let bookItemIndex = 0;

            // Initialize Select2 untuk semester
            setTimeout(function() {
                $('#semester_id').select2({
                    placeholder: 'Cari dan pilih semester...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('body'),
                    language: {
                        noResults: function() {
                            return "Semester tidak ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                });
            }, 50);

            // Event listener untuk perubahan semester
            $('#semester_id').on('change', function() {
                const selectedValue = this.value;
                selectedSemesterInput.value = selectedValue;

                if (selectedValue) {
                    showBookForm();
                } else {
                    hideBookForm();
                }
            });

            // Fungsi untuk menampilkan form buku
            function showBookForm() {
                placeholderSection.classList.add('hidden');
                bookFormSection.classList.remove('hidden');

                if (bookItemsContainer.children.length === 0) {
                    addBookItem();
                }
            }

            // Fungsi untuk menyembunyikan form buku
            function hideBookForm() {
                placeholderSection.classList.remove('hidden');
                bookFormSection.classList.add('hidden');

                // Clear book items
                bookItemsContainer.innerHTML = '';
                bookItemIndex = 0;
            }

            // Fungsi untuk generate HTML dengan error handling
            function generateBookItemHTML(currentIndex) {
                const errors = @json($errors->getMessages());

                // Helper function untuk check error
                function hasError(field) {
                    return errors[`books.${currentIndex}.${field}`] !== undefined;
                }

                // Helper function untuk get error message
                function getErrorMessage(field) {
                    const errorKey = `books.${currentIndex}.${field}`;
                    return errors[errorKey] ? errors[errorKey][0] : '';
                }

                // Helper function untuk get CSS class
                function getInputClass(field) {
                    const baseClass =
                        'text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';

                    if (hasError(field)) {
                        return 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 ' +
                            baseClass;
                    } else {
                        return 'bg-white border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600 ' +
                            baseClass;
                    }
                }

                // Helper function untuk generate error HTML
                function getErrorHTML(field) {
                    if (hasError(field)) {
                        return `
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        <span class="font-medium">${getErrorMessage(field)}</span>
                    </p>
                `;
                    }
                    return '';
                }

                // Get old values
                const oldBooks = @json(old('books') ?? []);
                const oldBookData = oldBooks[currentIndex] || {};

                return `
        <div class="book-item bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600" data-index="${currentIndex}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-md font-medium text-gray-900 dark:text-white">Buku #${currentIndex + 1}</h3>
                <button type="button" class="remove-book-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" ${currentIndex === 0 ? 'style="display: none;"' : ''}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div>
                    <label for="book_${currentIndex}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Pilih Buku <span class="text-red-500">*</span>
                    </label>
                    <select name="books[${currentIndex}][book_id]" id="book_${currentIndex}" required
                        class="book-select ${getInputClass('book_id')}">
                        <option value="">Pilih Buku</option>
                        @foreach ($books ?? [] as $book)
                            <option value="{{ $book->id }}">{{ $book->getFormattedTitleAttribute() }}</option>
                        @endforeach
                    </select>
                    ${getErrorHTML('book_id')}
                </div>
                
                <div>
                    <label for="quantity_${currentIndex}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="books[${currentIndex}][quantity]" id="quantity_${currentIndex}" 
                        min="1" max="10000" required placeholder="Masukkan jumlah" autocomplete="off"
                        class="${getInputClass('quantity')}"
                        value="${oldBookData.quantity || ''}">
                    ${getErrorHTML('quantity')}
                </div>
                
                <div>
                    <label for="unit_price_${currentIndex}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Harga Beli / Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="books[${currentIndex}][unit_price]" id="unit_price_${currentIndex}" 
                        min="100" step="1" required placeholder="Rp 0" autocomplete="off"
                        class="${getInputClass('unit_price')}"
                        value="${oldBookData.unit_price || ''}">
                    ${getErrorHTML('unit_price')}
                </div>
                
                <div>
                    <label for="mutation_percentage_${currentIndex}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Persentase Mutasi <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="books[${currentIndex}][mutation_percentage]" id="mutation_percentage_${currentIndex}" 
                        min="0" max="100" step="0.01" required placeholder="0.0" autocomplete="off"
                        class="${getInputClass('mutation_percentage')}"
                        value="${oldBookData.mutation_percentage || ''}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estimasi persentase buku yang bisa dimutasi (%)</p>
                    ${getErrorHTML('mutation_percentage')}
                </div>
                
                <div>
                    <label for="return_percentage_${currentIndex}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Persentase Retur <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="books[${currentIndex}][return_percentage]" id="return_percentage_${currentIndex}" 
                        min="0" max="100" step="0.01" required placeholder="0.0" autocomplete="off"
                        class="${getInputClass('return_percentage')}"
                        value="${oldBookData.return_percentage || ''}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estimasi persentase buku yang bisa diretur (%)</p>
                    ${getErrorHTML('return_percentage')}
                </div>
            </div>
        </div>
        `;
            }

            // Update fungsi addBookItem
            function addBookItem() {
                const currentIndex = bookItemIndex;
                const bookItemHtml = generateBookItemHTML(currentIndex);

                bookItemsContainer.insertAdjacentHTML('beforeend', bookItemHtml);

                // Initialize Select2 setelah element ditambahkan
                initializeSelect2ForNewItem(currentIndex);

                bookItemIndex++;
                updateRemoveButtons();
            }

            // Fungsi untuk inisialisasi Select2 pada item buku baru
            function initializeSelect2ForNewItem(index) {
                setTimeout(function() {
                    $(`#book_${index}`).select2({
                        placeholder: 'Cari dan pilih buku...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('body'),
                        language: {
                            noResults: function() {
                                return "Buku tidak ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });

                    // Set selected value jika ada old data
                    const oldBooks = @json(old('books') ?? []);
                    if (oldBooks[index] && oldBooks[index].book_id) {
                        $(`#book_${index}`).val(oldBooks[index].book_id).trigger('change');
                    }
                }, 50);
            }

            // Fungsi untuk menghapus item buku
            function removeBookItem(element) {
                const bookItem = element.closest('.book-item');
                const selectElement = bookItem.querySelector('.book-select');

                // Destroy Select2 sebelum menghapus
                if ($(selectElement).hasClass('select2-hidden-accessible')) {
                    $(selectElement).select2('destroy');
                }

                bookItem.remove();
                updateRemoveButtons();
                updateBookNumbers();
                bookItemIndex--;
            }

            // Fungsi untuk update tombol hapus
            function updateRemoveButtons() {
                const bookItems = bookItemsContainer.querySelectorAll('.book-item');
                bookItems.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-book-btn');
                    if (bookItems.length <= 1) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                    }
                });
            }

            // Fungsi untuk update nomor buku
            function updateBookNumbers() {
                const bookItems = bookItemsContainer.querySelectorAll('.book-item');
                bookItems.forEach((item, index) => {
                    const title = item.querySelector('h3');
                    title.textContent = `Buku #${index + 1}`;
                });
            }

            // Fungsi untuk restore old data setelah validation error
            function restoreOldData() {
                @if (old('semester_id'))
                    const oldSemesterId = '{{ old('semester_id') }}';
                    $('#semester_id').val(oldSemesterId).trigger('change');
                    selectedSemesterInput.value = oldSemesterId;
                    showBookForm();

                    @if (old('books'))
                        const oldBooks = @json(old('books'));

                        setTimeout(() => {
                            bookItemsContainer.innerHTML = '';
                            bookItemIndex = 0;

                            // Add book items berdasarkan jumlah old data
                            Object.keys(oldBooks).forEach((key, index) => {
                                addBookItem
                                    (); // currentIndex tersedia karena di dalam scope yang sama
                            });
                        }, 200);
                    @endif
                @endif
            }

            // Event listeners
            addBookBtn.addEventListener('click', addBookItem);

            bookItemsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-book-btn')) {
                    removeBookItem(e.target);
                }
            });

            // Panggil fungsi restore di akhir DOMContentLoaded
            setTimeout(restoreOldData, 300);
        });
    </script>
@endpush
