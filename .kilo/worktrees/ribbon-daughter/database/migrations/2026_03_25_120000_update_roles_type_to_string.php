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
        Schema::table('roles', function (Blueprint $table) {
            // Drop the enum constraint and change to string
            // MySQL doesn't support altering enum values directly, so we convert to string
            $table->string('type', 50)->default('admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Converting back to enum would require recreating table
        // This is a one-way migration for flexibility
    }
};
