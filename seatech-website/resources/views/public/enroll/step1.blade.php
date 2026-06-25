@extends('layouts.public')

@section('title', 'Enroll - Step 1: Select Course - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.enroll.partials.steps', ['current' => 1])

        <div class="mt-10">
            <h1 class="text-3xl font-bold text-[#003366] mb-2">Select Your Course</h1>
            <p class="text-gray-600 mb-8">Choose a training schedule that fits your availability.</p>

            <form method="POST" action="{{ route('enroll.postStep1') }}">
                @csrf

                @forelse ($categories as $category)
                    <div class="mb-10">
                        <div class="flex items-center mb-4">
                            <div class="w-1 h-8 bg-[#D4A017] rounded mr-3"></div>
                            <h2 class="text-xl font-bold text-[#003366]">{{ $category->name }}</h2>
                        </div>

                        @foreach ($category->courses as $course)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                                <div class="p-6">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <span class="inline-block bg-[#003366] text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $course->code }}</span>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $course->title }}</h3>
                                            @if ($course->duration)
                                                <p class="text-sm text-gray-500 mt-1">Duration: {{ $course->duration }}</p>
                                            @endif
                                            <p class="text-sm text-gray-500">Fee: ₱{{ number_format($course->fee, 2) }}</p>
                                        </div>
                                    </div>

                                    @if ($course->trainingSchedules && $course->trainingSchedules->count())
                                        <div class="mt-4 border-t border-gray-100 pt-4">
                                            <p class="text-sm font-medium text-gray-700 mb-3">Available Schedules:</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach ($course->trainingSchedules as $schedule)
                                                    @if (in_array($schedule->status, ['upcoming', 'ongoing']))
                                                        <label class="block border border-gray-200 rounded-lg p-4 cursor-pointer transition hover:border-[#0077B6] hover:shadow has-[:checked]:border-[#0077B6] has-[:checked]:bg-blue-50 has-[:checked]:ring-1 has-[:checked]:ring-[#0077B6]">
                                                            <input type="radio" name="training_schedule_id" value="{{ $schedule->id }}" class="sr-only">
                                                            <div class="flex items-start justify-between">
                                                                <div>
                                                                    <p class="font-semibold text-gray-900 text-sm">{{ $schedule->start_date->format('M d, Y') }} - {{ $schedule->end_date->format('M d, Y') }}</p>
                                                                    <p class="text-xs text-gray-500 mt-1">{{ $schedule->venue }}</p>
                                                                    <p class="text-xs text-gray-500">Capacity: {{ $schedule->enrolled_count ?? 0 }}/{{ $schedule->capacity }}</p>
                                                                </div>
                                                                <div class="text-right">
                                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-[#003366] text-white">₱{{ number_format($course->fee, 2) }}</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400 mt-3">No schedules available at this time.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                        <p class="text-gray-500">No courses are currently available for enrollment.</p>
                    </div>
                @endforelse

                @if ($categories->isNotEmpty())
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-[#003366] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#002244] transition text-base">
                            Continue to Personal Info →
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
