@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Courses</h2>
    @can('manage courses')
    @if(Route::has($p.'.courses.create'))
    <a href="{{ route($p.'.courses.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Course</a>
    @endif
    @endcan
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#003366] text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Duration</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fee</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($courses as $course)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $course->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $course->category->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $course->duration }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($course->fee, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($course->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <a href="{{ route($p.'.courses.show', $course) }}" class="text-[#0077B6] hover:underline">View</a>
                    @if(Route::has($p.'.courses.edit'))
                    <a href="{{ route($p.'.courses.edit', $course) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.courses.destroy'))
                    <form action="{{ route($p.'.courses.destroy', $course) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this course?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No courses found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $courses->links() }}
    </div>
</div>
@endsection
