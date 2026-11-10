<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('statistic_categories')
            ->where('slug', 'jasa-perjalanan-wisata')
            ->value('id');

        if ($categoryId !== null) {
            DB::table('tourism_service_statistics')
                ->whereNull('statistic_category_id')
                ->update(['statistic_category_id' => $categoryId]);
        }
    }

    public function down(): void
    {
        $categoryId = DB::table('statistic_categories')
            ->where('slug', 'jasa-perjalanan-wisata')
            ->value('id');

        if ($categoryId !== null) {
            DB::table('tourism_service_statistics')
                ->where('statistic_category_id', $categoryId)
                ->update(['statistic_category_id' => null]);
        }
    }
};
