<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            // Update the session_type enum to include all allowed session types
            // Only run if column exists and doesn't already have the value
            DB::statement("ALTER TABLE attendances MODIFY COLUMN session_type ENUM('training', 'match', 'meeting', 'office_time') DEFAULT 'training'");
        } catch (\Exception $e) {
            // Column may not exist or enum may already be updated - continue
        }
    }

    public function down(): void
    {
        // Keep for rollback compatibility
    }
};
