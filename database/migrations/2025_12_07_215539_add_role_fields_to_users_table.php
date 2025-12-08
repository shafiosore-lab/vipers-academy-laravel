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
            // Profile image for all users
            $table->string('profile_image')->nullable()->after('partner_details');

            // Approval workflow fields
            $table->timestamp('approved_at')->nullable()->after('profile_image');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->text('approval_notes')->nullable()->after('approved_by');

            // For partner staff accounts - reference to the partner who created them
            $table->unsignedBigInteger('created_by_partner_id')->nullable()->after('approval_notes');

            // Foreign key constraint
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_partner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by_partner_id']);
            $table->dropColumn(['profile_image', 'approved_at', 'approved_by', 'approval_notes', 'created_by_partner_id']);
        });
    }
};
