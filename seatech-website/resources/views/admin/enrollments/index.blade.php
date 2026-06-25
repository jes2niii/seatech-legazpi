@extends('layouts.admin')

@section('title', 'Enrollments')
@section('page-title', 'Enrollments')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Enrollments</h2>
    <div class="flex space-x-2">
        <form method="GET" action="{{ route($p.'.enrollments.index') }}">
            <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-sm shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Student</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Schedule</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($enrollments as $enrollment)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $enrollment->student->full_name ?? $enrollment->student->first_name . ' ' . $enrollment->student->last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($enrollment->trainingSchedule)->start_date->format('M d, Y') ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                        @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                        @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                        @elseif($enrollment->status == 'cancelled') bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    @if($enrollment->payment_status == 'paid')
                        <span class="text-green-600 font-medium">Paid</span>
                    @elseif($enrollment->payment_status == 'partial')
                        <span class="text-yellow-600 font-medium">Partial</span>
                    @else
                        <span class="text-red-600 font-medium">Unpaid</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.enrollments.show'))
                    <a href="{{ route($p.'.enrollments.show', $enrollment) }}" class="text-[#0077B6] hover:underline">View</a>
                    @endif
                    @if(Route::has($p.'.enrollments.destroy'))
                    <form action="{{ route($p.'.enrollments.destroy', $enrollment) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this enrollment?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No enrollments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $enrollments->links() }}
    </div>
</div>
@endsection
