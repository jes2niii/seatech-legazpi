@php
    $steps = [
        1 => ['label' => 'Course', 'route' => 'enroll.step1'],
        2 => ['label' => 'Personal Info', 'route' => 'enroll.step2'],
        3 => ['label' => 'Requirements', 'route' => 'enroll.step3'],
        4 => ['label' => 'Review', 'route' => 'enroll.review'],
        5 => ['label' => 'Confirmation', 'route' => '#'],
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <div class="flex items-center justify-between max-w-2xl mx-auto">
        @foreach ($steps as $num => $step)
            <div class="flex items-center">
                <a href="{{ $num < $current ? route($step['route']) : '#' }}"
                   class="flex flex-col items-center {{ $num < $current ? 'cursor-pointer' : 'cursor-default' }}">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition
                        {{ $num == $current ? 'bg-[#003366] text-white' : ($num < $current ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                        @if ($num < $current)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span class="text-xs mt-1 hidden sm:block {{ $num == $current ? 'text-[#003366] font-semibold' : 'text-gray-500' }}">
                        {{ $step['label'] }}
                    </span>
                </a>
                @if (!$loop->last)
                    <div class="w-8 sm:w-16 h-0.5 mx-1 sm:mx-2 {{ $num < $current ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
