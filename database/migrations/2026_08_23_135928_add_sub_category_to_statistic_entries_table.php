<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('statistic_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('statistic_entries', 'sub_category')) {
                $table->string('sub_category')->nullable()->after('statistic_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('statistic_entries', function (Blueprint $table) {
            if (Schema::hasColumn('statistic_entries', 'sub_category')) {
                $table->dropColumn('sub_category');
            }
        });
    }
};
