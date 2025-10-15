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
        Schema::table('production_units', function (Blueprint $table) {
            $table->foreignId('property_id')->after('culture_name')->constrained('properties')->onDelete('cascade');
        });
    }
};
