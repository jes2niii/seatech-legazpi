@extends('layouts.public')

@section('title', 'Verify Certificate - SEATECH Maritime Training')
@section('meta_description', 'Verify the authenticity of any certificate issued by SEATECH Maritime Training & Assessment Center.')
@section('og_title', 'Verify a SEATECH Certificate')
@section('og_description', 'Verify the authenticity of any certificate issued by SEATECH Maritime Training & Assessment Center.')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Certificate Verification</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">Verify the authenticity of any SEATECH-issued certificate by entering the certificate number below.</p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-[#003366] mb-2">Enter Certificate Number</h2>
            <p class="text-gray-600 text-sm mb-6">The certificate number can be found on the certificate document or scan the QR code if available.</p>

                <form method="POST" action="{{ route('verify.certificate.lookup') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-1">Certificate Number <span class="text-red-500">*</span></label>
                        <input type="text" name="certificate_number" id="certificate_number" value="{{ old('certificate_number', $scanned_number ?? '') }}" required
                            placeholder="e.g. STC-2026-0001"
                            class="w-full rounded-lg border-gray-300 border px-4 py-3 text-base focus:ring-[#0077B6] focus:border-[#0077B6] font-mono">
                        @error('certificate_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#003366] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#002244] transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Verify Certificate
                    </button>
                </form>
        </div>

        @if($certificate)
            <div class="mt-8 bg-white rounded-2xl border-2 border-green-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 flex items-center gap-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h3 class="font-bold text-lg">Certificate Verified</h3>
                        <p class="text-green-100 text-sm">This certificate is authentic and valid.</p>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Certificate Number</dt>
                            <dd class="font-mono font-bold text-[#003366] text-lg">{{ $certificate->certificate_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Status</dt>
                            <dd>
                                @if($certificate->is_verified)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Student Name</dt>
                            <dd class="font-medium text-gray-900">{{ $certificate->student->full_name ?? trim(($certificate->student->first_name ?? '') . ' ' . ($certificate->student->last_name ?? '')) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Issued Date</dt>
                            <dd class="font-medium text-gray-900">{{ $certificate->issued_date->format('F d, Y') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Course</dt>
                            <dd class="font-medium text-gray-900">{{ $certificate->course->title ?? 'N/A' }} @if($certificate->course)({{ $certificate->course->code }})@endif</dd>
                        </div>
                    </dl>
                </div>
            </div>
        @elseif(!empty($scanned_number))
            <div class="mt-8 bg-white rounded-2xl border-2 border-red-200 shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 flex items-center gap-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h3 class="font-bold text-lg">Certificate Not Found</h3>
                        <p class="text-red-100 text-sm">No certificate matches the scanned code.</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">The scanned certificate number <span class="font-mono font-bold">{{ $scanned_number }}</span> does not match any certificate in our records. Please verify the QR code or contact the issuing office.</p>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
