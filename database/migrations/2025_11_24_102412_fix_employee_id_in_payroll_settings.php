<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            // 1. Drop FK
            $table->dropForeign(['employee_id']);

            // 2. Drop unique key
            $table->dropUnique(['employee_id']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            // 3. Change column type (must use raw SQL)
            DB::statement('ALTER TABLE payroll_settings MODIFY employee_id VARCHAR(255) NOT NULL');
        }
    }

    public function down()
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Kembalikan ke bigint
            DB::statement('ALTER TABLE payroll_settings MODIFY employee_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->unique('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }
};
