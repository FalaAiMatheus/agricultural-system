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
        Schema::create('rural_producers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document')->unique('document_idx');
            $table->string('telephone');
            $table->string('email')->unique('email_idx');
            $table->string('address');
            $table->timestamp('registration_date');
            $table->timestamps();

            $table->index('address', 'address_idx');
            $table->index('registration_date', 'registration_date_idx');
            $table->fullText('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rural_producers');
    }
};
