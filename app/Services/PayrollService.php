<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\PayrollSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public static function calculateForEmployee(Employee $employee, string $from, string $to)
    {
        // Payroll Setting
        $setting = PayrollSetting::firstOrNew(
            ['employee_id' => $employee->employee_id],
            [
                'gaji_pokok' => 0,
                'gaji_lembur' => 0,
                'potongan_terlambat' => 0,
                'potongan_mode' => 'per_minute',
                'kerajinan' => 0,
                'pinjaman' => 0,
                'bonus' => 0,
            ]
        );

        $absensi = DB::table('absensi')
            ->where('id_karyawan', $employee->employee_id)
            ->whereBetween('tanggal', [$from, $to])
            ->orderBy('tanggal')
            ->get();

        // Rekap
        $totalGajiPokok = 0;
        $totalHari = 0;

        $menitLembur = 0;
        $menitKerajinan = 0;
        $lateMinutes = 0;
        $hariTelat = 0;

        $bonusEligible = true;

        foreach ($absensi as $a) {

            $isLastDate = $a->tanggal === $to;

            // Jika tidak hadir → skip total
            if (! $a->jam_masuk) {
                $bonusEligible = false;

                continue;
            }

            $totalHari++;

            // =============================
            // PARSING WAKTU
            // =============================
            $jam_masuk = Carbon::parse("{$a->tanggal} {$a->jam_masuk}");

            // AUTO JAM KELUAR KHUSUS TANGGAL TERAKHIR (17:00)
            if (! $a->jam_keluar && $isLastDate) {
    $jam_keluar = Carbon::parse("{$a->tanggal} 17:00:00");
    $autoClose = true;
} else {
    $jam_keluar = $a->jam_keluar
        ? Carbon::parse("{$a->tanggal} {$a->jam_keluar}")
        : null;
    $autoClose = false;
}


            // Jika bukan tanggal terakhir & jam keluar kosong → bonus gugur & skip
            if (! $jam_keluar && ! $isLastDate) {
                $bonusEligible = false;

                continue;
            }

            // =============================
            // PATOKAN JAM
            // =============================
            $jam0800 = Carbon::parse("{$a->tanggal} 08:00:00");
            $jam0805 = Carbon::parse("{$a->tanggal} 08:05:00");
            $jam0900 = Carbon::parse("{$a->tanggal} 09:00:00");
            $jam1600 = Carbon::parse("{$a->tanggal} 16:00:00");
            $jam1700 = Carbon::parse("{$a->tanggal} 17:00:00");
            $jam1900 = Carbon::parse("{$a->tanggal} 19:00:00");

            // =============================
            // BONUS RULE
            // =============================
            if (! $autoClose && $jam_keluar->lt($jam1600)) {
                $bonusEligible = false;
            }

            // =============================
            // KERAJINAN
            // =============================
            if ($jam_masuk->lt($jam0800)) {
                $menitKerajinan += $jam_masuk->diffInMinutes($jam0800);
            }

            // =============================
            // TERLAMBAT
            // =============================
            if ($jam_masuk->gt($jam0805)) {
                $lateMinutes += $jam0805->diffInMinutes($jam_masuk);
                $hariTelat++;
            }

            // =============================
            // HITUNG GAJI POKOK
            // =============================
            $gajiHarian = $setting->gaji_pokok;
            $gajiPerJam = $setting->gaji_pokok / 8;

            // Tentukan start kerja
            if ($jam_masuk->lt($jam0800)) {
                $start = $jam0800;
            } elseif ($jam_masuk->between($jam0800, $jam0900)) {
                $start = $jam0800;
            } else {
                $start = $jam_masuk;
            }

            // Tentukan end kerja
            $end = $jam_keluar->gt($jam1700) ? $jam1700 : $jam_keluar;

            // Durasi kerja
            $jamKerja = max(0, $start->diffInMinutes($end)) / 60;

            if ($jamKerja >= 8) {
                $dailyPay = $gajiHarian;
            } else {
                $dailyPay = $jamKerja * $gajiPerJam;
            }

            $totalGajiPokok += $dailyPay;

            // =============================
            // LEMBUR (TIDAK UNTUK AUTO CLOSE)
            // =============================
            if (! $autoClose && $jam_keluar->gt($jam1700)) {
    $menitLembur += $jam1700->diffInMinutes($jam_keluar);
}


        }

        // =============================
        // CEK KELENGKAPAN KEHADIRAN RANGE (SENIN–SABTU)
        // =============================

        // Hitung total hari kerja Senin–Sabtu dalam range
        $totalHariKerjaRange = 0;

        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);

        while ($startDate->lte($endDate)) {
            // 0 = Minggu, 6 = Sabtu
            if ($startDate->dayOfWeek !== Carbon::SUNDAY) {
                $totalHariKerjaRange++;
            }
            $startDate->addDay();
        }

        // Hitung jumlah hari hadir valid
        // hadir valid = punya jam_masuk DAN jam_keluar
        // kecuali last date boleh auto-close
        $hariHadirValid = 0;

        foreach ($absensi as $a) {

            $isLastDate = $a->tanggal === $to;

            if (! $a->jam_masuk) {
                continue;
            }

            // last date boleh jam keluar kosong
            if ($isLastDate) {
                $hariHadirValid++;

                continue;
            }

            // hari lain wajib punya jam_keluar
            if ($a->jam_keluar) {
                $hariHadirValid++;
            }
        }

        // Jika hadir valid tidak sama dengan total hari kerja → bonus gugur
        if ($hariHadirValid < $totalHariKerjaRange) {
            $bonusEligible = false;
        }

        // =============================
        // REKAP AKHIR
        // =============================
        $jamLembur = $menitLembur / 60;
        $jamKerajinan = $menitKerajinan / 60;

        $gaji_lembur_total = $setting->gaji_lembur * $jamLembur;
        $gaji_kerajinan = ($setting->gaji_pokok / 8) * $jamKerajinan;

        $potongan_total =
            $setting->potongan_mode === 'per_minute'
                ? $lateMinutes * $setting->potongan_terlambat
                : $hariTelat * $setting->potongan_terlambat;

        $bonus = $bonusEligible ? $setting->bonus : 0;

        $totalAkhir =
            $totalGajiPokok +
            $gaji_lembur_total +
            $gaji_kerajinan -
            $potongan_total +
            $bonus -
            $setting->pinjaman;

        return [
            'total_gaji' => max(0, intval(round($totalAkhir))),
            'breakdown' => [
                'total_hari_bekerja' => $totalHari,
                'gaji_pokok_total' => $totalGajiPokok,

                'jam_lembur' => round($jamLembur, 2),
                'menit_lembur' => $menitLembur,
                'gaji_lembur_total' => $gaji_lembur_total,

                'jam_kerajinan' => round($jamKerajinan, 2),
                'menit_kerajinan' => $menitKerajinan,
                'kerajinan_total' => $gaji_kerajinan,

                'total_keterlambatan_menit' => $lateMinutes,
                'jumlah_hari_terlambat' => $hariTelat,
                'potongan_total' => $potongan_total,

                'pinjaman' => $setting->pinjaman,
                'bonus' => $bonus,
            ],
            'raw_absensi' => $absensi,
        ];
    }
}
