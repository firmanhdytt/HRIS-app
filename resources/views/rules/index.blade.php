<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">📢 Aturan Kerja & Pemberitahuan</h1>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('rules.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition gap-1.5">
                        <span>+ Tambah Aturan Baru</span>
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        {{-- List Card --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($rules as $rule)
                <a href="{{ route('rules.show', $rule->id) }}"
                    class="bg-white px-6 py-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-indigo-300 hover:shadow-md transition-all duration-200 flex flex-col justify-between">

                    <div>
                        <h2 class="font-extrabold text-base text-slate-900 mb-2 leading-snug">
                            {{ $rule->judul }}
                        </h2>

                        <p class="text-slate-600 text-xs leading-relaxed">
                            {{ Str::limit($rule->isi, 120) }}
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
                        <span>Oleh: {{ $rule->author->name ?? 'Admin' }}</span>
                        <span class="text-indigo-600 font-bold">Selengkapnya →</span>
                    </div>

                </a>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400 bg-white border border-slate-200 rounded-2xl">
                    <p class="text-3xl mb-1">📢</p>
                    <p class="text-xs">Belum ada aturan kerja atau pemberitahuan yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>