@extends('layouts.app', ['metaTitle' => 'Tingkat Pendidikan', 'parentSection' => 'book', 'elementName' => 'educationLevel'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('education.level.index') }}"><span
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
                <a href="{{ route('education.level.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600" aria-current="page">Tingkat
                        Pendidikan</span></a>
            </div>
        </li>
    @endcomponent
    <div class="col-span-full">
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <div class="sm:flex">
                    <div class="items-center mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <form class="lg:pr-3" action="{{ route('education.level.index') }}" method="GET">
                            <label for="users-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input type="text" name="search" id="users-search" autocomplete="off"
                                    value="{{ request('search') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Cari nama tingkat pendidikan">
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                        <button type="button" data-modal-target="add-education-level-modal"
                            data-modal-toggle="add-education-level-modal"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
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

        <div class="flex flex-col">
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
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        @sortablelink('name', 'Nama')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[300px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach ($educationLevels as $key => $educationLevel)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 w-[50px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $educationLevels->firstItem() + $key }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $educationLevel->name }}</td>
                                        <td
                                            class="px-6 py-4 w-[300px] text-gray-900 font-medium dark:text-white text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <button type="button"
                                                    data-modal-target="edit-education-level-modal{{ $educationLevel->id }}"
                                                    data-modal-toggle="edit-education-level-modal{{ $educationLevel->id }}"
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
                                                    data-modal-target="delete-education-level{{ $educationLevel->id }}"
                                                    data-modal-toggle="delete-education-level{{ $educationLevel->id }}"
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

                                    <!-- Edit Education Level Modal -->
                                    <div class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/30 overflow-x-hidden overflow-y-auto"
                                        id="edit-education-level-modal{{ $educationLevel->id }}">
                                        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                <!-- Modal header -->
                                                <div
                                                    class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700 border-gray-200">
                                                    <h3 class="text-xl font-semibold dark:text-white">
                                                        Edit Tingkat Pendidikan
                                                    </h3>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                        data-modal-toggle="edit-education-level-modal{{ $educationLevel->id }}">
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
                                                        action="{{ route('education.level.update', ['educationLevelId' => $educationLevel->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="flex justify-center">
                                                            <div class="w-full max-w-md">
                                                                <div class="grid grid-cols-1 gap-6">
                                                                    <!-- Input Nama -->
                                                                    <div>
                                                                        <label for="edit_name"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                            Nama <span class="text-red-500">*</span>
                                                                        </label>
                                                                        <input type="text" id="edit_name"
                                                                            placeholder="nama tingkat pendidikan"
                                                                            name="name"
                                                                            value="{{ $educationLevel->name }}" required
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

                                    <!-- Delete Education Level Modal -->
                                    <div class="fixed inset-0 z-50 flex items-center justify-center min-h-screen hidden overflow-x-hidden overflow-y-auto"
                                        id="delete-education-level{{ $educationLevel->id }}">
                                        <div class="relative w-full max-w-md px-4">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                <!-- Modal header -->
                                                <div class="flex justify-end p-2">
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                        data-modal-hide="delete-education-level{{ $educationLevel->id }}">
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
                                                        action="{{ route('education.level.destroy', ['educationLevelId' => $educationLevel->id]) }}"
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
                                                            Yakin ingin menghapus tingkat pendidikan
                                                            <b>{{ $educationLevel->name }}</b>?
                                                        </h3>

                                                        <!-- Tombol Submit -->
                                                        <button type="submit"
                                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800">
                                                            Ya, saya yakin
                                                        </button>

                                                        <!-- Tombol Batal -->
                                                        <button type="button"
                                                            class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                                                            data-modal-hide="delete-education-level{{ $educationLevel->id }}">
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
                        {{ $educationLevels->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Education Level Modal -->
        <div class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/30 overflow-x-hidden overflow-y-auto"
            id="add-education-level-modal">
            <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div
                        class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700 border-gray-200">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Tambah Tingkat Pendidikan
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                            data-modal-toggle="add-education-level-modal">
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
                        <form action="{{ route('education.level.store') }}" method="post">
                            @csrf
                            @method('POST')
                            <div class="flex justify-center">
                                <div class="w-full max-w-md">
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Input Nama -->
                                        <div>
                                            <label for="edit_name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Nama <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="edit_name" placeholder="nama kurikulum"
                                                name="name" autocomplete="off" required
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex justify-end items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="submit">
                            Tambah
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
