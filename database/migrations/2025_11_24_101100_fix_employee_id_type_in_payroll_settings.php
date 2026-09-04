<?php

//untuk rubah format kolom

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
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->string('employee_id')->change();
        });
    }

    public function down()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->integer('employee_id')->change();
        });
    }
};
