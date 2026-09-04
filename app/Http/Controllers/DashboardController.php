<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today        = Carbon::today()->toDateString();
        $todayCarbon  = Carbon::today();
        $cutOffTelat  = '08:15:00';

        // ========================
        // MASUK HARI INI
        // ========================
        $masukToday = Absensi::whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->count();

        // ========================
        // KELUAR HARI INI
        // ========================
        $keluarToday = Absensi::whereDate('tanggal', $today)
            ->whereNotNull('jam_keluar')
            ->count();

        // ========================
        // TELAT HARI INI
        // ========================
        $lateToday = Absensi::whereDate('tanggal', $today)
            ->where('jam_masuk', '>', $cutOffTelat)
            ->count();

        // ========================
        // IZIN HARI INI (LOGIKA TERBARU & SINKRON)
        // ========================
        $izinToday = LeaveRequest::where('status', 'approved')
            ->where(function ($q) use ($today) {
                // izin multi hari
                $q->whereDate('tanggal_mulai', '<=', $today)
                  ->whereDate('tanggal_selesai', '>=', $today);
            })
            ->orWhere(function($q) use ($today) {
                // izin 1 hari tanpa tanggal_selesai
                $q->whereNull('tanggal_selesai')
                  ->whereDate('tanggal_mulai', $today)
                  ->where('status', 'approved');
            })
            ->count();

        // ========================
        // TOTAL KARYAWAN
        // ========================
        $totalKaryawan = Employee::count();

        // ========================
        // ABSEN HARI INI
        // ========================
        $absenToday = $totalKaryawan - $masukToday - $izinToday;
        if ($absenToday < 0) $absenToday = 0;

        // ========================
        // LIST KEHADIRAN HARI INI (TABLE)
        // ========================
        $kehadiranList = Employee::with(['absensi' => function ($q) use ($today) {
            $q->where('tanggal', $today);
        }])->get();

        // ========================
        // IZIN TERBARU
        // ========================
        $latestLeaves = LeaveRequest::latest()->limit(5)->get();

        // ========================
        // PENDING IZIN
        // ========================
        $pendingCount = LeaveRequest::where('status', 'pending')->count();

        // ========================
        // NOTIFIKASI
        // ========================
        $notifAll = ActivityLog::orderBy('created_at', 'desc')->get();

        // ========================
        // PIE CHART — DAILY (SINKRON)
        // ========================
        $filter = $request->filter ?? 'daily';

        if ($filter === 'daily') {

            $hadir = Absensi::whereDate('tanggal', $today)
                ->whereNotNull('jam_masuk')
                ->where('jam_masuk', '<=', $cutOffTelat)
                ->count();

            $telat = $lateToday;

            // IZIN HARI INI (PASTI SAMA DGN TABEL)
            $izin = LeaveRequest::where('status', 'approved')
                ->where(function ($q) use ($today) {
                    $q->whereDate('tanggal_mulai', '<=', $today)
                      ->whereDate('tanggal_selesai', '>=', $today);
                })
                ->orWhere(function($q) use ($today) {
                    $q->whereNull('tanggal_selesai')
                      ->whereDate('tanggal_mulai', $today)
                      ->where('status', 'approved');
                })
                ->count();

            $absen = $absenToday;

        } else {

            // RENTANG WEEKLY / MONTHLY
            if ($filter === 'weekly') {
                $from = $todayCarbon->copy()->subDays(6)->toDateString();
            } else {
                $from = $todayCarbon->copy()->startOfMonth()->toDateString();
            }

            $to = $today;

            $hadir = Absensi::whereBetween('tanggal', [$from, $to])
                ->whereNotNull('jam_masuk')
                ->where('jam_masuk', '<=', $cutOffTelat)
                ->count();

            $telat = Absensi::whereBetween('tanggal', [$from, $to])
                ->where('jam_masuk', '>', $cutOffTelat)
                ->count();

            // IZIN RANGE (SINKRON PER HARI)
            $izin = LeaveRequest::where('status', 'approved')
                ->where(function ($q) use ($today) {
                    $q->whereDate('tanggal_mulai', '<=', $today)
                      ->whereDate('tanggal_selesai', '>=', $today);
                })
                ->orWhere(function($q) use ($today) {
                    $q->whereNull('tanggal_selesai')
                      ->whereDate('tanggal_mulai', $today)
                      ->where('status', 'approved');
                })
                ->count();

            $absen = 0;
        }

        $chartData = [
            'hadir' => $hadir,
            'telat' => $telat,
            'izin'  => $izin,
            'absen' => $absen,
        ];

        // ================================
        // LINE CHART — WEEKLY & MONTHLY
        // ================================
        $labels     = [];
        $hadirLine  = [];
        $telatLine  = [];
        $izinLine   = [];
        $absenLine  = [];

        if ($filter === 'weekly') {

            $period = CarbonPeriod::create(
                Carbon::today()->subDays(6),
                Carbon::today()
            );

            foreach ($period as $date) {

                $tgl = $date->toDateString();
                $labels[] = $date->format('d M');

                $abs = Absensi::whereDate('tanggal', $tgl)->get();

                $hadirCount = $abs->whereNotNull('jam_masuk')
                    ->where('jam_masuk', '<=', $cutOffTelat)
                    ->count();

                $telatCount = $abs->whereNotNull('jam_masuk')
                    ->where('jam_masuk', '>', $cutOffTelat)
                    ->count();

                // IZIN PER-HARI (SINKRON)
                $izinCount = LeaveRequest::where('status', 'approved')
                    ->where(function ($q) use ($tgl) {
                        $q->whereDate('tanggal_mulai', '<=', $tgl)
                          ->whereDate('tanggal_selesai', '>=', $tgl);
                    })
                    ->orWhere(function($q) use ($tgl) {
                        $q->whereNull('tanggal_selesai')
                          ->whereDate('tanggal_mulai', $tgl)
                          ->where('status', 'approved');
                    })
                    ->count();

                $absenCount = $totalKaryawan - $hadirCount - $telatCount - $izinCount;
                if ($absenCount < 0) $absenCount = 0;

                $hadirLine[] = $hadirCount;
                $telatLine[] = $telatCount;
                $izinLine[]  = $izinCount;
                $absenLine[] = $absenCount;
            }

        } elseif ($filter === 'monthly') {

            $period = CarbonPeriod::create(
                Carbon::today()->startOfMonth(),
                Carbon::today()->endOfMonth()
            );

            foreach ($period as $date) {

                $tgl = $date->toDateString();
                $labels[] = $date->format('d');

                $abs = Absensi::whereDate('tanggal', $tgl)->get();

                $hadirCount = $abs->whereNotNull('jam_masuk')
                    ->where('jam_masuk', '<=', $cutOffTelat)
                    ->count();

                $telatCount = $abs->whereNotNull('jam_masuk')
                    ->where('jam_masuk', '>', $cutOffTelat)
                    ->count();

                // IZIN PER-HARI (SINKRON)
                $izinCount = LeaveRequest::where('status', 'approved')
                    ->where(function ($q) use ($tgl) {
                        $q->whereDate('tanggal_mulai', '<=', $tgl)
                          ->whereDate('tanggal_selesai', '>=', $tgl);
                    })
                    ->orWhere(function($q) use ($tgl) {
                        $q->whereNull('tanggal_selesai')
                          ->whereDate('tanggal_mulai', $tgl)
                          ->where('status', 'approved');
                    })
                    ->count();

                $absenCount = $totalKaryawan - $hadirCount - $telatCount - $izinCount;
                if ($absenCount < 0) $absenCount = 0;

                $hadirLine[] = $hadirCount;
                $telatLine[] = $telatCount;
                $izinLine[]  = $izinCount;
                $absenLine[] = $absenCount;
            }
        }

        return view('dashboard.index', compact(
            'masukToday',
            'keluarToday',
            'lateToday',
            'izinToday',
            'absenToday',
            'kehadiranList',
            'latestLeaves',
            'notifAll',
            'chartData',
            'filter',
            'labels',
            'hadirLine',
            'telatLine',
            'izinLine',
            'absenLine',
            'pendingCount'
        ));
    }
}
