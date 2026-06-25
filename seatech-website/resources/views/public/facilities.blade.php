@extends('layouts.public')

@section('title', 'Our Facilities - SEATECH Maritime Training')

@section('content')
<section class="bg-gradient-to-r from-[#003366] to-[#0077B6] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Our Facilities</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">Modern training facilities designed to provide a realistic and immersive maritime learning environment.</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="group relative rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-lg transition cursor-pointer">
                        <div class="aspect-[4/3] relative overflow-hidden">
                            @if($facility->hasMedia('photos'))
                                @php $firstPhoto = $facility->getMedia('photos')->first(); @endphp
                                <img src="{{ $firstPhoto->getUrl() }}" alt="{{ $facility->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#003366] to-[#0077B6] flex items-center justify-center">
                                    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23fff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M20 0L40 20 20 40 0 20z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                                    <svg class="w-16 h-16 text-white/40 group-hover:scale-110 transition relative" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#003366]/95 via-[#003366]/60 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-5">
                            <div>
                                <h3 class="text-white font-bold text-lg leading-tight">{{ $facility->name }}</h3>
                                @if($facility->description)
                                    <p class="text-blue-200 text-sm mt-1">{{ $facility->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-[#003366] text-lg">{{ $facility->name }}</h3>
                            @if($facility->description)
                                <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $facility->description }}</p>
                            @endif
                            @if($facility->features && is_array($facility->features) && count($facility->features))
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach(array_slice($facility->features, 0, 3) as $feature)
                                        <span class="text-xs bg-blue-50 text-[#0077B6] px-2 py-0.5 rounded-full">{{ $feature }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-[#F5F7FA] rounded-2xl">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                <p class="text-gray-500 text-lg">Facility gallery coming soon.</p>
                <p class="text-gray-400 text-sm mt-2">Photos and virtual tour will be available shortly.</p>
            </div>
        @endif
    </div>
</section>

<section class="py-12 lg:py-16 bg-[#F5F7FA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-[#003366] to-[#0077B6] rounded-2xl p-8 lg:p-12 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            <div class="relative">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-2xl lg:text-3xl font-bold mb-3">Virtual Tour</h2>
                <p class="text-blue-200 max-w-2xl mx-auto mb-6">Take a virtual walk through our training facilities from the comfort of your home. Coming soon!</p>
                <span class="inline-block bg-white/10 text-blue-200 px-4 py-2 rounded-lg text-sm font-medium">Coming Soon</span>
            </div>
        </div>
    </div>
</section>
@endsection
