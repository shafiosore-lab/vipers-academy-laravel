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
        Schema::create('organization_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->enum('document_type', array_keys(\App\Models\OrganizationDocument::getDocumentTypes()))
                ->default('document');
            $table->string('version')->default('1.0');
            $table->enum('status', array_keys(\App\Models\OrganizationDocument::getStatuses()))
                ->default('draft');
            $table->boolean('is_template')->default(false);
            $table->enum('category', array_keys(\App\Models\OrganizationDocument::getCategories()))
                ->default('administrative');
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'document_type']);
            $table->index(['organization_id', 'category']);
            $table->index('is_template');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_documents');
    }
};
