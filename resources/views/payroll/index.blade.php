<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-slate-900 text-xl font-extrabold leading-tight">Data Gaji Karyawan</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('payroll.history') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-2xs transition">
                    📊 Riwayat Payroll
                </a>
                <a href="{{ route('payroll.settings') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold shadow-2xs transition">
                    ⚙️ Setting Gaji
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto space-y-6">

        {{-- FILTER --}}
        <form method="GET" action=""
            class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-end justify-between gap-4">

            {{-- Kolom Tanggal --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-1">
                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Mulai Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                </div>

                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                </div>
            </div>

            {{-- Kolom Pencarian --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto flex-1 items-end">
                <div class="w-full">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Cari Karyawan</label>
                    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama karyawan..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-800 placeholder-slate-400 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" />
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5 h-[38px]">
                    <span>Cari 🔍</span>
                </button>
            </div>

        </form>

        {{-- TABEL DATA GAJI --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-slate-700 text-xs whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Total Hari</th>
                            <th class="px-4 py-3 text-left">Gaji Pokok</th>
                            <th class="px-4 py-3 text-left">Lembur</th>
                            <th class="px-4 py-3 text-left">Pot. Telat</th>
                            <th class="px-4 py-3 text-left">Kerajinan</th>
                            <th class="px-4 py-3 text-left">Pinjaman</th>
                            <th class="px-4 py-3 text-left">Bonus</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($data as $row)
                            @php
                                $br = $row['breakdown'];

                                $rp = function ($v, $type = null) {
                                    if (!$v || $v <= 0)
                                        return '<span class="text-slate-400">-</span>';

                                    $formatted = number_format($v, 0, ',', '.');

                                    if (in_array($type, ['potongan', 'pinjaman'])) {
                                        return "<span class='text-rose-600 font-semibold'>Rp -{$formatted}</span>";
                                    }

                                    return "<span class='text-slate-800 font-semibold'>Rp {$formatted}</span>";
                                };
                            @endphp

                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ $row['employee']->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $row['employee']->employee_id }}</div>
                                </td>

                                <td class="px-4 py-3 font-bold text-indigo-600">{{ $br['total_hari_bekerja'] }} Hari</td>

                                <td class="px-4 py-3">{!! $rp($br['gaji_pokok_total']) !!}</td>

                                <td class="px-4 py-3">{!! $rp($br['gaji_lembur_total']) !!}</td>

                                <td class="px-4 py-3">{!! $rp($br['potongan_total'], 'potongan') !!}</td>

                                <td class="px-4 py-3">{!! $rp($br['kerajinan_total']) !!}</td>

                                <td class="px-4 py-3">{!! $rp($br['pinjaman'], 'pinjaman') !!}</td>

                                <td class="px-4 py-3 font-bold text-emerald-600">{!! $rp($br['bonus']) !!}</td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('payroll.detail', $row['employee']->employee_id) }}?from={{ $from }}&to={{ $to }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                                    <p class="text-2xl mb-1">💳</p>
                                    <p>Tidak ada data gaji dalam rentang tanggal ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION INFO + LINKS --}}
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-600">
                <div>
                    Menampilkan
                    <span class="font-bold text-slate-800">{{ $employees->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-bold text-slate-800">{{ $employees->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-bold text-slate-800">{{ $employees->total() }}</span>
                    hasil
                </div>

                <div>
                    {{ $employees->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>