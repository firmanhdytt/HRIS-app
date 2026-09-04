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
        Schema::create('jadwal_piket', function (Blueprint $table) {
            $table->id();
            $table->string('hari');              // Senin–Sabtu
            $table->string('employee_id');       // EMP001
            $table->string('employee_name');     // Nama karyawan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_piket');
    }
};
