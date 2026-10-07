<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds subscription-based role restrictions to allow granular control over
     * which roles can be assigned based on subscription plan features.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Add minimum required plan level for this role (0 = available to all, higher = premium)
            $table->tinyInteger('min_plan_level')->default(0)->after('type');

            // Add comma-separated plan slugs that can access this role
            $table->text('allowed_plan_slugs')->nullable()->after('min_plan_level');

            // Add comma-separated features required to access this role
            $table->text('required_features')->nullable()->after('allowed_plan_slugs');

            // Add hierarchy level for role comparison (higher = more privileged)
            $table->tinyInteger('level')->default(0)->after('required_features');

            // Add module/category for grouping roles
            $table->string('module')->nullable()->after('level');

            // Add whether role is restricted by subscription
            $table->boolean('is_subscription_restricted')->default(false)->after('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'min_plan_level',
                'allowed_plan_slugs',
                'required_features',
                'level',
                'module',
                'is_subscription_restricted',
            ]);
        });
    }
};
