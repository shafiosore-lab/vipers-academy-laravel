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
        // First rename the table
        Schema::rename('website_players', 'website_uploaded_players');

        Schema::table('website_uploaded_players', function (Blueprint $table) {
            // Add missing fields
            $table->string('email', 150)->nullable()->after('last_name');
            $table->string('phone', 50)->nullable()->after('email');
            $table->boolean('auto_account_created')->default(false)->after('phone');
            $table->unsignedBigInteger('user_id')->nullable()->after('auto_account_created');

            // Add foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Remove fields not in the required schema
            $table->dropColumn(['category', 'age', 'jersey_number', 'goals', 'assists', 'appearances']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_uploaded_players', function (Blueprint $table) {
            // Reverse the changes
            $table->dropForeign(['user_id']);
            $table->dropColumn(['email', 'phone', 'auto_account_created', 'user_id']);

            // Add back the removed columns
            $table->string('category')->after('position');
            $table->integer('age')->after('category');
            $table->string('jersey_number')->nullable()->after('image_path');
            $table->integer('goals')->default(0)->after('bio');
            $table->integer('assists')->default(0)->after('goals');
            $table->integer('appearances')->default(0)->after('assists');
        });

        // Rename table back
        Schema::rename('website_uploaded_players', 'website_players');
    }
};
