<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * This migration is deprecated - functionality moved to 2026_02_19_000000_fix_attendances_session_fields.php
 * The attendances table changes are now handled by the fix migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        // This migration is now handled by 2026_02_19_000000_fix_attendances_session_fields.php
        // No action needed - columns and constraints are managed by the fix migration
    }

    public function down(): void
    {
        // No rollback - let the fix migration handle it
    }
};
