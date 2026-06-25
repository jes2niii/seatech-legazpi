@extends('layouts.student')

@section('title', 'My Certificates - SEATECH')
@section('page-title', 'My Certificates')

@section('content')
<div class="mb-6 bg-gradient-to-r from-[#003366] to-[#0077B6] text-white rounded-xl p-6">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold mb-1">Your Earned Certificates</h2>
            <p class="text-blue-200 text-sm">View, download, or verify any certificate you have earned at SEATECH.</p>
        </div>
    </div>
</div>

@if($certificates->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($certificates as $cert)
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="h-32 bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center relative">
                    <div class="absolute top-3 right-3">
                        @if($cert->is_verified)
                            <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Verified</span>
                        @else
                            <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded">Unverified</span>
                        @endif
                    </div>
                    <div class="bg-white p-1 rounded">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->margin(0)->generate(route('verify.certificate.scan', ['number' => $cert->certificate_number])) !!}
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-[#003366] leading-snug mb-1">{{ $cert->course->title ?? 'N/A' }}</h3>
                    <p class="text-xs text-gray-500 font-mono mb-3">{{ $cert->certificate_number }}</p>
                    <div class="text-sm text-gray-600 space-y-1 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Issued:</span>
                            <span class="font-medium">{{ $cert->issued_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('verify.certificate') }}" class="flex-1 text-center text-sm bg-[#003366] text-white px-3 py-2 rounded-lg font-medium hover:bg-[#002244] transition">Verify</a>
                        <a href="{{ route('verify.certificate') }}" class="flex-1 text-center text-sm border border-[#003366] text-[#003366] px-3 py-2 rounded-lg font-medium hover:bg-blue-50 transition">Public Page</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        <p class="text-gray-500 text-lg">No certificates yet.</p>
        <p class="text-gray-400 text-sm mt-2">Complete a training program to earn your first certificate.</p>
        <a href="{{ route('enroll.step1') }}" class="inline-block mt-6 bg-[#003366] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#002244] transition text-sm">
            Browse Training Programs
        </a>
    </div>
@endif
@endsection
