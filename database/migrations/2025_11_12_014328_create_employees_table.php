<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id')->unique(); // ID Karyawan (misal: EMP001)
        $table->string('name'); // Nama Lengkap
        $table->string('birth_place'); // Tempat Lahir
        $table->date('birth_date'); // Tanggal Lahir
        $table->enum('gender', ['L', 'P']); // Jenis Kelamin
        $table->string('position'); // Jabatan
        $table->string('department'); // Departemen
        $table->date('join_date'); // Tanggal Masuk
        $table->enum('employment_status', ['Tetap', 'Kontrak', 'Magang']); // Status Kepegawaian
        $table->string('phone'); // No. HP
        $table->string('email')->unique(); // Email
        $table->text('address'); // Alamat Lengkap
        $table->decimal('basic_salary', 15, 2)->nullable(); // Gaji Pokok (opsional)
        $table->date('resign_date')->nullable(); // Tanggal Keluar (opsional)
        $table->string('photo')->nullable(); // Path foto karyawan
        $table->string('qr_code')->nullable(); // Path QR Code (untuk absensi)
        $table->text('notes')->nullable(); // Catatan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
