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
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">Home</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">About</a>
                <a href="{{ route('courses') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">Courses</a>
                <a href="{{ route('calendar') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">Calendar</a>
                <a href="{{ route('facilities') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">Facilities</a>
                <a href="{{ route('news') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">News</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366] transition">Contact</a>
            </nav>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-[#003366] hover:text-[#0077B6]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-[#003366]">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[#003366] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#002244] transition">Enroll Now</a>
                @endauth
            </div>
        </div>
    </div>
</header>
