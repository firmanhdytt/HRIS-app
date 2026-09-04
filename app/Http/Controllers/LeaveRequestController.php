<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $employee = Employee::where('email', auth()->user()->email)->first();

        if (! $employee) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('izin_bukti', 'public');
        }

        $izin = LeaveRequest::create([
            'employee_id' => $employee->employee_id,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'bukti_path' => $path,
            'status' => 'pending',
        ]);

        logActivity(
            'ajukan_izin',
            'Pengajuan izin oleh '.$employee->name,
            $employee->employee_id
        );

        return redirect()->route('leave.my')
            ->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    public function myLeaves()
{
    // pastikan hanya karyawan yang punya employee_id
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'Halaman ini khusus karyawan.');
    }

    $izin = LeaveRequest::where('employee_id', $employee->employee_id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('leave.my', compact('izin'));
}


    public function adminIndex()
    {
        $leaves = LeaveRequest::with('employee')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return view('leave.admin_index', compact('leaves'));
    }

    public function approve($id)
    {
        $izin = LeaveRequest::findOrFail($id);
        $izin->status = 'approved';
        $izin->save();

        logActivity(
            'approve_izin',
            'Admin menyetujui izin: '.$izin->employee->name,
            $izin->employee_id
        );

        return back()->with('success', 'Izin berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $izin = LeaveRequest::findOrFail($id);

        $izin->status = 'rejected';
        $izin->keterangan_admin = $request->alasan ?? null;
        $izin->save();

        logActivity(
            'reject_izin',
            'Admin menolak izin: '.$izin->employee->name,
            $izin->employee_id
        );

        return back()->with('success', 'Izin berhasil ditolak.');
    }
}
