<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('employees')
                ->cascadeOnDelete();
            $table->date('periode_from');
            $table->date('periode_to');
            $table->bigInteger('total_gaji')->default(0);
            $table->json('breakdown')->nullable(); // store breakdown: pokok, lembur, potongan, kerajinan, pinjaman, bonus
            $table->foreignId('created_by')->nullable()->constrained('users'); // admin who generated
            $table->timestamps();

            $table->index(['periode_from', 'periode_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};
