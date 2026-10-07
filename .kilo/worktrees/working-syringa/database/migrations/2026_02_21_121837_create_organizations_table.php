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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'trial', 'pending'])->default('pending');
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->dateTime('trial_ends_at')->nullable();
            $table->dateTime('subscription_ends_at')->nullable();
            $table->integer('max_users')->nullable();
            $table->integer('max_players')->nullable();
            $table->string('billing_cycle')->default('monthly');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
