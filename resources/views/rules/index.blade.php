<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white tracking-tight">
            📢 Aturan Kerja & Pemberitahuan
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Tombol Tambah --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('rules.create') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 gap-1.5 mb-6">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Aturan</span>
                </a>
            @endif
        @endauth

        {{-- List Card --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($rules as $rule)
                <a href="{{ route('rules.show', $rule->id) }}"
                    class="bg-slate-900 px-6 py-5 rounded-2xl border border-slate-800 shadow-xl hover:border-indigo-500/20 hover:bg-slate-850/40 active:scale-[0.99] transition-all duration-200 flex flex-col justify-between">

                    <div>
                        <h3 class="font-bold text-base text-slate-200 mb-2 leading-snug">
                            {{ $rule->judul }}
                        </h3>

                        <p class="text-slate-400 text-xs leading-relaxed">
                            {{ Str::limit($rule->isi, 120) }}
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-850 flex items-center justify-between text-[10px] text-slate-500 font-semibold uppercase tracking-wider">
                        <span>Oleh: {{ $rule->author->name ?? 'Admin' }}</span>
                        <span class="text-indigo-400">Selengkapnya &rarr;</span>
                    </div>

                </a>
            @empty
                <div class="col-span-full py-8 text-center text-slate-500 italic">
                    Belum ada aturan kerja atau pemberitahuan yang diterbitkan.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>