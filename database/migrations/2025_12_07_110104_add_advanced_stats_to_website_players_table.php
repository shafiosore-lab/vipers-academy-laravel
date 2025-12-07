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
        Schema::table('website_players', function (Blueprint $table) {
            // Advanced position-specific stats
            $table->integer('saves')->default(0)->after('red_cards');
            $table->integer('clean_sheets')->default(0)->after('saves');
            $table->integer('goals_conceded')->default(0)->after('clean_sheets');
            $table->decimal('save_percentage', 5, 2)->default(0)->after('goals_conceded');
            $table->decimal('distribution_accuracy', 5, 2)->default(0)->after('save_percentage');
            $table->integer('aerial_duels_won')->default(0)->after('distribution_accuracy');
            $table->integer('clearances')->default(0)->after('aerial_duels_won');
            $table->integer('blocks')->default(0)->after('clearances');
            $table->integer('tackles_won')->default(0)->after('blocks');
            $table->integer('interceptions')->default(0)->after('tackles_won');
            $table->decimal('passing_accuracy', 5, 2)->default(0)->after('interceptions');
            $table->integer('ball_recoveries')->default(0)->after('passing_accuracy');
            $table->integer('crosses_attempted')->default(0)->after('ball_recoveries');
            $table->decimal('cross_accuracy', 5, 2)->default(0)->after('crosses_attempted');
            $table->integer('key_passes')->default(0)->after('cross_accuracy');
            $table->integer('dribbles_completed')->default(0)->after('key_passes');
            $table->integer('progressive_runs')->default(0)->after('dribbles_completed');
            $table->integer('defensive_duels_won')->default(0)->after('progressive_runs');
            $table->integer('progressive_passes')->default(0)->after('defensive_duels_won');
            $table->integer('duels_won')->default(0)->after('progressive_passes');
            $table->decimal('expected_assists', 5, 2)->default(0)->after('duels_won');
            $table->integer('shots')->default(0)->after('expected_assists');
            $table->integer('ball_progressions')->default(0)->after('shots');
            $table->decimal('expected_goals', 5, 2)->default(0)->after('ball_progressions');
            $table->integer('chances_created')->default(0)->after('expected_goals');
            $table->integer('through_balls')->default(0)->after('chances_created');
            $table->integer('passes_into_final_third')->default(0)->after('through_balls');
            $table->decimal('shot_conversion_rate', 5, 2)->default(0)->after('passes_into_final_third');
            $table->integer('touches_in_box')->default(0)->after('shot_conversion_rate');
            $table->integer('big_chances_scored')->default(0)->after('touches_in_box');
            $table->integer('big_chances_missed')->default(0)->after('big_chances_scored');
            $table->decimal('hold_up_play_success', 5, 2)->default(0)->after('big_chances_missed');
            $table->integer('chance_creation')->default(0)->after('hold_up_play_success');

            // Skills radar attributes (0-100 scale)
            $table->integer('shot_stopping')->default(0)->after('chance_creation');
            $table->integer('distribution')->default(0)->after('shot_stopping');
            $table->integer('command_area')->default(0)->after('distribution');
            $table->integer('handling')->default(0)->after('command_area');
            $table->integer('strength')->default(0)->after('handling');
            $table->integer('stamina')->default(0)->after('strength');
            $table->integer('vision')->default(0)->after('stamina');
            $table->integer('decisions')->default(0)->after('vision');
            $table->integer('technique')->default(0)->after('decisions');
            $table->integer('flair')->default(0)->after('technique');
            $table->integer('balance')->default(0)->after('flair');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_players', function (Blueprint $table) {
            // Drop advanced stats columns
            $table->dropColumn([
                'saves', 'clean_sheets', 'goals_conceded', 'save_percentage', 'distribution_accuracy',
                'aerial_duels_won', 'clearances', 'blocks', 'tackles_won', 'interceptions',
                'passing_accuracy', 'ball_recoveries', 'crosses_attempted', 'cross_accuracy',
                'key_passes', 'dribbles_completed', 'progressive_runs', 'defensive_duels_won',
                'progressive_passes', 'duels_won', 'expected_assists', 'shots', 'ball_progressions',
                'expected_goals', 'chances_created', 'through_balls', 'passes_into_final_third',
                'shot_conversion_rate', 'touches_in_box', 'big_chances_scored', 'big_chances_missed',
                'hold_up_play_success', 'chance_creation',
                // Skills radar attributes
                'shot_stopping', 'distribution', 'command_area', 'handling', 'strength',
                'stamina', 'vision', 'decisions', 'technique', 'flair', 'balance'
            ]);
        });
    }
};
