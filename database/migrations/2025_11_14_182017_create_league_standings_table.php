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
        Schema::create('league_standings', function (Blueprint $table) {
            $table->id();
            $table->string('season', 20); // e.g., "2024/25"
            $table->string('league_name');
            $table->string('team_name');
            $table->string('team_logo')->nullable();
            $table->integer('position')->default(0);
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('goals_for')->default(0);
            $table->integer('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->integer('points')->default(0);
            $table->integer('home_wins')->default(0);
            $table->integer('home_draws')->default(0);
            $table->integer('home_losses')->default(0);
            $table->integer('away_wins')->default(0);
            $table->integer('away_draws')->default(0);
            $table->integer('away_losses')->default(0);
            $table->integer('clean_sheets')->default(0);
            $table->integer('failed_to_score')->default(0);
            $table->decimal('points_per_game', 4, 2)->default(0);
            $table->string('form')->nullable(); // e.g., "WWDWL" (last 5 matches)
            $table->string('status')->default('active'); // active, relegated, promoted, etc.
            $table->text('notes')->nullable();
            $table->boolean('is_vipers_team')->default(false);
            $table->timestamps();

            $table->index(['season', 'league_name']);
            $table->index(['season', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_standings');
    }
};
