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
    Schema::create('absensi', function (Blueprint $table) {
        $table->id();
        $table->string('id_karyawan');      // dari employees.employee_id
        $table->string('nama_karyawan');    // optional
        $table->date('tanggal');
        $table->string('hari')->nullable();
        $table->time('jam_masuk')->nullable();
        $table->time('jam_keluar')->nullable();
        $table->integer('total_menit')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('absensi');
}
};
