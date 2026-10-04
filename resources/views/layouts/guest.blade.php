<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRIS System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-full flex flex-col justify-center selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="z-10 text-center mb-2">
                <a href="/" class="inline-flex flex-col items-center gap-2">
                    <x-application-logo class="w-16 h-16 fill-current text-indigo-600 shadow-md rounded-full" />
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">HRIS<span class="text-indigo-600">System</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-8 py-8 bg-white border border-slate-200/80 shadow-xl overflow-hidden sm:rounded-2xl z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
