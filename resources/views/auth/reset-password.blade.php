@extends('layouts.guest', ['metaTitle' => 'Atur Ulang Password'])
@section('content')
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Atur Ulang Password
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="{{ route('password.store') }}" method="post">
                        @csrf
                        @method('POST')
                        <div>
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <input type="hidden" name="email" value="{{ $request->email }}">
                            <label for="password"
                                class="{{ $errors->has('password') ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500' : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white' }}">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                class="{{ $errors->has('password') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required="">
                            @error('password')
                                @if (!str_contains($message, 'Confirmation') && !str_contains($message, 'Konfirmasi'))
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                        <span class="font-medium">{{ $message }}</span>
                                    </p>
                                @endif
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="{{ $errors->has('password') ? 'block mb-2 text-sm font-medium text-red-700 dark:text-red-500' : 'block mb-2 text-sm font-medium text-gray-900 dark:text-white' }}">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="••••••••"
                                class="{{ $errors->has('password') ? 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-600 focus:border-blue-600' }} rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required="">
                            @error('password')
                                @if (str_contains($message, 'Confirmation') || str_contains($message, 'Konfirmasi'))
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                        <span class="font-medium">{{ $message }}</span>
                                    </p>
                                @endif
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atur
                            Ulang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
