@extends('layouts.admin')

@section('title', 'Schedule Details')
@section('page-title', 'Schedule Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.schedules.index') }}" class="text-[#0077B6] hover:underline text-sm">&larr; Back to Schedules</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-[#003366] text-white">
        <h3 class="text-lg font-semibold">{{ $schedule->course->title ?? 'N/A' }}</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500">Course Code</label>
            <p class="text-gray-900 font-medium">{{ $schedule->course->code ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <p class="mt-1">
                <span class="px-2 py-1 text-xs font-semibold rounded-full
                    @if($schedule->status == 'upcoming') bg-blue-100 text-blue-800
                    @elseif($schedule->status == 'ongoing') bg-yellow-100 text-yellow-800
                    @elseif($schedule->status == 'completed') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($schedule->status) }}
                </span>
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Start Date</label>
            <p class="text-gray-900">{{ $schedule->start_date->format('F d, Y') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">End Date</label>
            <p class="text-gray-900">{{ $schedule->end_date->format('F d, Y') }}</p>
        </div>
        @if($schedule->registration_deadline)
        <div>
            <label class="block text-sm font-medium text-gray-500">Registration Deadline</label>
            <p class="text-gray-900">{{ $schedule->registration_deadline->format('F d, Y') }}</p>
        </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-500">Venue</label>
            <p class="text-gray-900">{{ $schedule->venue ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Capacity</label>
            <p class="text-gray-900">{{ $schedule->enrolled_count ?? 0 }} / {{ $schedule->capacity }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Assigned Instructor</label>
            <p class="text-gray-900">
                @if($schedule->instructor)
                    <span class="font-medium">{{ $schedule->instructor->name }}</span>
                    <span class="text-gray-500 text-sm">({{ $schedule->instructor->email }})</span>
                @else
                    <span class="text-gray-400 italic">Unassigned</span>
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
