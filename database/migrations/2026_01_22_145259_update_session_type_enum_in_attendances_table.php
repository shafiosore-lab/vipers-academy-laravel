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
        // Update the session_type enum to include all allowed session types
        DB::statement("ALTER TABLE attendances MODIFY COLUMN session_type ENUM('training', 'match', 'meeting', 'office_time') DEFAULT 'training'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE attendances MODIFY COLUMN session_type ENUM('training', 'match') DEFAULT 'training'");
    }
};
