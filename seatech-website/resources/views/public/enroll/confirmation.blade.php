@extends('layouts.public')

@section('title', 'Enrollment Submitted - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-10 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-[#003366] mb-2">Enrollment Submitted!</h1>
            <p class="text-gray-600 mb-6">Thank you for enrolling at SEATECH Maritime Training & Assessment Center.</p>

            <div class="bg-[#F5F7FA] rounded-lg p-6 mb-6 inline-block">
                <p class="text-sm text-gray-500 mb-1">Reference Number</p>
                <p class="text-2xl font-bold text-[#003366]">{{ $enrollment->id }}</p>
            </div>

            <div class="text-left bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="font-semibold text-gray-900 mb-3">Submitted Details</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Student</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->student->last_name ?? '' }}, {{ $enrollment->student->first_name ?? '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Contact No.</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->student->mobile_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->student->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Course</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Schedule</span>
                        <span class="font-medium text-gray-900">{{ optional($enrollment->trainingSchedule->start_date)->format('M d, Y') }} - {{ optional($enrollment->trainingSchedule->end_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium text-[#D4A017] capitalize">{{ $enrollment->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Payment</span>
                        <span class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $enrollment->payment_status) }}</span>
                    </div>
                </div>
            </div>

            <div class="text-left bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="font-semibold text-gray-900 mb-3">Emergency Contact</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Contact Person</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->emergency_contact_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Relationship</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->emergency_contact_relationship ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Mobile No.</span>
                        <span class="font-medium text-gray-900">{{ $enrollment->emergency_contact_mobile ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 text-left">
                <p class="text-sm text-blue-800">
                    <strong>What's next?</strong> Our team will review your application and contact you at {{ $enrollment->student->email ?? 'your registered email' }}.
                    You may also visit our office to complete the payment and submit your original documents.
                </p>
            </div>

            @if($enrollment->hasMedia('documents'))
                <div class="text-left bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="font-semibold text-gray-900 mb-3">Submitted Documents ({{ $enrollment->getMedia('documents')->count() }})</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach($enrollment->getMedia('documents') as $doc)
                            <li class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-4 h-4 text-[#0077B6] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-gray-800 truncate">{{ $doc->name }}</span>
                                </div>
                                <a href="{{ $doc->getUrl() }}" target="_blank" class="text-[#0077B6] hover:text-[#003366] font-medium text-xs flex-shrink-0">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <a href="{{ route('home') }}" class="inline-block bg-[#003366] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#002244] transition">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
