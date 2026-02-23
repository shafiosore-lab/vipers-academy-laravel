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
        Schema::create('subscription_plan_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained('subscription_plans')
                ->onDelete('cascade');
            $table->foreignId('permission_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('category')->nullable();
            $table->boolean('is_allowed')->default(true);
            $table->timestamps();

            $table->unique(['plan_id', 'permission_id'], 'plan_permission_unique');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plan_permissions');
    }
};
