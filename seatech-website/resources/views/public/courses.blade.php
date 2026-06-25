@extends('layouts.public')

@section('title', 'Training Programs - SEATECH Maritime Training')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Training Programs</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">STCW-compliant maritime training courses designed to equip seafarers with the knowledge and skills to excel at sea.</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($categories->count() > 0)
            <div class="flex flex-wrap items-center gap-2 mb-10">
                <span class="text-sm font-medium text-gray-700 mr-2">Filter by category:</span>
                <a href="{{ route('courses') }}" class="px-4 py-2 rounded-full text-sm font-medium bg-[#003366] text-white">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('courses') }}?category={{ $cat->id }}" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-[#0077B6] hover:text-white transition">{{ $cat->name }}</a>
                @endforeach
            </div>
        @endif

        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group flex flex-col">
                        <div class="h-40 relative overflow-hidden">
                            @if($course->hasMedia('featured_image'))
                                <img src="{{ $course->getFirstMediaUrl('featured_image') }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center">
                                    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23fff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M20 0L40 20 20 40 0 20z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                                    <div class="relative text-center px-4">
                                        <span class="text-[#D4A017] font-bold text-xs uppercase tracking-widest block mb-1">{{ $course->code }}</span>
                                        <h3 class="text-white font-bold text-lg leading-tight">{{ $course->title }}</h3>
                                    </div>
                                </div>
                            @endif
                            @if($course->category)
                                <span class="absolute top-3 right-3 bg-[#D4A017]/90 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $course->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center justify-between mb-3 text-sm">
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $course->duration ?: 'TBA' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[#D4A017] font-bold">
                                    <span>PHP {{ number_format($course->fee, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4 flex-1 line-clamp-3">{{ $course->description }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('courses.show', $course) }}" class="flex-1 text-center bg-[#003366] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#002244] transition">View Details</a>
                                <a href="{{ route('enroll.step1') }}" class="flex-1 text-center bg-[#D4A017] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#b8890f] transition">Enroll</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-[#F5F7FA] rounded-2xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-gray-500 text-lg">No training programs available at the moment.</p>
                <p class="text-gray-400 text-sm mt-2">Please check back later.</p>
            </div>
        @endif
    </div>
</section>
@endsection
