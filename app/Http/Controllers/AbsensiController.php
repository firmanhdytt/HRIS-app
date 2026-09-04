<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('karyawan');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(10);
        $karyawan = Employee::all();

        return view('absensi.index', compact('absensi', 'karyawan'));
    }

    

    function formatJam($menit)
{
    $jam = floor($menit / 60);
    $sisa = $menit % 60;

    return $jam . ' Jam ' . $sisa . ' Menit';
}

    /**
     * Form Absensi Manual
     */
    public function manualForm()
    {
        return view('absensi.manual');
    }

    /**
     * Proses Simpan Absensi
     */
    public function manualStore(Request $request)
{
    $request->validate([
        'identifier' => 'required',
        'tipe' => 'required|in:masuk,keluar',
    ]);

    // FIX TIMEZONE — WAJIB
    $now = Carbon::now('Asia/Jakarta');
    $today = $now->format('Y-m-d');
    $hari = ucfirst($now->locale('id')->isoFormat('dddd'));

    $identifier = $request->identifier;

    // Cari karyawan berdasarkan ID/Email/Nama
    $karyawan = Employee::where('employee_id', $identifier)
        ->orWhere('email', $identifier)
        ->orWhere('name', $identifier)
        ->first();

    if (!$karyawan) {
        return back()->with('error', 'Data karyawan tidak ditemukan.');
    }

    // Ambil absensi hari ini
    $absensi = Absensi::where('id_karyawan', $karyawan->employee_id)
        ->where('tanggal', $today)
        ->first();

    // ==================
    // ABSEN MASUK
    // ==================
    if ($request->tipe === 'masuk') {

        if ($absensi && $absensi->jam_masuk) {
            return back()->with('info', 'Sudah absen masuk hari ini.');
        }

        Absensi::updateOrCreate(
            [
                'id_karyawan' => $karyawan->employee_id,
                'tanggal' => $today,
            ],
            [
                'nama_karyawan' => $karyawan->name,
                'hari' => $hari,
                'jam_masuk' => $now->format('H:i:s'),
            ]
        );

        return back()->with('success', 'Absen masuk berhasil.');
    }

    // ==================
    // ABSEN KELUAR
    // ==================
    if ($request->tipe === 'keluar') {

        if (!$absensi || !$absensi->jam_masuk) {
            return back()->with('error', 'Belum absen masuk.');
        }

        if ($absensi->jam_keluar) {
            return back()->with('info', 'Sudah absen keluar hari ini.');
        }

        $jamMasuk = Carbon::parse($absensi->jam_masuk, 'Asia/Jakarta');
        $jamKeluar = $now;
        $totalMenit = $jamMasuk->diffInMinutes($jamKeluar);

        $absensi->update([
            'jam_keluar' => $jamKeluar->format('H:i:s'),
            'total_menit' => $totalMenit,
        ]);

        return back()->with('success', 'Absen keluar berhasil.');
    }

    return back()->with('error', 'Terjadi kesalahan.');
}


    /**
     * Quick Absensi dari Dashboard (Aksi Tombol Masuk/Keluar)
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'tipe' => 'required|in:masuk,keluar',
        ]);

        $user = auth()->user();
        $employeeId = $request->employee_id;

        // Jika bukan admin, pastikan hanya bisa mengabsen dirinya sendiri
        if (!$user->isAdmin()) {
            $myEmployee = $user->employee;
            if (!$myEmployee || $myEmployee->employee_id !== $employeeId) {
                return back()->with('error', 'Anda hanya dapat melakukan absensi untuk diri Anda sendiri.');
            }
        }

        $karyawan = Employee::where('employee_id', $employeeId)->first();
        if (!$karyawan) {
            return back()->with('error', 'Karyawan tidak ditemukan.');
        }

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->format('Y-m-d');
        $hari = ucfirst($now->locale('id')->isoFormat('dddd'));

        // Ambil absensi hari ini
        $absensi = Absensi::where('id_karyawan', $karyawan->employee_id)
            ->where('tanggal', $today)
            ->first();

        // ==================
        // ABSEN MASUK
        // ==================
        if ($request->tipe === 'masuk') {
            if ($absensi && $absensi->jam_masuk) {
                return back()->with('info', 'Sudah absen masuk hari ini.');
            }

            Absensi::updateOrCreate(
                [
                    'id_karyawan' => $karyawan->employee_id,
                    'tanggal' => $today,
                ],
                [
                    'nama_karyawan' => $karyawan->name,
                    'hari' => $hari,
                    'jam_masuk' => $now->format('H:i:s'),
                ]
            );

            return back()->with('success', 'Absen masuk berhasil untuk ' . $karyawan->name);
        }

        // ==================
        // ABSEN KELUAR
        // ==================
        if ($request->tipe === 'keluar') {
            if (!$absensi || !$absensi->jam_masuk) {
                return back()->with('error', 'Belum absen masuk.');
            }

            if ($absensi->jam_keluar) {
                return back()->with('info', 'Sudah absen keluar hari ini.');
            }

            $jamMasuk = Carbon::parse($absensi->jam_masuk, 'Asia/Jakarta');
            $jamKeluar = $now;
            $totalMenit = $jamMasuk->diffInMinutes($jamKeluar);

            $absensi->update([
                'jam_keluar' => $jamKeluar->format('H:i:s'),
                'total_menit' => $totalMenit,
            ]);

            return back()->with('success', 'Absen keluar berhasil untuk ' . $karyawan->name);
        }

        return back()->with('error', 'Terjadi kesalahan.');
    }



    /**
     * Tampilkan halaman scan QR (kamera aktif)
     */
    public function scanPage()
    {
        // scan blade akan mengirimkan QR ke route scanProcess
        return view('absensi.scan');
    }

    /**
     * Proses hasil scan QR.
     * Menerima JSON atau form field 'qr_code' dan mengembalikan JSON result.
     */
    public function scanProcess(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
        ]);

        $employeeId = $request->qr_code;

        $employee = Employee::where('employee_id', $employeeId)->first();

        if (! $employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR tidak valid / karyawan tidak ditemukan',
            ], 404);
        }

        $now = Carbon::now('Asia/Jakarta');

        $today = $now->copy()->startOfDay();

        // Definisikan rentang jam dengan tanggal sama
        $jam_masuk_mulai = $today->copy()->setTime(6, 0, 0);
        $jam_masuk_selesai = $today->copy()->setTime(12, 0, 0);

        $jam_tidak_boleh_mulai = $today->copy()->setTime(12, 0, 1);
        $jam_tidak_boleh_selesai = $today->copy()->setTime(16, 0, 0);

        $jam_keluar_mulai = $today->copy()->setTime(16, 0, 1);
        $jam_keluar_selesai = $today->copy()->setTime(21, 0, 0);

        $jam_luar_mulai_1 = $today->copy()->setTime(21, 0, 1);
        $jam_luar_selesai_1 = $today->copy()->setTime(23, 59, 59);
        $jam_luar_mulai_2 = $today->copy()->setTime(0, 0, 0);
        $jam_luar_selesai_2 = $today->copy()->setTime(6, 59, 59);

        // cek absensi hari ini
        $tanggal = $now->toDateString();
        $hari = ucfirst($now->locale('id')->isoFormat('dddd'));
        $absen = Absensi::where('id_karyawan', $employee->employee_id)
            ->where('tanggal', $tanggal)
            ->first();

        $currentTimeStr = $now->format('H:i:s');

        // Cek jam luar operasional (21:01 - 06:59)
        if (($now->between($jam_luar_mulai_1, $jam_luar_selesai_1))
            || ($now->between($jam_luar_mulai_2, $jam_luar_selesai_2))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, tidak bisa scan di jam '.$currentTimeStr,
            ], 403);
        }

        // Scan jam masuk 07.00 s/d 09.00
        if ($now->between($jam_masuk_mulai, $jam_masuk_selesai)) {
            if ($absen && $absen->jam_masuk) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Sudah absen masuk hari ini',
                ]);
            }

            // Buat absen masuk
            Absensi::create([
                'id_karyawan' => $employee->employee_id,
                'nama_karyawan' => $employee->name ?? $employee->nama ?? null,
                'tanggal' => $tanggal,
                'hari' => $hari,
                'jam_masuk' => $currentTimeStr,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $employee->name.' absen MASUK tercatat',
            ]);
        }

        // Scan jam 09.01 s/d 16.00 tidak bisa scan masuk/keluar
        if ($now->between($jam_tidak_boleh_mulai, $jam_tidak_boleh_selesai)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, tidak bisa scan di jam '.$currentTimeStr,
            ], 403);
        }

        // Scan jam keluar 16.01 s/d 21.00
        if ($now->between($jam_keluar_mulai, $jam_keluar_selesai)) {
            if (! $absen) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda belum absen masuk hari ini',
                ], 400);
            }

            if ($absen->jam_keluar) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Sudah absen masuk & keluar hari ini',
                ]);
            }

            // Pastikan jam_masuk valid sebelum diffInMinutes
            if (! $absen->jam_masuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data jam masuk tidak ditemukan',
                ], 400);
            }

            $jamKeluar = $now;
            $totalMenit = Carbon::parse($absen->jam_masuk)->diffInMinutes($jamKeluar);

            $absen->update([
                'jam_keluar' => $jamKeluar->format('H:i:s'),
                'total_menit' => $totalMenit,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $employee->name.' absen KELUAR tercatat',
            ]);
        }

        // Default fallback, misal waktu di luar semua rentang
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak dapat melakukan absensi di jam '.$currentTimeStr,
        ], 403);
    }

    // ============================
    // LAPORAN ABSENSI
    // ============================
    public function laporan(Request $request)
    {

        // =====================================
        // FILTER HARI INI / FILTER TANGGAL
        // =====================================
        $tanggal = $request->tanggal ?? Carbon::now('Asia/Jakarta')->toDateString();

        $absensi = Absensi::with('karyawan')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('tanggal', 'DESC')
            ->paginate(20);

        // Hitung total jam per baris untuk ditampilkan
        foreach ($absensi as $a) {

            // format WIB
            $a->jam_masuk_wib = $a->jam_masuk
                ? Carbon::parse($a->jam_masuk, 'Asia/Jakarta')->format('H:i:s')
                : '-';

            $a->jam_keluar_wib = $a->jam_keluar
                ? Carbon::parse($a->jam_keluar, 'Asia/Jakarta')->format('H:i:s')
                : '-';

            // hitung total menit
            if ($a->jam_masuk && $a->jam_keluar) {
                $masuk = Carbon::parse($a->jam_masuk, 'Asia/Jakarta');
                $keluar = Carbon::parse($a->jam_keluar, 'Asia/Jakarta');

                $a->total_menit = $masuk->diffInMinutes($keluar);

            } else {
                $a->total_menit = null;
            }
        }

        return view('absensi.laporan', compact('absensi'));
    }

    public function laporanRange(Request $request)
{
    $start = $request->start;
    $end = $request->end;

    if (!$start || !$end) {
        return view('absensi.laporan_range', [
            'data' => [],
            'start' => null,
            'end' => null,
        ]);
    }

    $rekap = Absensi::selectRaw("
            employees.employee_id,
            employees.name AS nama,
            COUNT(absensi.id) AS total_hari,
            SUM(TIMESTAMPDIFF(MINUTE, absensi.jam_masuk, absensi.jam_keluar)) AS total_menit
        ")
        ->join('employees', 'absensi.id_karyawan', '=', 'employees.employee_id')
        ->whereBetween('absensi.tanggal', [$start, $end])
        ->groupBy('employees.employee_id', 'employees.name')
        ->get();

    return view('absensi.laporan_range', [
        'data' => $rekap,
        'start' => $start,
        'end' => $end,
    ]);
}


    public function detail(Request $request, $employee_id)
{
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    // Pastikan start & end dikirim
    if (! $request->start || ! $request->end) {
        return back()->with('error', 'Start dan End wajib diisi');
    }

    $start = Carbon::parse($request->start);
    $end = Carbon::parse($request->end);

    // Ambil absensi karyawan di range dan keyBy tanggal (format Y-m-d)
    $absensi = Absensi::where('id_karyawan', $employee_id)
        ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
        ->orderBy('tanggal', 'ASC')
        ->get()
        ->keyBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

    // Buat list hari dalam periode
    $periode = CarbonPeriod::create($start->startOfDay(), $end->startOfDay());

    $detail = [];
    $totalMenit = 0;
    $hariKerja = 0;

    foreach ($periode as $d) {
        $tanggal = $d->format('Y-m-d');
        $hari = $d->locale('id')->dayName;

        if ($absensi->has($tanggal)) {
            $row = $absensi[$tanggal];
            $status = 'Bekerja';

            // Jika total_menit tersimpan gunakan, jika tidak hitung dari jam_masuk & jam_keluar
            $total = null;

            if (! empty($row->total_menit)) {
                $total = (int) $row->total_menit;
            } else {
                // Hanya hitung jika kedua jam ada
                if (! empty($row->jam_masuk) && ! empty($row->jam_keluar)) {
                    // Gabungkan tanggal + jam agar perhitungan tepat (hindari ambiguitas tanggal)
                    try {
                        $masuk = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . $row->jam_masuk, 'Asia/Jakarta');
                    } catch (\Exception $e) {
                        // fallback parse jam saja (jika format jam sudah H:i:s)
                        $masuk = Carbon::parse($row->jam_masuk, 'Asia/Jakarta');
                    }

                    try {
                        $keluar = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . $row->jam_keluar, 'Asia/Jakarta');
                    } catch (\Exception $e) {
                        $keluar = Carbon::parse($row->jam_keluar, 'Asia/Jakarta');
                    }

                    // Jika keluar < masuk (cross midnight), tambahkan 1 hari ke keluar
                    if ($keluar->lessThan($masuk)) {
                        $keluar = $keluar->addDay();
                    }

                    $total = $masuk->diffInMinutes($keluar);
                } else {
                    $total = 0;
                }
            }

            $totalMenit += $total;
            // Jika dianggap hadir (ada record), hitung hari kerja meski jam_keluar mungkin belum ada
            $hariKerja++;
        } else {
            $status = 'Libur';
            $row = null;
            $total = 0;
        }

        $detail[] = [
            'tanggal' => $tanggal,
            'hari' => $hari,
            'status' => $status,
            'jam_masuk' => $row->jam_masuk ?? '-',
            'jam_keluar' => $row->jam_keluar ?? '-',
            'total' => $total,
        ];
    }

    // Total keseluruhan
    $totalJam = floor($totalMenit / 60).' jam '.($totalMenit % 60).' menit';

    return view('absensi.laporan_detail', [
        'employee' => $employee,
        'detail' => $detail,
        'totalJam' => $totalJam,
        'hariKerja' => $hariKerja,
        'start' => $start,
        'end' => $end,
    ]);
}


    public function exportPdfHarian(Request $request)
    {
        $tanggal = $request->tanggal ??
        Carbon::now('Asia/Jakarta')->toDateString();
        $absensi = Absensi::with('karyawan')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_masuk', 'ASC')->get();

        return Pdf::loadView('absensi.export.laporan_pdf', ['absensi' => $absensi, 'tanggal' => $tanggal])
            ->setPaper('A4', 'portrait')->stream('Laporan-Harian.pdf');
    }

    public function exportPdfRange(Request $request)
    {
        if (! $request->start_date || ! $request->end_date) {
            return back()->with('error', 'Tanggal mulai dan akhir wajib diisi');
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        // ambil rekap seperti halaman laporan_range
        $rekap = Absensi::selectRaw('
            id_karyawan as id,
            nama_karyawan as nama,
            COUNT(id) as total_hari,
            SUM(TIMESTAMPDIFF(MINUTE, jam_masuk, jam_keluar) / 60) as total_jam
        ')
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('id_karyawan', 'nama_karyawan')
            ->get();

        return Pdf::loadView('absensi.export.laporan_pdf_range', [
            'rekap' => $rekap,
            'start' => $start,
            'end' => $end,
        ])->setPaper('A4', 'portrait')->stream('Laporan-Range.pdf');
    }

    public function exportPdfDetail(Request $request, $employee_id)
    {
        if (! $request->start || ! $request->end) {
            return back()->with('error', 'Start dan End wajib diisi');
        }

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        // Ambil data karyawan
        $karyawan = Employee::where('employee_id', $employee_id)->firstOrFail();

        // Ambil data absensi
        $data = Absensi::where('id_karyawan', $employee_id)
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Hitung total menit (durasi kerja)
        $totalMenit = $data->sum('total_menit');

        // Convert ke jam + menit
        $totalJamAkhir = floor($totalMenit / 60).' Jam '.($totalMenit % 60).' Menit';

        // Total hari bekerja
        $totalHari = $data->count();

        return Pdf::loadView('absensi.export.laporan_pdf_detail', [
            'karyawan' => $karyawan,
            'data' => $data,
            'start' => $start,
            'end' => $end,
            'totalJamAkhir' => $totalJamAkhir,
            'totalHari' => $totalHari,
        ])
            ->setPaper('A4', 'portrait')
            ->stream('Detail-'.$karyawan->name.'.pdf');
    }
}
