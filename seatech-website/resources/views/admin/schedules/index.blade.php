@extends('layouts.admin')

@section('title', 'Schedules')
@section('page-title', 'Training Schedules')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Schedules</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search by course, code, or venue...'])
        @can('manage schedules')
        @if(Route::has($p.'.schedules.create'))
        <a href="{{ route($p.'.schedules.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Schedule</a>
        @endif
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Venue</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($schedules as $schedule)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $schedule->course->title ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->start_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->end_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $schedule->venue }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $schedule->enrollments_count ?? $schedule->enrollments->count() }} / {{ $schedule->capacity }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($schedule->status == 'upcoming') bg-blue-100 text-blue-800
                        @elseif($schedule->status == 'ongoing') bg-yellow-100 text-yellow-800
                        @elseif($schedule->status == 'completed') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($schedule->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.schedules.edit'))
                    <a href="{{ route($p.'.schedules.edit', $schedule) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.schedules.destroy'))
                    <form action="{{ route($p.'.schedules.destroy', $schedule) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this schedule?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No schedules found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $schedules->links() }}
    </div>
</div>
@endsection
