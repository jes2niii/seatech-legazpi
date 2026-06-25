@extends('layouts.public')

@section('title', 'Enroll - Review Application - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.enroll.partials.steps', ['current' => 4])

        <div class="mt-10">
            <h1 class="text-3xl font-bold text-[#003366] mb-2">Review Your Application</h1>
            <p class="text-gray-600 mb-8">Please review all the information before submitting.</p>

            <form method="POST" action="{{ route('enroll.submit') }}">
                @csrf

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-[#003366] mb-4 flex items-center">
                            <span class="w-8 h-8 bg-[#003366] text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                            Course Selected
                        </h2>
                        @if ($schedule)
                            <div class="ml-11">
                                <p class="text-sm text-gray-500">Course</p>
                                <p class="font-semibold text-gray-900">{{ $schedule->course->title ?? 'N/A' }} ({{ $schedule->course->code ?? '' }})</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Schedule</p>
                                        <p class="font-medium text-gray-800">{{ $schedule->start_date->format('M d, Y') }} - {{ $schedule->end_date->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Venue</p>
                                        <p class="font-medium text-gray-800">{{ $schedule->venue }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Fee</p>
                                        <p class="font-medium text-gray-800">₱{{ number_format($schedule->course->fee ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-[#003366] mb-4 flex items-center">
                            <span class="w-8 h-8 bg-[#003366] text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
                            Personal Information
                        </h2>
                        <div class="ml-11 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <p class="text-sm text-gray-500">Full Name</p>
                                <p class="font-medium text-gray-800">{{ $data['last_name'] ?? '' }}, {{ $data['first_name'] ?? '' }} {{ $data['middle_name'] ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date of Birth</p>
                                <p class="font-medium text-gray-800">{{ isset($data['date_of_birth']) ? \Carbon\Carbon::parse($data['date_of_birth'])->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Gender</p>
                                <p class="font-medium text-gray-800">{{ ucfirst($data['gender'] ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Mobile Number</p>
                                <p class="font-medium text-gray-800">{{ $data['mobile_number'] ?? 'N/A' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-800">{{ $data['email'] ?? 'N/A' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-500">Address</p>
                                <p class="font-medium text-gray-800">{{ $data['address'] ?? 'N/A' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-500">Seaman's Book Number</p>
                                <p class="font-medium text-gray-800">{{ $data['seaman_book_number'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-[#003366] mb-4 flex items-center">
                            <span class="w-8 h-8 bg-[#003366] text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
                            Requirements Selected
                        </h2>
                        <div class="ml-11">
                            @if (!empty($data['requirements']))
                                <ul class="space-y-2">
                                    @foreach ($data['requirements'] as $req)
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-[#D4A017] rounded-full"></span>
                                            <span class="text-sm text-gray-800">{{ ucwords(str_replace('_', ' ', $req)) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-400">No requirements selected.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> By submitting this application, you confirm that all information provided is accurate and complete.
                    </p>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('enroll.step3') }}" class="text-sm font-medium text-[#0077B6] hover:text-[#005a8c] transition">&larr; Back to Requirements</a>
                    <button type="submit" class="bg-[#D4A017] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#b8890f] transition text-base">
                        Submit Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
