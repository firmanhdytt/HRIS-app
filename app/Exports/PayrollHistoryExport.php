<?php

namespace App\Exports;

use App\Models\PayrollRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return PayrollRecord::with('employee')
            ->whereBetween('periode_from', [$this->from, $this->to])
            ->orderBy('periode_from', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Periode Dari',
            'Periode Sampai',
            'Employee ID',
            'Nama Karyawan',
            'Total Gaji',
        ];
    }

    public function map($record): array
    {
        $totalGaji = $record->total_gaji;
        if ($record->employee) {
            $calc = \App\Services\PayrollService::calculateForEmployee(
                $record->employee,
                $record->periode_from,
                $record->periode_to
            );
            $totalGaji = $calc['total_gaji'];
        }

        return [
            $record->periode_from,
            $record->periode_to,
            $record->employee_id,
            $record->employee->name ?? '',
            number_format($totalGaji, 0, ',', '.')
        ];
    }
}
