@extends('layouts.public')

@section('title', 'Enroll - Step 2: Personal Information - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.enroll.partials.steps', ['current' => 2])

        <div class="mt-10">
            <h1 class="text-3xl font-bold text-[#003366] mb-2">Personal Information</h1>
            <p class="text-gray-600 mb-8">Please provide your personal details below.</p>

            <form method="POST" action="{{ route('enroll.postStep2') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" id="gender"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                            <option value="">Select Gender</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other" @selected(old('gender') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                        <select name="civil_status" id="civil_status"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                            <option value="">Select Civil Status</option>
                            <option value="single" @selected(old('civil_status') === 'single')>Single</option>
                            <option value="married" @selected(old('civil_status') === 'married')>Married</option>
                            <option value="widowed" @selected(old('civil_status') === 'widowed')>Widowed</option>
                            <option value="separated" @selected(old('civil_status') === 'separated')>Separated</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Place of Birth</label>
                        <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1">Contact No. <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        @error('mobile_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="rank" class="block text-sm font-medium text-gray-700 mb-1">Rank / Position</label>
                        <input type="text" name="rank" id="rank" value="{{ old('rank') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]"
                            placeholder="e.g., Deck Officer, Engine Cadet">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">{{ old('address') }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="seaman_book_number" class="block text-sm font-medium text-gray-700 mb-1">Seaman's Book Number</label>
                    <input type="text" name="seaman_book_number" id="seaman_book_number" value="{{ old('seaman_book_number') }}"
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('enroll.step1') }}" class="text-sm font-medium text-[#0077B6] hover:text-[#005a8c] transition">&larr; Back to Course Selection</a>
                    <button type="submit" class="bg-[#003366] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#002244] transition text-base">
                        Continue to Emergency Contact →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
