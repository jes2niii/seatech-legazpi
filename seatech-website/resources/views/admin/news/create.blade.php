@extends('layouts.admin')

@section('title', 'Create News')
@section('page-title', 'Create News')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('excerpt') }}</textarea>
                @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body <span class="text-red-500">*</span></label>
                <textarea name="body" id="body" rows="8" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('body') }}</textarea>
                @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" id="featured_image" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244]">
                <p class="text-xs text-gray-500 mt-1">JPEG, PNG, or WebP. Max 2MB.</p>
                @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_published" class="ml-2 text-sm text-gray-700">Published</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Save News</button>
            <a href="{{ route($p.'.news.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
