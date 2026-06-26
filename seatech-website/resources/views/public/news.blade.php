@extends('layouts.public')

@section('title', 'News & Announcements - SEATECH Maritime Training')
@section('meta_description', 'Stay informed with the latest announcements, training updates, and maritime industry news from SEATECH.')
@section('og_title', 'News & Announcements - SEATECH')
@section('og_description', 'Stay informed with the latest announcements, training updates, and maritime industry news from SEATECH.')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">News & Announcements</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">Stay informed with the latest announcements, training updates, and maritime industry news from SEATECH.</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->count() > 0)
            @php $featured = $posts->first(); $rest = $posts->slice(1); @endphp

            <div class="mb-10 bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="h-64 lg:h-auto relative overflow-hidden">
                        @if($featured->hasMedia('featured_image'))
                            <img src="{{ $featured->getFirstMediaUrl('featured_image') }}" alt="{{ $featured->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#003366] to-[#0077B6]">
                                <div class="absolute inset-0 opacity-20">
                                    <svg class="w-full h-full" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="white" opacity="0.2"/><circle cx="350" cy="150" r="60" fill="white" opacity="0.2"/><circle cx="200" cy="30" r="30" fill="white" opacity="0.1"/></svg>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-[#D4A017] text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Featured</div>
                    </div>
                    <div class="p-6 sm:p-8 flex flex-col justify-center">
                        <div class="text-sm text-gray-500 mb-2">{{ $featured->published_at ? $featured->published_at->format('F d, Y') : '' }}</div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-[#003366] mb-3 group-hover:text-[#0077B6] transition leading-tight">{{ $featured->title }}</h2>
                        <p class="text-gray-600 leading-relaxed mb-4 line-clamp-3">{{ $featured->excerpt }}</p>
                        <a href="{{ route('news.show', $featured) }}" class="inline-flex items-center gap-2 text-[#0077B6] hover:text-[#003366] font-semibold text-sm transition">
                            Read Full Story
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            @if($rest->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rest as $post)
                        <article class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group flex flex-col">
                            <div class="h-44 relative overflow-hidden">
                                @if($post->hasMedia('featured_image'))
                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#0077B6] to-[#003366]">
                                        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23fff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M20 0L40 20 20 40 0 20z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                                    </div>
                                @endif
                                <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm text-[#003366] text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="font-bold text-lg text-[#003366] mb-2 group-hover:text-[#0077B6] transition leading-snug line-clamp-2">{{ $post->title }}</h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4 flex-1 line-clamp-3">{{ $post->excerpt }}</p>
                                <a href="{{ route('news.show', $post) }}" class="inline-flex items-center gap-2 text-[#0077B6] hover:text-[#003366] font-semibold text-sm transition">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-20 bg-[#F5F7FA] rounded-2xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <p class="text-gray-500 text-lg">No news articles yet.</p>
                <p class="text-gray-400 text-sm mt-2">Check back soon for the latest updates and announcements.</p>
            </div>
        @endif
    </div>
</section>
@endsection
