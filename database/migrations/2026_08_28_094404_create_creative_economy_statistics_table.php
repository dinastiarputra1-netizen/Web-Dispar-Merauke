<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creative_economy_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statistic_category_id')
                ->nullable()
                ->constrained('statistic_categories')
                ->nullOnDelete();
            $table->string('sub_category')->nullable(); // Contoh: Kuliner Khas, Kriya, Fesyen
            $table->integer('year');
            $table->integer('month')->nullable();
            $table->string('indicator_label'); // Contoh: Pendapatan Omset, Jumlah Usaha
            $table->decimal('value', 15, 2)->default(0);
            $table->string('unit')->default('Unit'); // Contoh: Rupiah, Orang, Unit
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creative_economy_statistics');
    }
};
