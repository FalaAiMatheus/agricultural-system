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
        Schema::create('production_units', function (Blueprint $table) {
            $table->id();
            $table->string('culture_name');
            $table->decimal('total_area_ha', 12, 4);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();

            $table->index('total_area_ha', 'total_area_ha_idx');
            $table->index('latitude', 'latitude_idx');
            $table->index('longitude', 'longitude_idx');
            $table->fullText('culture_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_units');
    }
};
