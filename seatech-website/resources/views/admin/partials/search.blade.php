<form method="GET" action="{{ url()->current() }}" class="flex gap-2">
    <div class="relative w-64">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="{{ $placeholder ?? 'Search...' }}"
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:border-[#0077B6] focus:ring focus:ring-[#0077B6] focus:ring-opacity-20">
    </div>
    <button type="submit" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f94] transition">
        Search
    </button>
    @if(request('q'))
        <a href="{{ url()->current() }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition inline-flex items-center">
            Clear
        </a>
    @endif
</form>
