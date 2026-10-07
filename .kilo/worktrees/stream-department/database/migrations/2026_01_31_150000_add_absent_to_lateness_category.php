<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            // Add 'absent' to the lateness_category enum values
            DB::statement("ALTER TABLE attendances MODIFY lateness_category ENUM('on_time', 'late', 'very_late', 'absent') DEFAULT 'on_time'");
        } catch (\Exception $e) {
            // Column may not exist or enum may already be updated
        }
    }

    public function down(): void
    {
        // Keep for rollback compatibility
    }
};
