<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('name', 'SEATECH Maritime Training & Assessment Center'))</title>
    <meta name="description" content="@yield('meta_description', setting('seo.default_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol'))">
    @stack('meta')
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#003366">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="SEATECH">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icon-192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/icon-512.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', setting('name', 'SEATECH Maritime Training & Assessment Center'))">
    <meta property="og:description" content="@yield('og_description', setting('seo.default_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.webp'))">
    <meta property="og:site_name" content="SEATECH Maritime Training">
    <meta property="og:locale" content="en_PH">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', setting('name', 'SEATECH Maritime Training & Assessment Center'))">
    <meta name="twitter:description" content="@yield('og_description', setting('seo.default_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo.webp'))">
    @stack('jsonld')
</head>
<body class="font-sans antialiased text-gray-800 bg-white" x-data="{ mobileNavOpen: false }">
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
