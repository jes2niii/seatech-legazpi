@extends('layouts.admin')

@section('title', 'Edit Certificate')
@section('page-title', 'Edit Certificate')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.certificates.update', $certificate) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student <span class="text-red-500">*</span></label>
                <select name="student_id" id="student_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $certificate->student_id) == $student->id ? 'selected' : '' }}>{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
                @error('student_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course <span class="text-red-500">*</span></label>
                <select name="course_id" id="course_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $certificate->course_id) == $course->id ? 'selected' : '' }}>{{ $course->code }} - {{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="enrollment_id" class="block text-sm font-medium text-gray-700 mb-1">Enrollment (optional)</label>
                <select name="enrollment_id" id="enrollment_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="">No Enrollment</option>
                    @foreach($enrollments as $enrollment)
                        <option value="{{ $enrollment->id }}" {{ old('enrollment_id', $certificate->enrollment_id) == $enrollment->id ? 'selected' : '' }}>#{{ $enrollment->id }} - {{ $enrollment->student->full_name ?? $enrollment->student->first_name . ' ' . $enrollment->student->last_name }}</option>
                    @endforeach
                </select>
                @error('enrollment_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-1">Certificate Number <span class="text-red-500">*</span></label>
                <input type="text" name="certificate_number" id="certificate_number" value="{{ old('certificate_number', $certificate->certificate_number) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('certificate_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="issued_date" class="block text-sm font-medium text-gray-700 mb-1">Issued Date <span class="text-red-500">*</span></label>
                <input type="date" name="issued_date" id="issued_date" value="{{ old('issued_date', $certificate->issued_date->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('issued_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified', $certificate->is_verified) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_verified" class="ml-2 text-sm text-gray-700">Verified</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Certificate</button>
            <a href="{{ route($p.'.certificates.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
