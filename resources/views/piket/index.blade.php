<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white tracking-tight">📅 Jadwal Piket Mingguan</h2>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('piket.manage') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Jadwal</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- GRID HARI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach ($days as $day)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-slate-700/60 transition-colors flex flex-col justify-between">

                    <div>
                        {{-- Header Hari --}}
                        <div class="flex items-center justify-between pb-3 border-b border-slate-850 mb-4">
                            <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider">{{ $day }}</h3>
                            <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full shadow-lg shadow-indigo-500/50"></span>
                        </div>

                        {{-- List Nama --}}
                        <div class="space-y-2.5 max-h-56 overflow-y-auto pr-1">

                            @if(isset($jadwal[$day]) && count($jadwal[$day]) > 0)
                                @foreach ($jadwal[$day] as $row)
                                    <div class="flex items-center gap-2.5 bg-slate-950/60 border border-slate-850/80 px-3.5 py-2.5 rounded-xl text-sm text-slate-300 hover:text-slate-100 hover:border-indigo-500/20 transition-all duration-150">
                                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                        <span class="font-medium">{{ $row->employee_name }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-slate-600 text-xs italic py-3 text-center bg-slate-950/20 rounded-xl border border-slate-850/40">
                                    Tidak ada jadwal piket
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>