<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add guard_name to permissions table if it doesn't exist
        if (!Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('guard_name')->default('web')->after('description');
            });
        }

        // Add guard_name to roles table if it doesn't exist
        if (!Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name')->default('web')->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('guard_name');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('guard_name');
        });
    }
};
