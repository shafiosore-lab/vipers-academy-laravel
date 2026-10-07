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
        Schema::table('players', function (Blueprint $table) {
            $table->string('parent_guardian_name')->nullable()->after('email');
            $table->string('parent_phone')->nullable()->after('parent_guardian_name');
            $table->json('training_days')->nullable()->after('parent_phone'); // e.g., ["monday", "wednesday"]
            $table->decimal('monthly_contribution', 10, 2)->nullable()->after('training_days');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('monthly_contribution');
            $table->enum('fee_category', ['A', 'B'])->nullable()->after('status'); // A = 200, B = 500
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['parent_guardian_name', 'parent_phone', 'training_days', 'monthly_contribution', 'status', 'fee_category']);
        });
    }
};

