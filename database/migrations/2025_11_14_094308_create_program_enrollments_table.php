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
        Schema::create('program_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'inactive', 'completed', 'suspended'])->default('active');
            $table->date('enrollment_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, overdue
            $table->text('notes')->nullable();
            $table->json('enrollment_data')->nullable(); // Store additional enrollment information
            $table->timestamps();

            // Ensure a user can only enroll in a program once
            $table->unique(['user_id', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_enrollments');
    }
};
