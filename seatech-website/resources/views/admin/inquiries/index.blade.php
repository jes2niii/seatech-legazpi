@extends('layouts.admin')

@section('title', 'Inquiries')
@section('page-title', 'Inquiries')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Inquiries</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search inquiries...'])
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Subject</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($inquiries as $inquiry)
            <tr class="hover:bg-gray-50 {{ !$inquiry->is_read ? 'bg-blue-50' : '' }}">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $inquiry->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $inquiry->email }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $inquiry->subject }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($inquiry->is_read)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Read</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Unread</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.inquiries.show'))
                    <a href="{{ route($p.'.inquiries.show', $inquiry) }}" class="text-[#0077B6] hover:underline">View</a>
                    @endif
                    @if(Route::has($p.'.inquiries.destroy'))
                    <form action="{{ route($p.'.inquiries.destroy', $inquiry) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this inquiry?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No inquiries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $inquiries->links() }}
    </div>
</div>
@endsection
