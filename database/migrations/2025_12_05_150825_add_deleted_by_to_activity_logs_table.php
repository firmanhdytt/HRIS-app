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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Tambah kolom deleted_by (JSON) jika belum ada
            if (!Schema::hasColumn('activity_logs', 'deleted_by')) {
                $table->json('deleted_by')->nullable()->after('read_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'deleted_by')) {
                $table->dropColumn('deleted_by');
            }
        });
    }
};
