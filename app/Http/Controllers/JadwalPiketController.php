<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JadwalPiket;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index()
{
    $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    $jadwal = JadwalPiket::all()->groupBy('hari');

    $maxRows = 1;

    foreach ($days as $day) {
        if (isset($jadwal[$day])) {
            $count = $jadwal[$day]->count();
            if ($count > $maxRows) {
                $maxRows = $count;
            }
        }
    }

    return view('piket.index', compact('days','jadwal','maxRows'));
}


    public function manage()
    {
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        $jadwal = JadwalPiket::all()->groupBy('hari');
        $employees = Employee::orderBy('name')->get(['employee_id','name']);

        return view('piket.manage', compact('days','jadwal','employees'));
    }

    public function save(Request $request)
    {
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        foreach ($days as $day) {

            JadwalPiket::where('hari', $day)->delete();

            if ($request->$day) {
                foreach ($request->$day as $emp) {
                    $e = Employee::where('employee_id', $emp)->first();

                    JadwalPiket::create([
                        'hari'          => $day,
                        'employee_id'   => $e->employee_id,
                        'employee_name' => $e->name
                    ]);
                }
            }
        }

        return redirect()->route('piket.index')
            ->with('success','Jadwal Piket berhasil diperbarui!');
    }
}
