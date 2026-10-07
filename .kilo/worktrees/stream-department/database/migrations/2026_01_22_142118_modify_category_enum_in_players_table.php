<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change the column to VARCHAR to allow updates
        DB::statement("ALTER TABLE players MODIFY COLUMN category VARCHAR(255)");

        // Map existing category values to the new format
        DB::statement("UPDATE players SET category = 'u13' WHERE category = 'under-13'");
        DB::statement("UPDATE players SET category = 'u15' WHERE category = 'under-15'");
        DB::statement("UPDATE players SET category = 'u17' WHERE category = 'under-17'");
        // 'senior' stays as 'senior'

        // Then change back to ENUM with the correct values
        DB::statement("ALTER TABLE players MODIFY COLUMN category ENUM('u9', 'u11', 'u13', 'u15', 'u17', 'u19', 'senior', 'academy')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum values
        DB::statement("ALTER TABLE players MODIFY COLUMN category ENUM('under-13', 'under-15', 'under-17', 'senior')");
    }
};
