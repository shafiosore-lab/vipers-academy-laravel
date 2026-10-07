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
            $table->string('document_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->default('application/pdf');
            $table->integer('file_size')->nullable();
            $table->string('language')->default('en');
            $table->string('version')->default('1.0');
            $table->boolean('is_mandatory')->default(false);
            $table->json('target_roles');
            $table->boolean('requires_signature')->default(false);
            $table->integer('expiry_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

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

