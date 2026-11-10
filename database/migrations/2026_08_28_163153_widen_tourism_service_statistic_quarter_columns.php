<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tourism_service_statistics', function (Blueprint $table): void {
            $table->bigInteger('q1')->default(0)->change();
            $table->bigInteger('q2')->default(0)->change();
            $table->bigInteger('q3')->default(0)->change();
            $table->bigInteger('q4')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourism_service_statistics', function (Blueprint $table): void {
            $table->integer('q1')->default(0)->change();
            $table->integer('q2')->default(0)->change();
            $table->integer('q3')->default(0)->change();
            $table->integer('q4')->default(0)->change();
        });
    }
};
