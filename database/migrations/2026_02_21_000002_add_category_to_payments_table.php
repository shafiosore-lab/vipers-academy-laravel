<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_category_id')->nullable()->constrained('payment_categories')->onDelete('set null');
            $table->date('next_due_date')->nullable()->after('due_date');
            $table->boolean('is_joining_fee_paid')->default(false)->after('payment_status');
            $table->string('category_slug')->nullable()->after('payment_category_id');
        });

        // Add category to players table for direct assignment
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('payment_category_id')->nullable()->constrained('payment_categories')->onDelete('set null');
            $table->date('category_effective_date')->nullable()->after('payment_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_category_id']);
            $table->dropColumn(['payment_category_id', 'next_due_date', 'is_joining_fee_paid', 'category_slug']);
        });

        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['payment_category_id']);
            $table->dropColumn(['payment_category_id', 'category_effective_date']);
        });
    }
};
