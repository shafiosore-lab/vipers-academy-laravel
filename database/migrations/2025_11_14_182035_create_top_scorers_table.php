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
        Schema::create('top_scorers', function (Blueprint $table) {
            $table->id();
            $table->string('season', 20); // e.g., "2024/25"
            $table->string('league_name');
            $table->string('player_name');
            $table->string('player_image')->nullable();
            $table->string('team_name');
            $table->string('team_logo')->nullable();
            $table->integer('ranking_position')->default(0);
            $table->integer('goals')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('appearances')->default(0);
            $table->decimal('goals_per_game', 4, 2)->default(0);
            $table->integer('minutes_played')->default(0);
            $table->integer('shots_on_target')->default(0);
            $table->integer('shots_total')->default(0);
            $table->decimal('shot_accuracy', 5, 2)->nullable(); // percentage
            $table->integer('penalties_scored')->default(0);
            $table->integer('penalties_missed')->default(0);
            $table->integer('free_kicks')->default(0);
            $table->integer('headers')->default(0);
            $table->integer('left_foot')->default(0);
            $table->integer('right_foot')->default(0);
            $table->integer('inside_box')->default(0);
            $table->integer('outside_box')->default(0);
            $table->string('nationality')->nullable();
            $table->integer('age')->nullable();
            $table->string('player_position')->default('Forward'); // Forward, Midfielder, etc.
            $table->boolean('is_vipers_player')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['season', 'league_name']);
            $table->index(['season', 'goals']);
            $table->index(['season', 'ranking_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_scorers');
    }
};
