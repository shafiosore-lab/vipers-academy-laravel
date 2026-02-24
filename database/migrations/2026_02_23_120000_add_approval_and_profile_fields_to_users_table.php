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
            // Add approval-related fields
            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('users', 'approved_by_user_id')) {
                $table->unsignedBigInteger('approved_by_user_id')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('users', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('approved_by_user_id');
            }

            // Add profile fields
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('approval_notes');
            }

            // Add partner relationship
            if (!Schema::hasColumn('users', 'partner_id')) {
                $table->unsignedBigInteger('partner_id')->nullable()->after('profile_photo');
            }

            // Add last login
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('partner_id');
            }

            // Add foreign key for approved_by_user_id
            if (Schema::hasColumn('users', 'approved_by_user_id')) {
                $table->foreign('approved_by_user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }

            // Add foreign key for partner_id
            if (Schema::hasColumn('users', 'partner_id')) {
                $table->foreign('partner_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
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

            if (Schema::hasColumn('users', 'approved_at')) {
                $columnsToDrop[] = 'approved_at';
            }
            if (Schema::hasColumn('users', 'approved_by_user_id')) {
                $columnsToDrop[] = 'approved_by_user_id';
            }
            if (Schema::hasColumn('users', 'approval_notes')) {
                $columnsToDrop[] = 'approval_notes';
            }
            if (Schema::hasColumn('users', 'profile_photo')) {
                $columnsToDrop[] = 'profile_photo';
            }
            if (Schema::hasColumn('users', 'partner_id')) {
                $columnsToDrop[] = 'partner_id';
            }
            if (Schema::hasColumn('users', 'last_login_at')) {
                $columnsToDrop[] = 'last_login_at';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
