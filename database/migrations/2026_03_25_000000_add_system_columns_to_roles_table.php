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
            // System and template flags
            $table->boolean('is_system')->default(false)->after('type');
            $table->boolean('is_template')->default(false)->after('is_system');
            $table->boolean('is_active')->default(true)->after('is_template');

            // Organization scoping
            $table->unsignedBigInteger('organization_id')->nullable()->after('is_active');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            // Role hierarchy and inheritance
            $table->unsignedBigInteger('parent_role_id')->nullable()->after('organization_id');
            $table->foreign('parent_role_id')->references('id')->on('roles')->onDelete('set null');
            $table->boolean('inherit_permissions')->default(true)->after('parent_role_id');

            // Department and module
            $table->string('department')->nullable()->after('inherit_permissions');
            $table->string('module')->nullable()->after('department');

            // Additional fields
            $table->string('name_key')->nullable()->after('module');
            $table->string('description_key')->nullable()->after('name_key');
            $table->string('combined_role_ids')->nullable()->after('description_key');
            $table->json('metadata')->nullable()->after('combined_role_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['parent_role_id']);
            $table->dropColumn([
                'is_system',
                'is_template',
                'is_active',
                'organization_id',
                'parent_role_id',
                'inherit_permissions',
                'department',
                'module',
                'name_key',
                'description_key',
                'combined_role_ids',
                'metadata',
            ]);
        });
    }
};
