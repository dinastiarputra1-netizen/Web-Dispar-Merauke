<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'nama_daerah')) {
                $table->string('nama_daerah')->nullable()->default('Kabupaten Merauke')->after('nama_instansi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'nama_daerah')) {
                $table->dropColumn('nama_daerah');
            }
        });
    }
};
