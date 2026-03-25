<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Makes team_id nullable in tournament_teams to allow independent team registrations.
     */
    public function up(): void
    {
        // Check if foreign key exists before dropping
        $foreignKeys = DB::select('SHOW CREATE TABLE tournament_teams');
        $createTable = $foreignKeys[0]->{'Create Table'};

        if (strpos($createTable, 'CONSTRAINT `tournament_teams_team_id_foreign`') !== false) {
            DB::statement('ALTER TABLE tournament_teams DROP FOREIGN KEY tournament_teams_team_id_foreign');
        }

        // Make the column nullable
        DB::statement('ALTER TABLE tournament_teams MODIFY team_id BIGINT UNSIGNED NULL');

        // Add foreign key back as nullable (if teams table exists)
        if (Schema::hasTable('teams')) {
            DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT tournament_teams_team_id_foreign FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the nullable foreign key constraint if exists
        $foreignKeys = DB::select('SHOW CREATE TABLE tournament_teams');
        $createTable = $foreignKeys[0]->{'Create Table'};

        if (strpos($createTable, 'CONSTRAINT `tournament_teams_team_id_foreign`') !== false) {
            DB::statement('ALTER TABLE tournament_teams DROP FOREIGN KEY tournament_teams_team_id_foreign');
        }

        // Make the column required again
        DB::statement('ALTER TABLE tournament_teams MODIFY team_id BIGINT UNSIGNED NOT NULL');

        // Re-add the foreign key as required (if teams table exists)
        if (Schema::hasTable('teams')) {
            DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT tournament_teams_team_id_foreign FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE');
        }
    }
};
