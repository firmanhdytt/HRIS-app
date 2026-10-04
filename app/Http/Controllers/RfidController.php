<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Employee;
use App\Models\Absensi;
use Carbon\Carbon;

class RfidController extends Controller
{
    /**
     * =================================
     * REGISTER MODE
     * =================================
     */
    public function register(Request $request)
    {
        if (!$request->rfid_uid) {
            return response()->json(['error' => 'rfid_uid missing'], 400);
        }

        Cache::put('last_rfid_uid', $request->rfid_uid, 30);

        return response()->json([
            'success' => true,
            'status' => 'registered',
            'uid' => $request->rfid_uid
        ]);
    }

    public function last()
    {
        return [
            'uid' => Cache::get('last_rfid_uid')
        ];
    }

    /**
     * =================================
     * ABSENSI MODE
     * =================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required'
        ]);

        // Waktu (Mendukung Timestamp Offline jika dikirim dari ESP32)
        $now   = $request->filled('timestamp') 
                    ? Carbon::parse($request->timestamp, 'Asia/Jakarta') 
                    : Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $jam   = $now->format('H:i:s');
        $hari  = ucfirst($now->locale('id')->isoFormat('dddd'));

        // Karyawan
        $employee = Employee::where('rfid_uid', $request->rfid_uid)->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'status' => 'unknown',
                'message' => 'Kartu tidak terdaftar'
            ]);
        }

        // Absensi hari ini
        $absen = Absensi::where('id_karyawan', $employee->employee_id)
                        ->where('tanggal', $today)
                        ->first();

        // RANGE WAKTU
        $isMasukTime  = $now->between($now->copy()->setTime(6, 0),  $now->copy()->setTime(12, 0));
        $isPulangTime = $now->between($now->copy()->setTime(12, 0), $now->copy()->setTime(22, 0));

        $isLate = $now->greaterThan($now->copy()->setTime(8,5)) && $isMasukTime;

        /*
        |--------------------------------------------------------------------------
        | CASE 1 : BELUM ADA ABSEN HARI INI
        |--------------------------------------------------------------------------
        */
        if (!$absen) {

            // CASE : jam keluar tapi belum masuk
            if ($isPulangTime) {
                return response()->json([
                    'success' => false,
                    'status' => 'belum_absen_masuk',
                    'message' => 'Belum absen masuk'
                ]);
            }

            // CASE : jam masuk valid
            if ($isMasukTime) {

                Absensi::create([
                    'id_karyawan'   => $employee->employee_id,
                    'nama_karyawan' => $employee->name,
                    'tanggal'       => $today,
                    'hari'          => $hari,
                    'jam_masuk'     => $jam,
                    'source'        => 'rfid',
                ]);

                return response()->json([
                    'success' => true,
                    'status'  => $isLate ? 'masuk_terlambat' : 'masuk_normal',
                    'nama'    => $employee->name,
                    'jam'     => $jam
                ]);
            }

            // CASE : jam tidak valid
            return response()->json([
                'success' => false,
                'status'  => 'invalid_time',
                'message' => 'Tidak dalam waktu absensi'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CASE 2 : SUDAH ADA JAM MASUK, TAPI BELUM JAM KELUAR
        |--------------------------------------------------------------------------
        */
        if ($absen->jam_keluar === null) {

            // Double absen masuk
            if ($isMasukTime) {
                return response()->json([
                    'success' => false,
                    'status' => 'double_masuk',
                    'message' => 'Sudah absen masuk'
                ]);
            }

            // Jam keluar valid
            if ($isPulangTime) {
                $masuk = Carbon::parse($absen->jam_masuk, 'Asia/Jakarta');
                $keluar = $now;
                $totalMenit = $masuk->diffInMinutes($keluar);

                $absen->update([
                    'jam_keluar'  => $jam,
                    'total_menit' => $totalMenit,
                    'source'      => 'rfid'
                ]);

                return response()->json([
                    'success' => true,
                    'status'  => 'pulang',
                    'nama'    => $employee->name,
                    'jam'     => $jam,
                    'durasi'  => $totalMenit
                ]);
            }

            // Jam tidak valid
            return response()->json([
                'success' => false,
                'status'  => 'invalid_time',
                'message' => 'Tidak dalam waktu absensi'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CASE 3 : SUDAH ADA JAM MASUK DAN JAM KELUAR → DOUBLE PULANG
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => false,
            'status' => 'double_pulang',
            'message' => 'Sudah absen pulang'
        ]);
    }

    /**
     * =================================
     * COUNT HADIR HARI INI
     * =================================
     */
    public function countHadir()
{
    $today = Carbon::now('Asia/Jakarta')->toDateString();

    $masuk = Absensi::whereDate('tanggal', $today)
                    ->whereNotNull('jam_masuk')
                    ->count();

    $pulang = Absensi::whereDate('tanggal', $today)
                     ->whereNotNull('jam_keluar')
                     ->count();

    $total = Employee::count();

    return response()->json([
        'success' => true,
        'masuk'   => $masuk,
        'pulang'  => $pulang,
        'total'   => $total
    ]);
}

}
