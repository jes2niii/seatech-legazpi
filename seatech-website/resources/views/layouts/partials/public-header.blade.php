<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-10 w-auto">
                <div class="hidden sm:block">
                    <span class="text-[#003366] font-bold text-lg leading-tight block">{{ setting('short_name') }}</span>
                    <span class="text-[#0077B6] text-xs leading-tight block">{{ setting('tagline') }}</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class('home') }}">Home</a>
                <a href="{{ route('about') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class('about') }}">About</a>
                <a href="{{ route('courses') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class(['courses', 'courses.show']) }}">Courses</a>
                <a href="{{ route('calendar') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class('calendar') }}">Calendar</a>
                <a href="{{ route('facilities') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class('facilities') }}">Facilities</a>
                <a href="{{ route('news') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class(['news', 'news.show']) }}">News</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium border-b-2 py-1 transition {{ public_nav_link_class('contact') }}">Contact</a>
            </nav>

            <div class="flex items-center space-x-3">
                <button
                    type="button"
                    @click="mobileNavOpen = !mobileNavOpen"
                    :aria-expanded="mobileNavOpen.toString()"
                    aria-controls="public-mobile-nav"
                    aria-label="Open menu"
                    class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition">
                    <svg x-show="!mobileNavOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileNavOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-[#003366] hover:text-[#0077B6]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-medium text-gray-700 hover:text-[#003366]">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[#003366] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#002244] transition">Enroll Now</a>
                @endauth
            </div>
        </div>
    </div>

    <div
        x-show="mobileNavOpen"
        x-cloak
        x-transition.opacity
        @click="mobileNavOpen = false"
        class="lg:hidden fixed inset-0 bg-black/50 z-40"
        aria-hidden="true"
        style="display: none;"></div>

    <div
        id="public-mobile-nav"
        x-show="mobileNavOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="lg:hidden fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white shadow-xl overflow-y-auto"
        role="dialog"
        aria-label="Mobile navigation">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <span class="text-[#003366] font-bold text-lg">Menu</span>
            <button
                type="button"
                @click="mobileNavOpen = false"
                class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-[#003366] transition"
                aria-label="Close menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="flex flex-col px-3 py-4 space-y-1">
            <a href="{{ route('home') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('home') ? 'bg-blue-50 text-[#003366]' : '' }}">Home</a>
            <a href="{{ route('about') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('about') ? 'bg-blue-50 text-[#003366]' : '' }}">About</a>
            <a href="{{ route('courses') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('courses*') ? 'bg-blue-50 text-[#003366]' : '' }}">Courses</a>
            <a href="{{ route('calendar') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('calendar') ? 'bg-blue-50 text-[#003366]' : '' }}">Calendar</a>
            <a href="{{ route('facilities') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('facilities') ? 'bg-blue-50 text-[#003366]' : '' }}">Facilities</a>
            <a href="{{ route('news') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('news*') ? 'bg-blue-50 text-[#003366]' : '' }}">News</a>
            <a href="{{ route('contact') }}" @click="mobileNavOpen = false" class="px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-[#003366] transition {{ request()->routeIs('contact') ? 'bg-blue-50 text-[#003366]' : '' }}">Contact</a>

            <div class="pt-4 mt-2 border-t border-gray-200 space-y-2">
                @guest
                    <a href="{{ route('login') }}" @click="mobileNavOpen = false" class="block w-full text-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Log in</a>
                    <a href="{{ route('register') }}" @click="mobileNavOpen = false" class="block w-full text-center px-4 py-3 rounded-lg text-base font-medium bg-[#003366] text-white hover:bg-[#002244] transition">Enroll Now</a>
                @else
                    <a href="{{ route('dashboard') }}" @click="mobileNavOpen = false" class="block w-full text-center px-4 py-3 rounded-lg text-base font-medium bg-[#003366] text-white hover:bg-[#002244] transition">Go to Dashboard</a>
                @endguest
            </div>
        </nav>
    </div>
</header>
