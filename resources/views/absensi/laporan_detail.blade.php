<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight">
            Detail Laporan — {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 space-y-6">

        {{-- Info Range --}}
        <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl text-slate-100 flex items-center justify-between shadow-lg">
            <span class="text-sm font-semibold text-slate-300">
                Periode: <span class="text-slate-100">{{ $start->format('d-m-Y') }} s/d {{ $end->format('d-m-Y') }}</span>
            </span>
            <div>
                <a href="{{ route('absensi.export.detail', [
                    'employee_id' => $employee->employee_id
                ]) }}?start={{ $start }}&end={{ $end }}" class="rounded-xl px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold shadow-md active:scale-[0.98] transition-all duration-200 text-xs">
                    Export PDF 📤
                </a>
            </div>
        </div>

        {{-- Tabel Detail --}}
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg overflow-hidden">
            <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950/20">
                <table class="min-w-full divide-y divide-slate-800 text-slate-200 text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-950 text-slate-400">
                        <tr>
                            <th class="px-4 py-3.5 text-center font-semibold uppercase tracking-wider">No</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Hari</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-4 py-3.5 text-left font-semibold uppercase tracking-wider">Total Jam</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-800/60">
                        @php $no = 1; @endphp
                        @foreach ($detail as $row)
                            @if ($row['status'] === 'Bekerja')
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-center text-slate-450">{{ $no++ }}</td>

                                    <td class="px-4 py-3 text-slate-300 font-medium">
                                        {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-400">
                                        {{ $row['hari'] }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-400">
                                        {{ $row['jam_masuk'] !== '-' ? \Carbon\Carbon::parse($row['jam_masuk'])->format('H:i:s') : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-400">
                                        {{ $row['jam_keluar'] !== '-' ? \Carbon\Carbon::parse($row['jam_keluar'])->format('H:i:s') : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-200 font-semibold">
                                        @php
                                            $mnt = $row['total'];
                                            $jam = floor($mnt / 60);
                                            $menit = $mnt % 60;
                                        @endphp

                                        @if ($mnt > 0)
                                            {{ $jam }} Jam {{ $menit }} Menit
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="p-6 bg-slate-900 text-slate-300 rounded-2xl border border-slate-800/80 shadow-lg space-y-1.5 max-w-md">
            <h3 class="text-sm font-bold text-slate-100 uppercase tracking-wider mb-2">Ringkasan</h3>
            <p class="text-xs">Total Hari Bekerja: <span class="font-bold text-slate-100 text-sm ml-1">{{ $hariKerja }} hari</span></p>
            <p class="text-xs">Total Jam Kerja: <span class="font-bold text-slate-100 text-sm ml-1">{{ $totalJam }}</span></p>
        </div>

        {{-- Tombol --}}
        <div class="pt-4 flex">
            <a href="{{ url()->previous() }}"
                class="px-6 py-2.5 rounded-xl border border-slate-800 bg-slate-950 text-slate-400 hover:text-slate-200 hover:bg-slate-900 font-semibold active:scale-[0.98] transition-all">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>