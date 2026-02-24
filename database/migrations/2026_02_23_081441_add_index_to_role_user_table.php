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
        Schema::table('role_user', function (Blueprint $table) {
            // Add index on user_id for faster role lookups
            $table->index('user_id', 'role_user_user_id_index');

            // Add index on role_id for faster permission checks
            $table->index('role_id', 'role_user_role_id_index');
        });

        // Also add index on roles table for slug lookups (used in hasRole checks)
        Schema::table('roles', function (Blueprint $table) {
            // Check if index doesn't already exist
            if (!Schema::hasIndex('roles', 'roles_slug_index')) {
                $table->index('slug', 'roles_slug_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropIndex('role_user_user_id_index');
            $table->dropIndex('role_user_role_id_index');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex('roles_slug_index');
        });
    }
};
