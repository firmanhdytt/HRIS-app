<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
            $table->bigInteger('gaji_pokok')->default(0); // stored in Rupiah per hari (integer)
            $table->bigInteger('gaji_lembur')->nullable(); // rate per hour in Rupiah
            $table->bigInteger('potongan_terlambat')->nullable(); // per-minute or per-kali depending mode
            $table->enum('potongan_mode', ['per_minute','per_kali'])->default('per_minute');
            $table->integer('kerajinan')->nullable(); // stored as threshold? We'll store as minutes rate? (see notes)
            $table->bigInteger('pinjaman')->nullable();
            $table->bigInteger('bonus')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_settings');
    }
};
