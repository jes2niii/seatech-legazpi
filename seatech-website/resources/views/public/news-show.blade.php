@extends('layouts.public')

@section('title', $post->title . ' - SEATECH Maritime Training')

@section('content')
<article class="bg-white">
    <header class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('news') }}" class="inline-flex items-center gap-2 text-blue-200 hover:text-white text-sm mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to News
            </a>
            <div class="flex items-center gap-3 text-blue-200 text-sm mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <time>{{ $post->published_at ? $post->published_at->format('F d, Y') : 'Unpublished' }}</time>
                @if($post->author)
                    <span>•</span>
                    <span>By {{ $post->author->name }}</span>
                @endif
            </div>
            <h1 class="text-3xl lg:text-5xl font-extrabold text-white leading-tight">{{ $post->title }}</h1>
        </div>
    </header>

    <div class="py-12 lg:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($post->hasMedia('featured_image'))
                <img src="{{ $post->getFirstMediaUrl('featured_image') }}" alt="{{ $post->title }}" class="w-full h-auto rounded-2xl mb-8 shadow-md">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-[#003366] to-[#0077B6] rounded-2xl mb-8 flex items-center justify-center">
                    <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
            @endif

            @if($post->excerpt)
                <p class="text-lg text-gray-600 italic border-l-4 border-[#D4A017] pl-4 mb-8">{{ $post->excerpt }}</p>
            @endif

            <div class="prose max-w-none text-gray-800 leading-relaxed text-base">
                {!! nl2br(e($post->body)) !!}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('news') }}" class="inline-flex items-center gap-2 text-[#0077B6] hover:text-[#003366] font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to All News
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-[#D4A017] text-white px-5 py-2 rounded-lg font-medium hover:bg-[#b8890f] transition text-sm">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</article>
@endsection
