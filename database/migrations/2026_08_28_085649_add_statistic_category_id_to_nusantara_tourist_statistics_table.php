<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nusantara_tourist_statistics', function (Blueprint $table) {
            $table->foreignId('statistic_category_id')
                ->nullable()
                ->after('id')
                ->constrained('statistic_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('nusantara_tourist_statistics', function (Blueprint $table) {
            $table->dropForeign(['statistic_category_id']);
            $table->dropColumn('statistic_category_id');
        });
    }
};
