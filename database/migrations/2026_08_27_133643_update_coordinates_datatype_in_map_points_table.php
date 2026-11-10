<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('map_points', function (Blueprint $table) {
            $table->decimal('latitude', 8, 2)->change();
            $table->decimal('longitude', 8, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('map_points', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->change();
            $table->decimal('longitude', 11, 8)->change();
        });
    }
};
