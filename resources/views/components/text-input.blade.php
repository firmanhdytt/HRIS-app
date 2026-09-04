@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-650 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl shadow-sm transition-all duration-200']) }}>
