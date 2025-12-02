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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping']);
            $table->decimal('value', 10, 2); // % for percentage, amount for fixed
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('maximum_discount', 10, 2)->nullable(); // Max discount for % coupons
            $table->integer('usage_limit')->nullable(); // NULL = unlimited
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('applicable_products')->nullable(); // Specific product IDs
            $table->json('applicable_categories')->nullable(); // Specific categories
            $table->json('user_restrictions')->nullable(); // Specific user IDs
            $table->boolean('first_time_only')->default(false);
            $table->boolean('auto_apply')->default(false);
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
