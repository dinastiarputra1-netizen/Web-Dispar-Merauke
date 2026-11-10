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
        Schema::create('ekonomi_kreatifs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->enum('kategori_oap', ['OAP', 'NON OAP'])->default('OAP');
            $table->string('ketua')->nullable(); // Ketua / Sumber
            $table->string('subsektor'); // Kriya, Kuliner, Musik, dll.
            $table->string('distrik')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekonomi_kreatifs');
    }
};
