<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white tracking-tight">
            ➕ Tambah Aturan Kerja
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-2xl space-y-6">

            <div>
                <h3 class="text-lg font-medium text-slate-200">Buat Pengumuman Baru</h3>
                <p class="text-xs text-slate-400 mt-1">Gunakan form di bawah ini untuk menambahkan aturan kerja atau pemberitahuan baru bagi seluruh karyawan.</p>
            </div>

            <form method="POST" action="{{ route('rules.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Judul Aturan</label>
                    <input type="text" name="judul"
                        placeholder="Contoh: Ketentuan Jam Kerja Selama Ramadhan"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Isi Aturan</label>
                    <textarea name="isi" rows="8"
                        placeholder="Tuliskan isi aturan secara detail..."
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200"
                        required></textarea>
                </div>

                <div class="flex justify-between items-center pt-5 border-t border-slate-850/80">
                    <a href="{{ route('rules.index') }}"
                        class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200">
                        Kembali
                    </a>
                    
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200">
                        Simpan Aturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
