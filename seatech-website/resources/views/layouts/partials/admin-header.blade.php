<header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between flex-shrink-0">
    <div class="flex items-center gap-3 min-w-0">
        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            :aria-expanded="sidebarOpen.toString()"
            aria-controls="admin-sidebar"
            aria-label="Open menu"
            class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition flex-shrink-0">
            <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h1 class="text-base sm:text-lg font-semibold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-3 flex-shrink-0">
        <a href="{{ route('home') }}" class="hidden sm:inline-block text-sm text-[#0077B6] hover:text-[#003366] font-medium">View Site</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Log out</button>
        </form>
    </div>
</header>
