<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-extrabold text-slate-900 leading-tight">⚙️ Edit Jadwal Piket Mingguan</h1>
            <a href="{{ route('piket.index') }}" class="text-slate-500 hover:text-slate-800 text-xs font-semibold">
                ← Kembali ke Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('piket.save') }}" method="POST" class="space-y-6"
            data-confirm="Apakah Anda yakin ingin menyimpan perubahan jadwal piket mingguan ini?"
            data-confirm-title="Konfirmasi Perubahan Piket" data-confirm-btn="Ya, Simpan Piket">
            @csrf

            <!-- GRID HARI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($days as $day)
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">

                        <div>
                            {{-- HEADER HARI --}}
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ $day }}</h2>
                                <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full"></span>
                            </div>

                            {{-- LIST NAMA --}}
                            <div class="space-y-1.5 max-h-60 overflow-y-auto pr-1">
                                @foreach ($employees as $emp)
                                    <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl text-xs text-slate-800 hover:bg-slate-100 cursor-pointer transition">
                                        <input type="checkbox" name="{{ $day }}[]" value="{{ $emp->employee_id }}"
                                            class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" 
                                            @if(isset($jadwal[$day]) && $jadwal[$day]->contains('employee_id', $emp->employee_id)) checked @endif>
                                        <span class="font-medium select-none">{{ $emp->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 flex justify-between items-center border-t border-slate-200">
                <a href="{{ route('piket.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">
                    ← Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition gap-1.5">
                    <span>Simpan Perubahan Jadwal</span>
                </button>
            </div>

        </form>
    </div>
</x-app-layout>