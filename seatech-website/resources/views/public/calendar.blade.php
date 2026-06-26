@extends('layouts.public')

@section('title', 'Training Calendar - SEATECH Maritime Training')
@section('meta_description', 'View upcoming maritime training schedules and reserve your slot for the next batch at SEATECH Legazpi.')
@section('og_title', 'Training Calendar - SEATECH')
@section('og_description', 'View upcoming maritime training schedules and reserve your slot for the next batch at SEATECH Legazpi.')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Training Calendar</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">View all upcoming training schedules and reserve your slot for the next batch.</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($schedules->count() > 0)
            @php
                $grouped = $schedules->groupBy(function($s) {
                    return $s->start_date->format('Y-m');
                })->sortKeys();
            @endphp

            <div class="space-y-12">
                @foreach($grouped as $monthKey => $monthSchedules)
                    @php
                        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey);
                    @endphp
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="w-1.5 h-10 bg-[#D4A017] rounded mr-4"></div>
                            <div>
                                <h2 class="text-2xl font-bold text-[#003366]">{{ $monthDate->format('F Y') }}</h2>
                                <p class="text-sm text-gray-500">{{ $monthSchedules->count() }} {{ $monthSchedules->count() === 1 ? 'training' : 'trainings' }} scheduled</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($monthSchedules as $schedule)
                                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md hover:border-[#0077B6] transition">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="flex-1">
                                            <span class="text-xs text-[#0077B6] font-mono font-bold">{{ $schedule->course->code ?? '' }}</span>
                                            <h3 class="font-bold text-[#003366] mt-1 leading-snug">{{ $schedule->course->title ?? 'Course' }}</h3>
                                        </div>
                                        @php
                                            $statusColors = [
                                                'upcoming' => 'bg-blue-100 text-blue-800',
                                                'ongoing' => 'bg-green-100 text-green-800',
                                            ];
                                            $color = $statusColors[$schedule->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} flex-shrink-0">{{ ucfirst($schedule->status) }}</span>
                                    </div>

                                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $schedule->start_date->format('M d') }} - {{ $schedule->end_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>{{ $schedule->venue ?: 'Venue TBA' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Slots: {{ $schedule->enrolled_count }}/{{ $schedule->capacity }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <span class="text-sm font-bold text-[#D4A017]">PHP {{ number_format($schedule->course->fee ?? 0, 2) }}</span>
                                        @if(in_array($schedule->status, ['upcoming', 'ongoing']))
                                            <a href="{{ route('enroll.step1') }}" class="text-sm bg-[#003366] text-white px-4 py-1.5 rounded font-medium hover:bg-[#002244] transition">Enroll</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-[#F5F7FA] rounded-2xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-gray-500 text-lg">No upcoming trainings scheduled.</p>
                <p class="text-gray-400 text-sm mt-2">Please check back later or contact us for the next batch schedule.</p>
            </div>
        @endif
    </div>
</section>
@endsection
