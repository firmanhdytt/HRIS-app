<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white leading-tight tracking-tight">
            Timeline Payroll — {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- CARD INFO KARYAWAN -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center gap-4 shadow-xl">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-800 ring-2 ring-indigo-500/20">
                @if($employee->photo && Storage::disk('public')->exists($employee->photo))
                    <img src="{{ asset('storage/' . $employee->photo) }}" class="w-full h-full object-cover">
                @else
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=6366f1&color=fff&bold=true">
                @endif
            </div>

            <div>
                <h3 class="text-slate-200 text-lg font-bold">{{ $employee->name }}</h3>
                <p class="text-slate-400 text-xs mt-0.5">ID Karyawan: {{ $employee->employee_id }}</p>
                <p class="text-slate-400 text-xs">Jabatan: {{ $employee->jabatan ?? 'Karyawan' }}</p>
            </div>
        </div>

        <!-- TIMELINE -->
        <div class="relative border-l-2 border-indigo-500/20 pl-6 ml-3 space-y-8">

            @forelse($records as $r)
                <div class="relative">

                    <!-- BULLET -->
                    <div class="absolute w-4 h-4 bg-indigo-600 rounded-full -left-[31px] border-2 border-slate-950 shadow-md"></div>

                    <!-- CARD -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-lg hover:border-slate-700/80 active:scale-[0.99] transition-all duration-200">

                        <div class="flex justify-between items-center pb-2 border-b border-slate-850">
                            <h3 class="text-slate-200 font-bold text-base">
                                {{ \Carbon\Carbon::parse($r->periode_from)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($r->periode_to)->translatedFormat('d M Y') }}
                            </h3>

                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                Slip #{{ $loop->iteration }}
                            </span>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="text-slate-450 text-xs font-semibold uppercase tracking-wider">Total Gaji Dibayarkan</p>
                                <p class="text-lg font-bold text-emerald-450 mt-0.5">
                                    Rp {{ number_format($r->synced_total_gaji, 0, ',', '.') }}
                                </p>
                            </div>

                            @if($r->notes)
                                <div class="max-w-md bg-slate-950/40 p-2.5 rounded-lg border border-slate-850/60">
                                    <p class="text-slate-400 text-xs italic">
                                        <span class="font-semibold not-italic text-slate-500">Catatan:</span> {{ $r->notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-850/55">
                            <a href="{{ route('payroll.detail', $employee->employee_id) }}?from={{ $r->periode_from }}&to={{ $r->periode_to }}"
                                class="inline-flex items-center text-xs font-semibold text-indigo-400 hover:text-indigo-350 transition-colors">
                                <span>Lihat Detail Perhitungan</span>
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="relative py-8 text-center text-slate-500 italic ml-4">
                    Belum ada riwayat slip payroll untuk karyawan ini.
                </div>
            @endforelse

        </div>

        <div class="pt-4">
            <a href="{{ route('payroll.history') }}"
                class="inline-flex items-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-semibold text-xs active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

    </div>

</x-app-layout>