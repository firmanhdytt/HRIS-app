<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white tracking-tight">
            ⚙️ Edit Jadwal Piket Mingguan
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <form action="{{ route('piket.save') }}" method="POST" class="space-y-6">
            @csrf

            <!-- GRID HARI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($days as $day)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl flex flex-col justify-between">

                        <div>
                            {{-- HEADER HARI --}}
                            <div class="flex items-center justify-between pb-3 border-b border-slate-850 mb-4">
                                <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider">{{ $day }}</h3>
                                <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full shadow-lg shadow-indigo-500/50"></span>
                            </div>

                            {{-- LIST NAMA --}}
                            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                @foreach ($employees as $emp)
                                    <label class="flex items-center gap-3 bg-slate-950/60 border border-slate-850 px-3.5 py-2.5 rounded-xl text-sm text-slate-300 hover:text-slate-100 hover:bg-slate-800/40 hover:border-slate-700/60 cursor-pointer transition-all duration-150">
                                        <input type="checkbox" name="{{ $day }}[]" value="{{ $emp->employee_id }}"
                                            class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-indigo-600 focus:ring-indigo-500/30 focus:ring-offset-slate-900 focus:ring-2" 
                                            @if(isset($jadwal[$day]) && $jadwal[$day]->contains('employee_id', $emp->employee_id)) checked @endif>
                                        <span class="font-medium select-none text-slate-350">{{ $emp->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- ACTION BUTTONS -->
            <div class="pt-4 flex justify-between items-center border-t border-slate-850/80">
                <a href="{{ route('piket.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-semibold text-xs active:scale-[0.98] transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>

                <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>

    </div>
</x-app-layout>