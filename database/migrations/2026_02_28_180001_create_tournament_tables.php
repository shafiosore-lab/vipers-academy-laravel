<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates tournament tables: tournaments, tournament_teams, tournament_squads, tournament_matches
     */
    public function up(): void
    {
        // Tournaments table
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('season')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->integer('squad_limit')->default(25);
            $table->integer('min_players')->default(11);
            $table->integer('max_teams')->nullable();
            $table->enum('status', ['draft', 'open', 'closed', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('venue')->nullable();
            $table->text('rules')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Add foreign key after table creation
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['organization_id', 'status']);
            $table->index('slug');
        });

        // Tournament Teams table (teams registered in a tournament)
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('team_name')->nullable();
            $table->string('team_contact_name')->nullable();
            $table->string('team_contact_email')->nullable();
            $table->string('team_contact_phone')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'correction_requested'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('correction_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('registration_date')->nullable();
            $table->timestamps();

            $table->unique(['tournament_id', 'team_id']);
            $table->index(['tournament_id', 'approval_status']);
        });

        // Tournament Squads table (players registered for a specific tournament)
        Schema::create('tournament_squads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_team_id')->constrained('tournament_teams')->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->integer('jersey_number')->nullable();
            $table->string('position')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('registration_date')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->unique(['tournament_team_id', 'player_id']);
            $table->index(['tournament_team_id', 'verification_status']);
        });

        // Tournament Matches table
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('home_team_id')->constrained('tournament_teams')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('tournament_teams')->onDelete('cascade');
            $table->string('venue')->nullable();
            $table->dateTime('kickoff_time')->nullable();
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'postponed', 'cancelled'])->default('scheduled');
            $table->string('match_day')->nullable();
            $table->integer('round')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['tournament_id', 'status']);
            $table->index(['tournament_id', 'match_day']);
            $table->index(['home_team_id']);
            $table->index(['away_team_id']);
        });

        // Tournament Standings table
        Schema::create('tournament_standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_team_id')->constrained('tournament_teams')->onDelete('cascade');
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('goals_for')->default(0);
            $table->integer('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->integer('points')->default(0);
            $table->integer('position')->nullable();
            $table->timestamps();

            $table->unique(['tournament_id', 'tournament_team_id']);
            $table->index(['tournament_id', 'points']);
            $table->index(['tournament_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_standings');
        Schema::dropIfExists('tournament_matches');
        Schema::dropIfExists('tournament_squads');
        Schema::dropIfExists('tournament_teams');
        Schema::dropIfExists('tournaments');
    }
};
