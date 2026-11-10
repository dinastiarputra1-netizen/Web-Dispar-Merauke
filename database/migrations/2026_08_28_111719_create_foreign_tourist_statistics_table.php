<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foreign_tourist_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statistic_category_id')
                ->nullable()
                ->constrained('statistic_categories')
                ->nullOnDelete();
            $table->integer('year');
            $table->integer('q1')->default(0);
            $table->integer('q2')->default(0);
            $table->integer('q3')->default(0);
            $table->integer('q4')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foreign_tourist_statistics');
    }
};
