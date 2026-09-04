<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight tracking-tight">Data Gaji Karyawan</h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- FILTER --}}
        <form method="GET" action=""
            class="bg-slate-900 p-5 rounded-2xl border border-slate-800 mb-6 flex flex-col md:flex-row items-end justify-between gap-5 max-w-4xl mx-auto shadow-xl">

            {{-- Kolom Tanggal --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1">
                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Mulai Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200" />
                </div>

                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200" />
                </div>
            </div>

            {{-- Kolom Pencarian --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1 items-end">
                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Cari Karyawan</label>
                    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200" />
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 h-[46px] flex items-center justify-center gap-2">
                    <span>Cari</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

        </form>

        {{-- TABEL DATA GAJI --}}
        <div class="overflow-x-auto rounded-2xl shadow-xl bg-slate-900 border border-slate-800">
            <table class="min-w-full divide-y divide-slate-800 text-slate-300 text-sm whitespace-nowrap">
                <thead class="bg-slate-800/40 text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Total Hari</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Lembur</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Pot. Telat</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Kerajinan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Pinjaman</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Bonus</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 bg-slate-950/20">
                    @forelse($data as $row)
                        @php
                            $br = $row['breakdown'];

                            // formatter angka
                            $rp = function ($v, $type = null) {
                                if (!$v || $v <= 0)
                                    return '<span class="text-slate-600">-</span>';

                                $formatted = number_format($v, 0, ',', '.');

                                if (in_array($type, ['potongan', 'pinjaman'])) {
                                    return "<span class='text-rose-400 font-medium'>Rp -{$formatted}</span>";
                                }

                                return "<span class='text-slate-200 font-medium'>Rp {$formatted}</span>";
                            };
                        @endphp

                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-200">{{ $row['employee']->name }}</div>
                                <div class="text-xs text-slate-500">{{ $row['employee']->employee_id }}</div>
                            </td>

                            <td class="px-5 py-4 font-semibold text-indigo-400">{{ $br['total_hari_bekerja'] }} Hari</td>

                            <td class="px-5 py-4">{!! $rp($br['gaji_pokok_total']) !!}</td>

                            <td class="px-5 py-4">{!! $rp($br['gaji_lembur_total']) !!}</td>

                            <td class="px-5 py-4">{!! $rp($br['potongan_total'], 'potongan') !!}</td>

                            <td class="px-5 py-4">{!! $rp($br['kerajinan_total']) !!}</td>

                            <td class="px-5 py-4">{!! $rp($br['pinjaman'], 'pinjaman') !!}</td>

                            <td class="px-5 py-4 font-semibold text-emerald-400">{!! $rp($br['bonus']) !!}</td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('payroll.detail', $row['employee']->employee_id) }}?from={{ $from }}&to={{ $to }}"
                                    class="inline-flex items-center px-4 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold shadow-sm active:scale-[0.98] transition-all duration-200">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-slate-500 italic">
                                Tidak ada data gaji dalam rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION INFO + LINKS --}}
        <div class="mt-5 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-slate-400 bg-slate-900 border border-slate-800 p-4 rounded-xl shadow-md">
            <div>
                Menampilkan
                <span class="font-semibold text-slate-200">{{ $employees->firstItem() ?? 0 }}</span>
                -
                <span class="font-semibold text-slate-200">{{ $employees->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold text-slate-200">{{ $employees->total() }}</span>
                hasil
            </div>

            <div>
                {{ $employees->withQueryString()->links() }}
            </div>
        </div>

    </div>

</x-app-layout>