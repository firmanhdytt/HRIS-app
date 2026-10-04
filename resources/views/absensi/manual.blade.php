<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Form Absensi Manual</h1>
            <a href="{{ route('absensi.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-semibold">
                ← Kembali ke Option Absensi
            </a>
        </div>
    </x-slot>

    <div class="max-w-md mx-auto my-8 bg-white border border-slate-200/80 p-8 rounded-2xl shadow-xs">
        <form action="{{ route('absensi.manual.store') }}" method="POST" class="space-y-5"
            data-confirm="Apakah Anda yakin ingin menyimpan data absensi manual ini?"
            data-confirm-title="Konfirmasi Absensi Manual" data-confirm-btn="Ya, Simpan Absensi">
            @csrf

            <!-- INPUT IDENTIFIER -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">ID Karyawan / Email / Kode</label>
                <input type="text" name="identifier"
                    class="w-full rounded-xl bg-slate-50 text-slate-800 border border-slate-300 placeholder-slate-400 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition"
                    placeholder="Contoh: EMP001 atau email@karyawan.com" required>
            </div>

            <!-- JENIS ABSEN -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jenis Absensi</label>
                <select name="tipe"
                    class="w-full rounded-xl bg-slate-50 text-slate-800 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition"
                    required>
                    <option value="">-- Pilih Jenis Absen --</option>
                    <option value="masuk">Absen Masuk</option>
                    <option value="keluar">Absen Keluar</option>
                </select>
            </div>

            <!-- BUTTON SIMPAN -->
            <div class="pt-4 flex gap-3">
                <a href="{{ route('absensi.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 text-center font-bold text-xs transition flex-1">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex-[2]">
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>