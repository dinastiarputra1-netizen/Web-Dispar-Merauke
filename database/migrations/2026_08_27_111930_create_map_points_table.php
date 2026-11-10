<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_points', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nama Titik / Destinasi / Distrik
            $table->string('distrik')->nullable(); // Nama Distrik (misal: Distrik Merauke, Okaba, Sota, dll)
            $table->string('kategori')->default('Wisata'); // Wisata, Kuliner, Event, Akomodasi
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('link_postingan')->nullable(); // URL target saat pin diklik
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_points');
    }
};
