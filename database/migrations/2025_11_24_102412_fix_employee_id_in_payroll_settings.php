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
            $table->dropForeign('payroll_settings_employee_id_foreign');

            // 2. Drop unique key
            $table->dropUnique('payroll_settings_employee_id_unique');
        });

        // 3. Change column type (must use raw SQL)
        DB::statement('ALTER TABLE payroll_settings MODIFY employee_id VARCHAR(255) NOT NULL');
    }

    public function down()
    {
        // Kembalikan ke bigint
        DB::statement('ALTER TABLE payroll_settings MODIFY employee_id BIGINT UNSIGNED NOT NULL');

        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->unique('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }
};
