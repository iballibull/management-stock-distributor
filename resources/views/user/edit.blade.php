@extends('layouts.app', ['metaTitle' => 'Profile', 'parentSection' => '', 'elementName' => ''])
@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('user.edit') }}"><span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600"
                        aria-current="page">Profile</span></a>
            </div>
        </li>
    @endcomponent
    @php
        $user = Auth::user();
    @endphp
    <div class="col-span-full flex gap-4  items-stretch xl:col-auto">
        <div
            class="p-4 mb-4 bg-white border w-full border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flow-root">
                <h3 class="text-xl font-semibold dark:text-white">Profile</h3>
            </div>
            <div class="flex justify-center items-center h-40">
                <img class="w-32 h-32 rounded-full object-cover bg-gray-100"
                    src="{{ asset('storage/' . ($user->photo ?? 'photos/default.png')) }}" alt="foto-profile">
            </div>

            <div class="flex justify-center">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user->name }}</h5>
            </div>
            <div class="flex justify-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
            </div>
        </div>
    </div>
    <div class="col-span-2 ">
        <div
            class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <h3 class="mb-4 text-xl font-semibold dark:text-white">Update Profile</h3>
            <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="grid
                grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required value="{{ $user->name }}" autocomplete="off">
                        @if ($errors->has('name'))
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $errors->updateProfile->first('name') }}</span>
                            </p>
                        @endif
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required value="{{ $user->email }}" autocomplete="off">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $errors->first('email') }}</span>
                            </p>
                        @endif
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="file_input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Upload Profile
                        </label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer
                                bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                                dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                                file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-100
                                hover:file:bg-gray-100 hover:file:text-gray-700"
                            accept="image/jpeg, image/png" id="file_input" type="file" aria-describedby="photo"
                            name="photo">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-300" id="photo">
                            JPG,JPEG,PNG (MAX. 2MB).
                        </p>
                        @if ($errors->has('photo'))
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                <span class="font-medium">{{ $errors->first('photo') }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="col-span-6 sm:col-full flex justify-end">
                        <button
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div
        class="col-span-full p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="mb-4 text-xl font-semibold dark:text-white">Update Password</h3>
        <form action="{{ route('password.update') }}" method="post">
            @method('PUT')
            @csrf
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="current-password"
                        class="{{ $errors->updatePassword->has('current_password') ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500' : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white' }}">
                        Password sekarang <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" id="current-password"
                        class="{{ $errors->updatePassword->has('current_password') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500' }} shadow-sm sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="••••••••" required autocomplete="current-password">
                    @if ($errors->updatePassword->has('current_password'))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                            <span class="font-medium">{{ $errors->updatePassword->first('current_password') }}</span>
                        </p>
                    @endif
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="password"
                        class="{{ $errors->updatePassword->has('password') ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500' : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white' }}">
                        Password baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="{{ $errors->updatePassword->has('password') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500' }} shadow-sm sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="••••••••" required autocomplete="new-password">
                    @if (
                        $errors->updatePassword->has('password') &&
                            (!str_contains($errors->updatePassword->first('password'), 'Konfirmasi') &&
                                !str_contains($errors->updatePassword->first('password'), 'Confirmation')))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                            <span class="font-medium">{{ $errors->updatePassword->first('password') }}</span>
                        </p>
                    @endif
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="password_confirmation"
                        class="{{ $errors->updatePassword->has('password') ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500' : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white' }}">
                        Konfirmasi password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="{{ $errors->updatePassword->has('password') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500' }} shadow-sm sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="••••••••" required autocomplete="new-password">
                    @if (
                        $errors->updatePassword->has('password') &&
                            (str_contains($errors->updatePassword->first('password'), 'Konfirmasi') ||
                                str_contains($errors->updatePassword->first('password'), 'Confirmation')))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                            <span class="font-medium">{{ $errors->updatePassword->first('password') }}</span>
                        </p>
                    @endif
                </div>

                <div class="col-span-6 flex justify-end">
                    <button
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="submit">Ubah Password</button>
                </div>
            </div>
    </div>
@endsection
