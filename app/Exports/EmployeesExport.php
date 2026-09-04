<?php
namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'Employee ID', 'Name', 'Birth Place', 'Birth Date', 'Gender', 'Position', 'Department',
            'Join Date', 'Employment Status', 'Phone', 'Email', 'Address', 'Basic_salary',
            'Resign Date', 'Photo', 'QR Code', 'Notes', 'Created_at', 'Updated_at'
        ];
    }

    // Mapping data supaya format rapi
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->employee_id,
            $employee->name,
            $employee->birth_place,
            $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '',
            $employee->gender,
            $employee->position,
            $employee->department,
            $employee->join_date ? $employee->join_date->format('Y-m-d') : '',
            $employee->employment_status,
            $employee->phone,
            $employee->email,
            $employee->address,
            $employee->basic_salary,
            $employee->resign_date ? $employee->resign_date->format('Y-m-d') : '',
            $employee->photo ? asset('storage/photos/'.$employee->photo) : '',
            $employee->qr_code ? asset('storage/'.$employee->qr_code) : '',
            $employee->notes,
            $employee->created_at ? $employee->created_at->format('Y-m-d H:i:s') : '',
            $employee->updated_at ? $employee->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
