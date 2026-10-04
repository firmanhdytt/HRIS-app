<x-app-layout>
    <x-slot name="header">
        <h1 class="text-slate-900 text-xl font-extrabold leading-tight">
            Form Absensi Karyawan
        </h1>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4">
        <!-- WRAPPER CARD -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row gap-6">

            {{-- CARD – Absen Manual --}}
            <div class="w-full md:w-1/2 bg-slate-50 p-6 rounded-xl border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200 flex items-center justify-center font-bold text-lg mb-3">
                        📝
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900 mb-1">Absen Manual</h2>
                    <p class="text-xs text-slate-500 leading-relaxed">Gunakan metode manual untuk mencatat absen masuk & keluar karyawan secara terstruktur.</p>
                </div>
                <a href="{{ route('absensi.manual.form') }}" class="mt-6 block w-full text-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Buka Form Absen Manual
                </a>
            </div>

            {{-- CARD – Scan QR Code --}}
            <div class="w-full md:w-1/2 bg-slate-50 p-6 rounded-xl border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center font-bold text-lg mb-3">
                        📷
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900 mb-1">Scan QR Code</h2>
                    <p class="text-xs text-slate-500 leading-relaxed">Gunakan kamera perangkat untuk memindai kartu QR Code masuk / keluar karyawan.</p>
                </div>
                <a href="{{ route('absensi.scan') }}" class="mt-6 block w-full text-center py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Buka Camera QR Scanner
                </a>
            </div>

        </div>
    </div>
</x-app-layout>