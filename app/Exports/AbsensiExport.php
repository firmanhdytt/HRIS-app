<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Absensi::with('employee');

        // Terapkan filter seperti di laporan
        if (isset($this->filters['tanggal'])) {
            $query->where('tanggal', $this->filters['tanggal']);
        }
        // Tambahkan filter lainnya

        return $query->get()->map(function ($absensi) {
            return [
                'Nama' => $absensi->employee->name,
                'Tanggal' => $absensi->tanggal,
                'Jam Masuk' => $absensi->jam_masuk,
                'Jam Keluar' => $absensi->jam_keluar,
                'Total Jam' => $absensi->total_jam,
                'Keterangan' => $absensi->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Total Jam', 'Keterangan'];
    }
}