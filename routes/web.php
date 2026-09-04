<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\JadwalPiketController;
use App\Http\Controllers\RuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing')->withoutMiddleware(['auth']);

// Redirect ke login
Route::redirect('/', '/login');

// Route Publik Slip Gaji menggunakan Signed URL (bisa diakses karyawan tanpa login via WA)
Route::get('/public-payroll/{employee_id}/pdf', [PayrollController::class, 'publicPdf'])
    ->name('payroll.public.pdf')
    ->middleware('signed');

// Dashboard utama (Controller terbaru)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/notifications/delete/{id}', 
        [NotificationController::class, 'deleteOne']
    )->name('notifications.delete');

    Route::post('/notifications/clear-all', 
        [NotificationController::class, 'clearAll']
    )->name('notifications.clearAll');

    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])
    ->name('notifications.markRead');

});


// ===============================
// KARYAWAN: Ajukan & lihat izin saya
// ===============================
Route::middleware('auth')->group(function () {

    // Daftar izin saya
    Route::get('/izin', [LeaveRequestController::class, 'index'])
        ->name('leave.index');

    // Form ajukan izin
    Route::get('/izin/create', [LeaveRequestController::class, 'create'])
        ->name('leave.create');

    // Simpan izin
    Route::post('/izin/store', [LeaveRequestController::class, 'store'])
        ->name('leave.store');

    // Kotak "Izin Saya"
    Route::get('/izin/saya', [LeaveRequestController::class, 'myLeaves'])
        ->name('leave.my');
});


// ===============================
// ADMIN: Kelola izin karyawan
// ===============================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // List semua izin
    Route::get('/izin/admin', [LeaveRequestController::class, 'adminIndex'])
        ->name('leave.admin');

    // Approve izin
    Route::post('/izin/{id}/approve', [LeaveRequestController::class, 'approve'])
        ->name('leave.approve');

    // Reject izin
    Route::post('/izin/{id}/reject', [LeaveRequestController::class, 'reject'])
        ->name('leave.reject');
});




// Profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ===============================
// KARYAWAN (Untuk semua role yang login)
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
});

// ===============================
// ADMIN ONLY ROUTES
// ===============================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // CRUD Employee
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
});

// ===============================
// ABSENSI ROUTES
// ===============================
Route::middleware('auth')->group(function () {

    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi/quick-store', [AbsensiController::class, 'quickStore'])->name('absensi.quick-store');

    Route::middleware('role:admin')->group(function () {
        Route::get('absensi/manual', [AbsensiController::class, 'manualForm'])->name('absensi.manual.form');
        Route::post('absensi/manual', [AbsensiController::class, 'manualStore'])->name('absensi.manual.store');
    });

    Route::get('absensi/scan', [AbsensiController::class, 'scanPage'])->name('absensi.scan');
    Route::post('absensi/scan/process', [AbsensiController::class, 'scanProcess'])->name('absensi.scan.process');

    // Laporan Absensi
    Route::get('absensi/laporan', [AbsensiController::class, 'laporan'])->name('absensi.laporan');
    Route::get('absensi/laporan-range', [AbsensiController::class, 'laporanRange'])->name('absensi.range');
    Route::get('absensi/laporan/detail/{employee_id}', [AbsensiController::class, 'detail'])->name('absensi.laporan.detail');

    // Export PDF
    Route::get('absensi/export/harian', [AbsensiController::class, 'exportPdfHarian'])->name('absensi.export.harian');
    Route::get('absensi/export/range', [AbsensiController::class, 'exportPdfRange'])->name('absensi.export.range');
    Route::get('absensi/export/detail/{employee_id}', [AbsensiController::class, 'exportPdfDetail'])->name('absensi.export.detail');
});

// ===============================
// PAYROLL ROUTES (ADMIN ONLY)
// ===============================
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/settings', [PayrollController::class, 'settings'])->name('payroll.settings');

    Route::get('/payroll/{employee_id}/edit-setting',
        [PayrollController::class, 'editSetting'])->name('payroll.settings.edit');

    Route::post('/payroll/{employee_id}/update-setting',
        [PayrollController::class, 'updateSetting'])->name('payroll.settings.update');

    Route::get('/payroll/{employee:employee_id}/detail',
        [PayrollController::class, 'detail'])->name('payroll.detail');

    Route::get('/payroll/{employee_id}/detail/pdf',
        [PayrollController::class, 'detailPdf'])->name('payroll.detail.pdf');

    Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');

    Route::get('/payroll/history', [PayrollController::class, 'history'])->name('payroll.history');

    Route::get('/payroll/history/timeline/{employee_id}',
        [PayrollController::class, 'timeline'])->name('payroll.history.timeline');

    Route::get('/payroll/history/excel',
        [PayrollController::class, 'historyExcel'])->name('payroll.history.excel');
});


// Jadwal Piket
Route::middleware(['auth'])->group(function () {
    Route::get('/piket', [JadwalPiketController::class, 'index'])->name('piket.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/piket/manage', [JadwalPiketController::class, 'manage'])->name('piket.manage');
        Route::post('/piket/update', [JadwalPiketController::class, 'save'])->name('piket.save');
    });
});




Route::middleware(['auth'])->group(function () {
    Route::resource('rules', RuleController::class);
    
});


require __DIR__.'/auth.php';
