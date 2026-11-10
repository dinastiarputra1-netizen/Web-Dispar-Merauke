<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nusantara_tourist_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->unique();
            $table->integer('q1')->default(0); // Triwulan I
            $table->integer('q2')->default(0); // Triwulan II
            $table->integer('q3')->default(0); // Triwulan III
            $table->integer('q4')->default(0); // Triwulan IV
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nusantara_tourist_statistics');
    }
};
