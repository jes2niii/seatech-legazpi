@extends('layouts.public')

@section('title', 'Enroll - Step 3: Emergency Contact - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.enroll.partials.steps', ['current' => 3])

        <div class="mt-10">
            <h1 class="text-3xl font-bold text-[#003366] mb-2">Emergency Contact</h1>
            <p class="text-gray-600 mb-8">Please provide a person we can contact in case of emergency.</p>

            <form method="POST" action="{{ route('enroll.postStep2_5') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                @csrf

                <div class="mb-6">
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">Contact Person Name <span class="text-red-500">*</span></label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    @error('emergency_contact_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship <span class="text-red-500">*</span></label>
                        <select name="emergency_contact_relationship" id="emergency_contact_relationship" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                            <option value="">Select Relationship</option>
                            @foreach (['Parent', 'Spouse', 'Sibling', 'Guardian', 'Friend', 'Other'] as $rel)
                                <option value="{{ $rel }}" @selected(old('emergency_contact_relationship') === $rel)>{{ $rel }}</option>
                            @endforeach
                        </select>
                        @error('emergency_contact_relationship') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="emergency_contact_mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile No. <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_mobile" id="emergency_contact_mobile" value="{{ old('emergency_contact_mobile') }}" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        @error('emergency_contact_mobile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('enroll.step2') }}" class="text-sm font-medium text-[#0077B6] hover:text-[#005a8c] transition">&larr; Back to Personal Info</a>
                    <button type="submit" class="bg-[#003366] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#002244] transition text-base">
                        Continue to Requirements →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
