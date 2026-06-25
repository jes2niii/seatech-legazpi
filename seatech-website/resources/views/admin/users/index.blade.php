@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Users</h2>
    @can('manage users')
    @if(Route::has($p.'.users.create'))
    <a href="{{ route($p.'.users.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create User</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Roles</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    @foreach($user->roles as $role)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#003366] text-white mr-1">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.users.edit'))
                    <a href="{{ route($p.'.users.edit', $user) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(auth()->id() !== $user->id && Route::has($p.'.users.destroy'))
                    <form action="{{ route($p.'.users.destroy', $user) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this user?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection
