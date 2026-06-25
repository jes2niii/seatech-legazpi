@extends('layouts.admin')

@section('title', 'Students')
@section('page-title', 'Students')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Students</h2>
    @can('manage enrollments')
    @if(Route::has($p.'.students.create'))
    <a href="{{ route($p.'.students.create') }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">+ Create Student</a>
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Full Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mobile</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Gender</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Enrollments</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($students as $student)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->mobile_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($student->gender) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->enrollments_count ?? $student->enrollments->count() }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    @if(Route::has($p.'.students.show'))
                    <a href="{{ route($p.'.students.show', $student) }}" class="text-[#0077B6] hover:underline">View</a>
                    @endif
                    @if(Route::has($p.'.students.edit'))
                    <a href="{{ route($p.'.students.edit', $student) }}" class="text-[#D4A017] hover:underline">Edit</a>
                    @endif
                    @if(Route::has($p.'.students.destroy'))
                    <form action="{{ route($p.'.students.destroy', $student) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this student?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $students->links() }}
    </div>
</div>
@endsection
