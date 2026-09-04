<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->integer('total_jam')->nullable()->after('jam_keluar');
            $table->dropColumn('total_menit');
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->integer('total_menit')->nullable();
            $table->dropColumn('total_jam');
        });
    }
};
