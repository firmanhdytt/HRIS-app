<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white tracking-tight">
            ✏️ Edit Aturan Kerja
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-2xl space-y-6">

            <div>
                <h3 class="text-lg font-medium text-slate-200">Ubah Aturan Kerja</h3>
                <p class="text-xs text-slate-400 mt-1">Perbarui judul atau isi aturan kerja yang sudah diterbitkan sebelumnya.</p>
            </div>

            {{-- FORM UPDATE --}}
            <form id="updateForm" method="POST" action="{{ route('rules.update', $rule->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Judul Aturan</label>
                    <input type="text" name="judul" value="{{ $rule->judul }}"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Isi Aturan</label>
                    <textarea name="isi" rows="8" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-650 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200"
                        required>{{ $rule->isi }}</textarea>
                </div>
            </form>

            {{-- BUTTON ROW --}}
            <div class="flex justify-between items-center pt-5 border-t border-slate-850/80">

                {{-- KEMBALI --}}
                <a href="{{ route('rules.index') }}"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200">
                    Kembali
                </a>

                <div class="flex items-center gap-3">

                    {{-- BUTTON HAPUS --}}
                    @if(auth()->user()->role === 'admin')
                        <form action="{{ route('rules.destroy', $rule->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Yakin ingin menghapus aturan ini?')"
                                class="px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-455 font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200">
                                Hapus
                            </button>
                        </form>
                    @endif

                    {{-- BUTTON UPDATE --}}
                    <button type="submit" form="updateForm"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200">
                        Update
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
