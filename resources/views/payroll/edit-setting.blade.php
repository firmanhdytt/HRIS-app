<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight tracking-tight">
            Edit Pengaturan Gaji — {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-emerald-500/10 text-emerald-400 px-4 py-3.5 rounded-xl mb-6 border border-emerald-500/20 font-medium text-sm flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-2xl">
            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-slate-200">Form Parameter Gaji</h3>
                <p class="text-xs text-slate-400 mt-1">Ubah besaran komponen gaji pokok, tunjangan lembur, potongan telat, pinjaman, dan bonus karyawan.</p>
            </div>

            <form action="{{ route('payroll.settings.update', $employee->employee_id) }}" method="POST" class="space-y-6">
                @csrf

                {{-- HIDDEN: Mode potongan otomatis PER_KALI --}}
                <input type="hidden" name="potongan_mode" value="per_kali">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Gaji Pokok --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok"
                            value="{{ old('gaji_pokok', $employee->payrollSetting->gaji_pokok ?? '') }}"
                            placeholder="Contoh: 4000000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('gaji_pokok')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gaji Lembur --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Gaji Lembur / Jam (Rp)</label>
                        <input type="number" name="gaji_lembur"
                            value="{{ old('gaji_lembur', $employee->payrollSetting->gaji_lembur ?? '') }}"
                            placeholder="Contoh: 25000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('gaji_lembur')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Potongan Terlambat --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Potongan Terlambat / Kali (Rp)</label>
                        <input type="number" name="potongan_terlambat"
                            value="{{ old('potongan_terlambat', $employee->payrollSetting->potongan_terlambat ?? '') }}"
                            placeholder="Contoh: 10000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('potongan_terlambat')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kerajinan --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Tunjangan Kerajinan (Rp)</label>
                        <input type="number" name="kerajinan"
                            value="{{ old('kerajinan', $employee->payrollSetting->kerajinan ?? '') }}"
                            placeholder="Contoh: 200000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('kerajinan')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pinjaman --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Potongan Pinjaman (Rp)</label>
                        <input type="number" name="pinjaman"
                            value="{{ old('pinjaman', $employee->payrollSetting->pinjaman ?? '') }}"
                            placeholder="Contoh: 500000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('pinjaman')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bonus --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Bonus Bulanan (Rp)</label>
                        <input type="number" name="bonus"
                            value="{{ old('bonus', $employee->payrollSetting->bonus ?? '') }}"
                            placeholder="Contoh: 100000"
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                        @error('bonus')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-between items-center pt-5 border-t border-slate-800/80">
                    <a href="{{ route('payroll.settings') }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold active:scale-[0.98] transition-all duration-200">
                       Kembali
                    </a>

                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl px-6 py-2.5 font-semibold text-sm shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</x-app-layout>
