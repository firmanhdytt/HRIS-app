<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white tracking-tight">
            📘 Detail Aturan Kerja
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-2xl space-y-6">

            <div class="space-y-2">
                <h1 class="text-2xl font-bold text-slate-100 tracking-tight leading-snug">
                    {{ $rule->judul }}
                </h1>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-semibold uppercase tracking-wider">
                    <span>Oleh: {{ $rule->author->name ?? 'Admin' }}</span>
                    <span>•</span>
                    <span>Diterbitkan: {{ $rule->created_at ? $rule->created_at->translatedFormat('d M Y') : 'Baru saja' }}</span>
                </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-850 p-5 sm:p-6 rounded-xl leading-relaxed text-slate-300 text-sm whitespace-pre-line">
                {!! nl2br(e($rule->isi)) !!}
            </div>

            <div class="flex justify-between items-center pt-5 border-t border-slate-850/80">
                <a href="{{ route('rules.index') }}"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200">
                    Kembali
                </a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('rules.edit', $rule->id) }}"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200">
                            Edit Aturan
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</x-app-layout>