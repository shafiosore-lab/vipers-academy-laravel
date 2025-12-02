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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Core document information
            $table->string('document_id')->unique(); // Unique identifier (e.g., "player_code_of_conduct_v1")
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // codes_of_conduct, safety_protection, academy_policies, etc.
            $table->string('subcategory')->nullable(); // For grouping within category

            // File information
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->default('application/pdf');
            $table->integer('file_size')->nullable(); // Size in bytes

            // Localization and versioning
            $table->string('language')->default('en'); // en, sw, etc.
            $table->string('version')->default('1.0');

            // Access control
            $table->boolean('is_mandatory')->default(false);
            $table->json('target_roles'); // ['player', 'parent', 'coach', 'staff']
            $table->boolean('requires_signature')->default(false);

            // Expiration and scheduling
            $table->integer('expiry_days')->nullable(); // Days until document expires
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional structured data
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Indexes (optimized to avoid MySQL key length issues)
            $table->index(['category', 'is_active']);
            $table->index('document_id');
            $table->index('is_mandatory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
