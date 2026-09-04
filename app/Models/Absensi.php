<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi'; // tabel di database

    protected $fillable = [
        'id_karyawan','nama_karyawan','tanggal','hari','jam_masuk','jam_keluar','total_menit'
    ];

    // Relasi ke Employee
    public function karyawan()
    {
        return $this->belongsTo(Employee::class, 'id_karyawan', 'employee_id');
    }


    public static function hitungTotalJam($jamMasuk, $jamKeluar)
    {
        $masuk = strtotime($jamMasuk);
        $keluar = strtotime($jamKeluar);
        $durasi = $keluar - $masuk;

        return floor($durasi / 60); // Total menit
    }
}