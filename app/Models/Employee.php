<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'name', 'rfid_uid', 'birth_place', 'birth_date', 'gender',
        'position', 'department', 'join_date', 'employment_status',
        'phone', 'email', 'address', 'resign_date', 'photo', 'qr_code', 'notes',
    ];

    // Jika perlu relasi dengan User (misal berdasarkan NIK)
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik'); // Asumsi kolom nik di users
    }

    // absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_karyawan', 'employee_id');
    }

    public function payrollSetting()
    {
        return $this->hasOne(PayrollSetting::class, 'employee_id', 'employee_id');
    }

    public function payrollRecords()
    {
        return $this->hasMany(PayrollRecord::class, 'employee_id', 'employee_id');
    }

    // Relasi ke izin
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'employee_id');
    }

    // Relasi ke activity log (jika karyawan melakukan aksi)
    public function activities()
    {
        return $this->hasMany(ActivityLog::class, 'employee_id', 'employee_id');
    }
}
