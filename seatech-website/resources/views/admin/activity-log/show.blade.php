@extends('layouts.admin')

@section('title', 'Change Details')
@section('page-title', 'Change Details')

@section('content')
@php
    $p = request()->segment(1);
    $changes = $activity->changes();
    $old = $changes['old'] ?? [];
    $new = $changes['attributes'] ?? [];
    $logName = $activity->log_name ?? '';
    $subjectLabel = activity_subject_label($activity);
    $subjectLink = activity_subject_link($activity);
    $recordType = $logName ? activity_log_type($logName) : 'Record';
    $sentence = activity_action_sentence($activity);
@endphp

<div class="max-w-5xl mx-auto">
    <div class="mb-4">
        <a href="{{ route($p.'.activity-log.index') }}" class="text-[#0077B6] hover:underline text-sm">&larr; Back to Recent Changes</a>
    </div>

    {{-- Header card --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
            <div class="flex items-start gap-4 flex-1 min-w-0">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#003366] to-[#0077B6] text-white font-bold flex items-center justify-center flex-shrink-0">
                    {{ activity_causer_initials($activity) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl font-bold text-[#003366] leading-snug">
                        {{ $subjectLabel ? $subjectLabel : $recordType . ' #' . $activity->subject_id }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">{{ $sentence }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-gray-500">
                        <span class="px-2 py-0.5 rounded-full font-semibold {{ activity_event_badge_class($activity->event) }}">
                            {{ ucfirst(activity_event_label($activity->event)) }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-[#0077B6] font-semibold">
                            {{ $recordType }}
                        </span>
                        <span title="{{ $activity->created_at->format('F d, Y h:i:s A') }}">
                            {{ $activity->created_at->diffForHumans() }}
                            &middot;
                            {{ $activity->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($subjectLink)
                <a href="{{ $subjectLink }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition flex items-center gap-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View {{ $recordType }}
                </a>
            @endif
        </div>
    </div>

    {{-- Changes card --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">What changed</h3>

        @php
            $rows = [];
            foreach ($new as $key => $newVal) {
                $label = activity_field_label($logName, $key);
                if ($label === null) {
                    continue;
                }
                $oldVal = $old[$key] ?? null;
                if ($oldVal == $newVal) {
                    continue;
                }
                $rows[] = [
                    'label' => $label,
                    'old' => activity_format_value($logName, $key, $oldVal),
                    'new' => activity_format_value($logName, $key, $newVal),
                ];
            }
        @endphp

        @if(count($rows) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Field</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Old</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">New</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rows as $row)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800 font-medium">{{ $row['label'] }}</td>
                                <td class="px-4 py-2 text-sm text-red-700">{{ $row['old'] }}</td>
                                <td class="px-4 py-2 text-sm text-green-700">{{ $row['new'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded p-4 text-sm text-gray-600">
                @if($activity->event === 'created')
                    This was a new record — no previous values to compare.
                @elseif($activity->event === 'deleted')
                    This record was deleted. No further details were captured.
                @else
                    No displayable changes were recorded.
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
