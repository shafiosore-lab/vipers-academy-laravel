<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Premium Players", "Standard Players"
            $table->string('slug')->unique(); // e.g., "premium", "standard"
            $table->text('description')->nullable();
            $table->decimal('monthly_amount', 10, 2);
            $table->decimal('joining_fee', 10, 2);
            $table->integer('payment_interval_days')->default(30); // Monthly = 30 days
            $table->integer('grace_period_days')->default(7); // Days after due date before overdue
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_categories');
    }
};
