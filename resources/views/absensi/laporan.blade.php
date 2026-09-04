<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight">
            Laporan Absensi
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 space-y-6">

        <!-- FILTER SECTION -->
        <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-6 max-w-4xl mx-auto shadow-lg">

            <!-- Left Column: Filter Form -->
            <form method="GET" action="{{ route('absensi.laporan') }}"
                class="flex flex-col sm:flex-row gap-3 flex-1">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="rounded-xl px-4 py-2.5 bg-slate-950 text-slate-100 border border-slate-850 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200 flex-1 min-w-[160px]" />

                <button type="submit" class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-md active:scale-[0.98] transition-all duration-200">
                    Cari 🔍
                </button>
            </form>

            <!-- Right Column: Navigation & Export Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="GET" action="{{ route('absensi.range') }}" class="flex-1">
                    <button class="w-full rounded-xl px-5 py-2.5 border border-slate-800 bg-slate-950 text-slate-300 hover:text-slate-100 hover:bg-slate-900 font-semibold active:scale-[0.98] transition-all">
                        Filter Range
                    </button>
                </form>

                <a href="{{ route('absensi.export.harian', ['tanggal' => request('tanggal')]) }}" class="rounded-xl px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-center active:scale-[0.98] transition-all shadow-md shadow-emerald-500/10">
                    📤 Export PDF
                </a>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs md:text-sm whitespace-nowrap">

                    <thead class="bg-slate-950 text-slate-400">
                        <tr>
                            <th class="text-left px-4 py-3.5 font-semibold uppercase tracking-wider">Nama</th>
                            <th class="text-center px-4 py-3.5 font-semibold uppercase tracking-wider">Tanggal</th>
                            <th class="text-center px-4 py-3.5 font-semibold uppercase tracking-wider">Jam Masuk</th>
                            <th class="text-center px-4 py-3.5 font-semibold uppercase tracking-wider">Jam Keluar</th>
                            <th class="text-center px-4 py-3.5 font-semibold uppercase tracking-wider">Total Jam</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/60">
                        @forelse ($absensi as $a)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3.5 font-medium text-slate-300">
                                    {{ $a->karyawan?->name ?? 'N/A' }}
                                </td>

                                <td class="text-center px-4 py-3.5 text-slate-400">
                                    {{ $a->tanggal }}
                                </td>

                                <td class="text-center px-4 py-3.5 text-slate-450">
                                    {{ $a->jam_masuk ?: '-' }}
                                </td>

                                <td class="text-center px-4 py-3.5 text-slate-450">
                                    {{ $a->jam_keluar ?: '-' }}
                                </td>

                                <td class="text-center px-4 py-3.5 text-slate-300 font-semibold">
                                    @if ($a->total_menit)
                                        {{ floor($a->total_menit / 60) }} Jam
                                        {{ $a->total_menit % 60 }} Menit
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    Tidak ada data absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $absensi->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>