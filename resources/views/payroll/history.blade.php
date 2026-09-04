<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight tracking-tight">
            Riwayat Payroll
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- FILTER RANGE --}}
        <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl max-w-4xl mx-auto">
            <form method="GET" action="" class="flex flex-col sm:flex-row items-end gap-4">
                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Mulai Tanggal</label>
                    <input type="date" name="from" value="{{ $from }}"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                </div>
                <div class="w-full">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ $to }}"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 h-[46px] flex items-center justify-center gap-2">
                    <span>Cari</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- STATS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total Slip --}}
            <div class="bg-slate-900 px-5 py-4 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Total Slip Gaji</p>
                    <p class="text-2xl font-bold mt-1 text-slate-100">{{ number_format($records->total()) }}</p>
                </div>
                <div class="p-3 bg-indigo-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            {{-- Total Gaji --}}
            <div class="bg-slate-900 px-5 py-4 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Total Gaji Dibayarkan</p>
                    <p class="text-2xl font-bold mt-1 text-emerald-400">
                        Rp {{ number_format($summary, 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-3 bg-emerald-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Periode --}}
            <div class="bg-slate-900 px-5 py-4 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Periode</p>
                    <p class="text-sm font-bold text-slate-200 mt-2">
                        {{ \Carbon\Carbon::parse($from)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($to)->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="p-3 bg-amber-500/10 rounded-xl">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            {{-- Export --}}
            <div class="bg-slate-900 px-5 py-4 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-center">
                <a href="{{ route('payroll.history.excel', ['from' => $from, 'to' => $to]) }}" 
                   class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm rounded-xl active:scale-[0.98] transition-all duration-200 shadow-lg shadow-emerald-950/20 text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Download Excel</span>
                </a>
            </div>

        </div>

        {{-- GRAFIK PAYROLL --}}
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-xl">
            <h3 class="text-base font-semibold text-slate-200 mb-4">Grafik Total Payroll Bulanan</h3>
            <div class="relative w-full max-w-full">
                <div class="w-full h-64 sm:h-80">
                    <canvas id="chartPayroll"></canvas>
                </div>
            </div>
        </div>

        {{-- TABEL RIWAYAT PAYROLL --}}
        <div class="overflow-x-auto rounded-2xl shadow-xl bg-slate-900 border border-slate-800">
            <table class="min-w-full divide-y divide-slate-800 text-slate-300 text-sm whitespace-nowrap">
                <thead class="bg-slate-800/40 text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Periode</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Nama Karyawan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Total Gaji</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                    @forelse($records as $r)
                        <tr class="hover:bg-slate-800/30 transition-colors">

                            <td class="px-5 py-4 font-medium text-slate-200">
                                {{ \Carbon\Carbon::parse($r->periode_from)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($r->periode_to)->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-5 py-4 text-slate-300">{{ $r->employee->name }}</td>

                            <td class="px-5 py-4 font-semibold text-emerald-400">
                                Rp {{ number_format($r->synced_total_gaji, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('payroll.detail', $r->employee_id) }}?from={{ $r->periode_from }}&to={{ $r->periode_to }}"
                                        class="px-3.5 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold shadow-sm active:scale-[0.98] transition-all duration-200">
                                        Detail
                                    </a>

                                    {{-- TIMELINE --}}
                                    <a href="{{ route('payroll.history.timeline', $r->employee_id) }}" 
                                       class="px-3.5 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold shadow-sm active:scale-[0.98] transition-all duration-200">
                                        Timeline
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500 italic">
                                Tidak ada riwayat payroll pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">{{ $records->withQueryString()->links() }}</div>

    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartPayroll');

        const chartData = {
            labels: {!! json_encode($chart_labels) !!},
            datasets: [{
                label: 'Total Gaji',
                data: {!! json_encode($chart_values) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderColor: '#6366f1',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#0f172a',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#f8fafc',
                        bodyColor: '#38bdf8',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Total: Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#1e293b',
                            borderColor: '#1e293b'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: '#1e293b',
                            borderColor: '#1e293b'
                        },
                        ticks: {
                            color: '#94a3b8',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

    </script>

</x-app-layout>