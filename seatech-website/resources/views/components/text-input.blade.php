@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#0077B6] focus:ring-[#0077B6] rounded-md shadow-sm']) }}>
