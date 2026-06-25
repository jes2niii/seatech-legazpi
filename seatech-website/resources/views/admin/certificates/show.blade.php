@extends('layouts.admin')

@section('title', 'Certificate Details')
@section('page-title', 'Certificate Details')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-[#003366]">Certificate of Completion</h2>
            <p class="text-gray-500">Certificate #{{ $certificate->certificate_number }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-2 grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <span class="text-xs text-gray-500 uppercase">Student Name</span>
                    <p class="font-medium text-lg">{{ $certificate->student->full_name ?? $certificate->student->first_name . ' ' . $certificate->student->last_name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Course</span>
                    <p class="font-medium">{{ $certificate->course->title ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Issued Date</span>
                    <p class="font-medium">{{ $certificate->issued_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Status</span>
                    <p>
                        @if($certificate->is_verified)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="text-center p-4 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                <p class="text-xs text-gray-500 uppercase mb-2">Scan to Verify</p>
                <div class="inline-block bg-white p-2">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(1)->generate(route('verify.certificate.scan', ['number' => $certificate->certificate_number])) !!}
                </div>
                <p class="text-xs text-gray-500 mt-2 break-all px-2">{{ $certificate->certificate_number }}</p>
            </div>
        </div>

        @if($certificate->enrollment)
        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Related Enrollment</h4>
            <p class="text-gray-600">Enrollment #{{ $certificate->enrollment->id }} - {{ $certificate->enrollment->status }}</p>
        </div>
        @endif

        <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-xs text-blue-800 break-all"><strong>QR Payload URL:</strong> {{ $certificate->qr_code ?? route('verify.certificate.scan', ['number' => $certificate->certificate_number]) }}</p>
        </div>

        <div class="flex space-x-3">
            @if(Route::has($p.'.certificates.edit'))
            <a href="{{ route($p.'.certificates.edit', $certificate) }}" class="bg-[#D4A017] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#b88914] transition">Edit Certificate</a>
            @endif
            <a href="{{ route($p.'.certificates.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Back to List</a>
        </div>
    </div>
</div>
@endsection
