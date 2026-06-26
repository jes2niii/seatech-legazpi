@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@section('content')
@php $p = request()->segment(1); @endphp

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<form action="{{ route($p.'.settings.update') }}" method="POST">
    @csrf
    @method('PATCH')

    @foreach($groups as $groupKey => $groupLabel)
        @if(isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-[#003366] mb-4 pb-2 border-b border-gray-200">{{ $groupLabel }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings[$groupKey] as $setting)
                        <div class="{{ $setting->type === 'text' ? 'md:col-span-2' : '' }}">
                            <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $setting->label ?? $setting->key }}
                            </label>
                            @if($setting->type === 'text')
                                <textarea name="{{ str_contains($setting->key, '.') ? str_replace('.', '[', $setting->key) . ']' : $setting->key }}" id="setting_{{ $setting->key }}" rows="{{ str_starts_with($setting->key, 'about.') ? 6 : 3 }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">{{ $setting->value }}</textarea>
                                @if(str_starts_with($setting->key, 'about.'))
                                    <p class="text-xs text-gray-500 mt-1">Tip: use <code class="bg-gray-100 px-1 rounded">- </code> or <code class="bg-gray-100 px-1 rounded">* </code> at the start of a line for bullet points. Use blank lines for new paragraphs.</p>
                                @endif
                            @elseif($setting->type === 'int')
                                <input type="number" name="{{ str_contains($setting->key, '.') ? str_replace('.', '[', $setting->key) . ']' : $setting->key }}" id="setting_{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                            @else
                                <input type="text" name="{{ str_contains($setting->key, '.') ? str_replace('.', '[', $setting->key) . ']' : $setting->key }}" id="setting_{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20"
                                    {{ $setting->value === null ? 'placeholder="(not set)"' : '' }}>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <div class="bg-white rounded-lg shadow p-6 sticky bottom-0 border-t-4 border-[#D4A017]">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">Changes apply immediately to all public pages.</p>
            <button type="submit" class="bg-[#0077B6] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#005f94] transition">
                Save All Changes
            </button>
        </div>
    </div>
</form>
@endsection
