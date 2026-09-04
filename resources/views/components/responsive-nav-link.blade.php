@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-medium text-indigo-400 bg-indigo-950/30 focus:outline-none focus:text-indigo-300 focus:bg-indigo-950/50 focus:border-indigo-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 hover:border-slate-700 focus:outline-none focus:text-slate-200 focus:bg-slate-900 focus:border-slate-650 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
