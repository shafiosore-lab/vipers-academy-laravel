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
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('document_id')->constrained()->onDelete('cascade');

            // Status tracking
            $table->enum('status', ['pending', 'viewed', 'downloaded', 'signed', 'expired', 'rejected'])->default('pending');

            // Timestamps for tracking user interactions
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Document expiry for this user

            // Usage tracking
            $table->integer('download_count')->default(0);
            $table->boolean('is_mandatory_for_user')->default(false); // User-specific mandatory status

            // Signature information
            $table->string('signature_path')->nullable(); // Path to electronic signature if applicable

            // Additional data
            $table->json('user_metadata')->nullable(); // Additional user-specific data
            $table->text('notes')->nullable(); // Admin notes or user comments

            $table->timestamps();

            // Constraints and indexes
            $table->unique(['user_id', 'document_id']); // One record per user-document
            $table->index(['user_id', 'status']);
            $table->index(['document_id', 'status']);
            $table->index(['expires_at', 'status']);
            $table->index('is_mandatory_for_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
