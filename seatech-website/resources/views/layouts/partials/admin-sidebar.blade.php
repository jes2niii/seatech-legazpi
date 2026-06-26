@php
    $role = auth()->user()->roles->first()->name ?? '';
    $p = match($role) {
        'Super Admin' => 'admin',
        'Registrar' => 'registrar',
        'Training Coordinator' => 'coordinator',
        'Instructor' => 'instructor',
        default => 'admin',
    };
@endphp

<aside class="w-64 bg-[#003366] text-white flex-shrink-0 hidden lg:block sticky top-0 h-screen overflow-y-auto">
    <div class="p-4 border-b border-[#004080]">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-10 w-10 rounded-full object-cover bg-white p-1">
            <div>
                <span class="text-white font-bold text-sm leading-tight block">SEATECH Legazpi</span>
                <span class="text-blue-200 text-xs leading-tight block">{{ ucfirst($p) }} Panel</span>
            </div>
        </a>
    </div>

    <nav class="p-4 space-y-1">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Dashboard</span>
        </a>

        @can('manage users')
        @if(Route::has("$p.users.index"))
        <a href="{{ route("$p.users.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Users</span>
        </a>
        @endif
        @endcan

        @can('manage settings')
        @if(Route::has("$p.settings.edit"))
        <a href="{{ route("$p.settings.edit") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Site Settings</span>
        </a>
        @endif
        @endcan

        @can('manage courses')
        @if(Route::has("$p.categories.index"))
        <a href="{{ route("$p.categories.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Categories</span>
        </a>
        @endif
        <a href="{{ route("$p.courses.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Courses</span>
        </a>
        @endcan

        @can('manage schedules')
        <a href="{{ route("$p.schedules.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Schedules</span>
        </a>
        @endcan

        @can('manage enrollments')
        @if(Route::has("$p.enrollments.index"))
        <a href="{{ route("$p.enrollments.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Enrollments</span>
        </a>
        @endif
        @if(Route::has("$p.students.index"))
        <a href="{{ route("$p.students.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Students</span>
        </a>
        @endif
        @endcan

        <hr class="border-[#004080] my-2">

        @can('manage news')
        @if(Route::has("$p.news.index"))
        <a href="{{ route("$p.news.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>News</span>
        </a>
        @endif
        @endcan

        @can('manage gallery')
        @if(Route::has("$p.testimonials.index"))
        <a href="{{ route("$p.testimonials.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Testimonials</span>
        </a>
        @endif
        @if(Route::has("$p.facilities.index"))
        <a href="{{ route("$p.facilities.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Facilities</span>
        </a>
        @endif
        @if(Route::has("$p.team.index"))
        <a href="{{ route("$p.team.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Leadership Team</span>
        </a>
        @endif
        @if(Route::has("$p.core-values.index"))
        <a href="{{ route("$p.core-values.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Core Values</span>
        </a>
        @endif
        @endcan

        @can('manage inquiries')
        @if(Route::has("$p.inquiries.index"))
        <a href="{{ route("$p.inquiries.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Inquiries</span>
        </a>
        @endif
        @endcan

        <hr class="border-[#004080] my-2">

        @can('manage certificates')
        @if(Route::has("$p.certificates.index"))
        <a href="{{ route("$p.certificates.index") }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-[#004080] hover:text-white transition">
            <span>Certificates</span>
        </a>
        @endif
        @endcan
    </nav>
</aside>
