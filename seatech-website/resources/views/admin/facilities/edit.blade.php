@extends('layouts.admin')

@section('title', 'Edit Facility')
@section('page-title', 'Edit Facility')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $facility->name) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('description', $facility->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="features" class="block text-sm font-medium text-gray-700 mb-1">Features (JSON array or comma-separated)</label>
                <textarea name="features" id="features" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ old('features', is_array($facility->features) ? implode(', ', $facility->features) : $facility->features) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">e.g. ["AC","Projector","WiFi"] or AC, Projector, WiFi</p>
                @error('features') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @if($facility->hasMedia('photos'))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Photos</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($facility->getMedia('photos') as $photo)
                            <div class="relative group" id="photo-{{ $photo->id }}">
                                <img src="{{ $photo->getUrl() }}" alt="Facility photo" class="h-24 w-full object-cover rounded-lg border border-gray-200">
                                <button type="button"
                                        onclick="
                                            if (confirm('Delete this photo? This cannot be undone.')) {
                                                fetch('{{ route($p.'.facilities.photos.destroy', [$facility, $photo->id]) }}', {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json',
                                                    }
                                                }).then(r => r.json()).then(data => {
                                                    if (data.success) {
                                                        document.getElementById('photo-{{ $photo->id }}').remove();
                                                    } else {
                                                        alert('Failed to delete photo.');
                                                    }
                                                }).catch(() => alert('An error occurred.'));
                                            }
                                        "
                                        class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-red-700 shadow-lg"
                                        title="Delete this photo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Hover over a photo to reveal the delete button. Uploading new photos will add to the collection.</p>
                </div>
            @endif

            <div>
                <label for="photos" class="block text-sm font-medium text-gray-700 mb-1">Add More Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244]">
                <p class="text-xs text-gray-500 mt-1">JPEG, PNG, or WebP. Max 2MB each. Up to 10 files at a time.</p>
                @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $facility->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Facility</button>
            <a href="{{ route($p.'.facilities.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
