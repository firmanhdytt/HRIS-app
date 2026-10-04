@props(['class' => 'h-9 w-auto object-contain'])

<img src="{{ asset('images/logo.png') }}" alt="Logo" {{ $attributes->merge(['class' => $class]) }} />