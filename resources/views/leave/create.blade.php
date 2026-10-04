<x-app-layout>
    <x-slot name="header">
        <h2 class="text-slate-900 text-xl font-semibold">Ajukan Izin</h2>
    </x-slot>

    <div class="max-w-md mx-auto my-10 bg-white border border-slate-200 p-8 rounded-2xl shadow-sm">

        <form method="POST" action="{{ route('leave.store') }}" enctype="multipart/form-data" class="space-y-5" data-confirm="Apakah Anda yakin ingin mengajukan izin ini?">
            @csrf

            <!-- Jenis Izin -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Jenis Izin</label>
                <select name="jenis_izin" class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai"
                       class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Tanggal Selesai (opsional)</label>
                <input type="date" name="tanggal_selesai"
                       class="w-full rounded-xl bg-white text-slate-900 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full rounded-xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                    rows="3"></textarea>
            </div>

            <!-- Upload Bukti -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Upload Bukti (opsional)</label>
                <input type="file" name="bukti"
                       class="block w-full text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">
            </div>

            <!-- Submit -->
            <div class="pt-4 flex gap-3">
                <a href="{{ route('leave.my') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 text-center font-semibold active:scale-[0.98] transition-all flex-1">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-200 active:scale-[0.98] flex-[2]">
                    Ajukan Izin
                </button>
            </div>

        </form>

    </div>

</x-app-layout>
