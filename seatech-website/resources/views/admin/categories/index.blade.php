@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Categories</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search categories...'])
        @can('manage courses')
        @if(Route::has($p.'.categories.create'))
        <a href="{{ route($p.'.categories.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Category</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Icon</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Courses Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $category->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs"><i class="{{ $category->icon }}"></i> {{ $category->icon }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $category->courses_count ?? $category->courses->count() }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($category->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.categories.edit'))
                    <a href="{{ route($p.'.categories.edit', $category) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.categories.destroy'))
                    <form action="{{ route($p.'.categories.destroy', $category) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this category?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $categories->links() }}
    </div>
</div>
@endsection
