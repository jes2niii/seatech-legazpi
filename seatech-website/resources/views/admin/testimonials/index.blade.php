@extends('layouts.admin')

@section('title', 'Testimonials')
@section('page-title', 'Testimonials')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Testimonials</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search by student, course, or content...'])
        @can('manage gallery')
        @if(Route::has($p.'.testimonials.create'))
        <a href="{{ route($p.'.testimonials.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Testimonial</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Student Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Course Taken</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($testimonials as $testimonial)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $testimonial->student_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $testimonial->course_taken }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#D4A017]">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $testimonial->rating) &#9733; @else &#9734; @endif
                    @endfor
                    ({{ $testimonial->rating }})
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($testimonial->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.testimonials.edit'))
                    <a href="{{ route($p.'.testimonials.edit', $testimonial) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.testimonials.destroy'))
                    <form action="{{ route($p.'.testimonials.destroy', $testimonial) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this testimonial?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No testimonials found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $testimonials->links() }}
    </div>
</div>
@endsection
