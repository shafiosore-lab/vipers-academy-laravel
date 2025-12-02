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
        Schema::table('players', function (Blueprint $table) {
            // Only add fields that are definitely new and not in previous migrations
            $table->decimal('academic_gpa', 3, 2)->nullable();
            $table->text('academic_notes')->nullable();
            $table->integer('matches_played')->default(0);
            $table->integer('goals_scored')->default(0);
            $table->integer('assists')->default(0);
            $table->decimal('performance_rating', 3, 2)->nullable();
            $table->text('performance_notes')->nullable();
            $table->date('last_follow_up')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->boolean('needs_attention')->default(false);
            $table->string('attention_reason')->nullable();
            $table->string('development_stage')->nullable();
            $table->boolean('international_eligible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'academic_gpa',
                'academic_notes',
                'matches_played',
                'goals_scored',
                'assists',
                'performance_rating',
                'performance_notes',
                'last_follow_up',
                'follow_up_notes',
                'needs_attention',
                'attention_reason',
                'development_stage',
                'international_eligible'
            ]);
        });
    }
};
