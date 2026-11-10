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
        Schema::create('creative_economy_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('group_name');
            $table->string('classification', 20);
            $table->string('leader_source')->nullable();
            $table->string('subsector')->nullable();
            $table->string('district')->nullable();
            $table->timestamps();

            $table->unique(['year', 'group_name']);
            $table->index(['year', 'classification']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_economy_groups');
    }
};
