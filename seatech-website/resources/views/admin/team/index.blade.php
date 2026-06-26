@extends('layouts.admin')

@section('title', 'Leadership Team')
@section('page-title', 'Leadership Team')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Team Members</h2>
    <div class="flex flex-wrap items-center gap-2">
        @include('admin.partials.search', ['placeholder' => 'Search by name, position, or department...'])
        @can('manage gallery')
        @if(Route::has($p.'.team.create'))
        <a href="{{ route($p.'.team.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Add Member</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($members as $member)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->sort_order }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $member->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 break-words max-w-xs">{{ $member->position }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 break-words max-w-xs">{{ $member->department ?? '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($member->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.team.edit'))
                    <a href="{{ route($p.'.team.edit', $member) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.team.destroy'))
                    <form action="{{ route($p.'.team.destroy', $member) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this team member?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No team members yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $members->links() }}
    </div>
</div>
@endsection
