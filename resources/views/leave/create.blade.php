<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-lg font-semibold">Ajukan Izin</h2>
    </x-slot>

    <div class="max-w-md mx-auto my-10 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl">

        <form method="POST" action="{{ route('leave.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Jenis Izin -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Izin</label>
                <select name="jenis_izin" class="w-full rounded-xl bg-slate-950 text-slate-200 border border-slate-850 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai"
                       class="w-full rounded-xl bg-slate-950 text-slate-100 border border-slate-850 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Tanggal Selesai (opsional)</label>
                <input type="date" name="tanggal_selesai"
                       class="w-full rounded-xl bg-slate-950 text-slate-100 border border-slate-850 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full rounded-xl bg-slate-950 text-slate-100 placeholder-slate-650 border border-slate-850 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200"
                    rows="3"></textarea>
            </div>

            <!-- Upload Bukti -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Upload Bukti (opsional)</label>
                <input type="file" name="bukti"
                       class="block w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 transition">
            </div>

            <!-- Submit -->
            <div class="pt-4 flex gap-3">
                <a href="{{ route('leave.my') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 hover:text-slate-200 hover:bg-slate-900 text-center font-semibold active:scale-[0.98] transition-all flex-1">
                    Batal
                </a>
                <button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-200 active:scale-[0.98] flex-[2]">
                    Ajukan Izin
                </button>
            </div>

        </form>

    </div>

</x-app-layout>
