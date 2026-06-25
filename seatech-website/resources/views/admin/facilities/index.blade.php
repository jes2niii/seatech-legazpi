@extends('layouts.admin')

@section('title', 'Facilities')
@section('page-title', 'Facilities')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Facilities</h2>
    @can('manage gallery')
    @if(Route::has($p.'.facilities.create'))
    <a href="{{ route($p.'.facilities.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Facility</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Sort Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($facilities as $facility)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $facility->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($facility->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $facility->sort_order ?? 0 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.facilities.edit'))
                    <a href="{{ route($p.'.facilities.edit', $facility) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.facilities.destroy'))
                    <form action="{{ route($p.'.facilities.destroy', $facility) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this facility?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No facilities found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $facilities->links() }}
    </div>
</div>
@endsection
