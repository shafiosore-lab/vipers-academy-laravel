<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add 'player' to the trial_type enum values.
     */
    public function up(): void
    {
        // Check if we're using MySQL and need to modify enum
        if (DB::getDriverName() === 'mysql') {
            // Get the current enum values
            $tableName = 'users';
            $columnName = 'trial_type';

            // Check if the column exists
            if (Schema::hasColumn($tableName, $columnName)) {
                // MySQL requires rebuilding the column to change enum values
                // First, update any null values to 'general' to avoid issues
                DB::table($tableName)
                    ->whereNull($columnName)
                    ->update([$columnName => 'general']);

                // Get the table prefix
                $prefix = DB::getTablePrefix();

                // Change column to VARCHAR first (to hold temporary values)
                DB::statement("ALTER TABLE {$prefix}{$tableName} MODIFY {$columnName} VARCHAR(50)");

                // Change back to enum with new values
                DB::statement("ALTER TABLE {$prefix}{$tableName} MODIFY {$columnName} ENUM('organization', 'coach', 'team_manager', 'partner', 'player', 'general')");
            }
        }

        // For other databases (SQLite, PostgreSQL), use standard approach
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support enum, so this is typically a text column
            // Just ensure it's nullable
            Schema::table('users', function (Blueprint $table) {
                $table->string('trial_type', 50)->nullable()->change();
            });
        }

        if (DB::getDriverName() === 'pgsql') {
            // PostgreSQL uses CHECK constraints for enum
            // Drop and recreate the check constraint
            Schema::table('users', function (Blueprint $table) {
                $table->string('trial_type', 50)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            $tableName = 'users';
            $columnName = 'trial_type';
            $prefix = DB::getTablePrefix();

            if (Schema::hasColumn($tableName, $columnName)) {
                // Change back to original enum values
                DB::statement("ALTER TABLE {$prefix}{$tableName} MODIFY {$columnName} VARCHAR(50)");
                DB::statement("ALTER TABLE {$prefix}{$tableName} MODIFY {$columnName} ENUM('organization', 'coach', 'team_manager', 'partner', 'general')");
            }
        }
    }
};
