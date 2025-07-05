@extends('layouts.app', ['metaTitle' => 'Update Buku', 'parentSection' => 'book', 'elementName' => 'books'])

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
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Buku</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('books.index') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Daftar
                        Buku</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('books.edit', ['bookId' => $book->id]) }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Edit Buku</span></a>
            </div>
        </li>
    @endcomponent
    <div class="col-span-full">
        <div class="bg-white dark:bg-gray-900 rounded-lg">
            <div class="py-8 px-4 mx-auto max-w-6xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit Buku</h2>
                <form action="{{ route('books.update', ['bookId' => $book->id]) }}" method="post"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div>
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul
                                Buku <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title"
                                class="{{ $errors->has('title') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Judul Buku" autocomplete="off"
                                value="{{ old('title', Str::title($book->title)) }}" required="">

                            @error('title')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="category_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required
                                class="{{ $errors->has('category_id') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected="" value="">Pilih Kategori</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('category_id', $book->category->id) == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="education_level_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tingkat
                                Pendidikan <span class="text-red-500">*</span></label>
                            <select id="education_level_id" name="education_level_id" required
                                class="{{ $errors->has('education_level_id') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"">
                                <option selected="" value="">Pilih Tingkat Pendidikan</option>
                                @foreach ($educationLevels as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('education_level_id', $book->category_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @error('education_level_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="curriculum_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kurikulum<span
                                    class="text-red-500">*</span></label>
                            <select id="curriculum_id" name="curriculum_id" required
                                class="{{ $errors->has('education_level_id') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"">
                                <option selected="" value="">Pilih Kurikulum</option>
                                @foreach ($curriculums as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('curriculum_id', $book->curriculum_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @error('curriculum_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price"
                                class="{{ $errors->has('price') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Harga Buku" autocomplete="off" value="{{ old('price', $book->price) }}"
                                required>
                            @error('price')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="grade_level"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas <span
                                    class="text-red-500">*</span></label>
                            <select id="grade_number" name="grade_number" required
                                class="{{ $errors->has('grade_number') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"">
                                <option selected="" value="">Pilih Kelas</option>
                                <option value="1" @selected(old('grade_number', $book->grade_number) == '1')>1</option>
                                <option value="2" @selected(old('grade_number', $book->grade_number) == '2')>2</option>
                                <option value="3" @selected(old('grade_number', $book->grade_number) == '3')>3</option>
                                <option value="4" @selected(old('grade_number', $book->grade_number) == '4')>4</option>
                                <option value="5" @selected(old('grade_number', $book->grade_number) == '5')>5</option>
                                <option value="6" @selected(old('grade_number', $book->grade_number) == '6')>6</option>
                                <option value="7" @selected(old('grade_number', $book->grade_number) == '7')>7</option>
                                <option value="8" @selected(old('grade_number', $book->grade_number) == '8')>8</option>
                                <option value="9" @selected(old('grade_number', $book->grade_number) == '9')>9</option>
                                <option value="10" @selected(old('grade_number', $book->grade_number) == '10')>10</option>
                                <option value="11" @selected(old('grade_number', $book->grade_number) == '11')>11</option>
                                <option value="12" @selected(old('grade_number', $book->grade_number) == '12')>12</option>
                                <option value="BESAR" @selected(old('grade_number', $book->grade_number) == 'BESAR')>BESAR</option>
                                <option value="KECIL" @selected(old('grade_number', $book->grade_number) == 'KECIL')>KECIL</option>
                            </select>
                            @error('grade_number')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="semester"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester <span
                                    class="text-red-500">*</span></label>
                            <select id="semester" name="semester" required
                                class="{{ $errors->has('semester') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"">
                                <option selected="" value="">Pilih Semester</option>
                                <option value="1" @selected(old('semester', $book->semester) == '1')>1</option>
                                <option value="2" @selected(old('semester', $book->semester) == '2')>2</option>
                            </select>
                            @error('semester')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Gambar
                            </label>
                            <input
                                class="{{ $errors->has('image') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'text-gray-900 border border-gray-300 bg-gray-50' }} rounded-lg block w-full text-sm cursor-pointer
                                 dark:text-gray-400 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                                file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-100
                                hover:file:bg-gray-100 hover:file:text-gray-700"
                                accept="image/jpeg, image/png" id="image" type="file" aria-describedby="photo"
                                name="image">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300" id="image">
                                JPG,JPEG,PNG (MAX. 2MB).
                            </p>
                            @error('image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    <span class="font-medium">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-span-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                            Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
