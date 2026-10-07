<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration fixes the attendances table after failed migration runs
     */
    public function up(): void
    {
        // Get current table structure
        $columns = DB::getSchemaBuilder()->getColumnListing('attendances');

        // Add session_id column if missing
        if (!in_array('session_id', $columns)) {
            DB::statement('ALTER TABLE attendances ADD COLUMN session_id BIGINT UNSIGNED NULL AFTER id');
        }

        // Add trained_minutes column if missing
        if (!in_array('trained_minutes', $columns)) {
            DB::statement('ALTER TABLE attendances ADD COLUMN trained_minutes INT NULL AFTER total_duration_minutes');
        }

        // Add missed_minutes column if missing
        if (!in_array('missed_minutes', $columns)) {
            DB::statement('ALTER TABLE attendances ADD COLUMN missed_minutes INT DEFAULT 0 AFTER trained_minutes');
        }

        // Add lateness_category column if missing
        if (!in_array('lateness_category', $columns)) {
            DB::statement("ALTER TABLE attendances ADD COLUMN lateness_category ENUM('on_time', 'late', 'very_late', 'absent') DEFAULT 'on_time' AFTER missed_minutes");
        }

        // Try to drop the old unique constraint if it exists
        try {
            DB::statement('ALTER TABLE attendances DROP INDEX unique_player_session_date');
        } catch (\Exception $e) {
            // Index may not exist or has different name
        }

        // Try to add new unique constraint
        try {
            DB::statement('ALTER TABLE attendances ADD UNIQUE KEY unique_player_session (player_id, session_id)');
        } catch (\Exception $e) {
            // Unique key may already exist
        }

        // Add foreign key for player_id if not exists
        try {
            DB::statement('ALTER TABLE attendances ADD CONSTRAINT attendances_player_id_foreign FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key may already exist
        }

        // Add foreign key for session_id if not exists
        try {
            DB::statement('ALTER TABLE attendances ADD CONSTRAINT attendances_session_id_foreign FOREIGN KEY (session_id) REFERENCES training_sessions (id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key may already exist
        }

        // Add index for session_id and player_id
        try {
            DB::statement('ALTER TABLE attendances ADD INDEX idx_session_player (session_id, player_id)');
        } catch (\Exception $e) {
            // Index may already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed - this is a fix migration
    }
};
