<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal - SEATECH')</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        @include('student.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        :aria-expanded="sidebarOpen.toString()"
                        aria-controls="student-mobile-nav"
                        aria-label="Open menu"
                        class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition flex-shrink-0">
                        <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <h1 class="text-base sm:text-lg font-semibold text-gray-800 truncate">@yield('page-title', 'Student Portal')</h1>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('home') }}" class="hidden sm:inline-block text-sm text-[#0077B6] hover:text-[#003366] font-medium">View Site</a>
                    <span class="hidden sm:inline text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Log out</button>
                    </form>
                </div>
            </header>

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
