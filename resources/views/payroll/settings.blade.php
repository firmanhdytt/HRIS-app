<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight tracking-tight">
            {{ __('Pengaturan Payroll Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- SEARCH BAR --}}
        <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800 shadow-xl max-w-xl">
            <form method="GET" class="flex gap-3">
                <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200" />
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-1.5 whitespace-nowrap">
                    <span>Cari</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- TABLE DATA --}}
        <div class="overflow-x-auto rounded-2xl shadow-xl bg-slate-900 border border-slate-800">
            <table class="min-w-full divide-y divide-slate-800 text-slate-300 text-sm whitespace-nowrap">
                <thead class="bg-slate-800/40 text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Gaji Lembur</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Terlambat</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Kerajinan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Pinjaman</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Bonus</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                    @forelse($employees as $emp)
                        @php
                            $ps = $emp->payrollSetting ?? null;
                        @endphp

                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-5 py-4 font-medium text-slate-200">
                                <div>{{ $emp->name }}</div>
                                <div class="text-xs text-slate-500">{{ $emp->employee_id }}</div>
                            </td>

                            <td class="px-5 py-4 font-medium text-slate-100">
                                Rp {{ number_format($ps->gaji_pokok ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-slate-300">
                                Rp {{ number_format($ps->gaji_lembur ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-rose-400">
                                Rp {{ number_format($ps->potongan_terlambat ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-emerald-400">
                                @if($ps && $ps->gaji_pokok > 0)
                                    Rp {{ number_format($ps->gaji_pokok / 8, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-amber-500">
                                Rp {{ number_format($ps->pinjaman ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-indigo-400 font-semibold">
                                Rp {{ number_format($ps->bonus ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('payroll.settings.edit', $emp->employee_id) }}" 
                                   class="inline-flex items-center px-4 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold shadow-sm active:scale-[0.98] transition-all duration-200">
                                    Edit Settings
                                </a>

                                <form action="{{ route('payroll.settings.update', $emp) }}" method="POST"
                                    id="form-{{ $emp->employee_id }}" class="hidden">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-slate-500 italic">
                                Karyawan tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>