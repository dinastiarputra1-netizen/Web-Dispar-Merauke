<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tourism_foreign_exchange_statistics')) {
            Schema::create('tourism_foreign_exchange_statistics', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('statistic_category_id')->nullable();
                $table->integer('year');
                $table->bigInteger('q1')->default(0);
                $table->bigInteger('q2')->default(0);
                $table->bigInteger('q3')->default(0);
                $table->bigInteger('q4')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('tourism_foreign_exchange_statistics', function (Blueprint $table): void {
            $table->foreign('statistic_category_id', 'tfe_stats_category_fk')
                ->references('id')
                ->on('statistic_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourism_foreign_exchange_statistics');
    }
};
