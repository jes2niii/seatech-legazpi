@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#003366]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Students</h3>
        <p class="text-2xl font-bold text-[#003366] mt-1">{{ $stats['students'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#0077B6]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Enrollments</h3>
        <p class="text-2xl font-bold text-[#0077B6] mt-1">{{ $stats['enrollments'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#D4A017]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Active Courses</h3>
        <p class="text-2xl font-bold text-[#D4A017] mt-1">{{ $stats['courses'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Pending Enroll.</h3>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_enrollments'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Upcoming Sched.</h3>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['upcoming_schedules'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-red-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Unread Inquiries</h3>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['inquiries'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Enrollments</h2>
        @if(isset($recent_enrollments) && $recent_enrollments->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recent_enrollments as $enrollment)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $enrollment->student->full_name ?? ($enrollment->student->first_name . ' ' . $enrollment->student->last_name) }}</td>
                    <td class="px-4 py-2 text-sm">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                            @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                            @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-sm">No recent enrollments.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Inquiries</h2>
        @if(isset($recent_inquiries) && $recent_inquiries->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recent_inquiries as $inquiry)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->subject }}</td>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-sm">No recent inquiries.</p>
        @endif
    </div>
</div>
@endsection
