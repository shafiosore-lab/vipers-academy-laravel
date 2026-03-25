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
        Schema::create('website_uploaded_players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('category')->nullable();
            $table->string('position');
            $table->integer('age')->nullable();
            $table->string('image_path')->nullable();
            $table->string('jersey_number')->nullable();
            $table->text('bio')->nullable();
            $table->integer('goals')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('appearances')->default(0);
            $table->boolean('auto_account_created')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_uploaded_players');
    }
};
