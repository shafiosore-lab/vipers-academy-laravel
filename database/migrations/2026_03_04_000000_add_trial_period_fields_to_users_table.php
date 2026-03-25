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
            // Trial period fields
            $table->boolean('is_on_trial')->default(false)->after('status');
            $table->timestamp('trial_ends_at')->nullable()->after('is_on_trial');
            $table->enum('trial_type', ['organization', 'coach', 'team_manager', 'partner', 'general'])->nullable()->after('trial_ends_at');
            $table->boolean('trial_auto_activated')->default(false)->after('trial_type');

            // Override approval_status default for trial users (auto-approve)
            // The approval_status column already exists, we're just ensuring it's nullable for auto-approval
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_on_trial',
                'trial_ends_at',
                'trial_type',
                'trial_auto_activated',
            ]);
        });
    }
};
