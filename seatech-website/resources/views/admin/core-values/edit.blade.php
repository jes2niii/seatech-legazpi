@extends('layouts.admin')

@section('title', 'Edit Core Value')
@section('page-title', 'Edit Core Value')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.core-values.update', $value) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $value->name) }}" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $value->sort_order) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first.</p>
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-red-500">*</span></label>
                <select name="icon" id="icon" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    @php $icons = ['star','heart','shield','check','users','lightbulb','handshake','compass','anchor','clock','sliders','shield-alert']; @endphp
                    @foreach($icons as $icon)
                        <option value="{{ $icon }}" {{ old('icon', $value->icon) === $icon ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $icon)) }}</option>
                    @endforeach
                </select>
                @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color <span class="text-red-500">*</span></label>
                <select name="color" id="color" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    @php $colors = ['#003366' => 'Navy', '#0077B6' => 'Ocean', '#D4A017' => 'Gold']; @endphp
                    @foreach($colors as $hex => $name)
                        <option value="{{ $hex }}" {{ old('color', $value->color) === $hex ? 'selected' : '' }}>{{ $name }} ({{ $hex }})</option>
                    @endforeach
                </select>
                @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('description', $value->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $value->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active (show on public About page)</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Value</button>
            <a href="{{ route($p.'.core-values.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
