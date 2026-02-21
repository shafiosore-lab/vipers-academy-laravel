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
        Schema::create('football_terminologies', function (Blueprint $table) {
            $table->id();
            $table->string('term');           // e.g., "hat-trick"
            $table->string('category');       // e.g., "scoring", "defense", "general"
            $table->text('definition');        // e.g., "Three goals scored by one player"
            $table->string('example')->nullable(); // e.g., "Ronaldo scored a hat-trick"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_terminologies');
    }
};
