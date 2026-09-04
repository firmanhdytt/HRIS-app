@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\URL;

    // Format menit → "X jam Y menit"
    if (!function_exists('toJamMenit')) {
        function toJamMenit($min)
        {
            if (!$min || $min <= 0)
                return '-';

            $jam = floor($min / 60);
            $mnt = $min % 60;

            if ($jam > 0 && $mnt > 0)
                return "{$jam} jam {$mnt} menit";
            if ($jam > 0)
                return "{$jam} jam";
            return "{$mnt} menit";
        }
    }

    // Generate Temporary Signed URL (berlaku 30 hari)
    $publicPdfUrl = URL::temporarySignedRoute(
        'payroll.public.pdf',
        now()->addDays(30),
        [
            'employee_id' => $employee->employee_id,
            'from' => $from,
            'to' => $to,
        ]
    );

    // Format nomor WA karyawan (misal: 08123456789 -> 628123456789)
    $rawPhone = $employee->phone ?? '';
    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
    if (strpos($cleanPhone, '0') === 0) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }

    // Format mata uang untuk pesan
    $formatRupiah = function($val) {
        return 'Rp ' . number_format($val ?? 0, 0, ',', '.');
    };

    // Format rentang tanggal rapi
    $tglDari = Carbon::parse($from)->translatedFormat('d M Y');
    $tglSampai = Carbon::parse($to)->translatedFormat('d M Y');

    // Susun pesan WhatsApp
    $waMessage = "Halo *{$employee->name}*,\n\n"
               . "Berikut adalah rincian slip gaji Anda untuk periode *{$tglDari}* s/d *{$tglSampai}*:\n\n"
               . "• Gaji Pokok: " . $formatRupiah($calc['breakdown']['gaji_pokok_total']) . "\n"
               . "• Lembur: " . $formatRupiah($calc['breakdown']['gaji_lembur_total']) . "\n"
               . "• Tunjangan Kerajinan: " . $formatRupiah($calc['breakdown']['kerajinan_total']) . "\n"
               . "• Potongan Terlambat: -" . $formatRupiah($calc['breakdown']['potongan_total']) . "\n"
               . "• Potongan Pinjaman: -" . $formatRupiah($calc['breakdown']['pinjaman']) . "\n"
               . "• Bonus: " . $formatRupiah($calc['breakdown']['bonus']) . "\n\n"
               . "*Total Diterima (Gaji Bersih): " . $formatRupiah($calc['total_gaji']) . "*\n\n"
               . "Unduh detail slip PDF resmi Anda di sini (aktif 30 hari):\n"
               . "{$publicPdfUrl}\n\n"
               . "Terima kasih.";

    $waLink = "https://api.whatsapp.com/send?phone={$cleanPhone}&text=" . urlencode($waMessage);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-white text-xl font-semibold leading-tight tracking-tight">
            Detail Payroll — {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- TAMPILAN FILTER & AKSI PAYROLL --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Card 1: Form Filter Tanggal --}}
            <div class="lg:col-span-7 bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Filter Periode Tanggal</span>
                    </h4>
                    <form class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full">
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Mulai Tanggal</label>
                            <input type="date" name="from" value="{{ $from }}"
                                class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                        </div>

                        <div class="w-full">
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                            <input type="date" name="to" value="{{ $to }}"
                                class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200 text-sm" />
                        </div>

                        <button class="w-full sm:w-auto px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-950/20 active:scale-[0.98] transition-all duration-200 h-[38px] whitespace-nowrap flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card 2: Tombol Aksi --}}
            <div class="lg:col-span-5 bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span>Aksi Payroll Karyawan</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-3 w-full">

                        {{-- Tombol Kirim WA --}}
                        <a href="{{ $waLink }}" target="_blank"
                            class="px-4 py-2 bg-[#25D366] hover:bg-[#20ba5a] text-white font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200 shadow-md shadow-green-950/10 text-center flex items-center justify-center gap-1.5 h-[38px]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm11.953-2.176c1.812.001 3.585-.487 5.132-1.41l.368-.218c1.55.92 3.57.143 5.372-.619l.4-.236 1.758.46-1.79-1.745.247-.393c.966-1.536 1.474-3.327 1.471-5.176-.002-5.457-4.437-9.89-9.898-9.89-4.801 0-8.704 3.903-8.704 8.703 0 1.933.529 3.818 1.528 5.485l.233.39-1.066 3.905 4-.1.378-.225c1.47.876 3.14 1.34 4.854 1.34h-.002zM17.473 14.3c-.3-.149-1.772-.875-2.045-.974-.273-.1-.472-.149-.671.15-.198.3-.77.974-.943 1.173-.173.199-.347.224-.648.075-.3-.15-1.266-.467-2.41-1.487-.89-.793-1.49-1.772-1.664-2.072-.173-.3-.018-.462.13-.61.135-.133.3-.349.45-.523.15-.174.2-.299.3-.499.1-.2.05-.375-.025-.524-.075-.15-.672-1.62-.92-2.22-.242-.58-.487-.5-.671-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.772-.724 2.02-1.424.248-.699.248-1.299.173-1.424-.075-.124-.272-.198-.57-.347z"/>
                            </svg>
                            <span>Kirim WA</span>
                        </a>

                        {{-- Tombol Export PDF --}}
                        <a href="{{ route('payroll.detail.pdf', ['employee_id' => $employee->employee_id]) }}?from={{ $from }}&to={{ $to }}"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200 shadow-md shadow-emerald-950/10 text-center flex items-center justify-center gap-1.5 h-[38px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Export PDF</span>
                        </a>

                        {{-- Tombol Generate Payroll --}}
                        <form action="{{ route('payroll.generate') }}" method="POST" class="w-full flex">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">

                            <button type="submit"
                                class="w-full px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl active:scale-[0.98] transition-all duration-200 shadow-md shadow-rose-950/10 text-center flex items-center justify-center gap-1.5 h-[38px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Generate Payroll</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- RINGKASAN GAJI --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6">
            <h3 class="text-base font-semibold text-slate-200 mb-5 pb-3 border-b border-slate-850">Ringkasan Perhitungan Gaji</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3.5 text-sm">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Gaji Pokok Total</span>
                    <span class="text-slate-200 font-semibold">Rp {{ number_format($calc['breakdown']['gaji_pokok_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Gaji Lembur</span>
                    <span class="text-emerald-400 font-semibold">Rp {{ number_format($calc['breakdown']['gaji_lembur_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Tunjangan Kerajinan</span>
                    <span class="text-emerald-400 font-semibold">Rp {{ number_format($calc['breakdown']['kerajinan_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Potongan Terlambat</span>
                    <span class="text-rose-400 font-semibold">Rp -{{ number_format($calc['breakdown']['potongan_total'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Potongan Pinjaman</span>
                    <span class="text-rose-400 font-semibold">Rp -{{ number_format($calc['breakdown']['pinjaman'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center py-1.5 border-b border-slate-850/60">
                    <span class="text-slate-400 font-medium">Bonus</span>
                    <span class="text-indigo-400 font-semibold">Rp {{ number_format($calc['breakdown']['bonus'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-850 flex justify-between items-center">
                <span class="text-sm font-semibold text-slate-350 uppercase tracking-wider">Total Gaji Diterima</span>
                <span class="text-xl font-bold text-emerald-400">Rp {{ number_format($calc['total_gaji'], 0, ',', '.') }}</span>
            </div>
        </div>


        {{-- TABEL DETAIL ABSENSI --}}
        <div class="overflow-x-auto rounded-2xl shadow-xl bg-slate-900 border border-slate-800">
            <table class="min-w-full text-sm divide-y divide-slate-800 text-slate-300 whitespace-nowrap">

                <thead class="bg-slate-800/40 text-slate-400">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Hari</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jam Masuk</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Jam Keluar</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Lembur</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Terlambat</th>
                        <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Kerajinan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">

                    @foreach($calc['raw_absensi'] as $a)

                        @php
                            $jm = $a->jam_masuk ? Carbon::parse("{$a->tanggal} {$a->jam_masuk}") : null;
                            $jk = $a->jam_keluar ? Carbon::parse("{$a->tanggal} {$a->jam_keluar}") : null;

                            $jmFormatted = $jm ? $jm->format('H:i') : '<span class="text-slate-600">—</span>';
                            $jkFormatted = $jk ? $jk->format('H:i') : '<span class="text-slate-600">—</span>';

                            $base0800 = Carbon::parse("{$a->tanggal} 08:00:00");
                            $base0815 = Carbon::parse("{$a->tanggal} 08:05:00");
                            $base1700 = Carbon::parse("{$a->tanggal} 17:00:00");

                            $lembur = ($jk && $jk->gt($base1700)) ? $base1700->diffInMinutes($jk) : 0;
                            $telat = ($jm && $jm->gt($base0815)) ? $base0815->diffInMinutes($jm) : 0;
                            $rajin = ($jm && $jm->lt($base0800)) ? $jm->diffInMinutes($base0800) : 0;
                        @endphp

                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-5 py-3.5 font-medium text-slate-200">
                                {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">{{ $a->hari }}</td>
                            <td class="text-center px-5 py-3.5">{!! $jmFormatted !!}</td>
                            <td class="text-center px-5 py-3.5">{!! $jkFormatted !!}</td>
                            <td class="text-center px-5 py-3.5 font-medium text-emerald-400">
                                {{ $lembur > 0 ? toJamMenit($lembur) : '-' }}
                            </td>
                            <td class="text-center px-5 py-3.5 font-medium text-rose-400">
                                {{ $telat > 0 ? toJamMenit($telat) : '-' }}
                            </td>
                            <td class="text-center px-5 py-3.5 font-medium text-indigo-400">
                                {{ $rajin > 0 ? toJamMenit($rajin) : '-' }}
                            </td>
                        </tr>

                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>