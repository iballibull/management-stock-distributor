@extends('layouts.app', ['metaTitle' => 'Tambah User', 'parentSection' => 'userManagement', 'elementName' => 'addUser'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('invite.create') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600" aria-current="page">Tambah
                        User</span></a>
            </div>
        </li>
    @endcomponent
    <div class="col-span-full">
        <div
            class="bg-white mx-auto w-full sm:max-w-lg rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Kirim Undangan Daftar
                </h1>
                <p class="text-gray-500 mt-0">
                    Silakan masukkan email dan pilih role pengguna yang ingin ditambahkan. Pengguna akan mendaftar
                    melalui
                    tautan yang dikirimkan ke email tersebut.
                </p>

                <form class="max-w-lg mx-auto" method="post" action="{{ route('invite.store') }}">
                    @csrf
                    @method('POST')
                    <div>
                        <label for="email-address-icon"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                    <path
                                        d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                                    <path
                                        d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                                </svg>
                            </div>
                            <input type="email" id="email-address-icon"
                                class="bg-gray-50 border mb-8 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="nama@gmail.com" name="email" autocomplete="off" required="">
                        </div>
                    </div>
                    <div>
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                            role <span class="text-red-500">*</span></label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-8"
                            required="" name="role_id">
                            <option selected>Pilih role </option>
                            @foreach ($roles as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
