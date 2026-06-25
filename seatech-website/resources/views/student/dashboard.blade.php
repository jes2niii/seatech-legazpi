@extends('layouts.student')

@section('title', 'Student Dashboard - SEATECH')
@section('page-title', 'Welcome, ' . auth()->user()->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border-t-4 border-[#003366] p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Enrollments</h3>
                <p class="text-3xl font-bold text-[#003366] mt-1">{{ $stats['active'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-[#003366]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border-t-4 border-[#D4A017] p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completed Trainings</h3>
                <p class="text-3xl font-bold text-[#D4A017] mt-1">{{ $stats['completed'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border-t-4 border-[#0077B6] p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide">My Certificates</h3>
                <p class="text-3xl font-bold text-[#0077B6] mt-1">{{ $stats['certificates'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-[#0077B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-[#003366]">Recent Enrollments</h2>
            <a href="{{ route('student.enrollments') }}" class="text-sm text-[#0077B6] hover:text-[#003366] font-medium">View All</a>
        </div>
        @if($recent_enrollments->count() > 0)
            <div class="space-y-3">
                @foreach($recent_enrollments as $enrollment)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-[#0077B6] transition">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ optional($enrollment->trainingSchedule->start_date)->format('M d, Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                            @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                            @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-[#F5F7FA] rounded-lg">
                <p class="text-gray-500 text-sm">You have no enrollments yet.</p>
                <a href="{{ route('enroll.step1') }}" class="inline-block mt-3 text-sm text-[#0077B6] hover:text-[#003366] font-semibold">Start your enrollment →</a>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <a href="{{ route('enroll.step1') }}" class="block bg-gradient-to-r from-[#003366] to-[#0077B6] text-white rounded-xl p-6 shadow-md hover:shadow-lg transition">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <h3 class="font-bold">Enroll in a Course</h3>
            </div>
            <p class="text-blue-200 text-sm">Browse upcoming trainings and reserve your slot.</p>
        </a>
        <a href="{{ route('student.certificates') }}" class="block bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:border-[#0077B6] transition">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0077B6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="font-bold text-[#003366]">My Certificates</h3>
            </div>
            <p class="text-gray-500 text-sm">View and download your earned certificates.</p>
        </a>
    </div>
</div>
@endsection
