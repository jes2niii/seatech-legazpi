@extends('layouts.admin')

@section('title', $course->title)
@section('page-title', $course->title)

@section('content')
@php $p = request()->segment(1); @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-sm text-gray-500">{{ $course->code }}</span>
                    <h2 class="text-2xl font-bold text-[#003366] mt-1">{{ $course->title }}</h2>
                </div>
                @if($course->isArchived())
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">Archived</span>
                @elseif($course->is_active)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Category</span>
                    <p class="font-medium">{{ $course->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Duration</span>
                    <p class="font-medium">{{ $course->duration }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Fee</span>
                    <p class="font-medium text-[#D4A017]">₱{{ number_format($course->fee, 2) }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Max Participants</span>
                    <p class="font-medium">{{ $course->max_participants ?? 'Unlimited' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                <p class="text-gray-700">{{ $course->description ?? 'No description provided.' }}</p>
            </div>

            @if($course->prerequisites)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Prerequisites</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $course->prerequisites }}</p>
            </div>
            @endif

            @if($course->learning_outcomes)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Learning Outcomes</h3>
                <div class="text-gray-700">
                    {!! format_rich_text($course->learning_outcomes) !!}
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Training Schedules</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#003366] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Start Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">End Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Venue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($course->trainingSchedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $schedule->start_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $schedule->end_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $schedule->venue }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($schedule->status == 'upcoming') bg-blue-100 text-blue-800
                                @elseif($schedule->status == 'ongoing') bg-yellow-100 text-yellow-800
                                @elseif($schedule->status == 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">No schedules yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-3">
                @if(Route::has($p.'.courses.edit'))
                <a href="{{ route($p.'.courses.edit', $course) }}" class="block w-full text-center bg-[#D4A017] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#b88914] transition">Edit Course</a>
                @endif
                @if(Route::has($p.'.courses.archive') && ! $course->isArchived())
                <form action="{{ route($p.'.courses.archive', $course) }}" method="POST" x-data @submit.prevent="if(confirm('Archive this course?')) $el.submit()">
                    @csrf
                    <button type="submit" class="block w-full text-center bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">Archive Course</button>
                </form>
                @endif
                @if(Route::has($p.'.courses.restore') && $course->isArchived())
                <form action="{{ route($p.'.courses.restore', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">Restore Course</button>
                </form>
                @endif
                <a href="{{ route($p.'.courses.index') }}" class="block w-full text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Back to List</a>
                @if(Route::has($p.'.courses.destroy'))
                <form action="{{ route($p.'.courses.destroy', $course) }}" method="POST" x-data @submit.prevent="if(confirm('Delete this course?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="block w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">Delete Course</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
