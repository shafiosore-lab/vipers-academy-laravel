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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('age_group');
            $table->text('description');
            $table->text('schedule');
            $table->enum('status', ['active', 'inactive', 'upcoming', 'completed'])->default('active');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_participants')->nullable();
            $table->decimal('program_fee', 10, 2)->nullable();
            $table->text('objectives')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->json('schedule_details')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('regular_fee', 10, 2)->nullable();
            $table->decimal('mumias_fee', 10, 2)->nullable();
            $table->integer('mumias_discount_percentage')->default(50);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};

