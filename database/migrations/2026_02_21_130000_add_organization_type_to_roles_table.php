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
        // Drop the enum constraint and recreate with all types
        DB::statement("ALTER TABLE roles MODIFY COLUMN type ENUM('admin', 'organization', 'partner_staff', 'player') DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE roles MODIFY COLUMN type ENUM('admin', 'partner_staff', 'player') DEFAULT 'admin'");
    }
};
