<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Laporan Absensi Karyawan</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('absensi.range') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-2xs transition">
                    Filter Range
                </a>
                <a href="{{ route('absensi.export.harian', ['tanggal' => request('tanggal')]) }}" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                    📤 Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 space-y-6">

        <!-- FILTER SECTION -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            <form method="GET" action="{{ route('absensi.laporan') }}" class="flex flex-col sm:flex-row gap-3 flex-1">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="rounded-xl px-4 py-2 bg-slate-50 text-slate-800 border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-xs transition flex-1 min-w-[160px]" />

                <button type="submit" class="rounded-xl px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition">
                    Cari Tanggal 🔍
                </button>
            </form>
        </div>

        <!-- TABLE CARD -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-slate-700 text-xs whitespace-nowrap">

                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-4 py-3">Nama Karyawan</th>
                            <th class="text-center px-4 py-3">Tanggal</th>
                            <th class="text-center px-4 py-3">Jam Masuk</th>
                            <th class="text-center px-4 py-3">Jam Keluar</th>
                            <th class="text-center px-4 py-3">Total Jam Kerja</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($absensi as $a)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $a->karyawan?->name ?? 'N/A' }}
                                </td>

                                <td class="text-center px-4 py-3 text-slate-600">
                                    {{ $a->tanggal }}
                                </td>

                                <td class="text-center px-4 py-3 text-slate-600 font-medium">
                                    {{ $a->jam_masuk ?: '-' }}
                                </td>

                                <td class="text-center px-4 py-3 text-slate-600 font-medium">
                                    {{ $a->jam_keluar ?: '-' }}
                                </td>

                                <td class="text-center px-4 py-3 text-slate-900 font-bold">
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
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    <p class="text-2xl mb-1">📄</p>
                                    <p>Tidak ada data absensi untuk tanggal ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $absensi->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>