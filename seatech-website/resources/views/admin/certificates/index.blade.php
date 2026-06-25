@extends('layouts.admin')

@section('title', 'Certificates')
@section('page-title', 'Certificates')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Certificates</h2>
    @can('manage certificates')
    @if(Route::has($p.'.certificates.create'))
    <a href="{{ route($p.'.certificates.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Issue Certificate</a>
    @endif
    @endcan
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Certificate #</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Student</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Issued Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Verified</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($certificates as $certificate)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $certificate->certificate_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $certificate->student->full_name ?? $certificate->student->first_name . ' ' . $certificate->student->last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $certificate->course->title ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $certificate->issued_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($certificate->is_verified)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.certificates.show'))
                    <a href="{{ route($p.'.certificates.show', $certificate) }}" class="text-[#0077B6] hover:underline">View</a>
                    @endif
                    @if(Route::has($p.'.certificates.edit'))
                    <a href="{{ route($p.'.certificates.edit', $certificate) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.certificates.destroy'))
                    <form action="{{ route($p.'.certificates.destroy', $certificate) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this certificate?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No certificates found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $certificates->links() }}
    </div>
</div>
@endsection
