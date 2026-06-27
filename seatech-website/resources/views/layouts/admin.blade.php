<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - SEATECH')</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        @include('layouts.partials.admin-sidebar')

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            @include('layouts.partials.admin-header')

            <main class="flex-1 p-4 sm:p-6 min-w-0">
                @yield('content')
            </main>
        </div>
    </div>

    <div
        x-show="sidebarOpen"
        x-cloak
        x-transition.opacity
        @click="sidebarOpen = false"
        class="lg:hidden fixed inset-0 bg-black/50 z-30"
        aria-hidden="true"></div>

    @stack('scripts')
</body>
</html>
