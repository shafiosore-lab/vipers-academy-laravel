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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference')->unique();
            $table->string('transaction_id')->nullable()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payer_type', ['player', 'partner', 'customer']); // Who is making the payment
            $table->unsignedBigInteger('payer_id'); // ID of the player/partner/customer
            $table->enum('payment_type', [
                'registration_fee',
                'subscription_fee',
                'program_fee',
                'tournament_fee',
                'merchandise',
                'donation',
                'sponsorship',
                'other'
            ]);
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('KES');
            $table->enum('payment_method', ['mpesa', 'card', 'bank_transfer', 'cash', 'cheque'])->default('mpesa');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->enum('payment_gateway', ['mpesa', 'stripe', 'paypal', 'bank', 'cash'])->nullable();
            $table->json('gateway_response')->nullable(); // Store gateway response data
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['payment_status', 'payment_type']);
            $table->index(['payer_type', 'payer_id']);
            $table->index(['created_at']);
            $table->index(['paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
