<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SEATECH Legazpi') }}</title>

        <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#F5F7FA]">
            <div class="text-center">
                <a href="/" class="inline-flex flex-col items-center">
                    <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-16 w-auto mb-3">
                    <span class="text-[#003366] font-bold text-xl leading-tight">SEATECH Legazpi</span>
                    <span class="text-[#0077B6] text-xs leading-tight mt-1 max-w-xs">Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <div class="h-1.5 bg-gradient-to-r from-[#003366] via-[#0077B6] to-[#D4A017]"></div>
                <div class="px-6 py-4">
                    {{ $slot }}
                </div>
            </div>

            <p class="mt-6 text-xs text-gray-400">&copy; {{ date('Y') }} SEATECH Legazpi. All rights reserved.</p>
        </div>
    </body>
</html>
