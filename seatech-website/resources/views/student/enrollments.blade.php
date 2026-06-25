@extends('layouts.student')

@section('title', 'My Enrollments - SEATECH')
@section('page-title', 'My Enrollments')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('student.enrollments') }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'all' ? 'bg-[#003366] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">All</a>
            <a href="{{ route('student.enrollments', ['status' => 'pending']) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Pending</a>
            <a href="{{ route('student.enrollments', ['status' => 'approved']) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Approved</a>
            <a href="{{ route('student.enrollments', ['status' => 'completed']) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Completed</a>
            <a href="{{ route('student.enrollments', ['status' => 'rejected']) }}" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">Rejected</a>
        </div>
    </div>

    @if(isset($enrollments) && method_exists($enrollments, 'count') && $enrollments->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($enrollments as $enrollment)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-[#0077B6] font-mono font-bold">{{ $enrollment->trainingSchedule->course->code ?? '' }}</span>
                                <h3 class="font-bold text-[#003366]">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2 text-sm text-gray-600">
                                <div>
                                    <span class="text-gray-400">Schedule: </span>
                                    <span class="font-medium">{{ optional($enrollment->trainingSchedule->start_date)->format('M d, Y') }} - {{ optional($enrollment->trainingSchedule->end_date)->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Venue: </span>
                                    <span class="font-medium">{{ $enrollment->trainingSchedule->venue ?? 'TBA' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Enrolled: </span>
                                    <span class="font-medium">{{ $enrollment->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @if($enrollment->notes)
                                <p class="text-xs text-gray-500 mt-2 italic">{{ $enrollment->notes }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                                @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                                @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                @if($enrollment->payment_status == 'paid') bg-green-50 text-green-700
                                @elseif($enrollment->payment_status == 'partial') bg-yellow-50 text-yellow-700
                                @elseif($enrollment->payment_status == 'unpaid') bg-gray-100 text-gray-700
                                @else bg-red-50 text-red-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $enrollment->payment_status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            @if(method_exists($enrollments, 'links'))
                {{ $enrollments->links() }}
            @endif
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-500 text-lg">
                @if($filter === 'all')
                    You have no enrollments yet.
                @else
                    No {{ $filter }} enrollments.
                @endif
            </p>
            <a href="{{ route('enroll.step1') }}" class="inline-block mt-4 bg-[#003366] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#002244] transition text-sm">
                Browse Training Programs
            </a>
        </div>
    @endif
</div>
@endsection
