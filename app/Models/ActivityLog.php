<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employee;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',       // admin / HR / karyawan login
        'employee_id',   // employee_id dari tabel employees
        'action',        // type aksi: update_employee, request_leave, delete_payroll, dll
        'description',   // deskripsi detail
        'is_read'
    ];

    protected $attributes = [
    'is_read' => false
    ];


    // Relasi ke User (admin/karyawan yang login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Employee (karyawan yang melakukan aksi)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    
}
