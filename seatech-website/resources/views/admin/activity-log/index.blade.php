@extends('layouts.admin')

@section('title', 'Recent Changes')
@section('page-title', 'Recent Changes')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Recent Changes</h2>
        <p class="text-sm text-gray-500">A history of important updates made by administrators.</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<form method="GET" action="{{ route($p.'.activity-log.index') }}" class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
            <select name="log_name" class="w-full border-gray-300 rounded text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                <option value="">All types</option>
                @foreach($logNames as $name)
                    <option value="{{ $name }}" {{ ($filters['log_name'] ?? '') === $name ? 'selected' : '' }}>{{ activity_log_type($name) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Action</label>
            <select name="event" class="w-full border-gray-300 rounded text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                <option value="">Any action</option>
                @foreach(['created', 'updated', 'deleted', 'restored'] as $ev)
                    <option value="{{ $ev }}" {{ ($filters['event'] ?? '') === $ev ? 'selected' : '' }}>{{ ucfirst(activity_event_label($ev)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Done by</label>
            <select name="causer_id" class="w-full border-gray-300 rounded text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                <option value="">Anyone</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ (string)($filters['causer_id'] ?? '') === (string)$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full border-gray-300 rounded text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full border-gray-300 rounded text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
        </div>
    </div>
    <div class="mt-3 flex items-center gap-2">
        <button type="submit" class="bg-[#0077B6] text-white px-4 py-2 rounded text-sm hover:bg-[#005f94] transition">Filter</button>
        <a href="{{ route($p.'.activity-log.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300 transition">Reset</a>
    </div>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">When</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Who</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">What changed</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Details</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($activities as $activity)
                @php
                    $changes = $activity->changes();
                    $newCols = $changes['attributes'] ?? [];
                    $logName = $activity->log_name ?? '';
                    $visibleChangeCount = 0;
                    foreach ($newCols as $key => $val) {
                        if (activity_field_label($logName, $key) !== null) {
                            $visibleChangeCount++;
                        }
                    }
                    $subjectLabel = activity_subject_label($activity);
                    $subjectLink = activity_subject_link($activity);
                    $recordType = $logName ? activity_log_type($logName) : 'Record';
                    $deleted = ! $activity->subject && $activity->event === 'deleted';
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700" title="{{ $activity->created_at->format('F d, Y h:i:s A') }}">
                        {{ $activity->created_at->diffForHumans() }}
                        <div class="text-gray-400">{{ $activity->created_at->format('M d, Y h:i A') }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs">
                        <span class="px-2 py-0.5 rounded-full font-semibold {{ activity_event_badge_class($activity->event) }}">
                            {{ ucfirst(activity_event_label($activity->event)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-800">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-br from-[#003366] to-[#0077B6] text-white font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                {{ activity_causer_initials($activity) }}
                            </span>
                            <span class="font-medium">{{ activity_causer_name($activity) }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-800 max-w-md">
                        @if($subjectLabel)
                            @if($subjectLink)
                                <a href="{{ $subjectLink }}" class="text-[#003366] hover:text-[#0077B6] font-semibold hover:underline">
                                    {{ $subjectLabel }}
                                </a>
                            @else
                                <span class="font-semibold text-gray-700">{{ $subjectLabel }}</span>
                                @if($deleted)<span class="ml-1 text-xs text-red-500 font-normal italic">(deleted)</span>@endif
                            @endif
                        @else
                            <span class="text-gray-400 italic">{{ $recordType }} #{{ $activity->subject_id }}</span>
                        @endif
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ ucfirst(activity_event_label($activity->event)) }} {{ activity_article_for($recordType) }} {{ $recordType }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs">
                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-[#0077B6] font-semibold">{{ $recordType }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs">
                        <a href="{{ route($p.'.activity-log.show', $activity) }}" class="text-[#0077B6] hover:underline font-medium">
                            {{ $visibleChangeCount > 0 ? "{$visibleChangeCount} " . \Illuminate\Support\Str::plural('field', $visibleChangeCount) . ' changed' : 'View' }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No activity recorded yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $activities->links() }}
    </div>
</div>
@endsection
