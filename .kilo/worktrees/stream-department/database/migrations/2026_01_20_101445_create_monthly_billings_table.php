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
        Schema::create('monthly_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->string('month_year', 7); // YYYY-MM format
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('monthly_fee', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->default(0);
            $table->decimal('balance_carried_forward', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['player_id', 'month_year'], 'unique_player_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_billings');
    }
};

