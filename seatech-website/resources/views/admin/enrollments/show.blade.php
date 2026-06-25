@extends('layouts.admin')

@section('title', 'Enrollment Details')
@section('page-title', 'Enrollment Details')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Enrollment Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Enrollment ID</span>
                    <p class="font-medium">#{{ $enrollment->id }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Status</span>
                    <p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                            @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                            @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                            @elseif($enrollment->status == 'cancelled') bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Payment Status</span>
                    <p class="font-medium">{{ ucfirst($enrollment->payment_status) }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Enrolled Date</span>
                    <p class="font-medium">{{ $enrollment->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Full Name</span>
                    <p class="font-medium">{{ $enrollment->student->full_name ?? $enrollment->student->first_name . ' ' . $enrollment->student->last_name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Email</span>
                    <p class="font-medium">{{ $enrollment->student->email }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Mobile</span>
                    <p class="font-medium">{{ $enrollment->student->mobile }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Gender</span>
                    <p class="font-medium">{{ ucfirst($enrollment->student->gender) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Course & Schedule</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Course</span>
                    <p class="font-medium">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Schedule</span>
                    <p class="font-medium">{{ optional($enrollment->trainingSchedule)->start_date->format('M d, Y') }} - {{ optional($enrollment->trainingSchedule)->end_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Venue</span>
                    <p class="font-medium">{{ $enrollment->trainingSchedule->venue ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if($enrollment->requirements || $enrollment->notes)
        <div class="bg-white rounded-lg shadow p-6">
            @if($enrollment->requirements)
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Requirements</h4>
                <p class="text-gray-600">{{ $enrollment->requirements }}</p>
            </div>
            @endif
            @if($enrollment->notes)
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Notes</h4>
                <p class="text-gray-600">{{ $enrollment->notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
            @if($enrollment->status == 'pending')
            <div class="space-y-3">
                @if(Route::has($p.'.enrollments.approve'))
                <form action="{{ route($p.'.enrollments.approve', $enrollment) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">Approve</button>
                </form>
                <form action="{{ route($p.'.enrollments.reject', $enrollment) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-1">Rejection Reason</label>
                        <textarea name="notes" rows="2" required class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">Reject</button>
                </form>
                @endif
            </div>
            @endif
            @if(Route::has($p.'.enrollments.index'))
            <div class="mt-4">
                <a href="{{ route($p.'.enrollments.index') }}" class="block w-full text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Back to List</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
