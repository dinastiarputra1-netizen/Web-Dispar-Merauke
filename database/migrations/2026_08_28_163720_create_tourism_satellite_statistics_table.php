<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tourism_satellite_statistics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('statistic_category_id')->nullable()->constrained('statistic_categories')->nullOnDelete();
            $table->integer('year');
            $table->bigInteger('q1')->default(0);
            $table->bigInteger('q2')->default(0);
            $table->bigInteger('q3')->default(0);
            $table->bigInteger('q4')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourism_satellite_statistics');
    }
};
