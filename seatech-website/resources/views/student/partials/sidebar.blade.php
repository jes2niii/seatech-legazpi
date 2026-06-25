@php
    $p = 'student';
@endphp
<aside class="w-64 bg-[#003366] text-white flex-shrink-0 hidden lg:block">
    <div class="p-4 border-b border-[#004080]">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-10 w-10 rounded-full object-cover bg-white p-1">
            <div>
                <span class="text-white font-bold text-sm leading-tight block">SEATECH Legazpi</span>
                <span class="text-blue-200 text-xs leading-tight block">Student Portal</span>
            </div>
        </a>
    </div>

    <nav class="p-4 space-y-1">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs("$p.dashboard") ? 'bg-[#004080] text-white' : 'text-blue-200 hover:bg-[#004080] hover:text-white' }} transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route("$p.enrollments") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs("$p.enrollments") ? 'bg-[#004080] text-white' : 'text-blue-200 hover:bg-[#004080] hover:text-white' }} transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span>My Enrollments</span>
        </a>
        <a href="{{ route("$p.certificates") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs("$p.certificates") ? 'bg-[#004080] text-white' : 'text-blue-200 hover:bg-[#004080] hover:text-white' }} transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            <span>My Certificates</span>
        </a>

        <hr class="border-[#004080] my-2">

        <a href="{{ route('courses') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Browse Courses</span>
        </a>
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Back to Site</span>
        </a>
    </nav>
</aside>
