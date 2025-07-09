<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? 'CV. Inti Bina Ilmu' }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-800" data-success="{{ e(session('success')) }}"
    data-failed="{{ e(session('failed')) }}">
    @include('layouts.navbars.navigation')
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        @include('layouts.navbars.sidebar')

        <!-- Page Content -->
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main>
                <div class="px-4 pt-6">
                    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
                        @yield('content')
                    </div>
                </div>
            </main>
            @include('layouts.footers.footer')
        </div>
    </div>
    @stack('scripts')
</body>

</html>
