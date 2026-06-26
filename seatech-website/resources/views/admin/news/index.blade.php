@extends('layouts.admin')

@section('title', 'News')
@section('page-title', 'News')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All News</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search news...'])
        @can('manage news')
        @if(Route::has($p.'.news.create'))
        <a href="{{ route($p.'.news.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create News</a>
        @endif
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Published Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($news as $article)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $article->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $article->author ?? 'SEATECH' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($article->is_published)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $article->published_at?->format('M d, Y') ?? $article->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.news.edit'))
                    <a href="{{ route($p.'.news.edit', $article) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.news.destroy'))
                    <form action="{{ route($p.'.news.destroy', $article) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this news article?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No news articles found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $news->links() }}
    </div>
</div>
@endsection
