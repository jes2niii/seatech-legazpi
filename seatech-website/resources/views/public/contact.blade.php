@extends('layouts.public')

@section('title', 'Contact Us - SEATECH Maritime Training')
@section('meta_description', 'Get in touch with SEATECH Maritime Training Center in Legazpi City, Albay, Philippines. Call, email, or visit our office.')
@section('og_title', 'Contact SEATECH Maritime Training')
@section('og_description', 'Get in touch with SEATECH Maritime Training Center in Legazpi City, Albay, Philippines.')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Contact Us</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">We'd love to hear from you. Reach out to us for inquiries, training information, or partnership opportunities.</p>
    </div>
</section>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div class="bg-gradient-to-br from-[#003366] to-[#0077B6] text-white rounded-2xl p-6 text-center shadow-md hover:shadow-lg transition">
                <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Visit Us</h3>
                <p class="text-blue-200 text-sm leading-relaxed">{{ setting('address.city') }}, {{ setting('address.province') }}<br>{{ setting('address.country') }}</p>
            </div>
            <div class="bg-gradient-to-br from-[#003366] to-[#0077B6] text-white rounded-2xl p-6 text-center shadow-md hover:shadow-lg transition">
                <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Call Us</h3>
                <p class="text-blue-200 text-sm">{{ setting('contact.phone') }}</p>
                <p class="text-blue-200 text-xs mt-1">Mon - Fri, {{ setting('hours.weekdays') }}</p>
            </div>
            <div class="bg-gradient-to-br from-[#003366] to-[#0077B6] text-white rounded-2xl p-6 text-center shadow-md hover:shadow-lg transition">
                <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Email Us</h3>
                <p class="text-blue-200 text-sm">{{ setting('contact.email') }}</p>
                <p class="text-blue-200 text-xs mt-1">We reply within 24 hours</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-[#003366] mb-2">Send us a Message</h2>
                <p class="text-gray-600 text-sm mb-6">Fill out the form and our team will get back to you as soon as possible.</p>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 text-sm">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                        </div>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="5" required
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 text-sm focus:ring-[#0077B6] focus:border-[#0077B6]">{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#003366] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#002244] transition">
                        Send Message
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-[#F5F7FA] rounded-2xl p-6">
                    <h3 class="font-bold text-[#003366] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Office Hours
                    </h3>
                    <ul class="space-y-1 text-sm text-gray-700">
                        <li class="flex justify-between"><span>Monday - Friday</span><span class="font-medium">{{ setting('hours.weekdays') }}</span></li>
                        <li class="flex justify-between"><span>Saturday</span><span class="font-medium">{{ setting('hours.saturday') }}</span></li>
                        <li class="flex justify-between"><span>Sunday</span><span class="font-medium text-red-600">{{ setting('hours.sunday') }}</span></li>
                    </ul>
                </div>

                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
                    <iframe src="{{ setting('maps.embed_url') }}" width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
