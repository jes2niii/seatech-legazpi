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
                    <span class="text-xs text-gray-500 uppercase">Last Name</span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-medium break-words">{{ $enrollment->student->last_name }}</p>
                        <button type="button" x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText(@js($enrollment->student->last_name)); copied = true; setTimeout(() => copied = false, 1500)"
                            :title="copied ? 'Copied!' : 'Copy last name'"
                            class="shrink-0 text-gray-400 hover:text-[#0077B6] transition">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">First Name</span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-medium break-words">{{ $enrollment->student->first_name }}</p>
                        <button type="button" x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText(@js($enrollment->student->first_name)); copied = true; setTimeout(() => copied = false, 1500)"
                            :title="copied ? 'Copied!' : 'Copy first name'"
                            class="shrink-0 text-gray-400 hover:text-[#0077B6] transition">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Middle Name</span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-medium break-words">{{ $enrollment->student->middle_name ?? '—' }}</p>
                        <button type="button" x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText(@js($enrollment->student->middle_name ?? '')); copied = true; setTimeout(() => copied = false, 1500)"
                            :title="copied ? 'Copied!' : 'Copy middle name'"
                            class="shrink-0 text-gray-400 hover:text-[#0077B6] transition">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Email</span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-medium break-words">{{ $enrollment->student->email }}</p>
                        <button type="button" x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText(@js($enrollment->student->email)); copied = true; setTimeout(() => copied = false, 1500)"
                            :title="copied ? 'Copied!' : 'Copy email'"
                            class="shrink-0 text-gray-400 hover:text-[#0077B6] transition">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Mobile</span>
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-medium break-words">{{ $enrollment->student->mobile_number }}</p>
                        <button type="button" x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText(@js($enrollment->student->mobile_number)); copied = true; setTimeout(() => copied = false, 1500)"
                            :title="copied ? 'Copied!' : 'Copy mobile'"
                            class="shrink-0 text-gray-400 hover:text-[#0077B6] transition">
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Gender</span>
                    <p class="font-medium">{{ ucfirst($enrollment->student->gender ?? 'N/A') }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Civil Status</span>
                    <p class="font-medium">{{ ucfirst($enrollment->student->civil_status ?? 'N/A') }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Place of Birth</span>
                    <p class="font-medium">{{ $enrollment->student->place_of_birth ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Rank / Position</span>
                    <p class="font-medium">{{ $enrollment->student->rank ?? 'N/A' }}</p>
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
                    @php $schedule = $enrollment->trainingSchedule; @endphp
                    <p class="font-medium">
                        @if($schedule && $schedule->start_date)
                            {{ $schedule->start_date->format('M d, Y') }}
                            @if($schedule->end_date) - {{ $schedule->end_date->format('M d, Y') }} @endif
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Venue</span>
                    <p class="font-medium">{{ $enrollment->trainingSchedule->venue ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if(!empty($enrollment->requirements) || $enrollment->notes || $enrollment->emergency_contact_name)
        <div class="bg-white rounded-lg shadow p-6">
            @if(!empty($enrollment->requirements))
            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Requirements Submitted</h4>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    @foreach((array) $enrollment->requirements as $req)
                        <li>{{ $requirementLabels[$req] ?? ucwords(str_replace('_', ' ', $req)) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($enrollment->notes)
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Notes</h4>
                <p class="text-gray-600 whitespace-pre-line">{{ $enrollment->notes }}</p>
            </div>
            @endif
        </div>
        @endif

        @if($enrollment->emergency_contact_name)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Emergency Contact</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Contact Person</span>
                    <p class="font-medium">{{ $enrollment->emergency_contact_name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Relationship</span>
                    <p class="font-medium">{{ $enrollment->emergency_contact_relationship ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Mobile No.</span>
                    <p class="font-medium">{{ $enrollment->emergency_contact_mobile ?? 'N/A' }}</p>
                </div>
            </div>
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
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-1">Notes (optional)</label>
                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">Approve</button>
                </form>
                <form action="{{ route($p.'.enrollments.reject', $enrollment) }}" method="POST">
                    @csrf
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
