@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('description', $course->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                @if($course->hasMedia('featured_image'))
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ $course->getFirstMediaUrl('featured_image') }}" alt="{{ $course->title }}" class="h-32 w-48 object-cover rounded-lg border border-gray-200">
                        <div>
                            <p class="text-sm text-gray-700 font-medium">Current image</p>
                            <p class="text-xs text-gray-500">Uploading a new file will replace it.</p>
                        </div>
                    </div>
                @endif
                <input type="file" name="featured_image" id="featured_image" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244]">
                <p class="text-xs text-gray-500 mt-1">JPEG, PNG, or WebP. Max 2MB.</p>
                @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                <input type="text" name="duration" id="duration" value="{{ old('duration', $course->duration) }}" placeholder="e.g. 5 Days" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('duration') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">Fee (₱) <span class="text-red-500">*</span></label>
                <input type="number" name="fee" id="fee" value="{{ old('fee', $course->fee) }}" step="0.01" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('fee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="prerequisites" class="block text-sm font-medium text-gray-700 mb-1">Prerequisites</label>
                <textarea name="prerequisites" id="prerequisites" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('prerequisites', $course->prerequisites) }}</textarea>
                @error('prerequisites') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">Max Participants <span class="text-red-500">*</span></label>
                <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $course->max_participants) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('max_participants') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Course</button>
            <a href="{{ route($p.'.courses.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
