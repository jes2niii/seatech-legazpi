@extends('layouts.public')

@section('title', 'About Us - SEATECH Maritime Training')
@section('meta_description', 'Learn about SEATECH Maritime Training & Assessment Center, our mission, vision, and core values in Bicol.')
@section('og_title', 'About SEATECH Maritime Training Center')
@section('og_description', 'Learn about SEATECH Maritime Training & Assessment Center, our mission, vision, and core values in Bicol.')

@php
    $teamMembers = \App\Models\TeamMember::where('is_active', true)->orderBy('sort_order')->get();
    $coreValues = \App\Models\CoreValue::active()->ordered()->get();
@endphp

@section('content')
{{-- Hero Banner --}}
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">About SEATECH Legazpi</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol</p>
    </div>
</section>

{{-- Mission Section --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-start gap-6 mb-16">
                <div class="flex-shrink-0 w-14 h-14 bg-[#003366] rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-[#003366] mb-4">Our Mission</h2>
                    <div class="text-gray-700 text-lg leading-relaxed">
                        {!! format_rich_text(setting('about.mission')) !!}
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-6 mb-16">
                <div class="flex-shrink-0 w-14 h-14 bg-[#D4A017] rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-[#003366] mb-4">Our Vision</h2>
                    <div class="text-gray-700 text-lg leading-relaxed">
                        {!! format_rich_text(setting('about.vision')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Core Values Section --}}
@if($coreValues->count() > 0)
<section class="py-16 lg:py-20 bg-[#F5F7FA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">Our Core Values</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">The principles that guide every aspect of our training and operations.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($coreValues as $coreValue)
                <div class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] grow-0 bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: {{ $coreValue->color }}">
                        {!! core_value_icon($coreValue->icon) !!}
                    </div>
                    <h3 class="font-bold text-[#003366] mb-2">{{ $coreValue->name }}</h3>
                    @if($coreValue->description)
                        <p class="text-gray-600 text-sm text-justify">{{ $coreValue->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Company History Section --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="flex items-center mb-6">
                    <div class="w-1.5 h-10 bg-[#D4A017] rounded mr-4"></div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-[#003366]">Our Story</h2>
                </div>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    @foreach(setting_group('about.story') as $key => $paragraph)
                        @php
                            $paragraph = str_replace(
                                ['{city}', '{province}', '{name}'],
                                [setting('address.city'), setting('address.province'), setting('name')],
                                $paragraph
                            );
                        @endphp
                        {!! format_rich_text($paragraph) !!}
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/3] bg-gradient-to-br from-[#003366] to-[#0077B6] rounded-2xl flex items-center justify-center relative overflow-hidden shadow-xl">
                    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                    <div class="relative text-center text-white p-8">
                        <svg class="w-20 h-20 text-[#D4A017] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-5xl font-extrabold mb-2">{{ setting('stats.years_excellence') }}+</p>
                        <p class="text-lg text-blue-200 uppercase tracking-wider">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Leadership Team Section --}}
@if($teamMembers->count() > 0)
<section class="py-16 lg:py-20 bg-[#F5F7FA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-4">
                <div class="w-1.5 h-10 bg-[#D4A017] rounded mr-4"></div>
                <h2 class="text-3xl lg:text-4xl font-bold text-[#003366]">Our Leadership Team</h2>
            </div>
            <p class="text-gray-600 max-w-2xl mx-auto">Meet the experienced professionals leading SEATECH's mission to deliver world-class maritime training.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($teamMembers->count(), 4) }} gap-6">
            @foreach($teamMembers as $member)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition text-center">
                    <div class="aspect-square bg-gradient-to-br from-[#003366] to-[#0077B6] relative overflow-hidden">
                        @if($member->hasMedia('photo'))
                            <img src="{{ $member->getFirstMediaUrl('photo') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-32 h-32 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-[#003366] text-lg">{{ $member->name }}</h3>
                        <p class="text-[#D4A017] font-medium text-sm mt-1">{{ $member->position }}</p>
                        @if($member->department)
                            <p class="text-gray-500 text-xs mt-0.5">{{ $member->department }}</p>
                        @endif
                        @if($member->bio)
                            <p class="text-gray-600 text-sm mt-3 line-clamp-3">{{ $member->bio }}</p>
                        @endif
                        @if($member->email || $member->phone)
                            <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
                                @if($member->email)<p>{{ $member->email }}</p>@endif
                                @if($member->phone)<p>{{ $member->phone }}</p>@endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection