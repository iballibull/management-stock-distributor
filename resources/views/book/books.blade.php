@extends('layouts.app', ['metaTitle' => 'Daftar Buku', 'parentSection' => 'book', 'elementName' => 'books'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('books.index') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600"
                        aria-current="page">Buku</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('books.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600" aria-current="page">Daftar
                        Buku</span></a>
            </div>
        </li>
    @endcomponent
    <div x-data="{ showFilter: {{ request()->input('category_id') || request()->input('curriculum_id') || request()->input('education_level_id') || request()->input('price') || request()->input('grade_number') || request()->input('semester') ? 'true' : 'false' }} }" class="col-span-full">
        @php
            $keepQuery = request()->only(['search', 'sort', 'direction']);
        @endphp
        <form action="{{ route('books.index') }}">
            <div
                class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
                <div class="w-full mb-1">
                    <div class="sm:flex">
                        <div class="items-center mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                            <div class="lg:pr-3">
                                <label for="users-search" class="sr-only">Search</label>
                                <div class="relative mt-1 lg:w-64 xl:w-96">
                                    <input type="text" name="search" id="users-search"
                                        value="{{ request('search') ?? '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Cari nama judul buku" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                            <button type="button" @click="showFilter = !showFilter"
                                class ="inline-flex items-center justify-center w-1/2 px-3 border border-gray-200
                            py-2 text-sm font-medium text-center text-gray-900 rounded-lg bg-white hover:bg-gray-100 hover:text-blue-700 active:text-blue-700
                            focus:ring-4 focus:ring-gray-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700
                            dark:focus:ring-blue-800">
                                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd"
                                        d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Filter
                            </button>
                            <button type="button" @click="window.location.href = '{{ route('books.create') }}'"
                                class="inline-flex
                                items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white
                                rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto
                                dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="showFilter" x-transition class="flex flex-wrap items-start justify-end gap-4">
                <div class="w-full min-w-[320px]">
                    <div class="flex flex-wrap gap-4 items-end bg-white p-4 max-w-full text-white">
                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="category"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="category_id">
                                <option value="" selected>Pilih Kategori</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" @selected(request('category_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="curriculum_id"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="curriculum_id">
                                <option value="" selected>Pilih Kurikulum</option>
                                @foreach ($curriculums as $id => $name)
                                    <option value="{{ $id }}" @selected(request('curriculum_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="educationLEvel"
                                class=" block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="education_level_id">
                                <option value="" selected>Pilih Tingkat Pendidikan</option>
                                @foreach ($educationLevels as $id => $name)
                                    <option value="{{ $id }}" @selected(request('education_level_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <input type="number" name="price" id="price"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-500 peer"
                                placeholder=" " autocomplete="off" value="{{ request('price') ?? '' }}" />
                            <label for="price"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Harga</label>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <input type="text" name="grade_number" id="grade_number"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-500 peer"
                                placeholder=" " autocomplete="off" value="{{ request('grade_number') ?? '' }}" />
                            <label for="grade_number"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Kelas</label>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="semester"
                                class=" block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="semester">
                                <option value="" selected>Pilih Semester</option>
                                <option value="1" @selected(request('semester') == 1)>1</option>
                                <option value="2" @selected(request('semester') == 2)>2</option>
                            </select>
                        </div>

                        <div class="w-full flex justify-end mt-2">
                            <button type="button"
                                @click="window.location.href = '{{ route('books.index', request()->only(['search', 'sort', 'direction'])) }}'"
                                class="mx-4 text-gray-900 border bg-white  focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Reset Filter
                            </button>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="flex flex-col">
            <div x-data="tableSort()">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow">
                            <table
                                class=" min-w-max w-full text-sm text-left rtl:text-right whitespace-nowrap text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                            No
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                            @sortablelink('title', 'Judul')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('educationLevel.name', 'Tingkat Pendidikan')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('grade_number', 'Kelas')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('curriculum.name', 'Kurikulum')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('price', 'Harga')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('semester', 'Semester')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[300px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach ($books as $key => $book)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 w-[50px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $books->firstItem() + $key }}
                                            </td>
                                            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap"
                                                title="{{ $book->title }}">
                                                <div class="flex-shrink-0">
                                                    <img class="w-12 h-12 object-cover rounded-md border border-gray-200 dark:border-gray-700"
                                                        src="{{ asset('storage/' . $book->image) }}"
                                                        alt="Foto Buku {{ $book->title }}">
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div
                                                        class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ \Illuminate\Support\Str::title($book->title) }}
                                                    </div>
                                                    <div
                                                        class="text-sm font-normal text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $book->category->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $book->educationLevel->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $book->grade_number }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $book->curriculum->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ 'Rp ' . number_format($book->price, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $book->semester }}</td>

                                            <td
                                                class="px-6 py-4 w-[300px] text-gray-900 font-medium dark:text-white text-center">
                                                <div class="inline-flex items-center gap-2">
                                                    <button type="button"
                                                        data-modal-target="edit-book-modal{{ $book->id }}"
                                                        data-modal-toggle="edit-book-modal{{ $book->id }}"
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
                                                        data-modal-target="delete-book-modal{{ $book->id }}"
                                                        data-modal-toggle="delete-book-modal{{ $book->id }}"
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
                                        </tr>

                                        <!-- Edit Kategori Modal -->
                                        <div class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/30 overflow-x-hidden overflow-y-auto"
                                            id="edit-book-modal{{ $book->id }}">
                                            <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                    <!-- Modal header -->
                                                    <div
                                                        class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700 border-gray-200">
                                                        <h3 class="text-xl font-semibold dark:text-white">
                                                            Edit Kategori
                                                        </h3>
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                            data-modal-toggle="edit-book-modal{{ $book->id }}">
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
                                                            action="{{ route('books.update', ['bookId' => $book->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="flex justify-center">
                                                                <div class="w-full max-w-md">
                                                                    <div class="grid grid-cols-1 gap-6">
                                                                        <!-- Input Nama -->
                                                                        <div>
                                                                            <label for="add_name"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                                Nama <span class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="text" id="add_name"
                                                                                placeholder="nama kategori" name="name"
                                                                                value="{{ $book->name }}"
                                                                                autocomplete="off"
                                                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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

                                        <!-- Delete categori Modal -->
                                        <div class="fixed inset-0 z-50 flex items-center justify-center min-h-screen hidden overflow-x-hidden overflow-y-auto"
                                            id="delete-book-modal{{ $book->id }}">
                                            <div class="relative w-full max-w-md px-4">
                                                <!-- Modal content -->
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                    <!-- Modal header -->
                                                    <div class="flex justify-end p-2">
                                                        <button type="button"
                                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                            data-modal-hide="delete-book-modal{{ $book->id }}">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="p-6 pt-0 text-center">
                                                        <form
                                                            action="{{ route('books.destroy', ['bookId' => $book->id]) }}"
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
                                                                Yakin ingin menghapus kategori
                                                                <b>{{ $book->name }}</b>?
                                                            </h3>

                                                            <!-- Tombol Submit -->
                                                            <button type="submit"
                                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800">
                                                                Ya, saya yakin
                                                            </button>

                                                            <!-- Tombol Batal -->
                                                            <button type="button"
                                                                class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                                                                data-modal-hide="delete-book-modal{{ $book->id }}">
                                                                Tidak, batal
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $books->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
