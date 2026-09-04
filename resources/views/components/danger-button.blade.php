<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 bg-rose-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-500 focus:bg-rose-500 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all duration-200 shadow-md']) }}>
    {{ $slot }}
</button>
