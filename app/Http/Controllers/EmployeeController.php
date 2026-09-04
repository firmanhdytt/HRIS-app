<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    // Method index: Tampilkan tabel dengan search dan pagination
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('employee_id', 'like', '%'.$search.'%');
        }

        if ($request->department) {
            $query->where('department', $request->department);
        }

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $employees = $query->paginate($request->per_page ?? 10);

        // Cek file exists
        // $photoPath = $employee->photo ?? '';

        // if (! empty($photoPath) && Storage::exists($photoPath)) {
        //     Storage::delete($photoPath);
        // }

        return view('employees.index', compact('employees'));
    }

    // Method create: Tampilkan form tambah
    public function create()
    {
        return view('employees.create');
    }

    // Method store: Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rfid_uid' => 'nullable|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:L,P',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employment_status' => 'required|in:Tetap,Kontrak,Magang',
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date|after:join_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Generate Employee ID otomatis
        $lastEmployee = Employee::latest('id')->first();
        $data['employee_id'] = 'EMP'.str_pad(($lastEmployee ? $lastEmployee->id + 1 : 1), 3, '0', STR_PAD_LEFT);

        // Handle upload foto
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $employee = Employee::create($data);

        // pastikan folder ada
        if (! Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        logActivity(
            'create_karyawan',
            'Menambahkan karyawan baru: '.$employee->name,
            $employee->employee_id
        );
        
        // Generate QR Code
        $qrCode = QrCode::create('Employee ID: '.$employee->employee_id.' - '.$employee->name);
        $writer = new PngWriter;
        $result = $writer->write($qrCode);
        // Simpan file QR ke storage disk 'public'
        $qrPath = 'qrcodes/'.$employee->employee_id.'.png';
        // Gunakan Storage disk public dengan method put:
        Storage::disk('public')->put($qrPath, $result->getString());
        //  Update field qr_code path di database
        $employee->update(['qr_code' => $qrPath]);

        return redirect()->route('employees.index')->with('status', 'Karyawan berhasil ditambahkan.');
    }

    // Method show: (Opsional)
    // public function show($id)
    // {
    //     $employee = Employee::findOrFail($id);

    //     return view('employees.show', compact('employee'));
    // }

    // Method edit: Full edit
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return view('employees.edit', compact('employee'));
    }

    // Method update: Update semua data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rfid_uid' => 'nullable|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employment_status' => 'required|in:Tetap,Kontrak,Magang',
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date|after:join_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($id);
        $data = $request->all();

        // Handle upload foto baru
        if ($request->hasFile('photo')) {
            if ($employee->photo && Storage::exists('public/'.$employee->photo)) {
                Storage::delete('public/'.$employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $employee->update($data);
        // 🔥 CATAT LOG AKTIVITAS (INI POSISI YANG BENAR)
        logActivity(
            'update_karyawan',
            'Mengedit data karyawan: '.$employee->name,
            $employee->employee_id
        );

        // Generate QR Code
        $qrCode = QrCode::create('Employee ID: '.$employee->employee_id.' - '.$employee->name);
        $writer = new PngWriter;
        $result = $writer->write($qrCode);
        // Simpan file QR ke storage disk 'public'
        $qrPath = 'qrcodes/'.$employee->employee_id.'.png';
        // Gunakan Storage disk public dengan method put:
        Storage::disk('public')->put($qrPath, $result->getString());
        //  Update field qr_code path di database
        $employee->update(['qr_code' => $qrPath]);

        return redirect()->route('employees.index')->with('status', 'Data karyawan berhasil diperbarui.');
    }

    // Method destroy: Hapus data
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->photo && Storage::exists('public/'.$employee->photo)) {
            Storage::delete('public/'.$employee->photo);
        }
        if ($employee->qr_code && Storage::exists('public/'.$employee->qr_code)) {
            Storage::delete('public/'.$employee->qr_code);
        }
        logActivity(
            'delete_karyawan',
            'Menghapus karyawan: '.$employee->name,
            $employee->employee_id
        );

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    // Method export: Export ke Excel
    public function export()
    {
        return Excel::download(new \App\Exports\EmployeesExport, 'employees.xlsx');
    }
}
