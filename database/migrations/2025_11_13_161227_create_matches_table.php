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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['friendly', 'tournament', 'league', 'cup']);
            $table->string('opponent');
            $table->dateTime('match_date');
            $table->string('venue');
            $table->enum('status', ['upcoming', 'completed', 'planned', 'cancelled']);
            $table->integer('vipers_score')->nullable();
            $table->integer('opponent_score')->nullable();
            $table->text('description')->nullable();
            $table->string('tournament_name')->nullable();
            $table->json('images')->nullable();
            $table->string('live_link')->nullable();
            $table->string('highlights_link')->nullable();
            $table->text('match_summary')->nullable();
            $table->boolean('registration_open')->default(false);
            $table->date('registration_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
