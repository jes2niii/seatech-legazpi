<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SEATECH Maritime Training & Assessment Center')</title>
    <meta name="description" content="@yield('meta_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol')">
    @stack('meta')
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 bg-white">
    <div class="min-h-screen flex flex-col">
        @include('layouts.partials.public-header')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('layouts.partials.public-footer')
    </div>

    @stack('scripts')
</body>
</html>
