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
        Schema::table('users', function (Blueprint $table) {
            // Add approval status column if it doesn't exist
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('user_type');
            }

            // Add first name and last name columns if they don't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }

            // Add phone and last login columns if they don't exist
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'approval_status')) {
                $columnsToDrop[] = 'approval_status';
            }
            if (Schema::hasColumn('users', 'first_name')) {
                $columnsToDrop[] = 'first_name';
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $columnsToDrop[] = 'last_name';
            }
            if (Schema::hasColumn('users', 'phone')) {
                $columnsToDrop[] = 'phone';
            }
            if (Schema::hasColumn('users', 'last_login')) {
                $columnsToDrop[] = 'last_login';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
