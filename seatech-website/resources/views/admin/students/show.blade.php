@extends('layouts.admin')

@section('title', $student->full_name ?? $student->first_name . ' ' . $student->last_name)
@section('page-title', $student->full_name ?? $student->first_name . ' ' . $student->last_name)

@section('content')
@php $p = request()->segment(1); @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">First Name</span>
                    <p class="font-medium">{{ $student->first_name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Last Name</span>
                    <p class="font-medium">{{ $student->last_name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Email</span>
                    <p class="font-medium">{{ $student->email }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Mobile</span>
                    <p class="font-medium">{{ $student->mobile_number }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Gender</span>
                    <p class="font-medium">{{ ucfirst($student->gender) }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">Date of Birth</span>
                    <p class="font-medium">{{ $student->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
                </div>

                <div class="col-span-2">
                    <span class="text-xs text-gray-500 uppercase">Address</span>
                    <p class="font-medium">{{ $student->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Enrollments</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#003366] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Schedule</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($student->enrollments as $enrollment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">{{ optional($enrollment->trainingSchedule)->start_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                                @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                                @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route($p.'.enrollments.show', $enrollment) }}" class="text-[#0077B6] hover:underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">No enrollments.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-3">
                @if(Route::has($p.'.students.edit'))
                <a href="{{ route($p.'.students.edit', $student) }}" class="block w-full text-center bg-[#D4A017] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#b88914] transition">Edit Student</a>
                @endif
                <a href="{{ route($p.'.students.index') }}" class="block w-full text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
