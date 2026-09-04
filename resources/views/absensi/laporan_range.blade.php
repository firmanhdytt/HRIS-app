<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight">
            Laporan Absensi — Rekap Per Karyawan
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 space-y-6">

        {{-- FILTER RANGE --}}
        <form action="{{ route('absensi.range') }}" method="GET"
            class="bg-slate-900 p-6 rounded-2xl border border-slate-800/80 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-6 max-w-4xl mx-auto shadow-lg">

            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="flex-1 min-w-[140px]">
                    <input type="date" name="start" value="{{ $start }}"
                        class="w-full rounded-xl px-4 py-2.5 bg-slate-950 text-slate-100 border border-slate-850 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>

                <div class="flex-1 min-w-[140px]">
                    <input type="date" name="end" value="{{ $end }}"
                        class="w-full rounded-xl px-4 py-2.5 bg-slate-950 text-slate-100 border border-slate-850 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-md active:scale-[0.98] transition-all">
                    Tampilkan 🔍
                </button>

                @if ($start && $end)
                    <a href="{{ route('absensi.export.range', [
                        'start_date' => $start,
                        'end_date' => $end
                    ]) }}" class="rounded-xl px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-center active:scale-[0.98] transition-all shadow-md shadow-emerald-500/10 whitespace-nowrap">
                        Export PDF 📤
                    </a>
                @endif
            </div>
        </form>

        {{-- INFO --}}
        @if ($start && $end)
            <div class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-5 py-3.5 rounded-xl font-medium text-xs max-w-4xl mx-auto">
                Menampilkan laporan dari <strong class="text-slate-100 font-semibold">{{ $start }}</strong> sampai <strong class="text-slate-100 font-semibold">{{ $end }}</strong>
            </div>
        @endif

        {{-- TABEL CARD --}}
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-950 text-slate-400">
                        <tr>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-4 py-3.5 text-center font-semibold uppercase tracking-wider">Total Hari</th>
                            <th class="px-4 py-3.5 text-center font-semibold uppercase tracking-wider">Total Jam</th>
                            <th class="px-4 py-3.5 text-center font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/60">
                        @forelse ($data as $r)
                            @php
                                $jam = intdiv($r->total_menit, 60);
                                $menit = $r->total_menit % 60;
                            @endphp

                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-4 font-medium text-slate-200">{{ $r->nama }}</td>

                                <td class="px-4 py-4 text-center text-slate-300 font-semibold">
                                    {{ $r->total_hari }} hari
                                </td>

                                <td class="px-4 py-4 text-center text-slate-300 font-semibold">
                                    {{ $jam }} Jam {{ $menit }} Menit
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('absensi.laporan.detail', $r->employee_id) }}?start={{ $start }}&end={{ $end }}"
                                        class="px-4 py-1.5 rounded-lg border border-slate-850 bg-slate-950 text-slate-300 hover:text-slate-100 hover:bg-slate-800 active:scale-[0.97] transition-all">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-500">
                                    Tidak ada data untuk rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="pt-4 flex">
            <a href="{{ route('absensi.laporan') }}"
                class="px-6 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 hover:text-slate-200 hover:bg-slate-900 font-semibold active:scale-[0.98] transition-all">
                Kembali
            </a>
        </div>

    </div>
</x-app-layout>