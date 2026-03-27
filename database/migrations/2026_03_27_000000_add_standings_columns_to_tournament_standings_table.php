<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds missing columns to tournament_standings table for enhanced statistics tracking.
     */
    public function up(): void
    {
        Schema::table('tournament_standings', function (Blueprint $table) {
            // Disciplinary columns
            $table->integer('yellow_cards')->default(0)->after('points');
            $table->integer('red_cards')->default(0)->after('yellow_cards');
            $table->integer('clean_sheets')->default(0)->after('red_cards');

            // Home performance columns
            $table->integer('home_played')->default(0)->after('clean_sheets');
            $table->integer('home_won')->default(0)->after('home_played');
            $table->integer('home_drawn')->default(0)->after('home_won');
            $table->integer('home_lost')->default(0)->after('home_drawn');
            $table->integer('home_goals_for')->default(0)->after('home_lost');
            $table->integer('home_goals_against')->default(0)->after('home_goals_for');

            // Away performance columns
            $table->integer('away_played')->default(0)->after('home_goals_against');
            $table->integer('away_won')->default(0)->after('away_played');
            $table->integer('away_drawn')->default(0)->after('away_won');
            $table->integer('away_lost')->default(0)->after('away_drawn');
            $table->integer('away_goals_for')->default(0)->after('away_lost');
            $table->integer('away_goals_against')->default(0)->after('away_goals_for');

            // Form column (JSON array for recent match results)
            $table->json('form')->nullable()->after('away_goals_against');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_standings', function (Blueprint $table) {
            $table->dropColumn([
                'yellow_cards',
                'red_cards',
                'clean_sheets',
                'home_played',
                'home_won',
                'home_drawn',
                'home_lost',
                'home_goals_for',
                'home_goals_against',
                'away_played',
                'away_won',
                'away_drawn',
                'away_lost',
                'away_goals_for',
                'away_goals_against',
                'form',
            ]);
        });
    }
};
