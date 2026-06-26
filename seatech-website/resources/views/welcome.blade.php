@extends('layouts.public')

@section('title', 'SEATECH Maritime Training & Assessment Center')
@section('meta_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol. STCW-compliant courses, modern facilities, and expert instructors.')
@section('og_title', 'SEATECH Maritime Training & Assessment Center')
@section('og_description', 'Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol. STCW-compliant courses, modern facilities, and expert instructors.')

@push('jsonld')
@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => route('home'),
        'name' => setting('name'),
        'description' => setting('seo.default_description') . '.',
        'url' => route('home'),
        'logo' => asset('images/logo.webp'),
        'image' => asset('images/logo.webp'),
        'telephone' => setting('contact.phone_raw'),
        'email' => setting('contact.email'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => setting('address.street'),
            'addressLocality' => setting('address.city'),
            'addressRegion' => setting('address.province'),
            'addressCountry' => setting('address.country_code'),
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '08:00',
            'closes' => '17:00',
        ],
        'sameAs' => array_values(array_filter([
            setting('social.facebook'),
            setting('social.instagram'),
            setting('social.youtube'),
            setting('social.linkedin'),
        ])),
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-r from-[#003366] to-[#0077B6] overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Navigate Your Future With Confidence
                </h1>
                <p class="text-lg sm:text-xl text-blue-200 max-w-3xl mx-auto mb-10 leading-relaxed">
                    Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('courses') }}" class="bg-[#D4A017] hover:bg-[#b88914] text-white font-semibold px-8 py-4 rounded-lg text-lg transition shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Explore Courses
                    </a>
                    <a href="{{ route('enroll.step1') }}" class="bg-white hover:bg-gray-100 text-[#003366] font-semibold px-8 py-4 rounded-lg text-lg transition shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Enroll Now
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                <path d="M0 60V30C240 50 480 60 720 60C960 60 1200 50 1440 30V60H0Z" fill="#F5F7FA"/>
            </svg>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="bg-[#F5F7FA] py-16 lg:py-20" x-data="{ stats: { years: 0, courses: 0, trained: 0, vessels: 0 }, init() { let observer = new IntersectionObserver((entries) => { entries.forEach(entry => { if (entry.isIntersecting) { this.animateStats(); observer.disconnect(); } }); }, { threshold: 0.3 }); observer.observe(this.$el); }, animateStats() { const duration = 2000; const start = performance.now(); const targets = { years: 15, courses: {{ $courses->count() }}, trained: 5000, vessels: 50 }; const ease = (t) => 1 - Math.pow(1 - t, 3); const frame = (now) => { const elapsed = now - start; const progress = Math.min(elapsed / duration, 1); const eased = ease(progress); this.stats.years = Math.floor(eased * targets.years); this.stats.courses = Math.floor(eased * targets.courses); this.stats.trained = Math.floor(eased * targets.trained); this.stats.vessels = Math.floor(eased * targets.vessels); if (progress < 1) requestAnimationFrame(frame); else { this.stats.years = targets.years; this.stats.courses = targets.courses; this.stats.trained = targets.trained; this.stats.vessels = targets.vessels; } }; requestAnimationFrame(frame); } }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <div class="bg-white rounded-2xl shadow-md p-6 lg:p-8 text-center hover:shadow-lg transition">
                    <div class="text-4xl lg:text-5xl font-extrabold text-[#003366] mb-2" x-text="stats.years + '+'">0+</div>
                    <div class="text-gray-500 font-medium">Years of Excellence</div>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 lg:p-8 text-center hover:shadow-lg transition">
                    <div class="text-4xl lg:text-5xl font-extrabold text-[#003366] mb-2" x-text="stats.courses + '+'">0+</div>
                    <div class="text-gray-500 font-medium">Courses Offered</div>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 lg:p-8 text-center hover:shadow-lg transition">
                    <div class="text-4xl lg:text-5xl font-extrabold text-[#003366] mb-2" x-text="stats.trained.toLocaleString() + '+'">0+</div>
                    <div class="text-gray-500 font-medium">Trained Seafarers</div>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 lg:p-8 text-center hover:shadow-lg transition">
                    <div class="text-4xl lg:text-5xl font-extrabold text-[#003366] mb-2" x-text="stats.vessels + '+'">0+</div>
                    <div class="text-gray-500 font-medium">Partner Vessels</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Courses Section --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">Featured Training Programs</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">STCW-compliant courses designed to equip seafarers with the knowledge and skills to excel at sea.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($courses as $course)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                        <div class="h-48 relative overflow-hidden">
                            @if($course->hasMedia('featured_image'))
                                <img src="{{ $course->getFirstMediaUrl('featured_image') }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center">
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23fff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M0 0h40v40H0z\' fill=\'none\'/%3E%3Cpath d=\'M20 0L40 20 20 40 0 20z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                                    </div>
                                    <div class="relative text-center px-4">
                                        <span class="text-[#D4A017] font-bold text-sm uppercase tracking-widest block mb-1">{{ $course->code }}</span>
                                        <h3 class="text-white font-bold text-xl leading-tight">{{ $course->title }}</h3>
                                    </div>
                                </div>
                            @endif
                            @if($course->category)
                                <span class="absolute top-3 right-3 bg-[#D4A017]/90 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $course->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4 text-sm">
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $course->duration }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[#D4A017] font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>PHP {{ number_format($course->fee, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-5 line-clamp-2">{{ $course->description }}</p>
                            <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center gap-2 text-[#0077B6] hover:text-[#003366] font-semibold text-sm transition group">
                                View Details
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p class="text-lg">No courses available at the moment. Please check back later.</p>
                    </div>
                @endforelse
            </div>
            @if($courses->count() > 0)
                <div class="text-center mt-10">
                    <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 bg-[#003366] hover:bg-[#002244] text-white font-semibold px-8 py-4 rounded-lg transition shadow-md hover:shadow-lg">
                        View All Courses
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Why Choose SEATECH Section --}}
    <section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-16 lg:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' viewBox=\'0 0 80 80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.5\'%3E%3Cpath d=\'M50 50c-5.523 0-10-4.477-10-10s4.477-10 10-10 10 4.477 10 10-4.477 10-10 10z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Why Choose SEATECH?</h2>
                <p class="text-blue-200 max-w-2xl mx-auto">We are committed to delivering world-class maritime education and training.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 lg:p-8 text-center border border-white/20 hover:bg-white/20 transition group">
                    <div class="w-16 h-16 bg-[#D4A017]/20 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Expert Instructors</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">Learn from seasoned maritime professionals with years of real-world experience at sea.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 lg:p-8 text-center border border-white/20 hover:bg-white/20 transition group">
                    <div class="w-16 h-16 bg-[#D4A017]/20 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Modern Facilities</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">State-of-the-art training facilities and equipment that simulate real maritime environments.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 lg:p-8 text-center border border-white/20 hover:bg-white/20 transition group">
                    <div class="w-16 h-16 bg-[#D4A017]/20 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">STCW Compliance</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">All programs adhere to the latest STCW standards and international maritime regulations.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 lg:p-8 text-center border border-white/20 hover:bg-white/20 transition group">
                    <div class="w-16 h-16 bg-[#D4A017]/20 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Flexible Schedule</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">Convenient class schedules designed to accommodate both working seafarers and new applicants.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">What Our Trainees Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Hear from the seafarers who have trained with us and advanced their maritime careers.</p>
            </div>
            @if($testimonials->count() > 0)
                <div x-data="{ current: 0, items: {{ $testimonials->count() }} }" class="relative max-w-3xl mx-auto">
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${current * 100}%)`">
                            @foreach($testimonials as $testimonial)
                                <div class="w-full flex-shrink-0 px-4">
                                    <div class="bg-[#F5F7FA] rounded-2xl p-8 lg:p-10 text-center">
                                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                                            {{ substr($testimonial->student_name, 0, 1) }}
                                        </div>
                                        <div class="flex items-center justify-center gap-1 mb-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-[#D4A017]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <blockquote class="text-gray-700 text-lg leading-relaxed mb-6 italic">"{{ $testimonial->content }}"</blockquote>
                                        <div>
                                            <div class="font-bold text-[#003366]">{{ $testimonial->student_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $testimonial->course_taken }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($testimonials->count() > 1)
                        <button @click="current = current > 0 ? current - 1 : items - 1" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 lg:-translate-x-6 w-12 h-12 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="current = current < items - 1 ? current + 1 : 0" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 lg:translate-x-6 w-12 h-12 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div class="flex items-center justify-center gap-2 mt-6">
                            <template x-for="(_, i) in Array.from({ length: items })" :key="i">
                                <button @click="current = i" class="w-3 h-3 rounded-full transition" :class="current === i ? 'bg-[#003366]' : 'bg-gray-300'"></button>
                            </template>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">Testimonials coming soon.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Facilities Gallery Section --}}
    <section class="py-16 lg:py-20 bg-[#F5F7FA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">Our Facilities</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Modern training facilities designed to provide a realistic maritime learning environment.</p>
            </div>
            @if($facilities->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($facilities as $facility)
                        <div class="group relative rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-lg transition cursor-pointer">
                            <div class="aspect-[4/3]">
                                @if($facility->hasMedia('photos'))
                                    <img src="{{ $facility->getFirstMediaUrl('photos') }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white/40 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#003366]/90 via-[#003366]/40 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-4 lg:p-6">
                                <div>
                                    <h3 class="text-white font-bold text-sm lg:text-base">{{ $facility->name }}</h3>
                                    @if($facility->description)
                                        <p class="text-blue-200 text-xs mt-1 line-clamp-2">{{ $facility->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">Facility information coming soon.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Latest News Section --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#003366] mb-4">Latest News & Updates</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Stay informed with the latest announcements and developments from SEATECH.</p>
            </div>
            @if($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $post)
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group flex flex-col">
                            <div class="h-48 relative overflow-hidden">
                                @if($post->hasMedia('featured_image'))
                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#0077B6] to-[#003366]">
                                        <div class="absolute inset-0 opacity-20">
                                            <svg class="w-full h-full" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="white" opacity="0.1"/><circle cx="350" cy="150" r="60" fill="white" opacity="0.1"/><circle cx="200" cy="30" r="30" fill="white" opacity="0.05"/><rect x="300" y="20" width="80" height="80" rx="10" fill="white" opacity="0.05"/></svg>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm text-[#003366] text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Unpublished' }}
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="font-bold text-lg text-[#003366] mb-3 group-hover:text-[#0077B6] transition leading-snug">{{ $post->title }}</h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-5 flex-1 line-clamp-3">{{ $post->excerpt }}</p>
                                <a href="{{ route('news.show', $post) }}" class="inline-flex items-center gap-2 text-[#0077B6] hover:text-[#003366] font-semibold text-sm transition">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">No news articles yet.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Call-to-Action Section --}}
    <section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-16 lg:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Start Your Maritime Career Today</h2>
            <p class="text-lg text-blue-200 mb-8 max-w-2xl mx-auto">Take the first step toward a rewarding career at sea. Enroll now and begin your journey with SEATECH.</p>
            <a href="{{ route('enroll.step1') }}" class="inline-flex items-center gap-2 bg-[#D4A017] hover:bg-[#b88914] text-white font-bold px-10 py-4 rounded-lg text-lg transition shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Enroll Now
            </a>
        </div>
    </section>
@endsection
