<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl font-semibold text-xs text-slate-350 uppercase tracking-widest hover:bg-slate-700 hover:text-slate-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200 shadow-sm disabled:opacity-25']) }}>
    {{ $slot }}
</button>
