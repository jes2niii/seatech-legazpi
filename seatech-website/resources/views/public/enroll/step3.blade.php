@extends('layouts.public')

@section('title', 'Enroll - Step 3: Requirements - SEATECH')

@section('content')
<div class="bg-[#F5F7FA] min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.enroll.partials.steps', ['current' => 3])

        <div class="mt-10">
            <h1 class="text-3xl font-bold text-[#003366] mb-2">Requirements</h1>
            <p class="text-gray-600 mb-4">Check all requirements you are able to submit. You may upload the actual files later or present them in person.</p>

            <form method="POST" action="{{ route('enroll.postStep3') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                @csrf

                <div class="space-y-4">
                    @php
                        $commonRequirements = [
                            'birth_certificate' => 'Birth Certificate (PSA)',
                            'transcript' => 'Transcript of Records (TOR)',
                            'medical_certificate' => 'Medical Certificate',
                            'seamans_book' => "Seaman's Book",
                            'passport_copy' => 'Passport Copy',
                            'photo_2x2' => '2x2 ID Photo',
                        ];
                        $selected = old('requirements', []);
                    @endphp

                    @foreach ($commonRequirements as $key => $label)
                        <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer transition hover:border-[#0077B6] hover:bg-blue-50 has-[:checked]:border-[#0077B6] has-[:checked]:bg-blue-50">
                            <input type="checkbox" name="requirements[]" value="{{ $key }}"
                                {{ in_array($key, $selected) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-gray-300 text-[#0077B6] focus:ring-[#0077B6]">
                            <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                @error('requirements') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> You may submit digital copies of your requirements during the enrollment process
                        or present the original documents in person at the SEATECH office. You will be guided by our staff once your enrollment is processed.
                    </p>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-[#003366] mb-1">Optional: Upload Supporting Documents</h3>
                    <p class="text-sm text-gray-600 mb-4">If you have digital copies ready (PDF, JPEG, PNG), you may upload them now. You can also bring the originals to our office.</p>

                    <input type="file" name="documents[]" id="documents" multiple accept="image/jpeg,image/png,image/webp,application/pdf"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#003366] file:text-white hover:file:bg-[#002244]">
                    <p class="text-xs text-gray-500 mt-1">JPEG, PNG, WebP, or PDF. Max 5MB each. Up to 6 files.</p>
                    @error('documents.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('enroll.step2') }}" class="text-sm font-medium text-[#0077B6] hover:text-[#005a8c] transition">&larr; Back to Personal Info</a>
                    <button type="submit" class="bg-[#003366] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#002244] transition text-base">
                        Review Application →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
