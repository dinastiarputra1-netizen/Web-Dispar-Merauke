<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'logo_title')) {
                $table->string('logo_title')->nullable()->default('LOGO PARIWISATA MERAUKE');
                $table->text('logo_subtitle')->nullable();
                $table->longText('logo_description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_title', 'logo_subtitle', 'logo_description']);
        });
    }
};
