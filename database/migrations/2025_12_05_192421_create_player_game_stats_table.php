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
        Schema::create('player_game_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('website_players')->onDelete('cascade');
            $table->date('game_date');
            $table->integer('minutes_played')->default(0);
            $table->string('opponent_team');
            $table->string('tournament')->nullable();
            $table->integer('goals_scored')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('shots_on_target')->default(0);
            $table->integer('passes_completed')->default(0);
            $table->integer('tackles')->default(0);
            $table->integer('interceptions')->default(0);
            $table->integer('saves')->default(0);
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->text('game_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_game_stats');
    }
};
