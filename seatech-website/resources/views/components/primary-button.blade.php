<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#003366] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#002244] focus:bg-[#002244] active:bg-[#001429] focus:outline-none focus:ring-2 focus:ring-[#0077B6] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
