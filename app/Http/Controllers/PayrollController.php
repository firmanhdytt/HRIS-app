<?php

namespace App\Http\Controllers;

use App\Exports\PayrollHistoryExport;
use App\Models\Employee;
use App\Models\PayrollRecord;
use App\Models\PayrollSetting;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
    // ============================
    // INDEX — LIST DATA GAJI
    // ============================
    public function index(Request $request)
    {
        $q = $request->get('q');
        $from = $request->get('from') ?: Carbon::now()->startOfMonth()->toDateString();
        $to = $request->get('to') ?: Carbon::now()->endOfMonth()->toDateString();

        $employees = Employee::query()
            ->when($q, fn ($qr) => $qr->where('name', 'like', "%{$q}%"))
            ->paginate(15);

        $data = [];
        foreach ($employees as $emp) {
            $calc = PayrollService::calculateForEmployee($emp, $from, $to);

            $data[] = [
                'employee' => $emp,
                'breakdown' => $calc['breakdown'],
                'total_gaji' => $calc['total_gaji'],
            ];
        }

        return view('payroll.index', compact('employees', 'data', 'from', 'to', 'q'));
    }

    // ============================
    // SETTINGS LIST
    // ============================
    public function settings(Request $request)
    {
        $q = $request->get('q');

        $employees = Employee::query()
            ->when($q, fn ($b) => $b->where('name', 'like', "%{$q}%"))
            ->with('payrollSetting')
            ->paginate(15);

        return view('payroll.settings', compact('employees', 'q'));
    }

    // ============================
    // DETAIL PAYROLL
    // ============================
    public function detail(Request $request, $employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $from = $request->get('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->get('to') ?: now()->endOfMonth()->toDateString();

        $calc = PayrollService::calculateForEmployee($employee, $from, $to);

        return view('payroll.detail', compact('employee', 'from', 'to', 'calc'));
    }

    // ============================
    // EDIT SETTING PAYROLL
    // ============================
    public function editSetting($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
        $payroll = $employee->payrollSetting;

        return view('payroll.edit-setting', compact('employee', 'payroll'));
    }

    // ============================
    // UPDATE SETTING PAYROLL
    // ============================
    public function updateSetting(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'gaji_pokok' => 'required|numeric',
            'gaji_lembur' => 'required|numeric',
            'potongan_terlambat' => 'required|numeric',
            'potongan_mode' => 'required',
            'kerajinan' => 'nullable|numeric',
            'pinjaman' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
        ]);

        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $employee->payrollSetting()->updateOrCreate(
            ['employee_id' => $employee->employee_id],
            $request->only([
                'gaji_pokok', 'gaji_lembur', 'potongan_terlambat',
                'potongan_mode', 'kerajinan', 'pinjaman', 'bonus',
            ])
        );

        return redirect()->route('payroll.settings')->with('success', 'Payroll setting updated');
    }

    // ============================
    // EXPORT PDF DETAIL
    // ============================
    public function detailPdf($employee_id)
    {
        $from = request('from');
        $to = request('to');

        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $setting = PayrollSetting::firstOrNew(['employee_id' => $employee_id], [
            'gaji_pokok' => 0,
            'gaji_lembur' => 0,
            'potongan_terlambat' => 0,
            'potongan_mode' => 'per_minute',
            'kerajinan' => 0,
            'pinjaman' => 0,
            'bonus' => 0,
        ]);

        $calc = PayrollService::calculateForEmployee($employee, $from, $to);
        $bd = $calc['breakdown'];

        return Pdf::loadView('payroll.pdf', [
            'employee' => $employee,
            'setting' => $setting,
            'calc' => $calc,
            'bd' => $bd,
            'from' => $from,
            'to' => $to,
            'gajiPerHari' => $setting->gaji_pokok,
            'tarifLembur' => $setting->gaji_lembur,
            'tarifTelat' => $setting->potongan_terlambat,
            'menitLembur' => $bd['menit_lembur'],
            'menitKerajinan' => $bd['menit_kerajinan'],
        ])
            ->setPaper('a4', 'portrait')
            ->stream('Slip-Gaji-'.$employee->name.'.pdf');
    }

    // ============================
    // GENERATE PAYROLL
    // ============================
    public function generate(Request $request)
    {
        $employeeId = $request->employee_id;
        $from = $request->from;
        $to = $request->to;

        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();
        $calc = PayrollService::calculateForEmployee($employee, $from, $to);

        PayrollRecord::create([
            'employee_id' => $employee->employee_id,
            'periode_from' => $from,
            'periode_to' => $to,
            'total_gaji' => $calc['total_gaji'],
            'breakdown' => $calc['breakdown'],
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('payroll.detail', $employee->employee_id)
            ->with('success', 'Payroll berhasil digenerate & disimpan.');
    }

    // ============================
    // HISTORY PAYROLL  (FINAL REVISI SINKRON)
    // ============================
    public function history(Request $request)
    {
        $from = $request->get('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->get('to') ?: now()->endOfMonth()->toDateString();

        

        $records = PayrollRecord::with('employee')
            ->whereBetween('periode_from', [$from, $to])
            ->orderBy('periode_from', 'asc')
            ->paginate(20);

        // Ambil semua records dalam rentang tanggal untuk menghitung Summary & Chart agar tidak terpengaruh pagination
        $allRecords = PayrollRecord::with('employee')
            ->whereBetween('periode_from', [$from, $to])
            ->orderBy('periode_from', 'asc')
            ->get();

        // Sinkron total gaji untuk records halaman saat ini
        foreach ($records as $r) {
            if ($r->employee) {
                $calc = PayrollService::calculateForEmployee(
                    $r->employee,
                    $r->periode_from,
                    $r->periode_to
                );

                $r->synced_total_gaji = $calc['total_gaji'];
            } else {
                $r->synced_total_gaji = $r->total_gaji;
            }
        }

        // Sinkron total gaji untuk semua records (untuk Summary & Chart)
        foreach ($allRecords as $r) {
            if ($r->employee) {
                $calc = PayrollService::calculateForEmployee(
                    $r->employee,
                    $r->periode_from,
                    $r->periode_to
                );

                $r->synced_total_gaji = $calc['total_gaji'];
            } else {
                $r->synced_total_gaji = $r->total_gaji;
            }
        }

        // Summary sinkron
        $summary = $allRecords->sum('synced_total_gaji');

        // Chart sinkron
        $chart_rows = [];
        foreach ($allRecords as $r) {
            $key = $r->periode_from;

            if (!isset($chart_rows[$key])) {
                $chart_rows[$key] = 0;
            }
            $chart_rows[$key] += $r->synced_total_gaji;
        }

        $chart_labels = array_keys($chart_rows);
        $chart_values = array_values($chart_rows);

        return view('payroll.history', compact(
            'records', 'summary', 'from', 'to',
            'chart_labels', 'chart_values'
        ));
    }

    public function timeline($employee_id)
{
    $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

    $records = PayrollRecord::where('employee_id', $employee_id)
        ->orderBy('periode_from', 'asc')
        ->get();

    

    // ============================================================
    // SINKRON DATA PERIODE DENGAN PERHITUNGAN TERBARU
    // ============================================================
    foreach ($records as $r) {
        if ($r->employee) {

            // Recalculate seluruh komponen payroll
            $calc = PayrollService::calculateForEmployee(
                $r->employee,
                $r->periode_from,
                $r->periode_to
            );

            $bd = $calc['breakdown'];

            // Inject ke object (tidak ubah DB)
            $r->synced_total_gaji = $calc['total_gaji'];
            $r->synced_breakdown = $bd;

        } else {

            // fallback jika employee tidak ditemukan
            $r->synced_total_gaji = $r->total_gaji;
            $r->synced_breakdown = $r->breakdown ?? [];
        }
    }

    // ============================================================
    // DATA UNTUK CHART (sinkron)
    // ============================================================
    $labels = [];
    $absensi = [];
    $lembur = [];
    $kerajinan = [];
    $potongan = [];
    $totalGaji = [];

    foreach ($records as $r) {
        $bd = $r->synced_breakdown;

        $labels[]       = $r->periode_from . " s/d " . $r->periode_to;
        $absensi[]      = $bd['total_hari_bekerja'] ?? 0;
        $lembur[]       = $bd['jam_lembur'] ?? 0;
        $kerajinan[]    = $bd['kerajinan_total'] ?? 0;
        $potongan[]     = $bd['potongan_total'] ?? 0;
        $totalGaji[]    = $r->synced_total_gaji ?? 0;
    }

    return view('payroll.timeline', [
        'employee' => $employee,
        'records' => $records,
        'labels' => $labels,
        'absensi' => $absensi,
        'lembur' => $lembur,
        'kerajinan' => $kerajinan,
        'potongan' => $potongan,
        'totalGaji' => $totalGaji,
    ]);
}


    // ============================
    // EXPORT PDF DETAIL PUBLIC (SIGNED URL)
    // ============================
    public function publicPdf(Request $request, $employee_id)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        $setting = PayrollSetting::firstOrNew(['employee_id' => $employee_id], [
            'gaji_pokok' => 0,
            'gaji_lembur' => 0,
            'potongan_terlambat' => 0,
            'potongan_mode' => 'per_minute',
            'kerajinan' => 0,
            'pinjaman' => 0,
            'bonus' => 0,
        ]);

        $calc = PayrollService::calculateForEmployee($employee, $from, $to);
        $bd = $calc['breakdown'];

        return Pdf::loadView('payroll.pdf', [
            'employee' => $employee,
            'setting' => $setting,
            'calc' => $calc,
            'bd' => $bd,
            'from' => $from,
            'to' => $to,
            'gajiPerHari' => $setting->gaji_pokok,
            'tarifLembur' => $setting->gaji_lembur,
            'tarifTelat' => $setting->potongan_terlambat,
            'menitLembur' => $bd['menit_lembur'],
            'menitKerajinan' => $bd['menit_kerajinan'],
        ])
            ->setPaper('a4', 'portrait')
            ->stream('Slip-Gaji-'.$employee->name.'.pdf');
    }

    // ============================
    // EXPORT EXCEL
    // ============================
    public function historyExcel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        return Excel::download(
            new PayrollHistoryExport($from, $to),
            "Payroll_History_{$from}_sd_{$to}.xlsx"
        );
    }
}
