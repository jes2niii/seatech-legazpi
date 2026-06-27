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

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-[#003366] text-white flex-shrink-0 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto">
    <div class="flex items-center justify-between p-4 border-b border-[#004080]">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-10 w-10 rounded-full object-cover bg-white p-1">
            <div>
                <span class="text-white font-bold text-sm leading-tight block">SEATECH Legazpi</span>
                <span class="text-blue-200 text-xs leading-tight block">{{ ucfirst($p) }} Panel</span>
            </div>
        </a>
        <button
            type="button"
            @click="sidebarOpen = false"
            class="lg:hidden w-9 h-9 inline-flex items-center justify-center rounded-lg text-blue-200 hover:bg-[#004080] hover:text-white transition"
            aria-label="Close menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-73px)]">
        <a href="{{ route("$p.dashboard") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.dashboard") }}">
            <span>Dashboard</span>
        </a>

        @can('manage users')
        @if(Route::has("$p.users.index"))
        <a href="{{ route("$p.users.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.users.*") }}">
            <span>Users</span>
        </a>
        @endif
        @endcan

        @can('manage settings')
        @if(Route::has("$p.settings.edit"))
        <a href="{{ route("$p.settings.edit") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.settings.*") }}">
            <span>Site Settings</span>
        </a>
        @endif
        @endcan

        @if(Route::has("$p.activity-log.index"))
        <a href="{{ route("$p.activity-log.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.activity-log.*") }}">
            <span>Recent Changes</span>
        </a>
        @endif

        @can('manage courses')
        @if(Route::has("$p.categories.index"))
        <a href="{{ route("$p.categories.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.categories.*") }}">
            <span>Categories</span>
        </a>
        @endif
        <a href="{{ route("$p.courses.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.courses.*") }}">
            <span>Courses</span>
        </a>
        @endcan

        @can('manage schedules')
        <a href="{{ route("$p.schedules.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.schedules.*") }}">
            <span>Schedules</span>
        </a>
        @endcan

        @can('manage enrollments')
        @if(Route::has("$p.enrollments.index"))
        <a href="{{ route("$p.enrollments.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.enrollments.*") }}">
            <span>Enrollments</span>
        </a>
        @endif
        @if(Route::has("$p.students.index"))
        <a href="{{ route("$p.students.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.students.*") }}">
            <span>Students</span>
        </a>
        @endif
        @endcan

        <hr class="border-[#004080] my-2">

        @can('manage news')
        @if(Route::has("$p.news.index"))
        <a href="{{ route("$p.news.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.news.*") }}">
            <span>News</span>
        </a>
        @endif
        @endcan

        @can('manage gallery')
        @if(Route::has("$p.testimonials.index"))
        <a href="{{ route("$p.testimonials.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.testimonials.*") }}">
            <span>Testimonials</span>
        </a>
        @endif
        @if(Route::has("$p.facilities.index"))
        <a href="{{ route("$p.facilities.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.facilities.*") }}">
            <span>Facilities</span>
        </a>
        @endif
        @if(Route::has("$p.team.index"))
        <a href="{{ route("$p.team.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.team.*") }}">
            <span>Leadership Team</span>
        </a>
        @endif
        @if(Route::has("$p.core-values.index"))
        <a href="{{ route("$p.core-values.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.core-values.*") }}">
            <span>Core Values</span>
        </a>
        @endif
        @endcan

        @can('manage inquiries')
        @if(Route::has("$p.inquiries.index"))
        <a href="{{ route("$p.inquiries.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.inquiries.*") }}">
            <span>Inquiries</span>
        </a>
        @endif
        @endcan

        <hr class="border-[#004080] my-2">

        @can('manage certificates')
        @if(Route::has("$p.certificates.index"))
        <a href="{{ route("$p.certificates.index") }}" class="flex items-center pl-3 pr-4 py-2 rounded-r-lg text-sm transition {{ nav_link_class("$p.certificates.*") }}">
            <span>Certificates</span>
        </a>
        @endif
        @endcan
    </nav>
</aside>
