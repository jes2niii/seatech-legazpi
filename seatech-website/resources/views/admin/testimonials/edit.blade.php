@extends('layouts.admin')

@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Testimonial')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="student_name" class="block text-sm font-medium text-gray-700 mb-1">Student Name <span class="text-red-500">*</span></label>
                <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $testimonial->student_name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('student_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="course_taken" class="block text-sm font-medium text-gray-700 mb-1">Course Taken</label>
                <input type="text" name="course_taken" id="course_taken" value="{{ old('course_taken', $testimonial->course_taken) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('course_taken') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('content', $testimonial->content) }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5) <span class="text-red-500">*</span></label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', $testimonial->rating) }}" min="1" max="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                <input type="text" name="photo" id="photo" value="{{ old('photo', $testimonial->photo) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Testimonial</button>
            <a href="{{ route($p.'.testimonials.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
