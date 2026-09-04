<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">Form Absensi Manual</h2>
    </x-slot>

    <div class="max-w-md mx-auto my-10 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl">

        {{-- FORM --}}
        <form action="{{ route('absensi.manual.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- INPUT IDENTIFIER --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Masukkan ID / Email / Kode Karyawan</label>
                <input type="text" name="identifier"
                    class="w-full rounded-xl bg-slate-950 text-slate-100 border border-slate-850 placeholder-slate-600 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                    placeholder="Contoh: EMP001 atau email" required>
            </div>

            {{-- JENIS ABSEN --}}
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Absen</label>
                <select name="tipe"
                    class="w-full rounded-xl bg-slate-950 text-slate-200 border border-slate-850 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                    required>
                    <option value="">-- Pilih Jenis Absen --</option>
                    <option value="masuk">Absen Masuk</option>
                    <option value="keluar">Absen Keluar</option>
                </select>
            </div>

            {{-- BUTTON SIMPAN --}}
            <div class="pt-4 flex gap-3">
                <a href="{{ route('absensi.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 hover:text-slate-200 hover:bg-slate-900 text-center font-semibold active:scale-[0.98] transition-all flex-1">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all flex-[2]">
                    Simpan Absensi
                </button>
            </div>
        </form>

    </div>
</x-app-layout>