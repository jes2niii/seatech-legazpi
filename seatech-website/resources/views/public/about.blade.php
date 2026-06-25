@extends('layouts.public')

@section('title', 'About Us - SEATECH Maritime Training')

@section('content')
{{-- Hero Banner --}}
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">About SEATECH Legazpi</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol</p>
    </div>
</section>

{{-- Mission Section --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-start gap-6 mb-16">
                <div class="flex-shrink-0 w-14 h-14 bg-[#003366] rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-[#003366] mb-4">Our Mission</h2>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        To provide world-class maritime education and training that meets international standards, 
                        producing competent, disciplined, and highly skilled seafarers who contribute to the 
                        global maritime industry.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-6 mb-16">
                <div class="flex-shrink-0 w-14 h-14 bg-[#D4A017] rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-[#003366] mb-4">Our Vision</h2>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        To be the leading maritime training and assessment center in the Bicol Region, 
                        recognized for excellence in seafarer education, innovation in training 
                        methodologies, and unwavering commitment to maritime safety and professionalism.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Core Values Section --}}
<section class="py-16 lg:py-20 bg-[#F5F7FA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">Our Core Values</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">The principles that guide every aspect of our training and operations.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-[#003366] rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-bold text-[#003366] mb-2">Excellence</h3>
                <p class="text-gray-600 text-sm">We strive for the highest standards in maritime training and education.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-[#D4A017] rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                </div>
                <h3 class="font-bold text-[#003366] mb-2">Integrity</h3>
                <p class="text-gray-600 text-sm">We uphold honesty and transparency in all our dealings.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-[#0077B6] rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
                <h3 class="font-bold text-[#003366] mb-2">Discipline</h3>
                <p class="text-gray-600 text-sm">We cultivate the discipline essential for success at sea.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-[#D4A017] rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/></svg>
                </div>
                <h3 class="font-bold text-[#003366] mb-2">Safety</h3>
                <p class="text-gray-600 text-sm">Safety is paramount in everything we teach and practice.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-[#003366] rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="font-bold text-[#003366] mb-2">Service</h3>
                <p class="text-gray-600 text-sm">We are dedicated to serving our students and the maritime community.</p>
            </div>
        </div>
    </div>
</section>
@endsection