<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white font-semibold text-xl leading-tight">
            Form Absensi
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4">

        <!-- WRAPPER CARD -->
        <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl flex flex-col md:flex-row gap-6">

            {{-- CARD – Absen Manual --}}
            <div class="w-full md:w-1/2 bg-slate-950 p-6 rounded-xl border border-slate-850 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-100 mb-2">Absen Manual</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Gunakan metode manual untuk mencatat absen masuk & keluar karyawan dengan cepat</p>
                </div>
                <a href="{{ route('absensi.manual.form') }}" class="mt-6 block w-full text-center py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md shadow-indigo-500/10 transition active:scale-[0.98]">
                    Form Absen Manual
                </a>
            </div>

            {{-- CARD – Scan QR Code --}}
            <div class="w-full md:w-1/2 bg-slate-950 p-6 rounded-xl border border-slate-850 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-100 mb-2">Scan QR Code</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Gunakan kamera perangkat untuk memindai kartu QR Code masuk / keluar karyawan</p>
                </div>
                <a href="{{ route('absensi.scan') }}" class="mt-6 block w-full text-center py-3 px-4 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl shadow-md shadow-purple-500/10 transition active:scale-[0.98]">
                    Scan QR Code
                </a>
            </div>

        </div>

    </div>
</x-app-layout>