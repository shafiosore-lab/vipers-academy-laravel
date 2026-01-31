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
            // Update user_type enum to include 'staff'
            $table->enum('user_type', ['admin', 'partner', 'staff', 'player'])->default('player')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->dropForeign(['approved_by_user_id']);
            $table->dropForeign(['partner_id']);

            $table->renameColumn('approved_by_user_id', 'approved_by');
            $table->renameColumn('partner_id', 'created_by_partner_id');
            $table->renameColumn('profile_photo', 'profile_image');

            $table->dropColumn(['first_name', 'last_name', 'phone', 'last_login']);

            // Recreate old foreign keys
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_partner_id')->references('id')->on('users')->onDelete('set null');

            // Revert user_type enum
            $table->enum('user_type', ['admin', 'player', 'partner'])->default('player')->change();

            // Revert approval_status to status
            $table->dropColumn('approval_status');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('pending')->after('user_type');

            // Recreate player_id
            $table->foreignId('player_id')->nullable()->constrained('players')->onDelete('cascade')->after('status');
        });
    }
};
