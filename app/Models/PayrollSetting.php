<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    protected $table = 'payroll_settings';

    protected $fillable = [
        'employee_id',
        'gaji_pokok',
        'gaji_lembur',
        'potongan_terlambat',
        'potongan_mode',
        'kerajinan',
        'pinjaman',
        'bonus',
    ];

    // Relasi: payroll_settings.employee_id -> employees.id_karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
