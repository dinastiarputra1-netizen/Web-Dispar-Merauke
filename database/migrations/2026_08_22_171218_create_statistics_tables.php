<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistic_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Wisatawan Nusantara, Devisa Pariwisata
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('statistic_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statistic_category_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('month')->nullable(); // 1-12 (opsional jika data bulanan)
            $table->string('indicator_label');  // e.g., "Jumlah Kunjungan", "Okupansi Kamar"
            $table->decimal('value', 15, 2);
            $table->string('unit')->nullable();   // e.g., "Orang", "Rupiah", "%"
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistic_entries');
        Schema::dropIfExists('statistic_categories');
    }
};
