<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            // Drop FK lama pakai kolom (cara Laravel)
            $table->dropForeign(['employee_id']);
        });

        Schema::table('payroll_settings', function (Blueprint $table) {
            // Pastikan tipe sesuai employees.employee_id
            $table->string('employee_id')->change();
        });

        Schema::table('payroll_settings', function (Blueprint $table) {
            // FK yang benar
            $table->foreign('employee_id')
                  ->references('employee_id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });

        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->integer('employee_id')->change();
        });

        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }
};
