<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->text('address')->nullable();
            $table->enum('business_type', [
                'Restaurant/Rumah Makan',
                'Jasa Boga',
                'Cafe',
                'Bar',
                'Karaoke',
                'Kelab Malam',
            ])->default('Restaurant/Rumah Makan');
            $table->string('license_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
