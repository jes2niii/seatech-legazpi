@extends('layouts.admin')

@section('title', 'Edit Schedule')
@section('page-title', 'Edit Schedule')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route($p.'.schedules.update', $schedule) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course <span class="text-red-500">*</span></label>
                <select name="course_id" id="course_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id) == $course->id ? 'selected' : '' }}>{{ $course->code }} - {{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $schedule->end_date->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                <input type="date" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline', $schedule->registration_deadline?->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('registration_deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="venue" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                <input type="text" name="venue" id="venue" value="{{ old('venue', $schedule->venue) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('venue') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity <span class="text-red-500">*</span></label>
                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $schedule->capacity) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
                    <option value="upcoming" {{ old('status', $schedule->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ old('status', $schedule->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-[#0077B6] text-white px-6 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Update Schedule</button>
            <a href="{{ route($p.'.schedules.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
