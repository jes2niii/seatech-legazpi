@extends('layouts.public')

@section('title', $course->title . ' - SEATECH Maritime Training')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center gap-2 text-blue-200 text-sm mb-3">
            <a href="{{ route('courses') }}" class="hover:text-white">Training Programs</a>
            <span>/</span>
            @if($course->category)
                <span>{{ $course->category->name }}</span>
                <span>/</span>
            @endif
            <span class="text-white">{{ $course->code }}</span>
        </div>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <span class="inline-block bg-[#D4A017] text-white text-xs font-semibold px-3 py-1 rounded mb-3">{{ $course->code }}</span>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white mb-2">{{ $course->title }}</h1>
                @if($course->category)
                    <p class="text-blue-200">{{ $course->category->name }}</p>
                @endif
            </div>
            <a href="{{ route('enroll.step1') }}" class="bg-[#D4A017] hover:bg-[#b8890f] text-white font-semibold px-6 py-3 rounded-lg transition shadow-lg inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Enroll Now
            </a>
        </div>
    </div>
</section>

@if($course->hasMedia('featured_image'))
<section class="bg-white pt-0 pb-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10">
        <img src="{{ $course->getFirstMediaUrl('featured_image') }}" alt="{{ $course->title }}" class="w-full h-64 lg:h-96 object-cover rounded-2xl shadow-lg">
    </div>
</section>
@endif

<section class="py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-[#003366] mb-4">Course Description</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $course->description ?: 'Detailed course description will be available soon. Contact us for more information about this training program.' }}</p>
                </div>

                @if($course->prerequisites)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-[#003366] mb-4">Prerequisites</h2>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $course->prerequisites }}</p>
                    </div>
                @endif

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-[#003366]">Available Schedules</h2>
                        <span class="text-sm text-gray-500">{{ $course->trainingSchedules->count() }} schedule(s)</span>
                    </div>
                    @if($course->trainingSchedules->count() > 0)
                        <div class="space-y-3">
                            @foreach($course->trainingSchedules as $schedule)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-[#0077B6] transition">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $schedule->start_date->format('M d, Y') }} - {{ $schedule->end_date->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $schedule->venue ?: 'Venue TBA' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Slots: {{ $schedule->enrolled_count }}/{{ $schedule->capacity }}</p>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            @php
                                                $statusColors = [
                                                    'upcoming' => 'bg-blue-100 text-blue-800',
                                                    'ongoing' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-gray-100 text-gray-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                                $color = $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">{{ ucfirst($schedule->status) }}</span>
                                            @if(in_array($schedule->status, ['upcoming', 'ongoing']))
                                                <a href="{{ route('enroll.step1') }}" class="text-xs bg-[#003366] text-white px-3 py-1.5 rounded font-medium hover:bg-[#002244] transition">Enroll</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No schedules available at this time. Please check back later or contact us.</p>
                    @endif
                </div>
            </div>

            <aside class="lg:sticky lg:top-24 self-start">
                <div class="bg-gradient-to-br from-[#003366] to-[#0077B6] text-white rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4">Course Information</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-blue-200 text-xs uppercase tracking-wide">Duration</dt>
                            <dd class="font-semibold">{{ $course->duration ?: 'TBA' }}</dd>
                        </div>
                        <div>
                            <dt class="text-blue-200 text-xs uppercase tracking-wide">Fee</dt>
                            <dd class="font-bold text-2xl text-[#D4A017]">PHP {{ number_format($course->fee, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-blue-200 text-xs uppercase tracking-wide">Max Participants</dt>
                            <dd class="font-semibold">{{ $course->max_participants }} trainees</dd>
                        </div>
                        <div>
                            <dt class="text-blue-200 text-xs uppercase tracking-wide">Category</dt>
                            <dd class="font-semibold">{{ $course->category->name ?? 'General' }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('enroll.step1') }}" class="mt-6 block text-center bg-[#D4A017] hover:bg-[#b8890f] text-white font-semibold px-6 py-3 rounded-lg transition">
                        Enroll in this Course
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
