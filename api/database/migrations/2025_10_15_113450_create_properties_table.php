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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('municipality');
            $table->string('uf');
            $table->integer('state_registration')->unique('state_registration_idx');
            $table->float('total_area');
            $table->foreignId('producer_id')->constrained('rural_producers')->onDelete('cascade');
            $table->timestamps();

            $table->index('total_area', 'total_area_idx');
            $table->index('municipality', 'municipality_idx');
            $table->index('uf', 'uf_idx');
            $table->fullText('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
