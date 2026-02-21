<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Enhancements for comprehensive role management:
     * - Role templates for reuse across organizations
     * - Role inheritance (parent-child relationships)
     * - Organization-specific role configurations
     * - Module-action level permissions
     */
    public function up(): void
    {
        // 1. Add role template and inheritance fields to roles table
        Schema::table('roles', function (Blueprint $table) {
            // Role template support
            $table->boolean('is_template')->default(false)->after('is_subscription_restricted');
            $table->foreignId('organization_id')->nullable()->after('is_template')->constrained('organizations')->nullOnDelete();

            // Role inheritance
            $table->foreignId('parent_role_id')->nullable()->after('organization_id')->constrained('roles')->nullOnDelete();
            $table->boolean('inherit_permissions')->default(true)->after('parent_role_id');

            // Hybrid role support (combines multiple roles)
            $table->text('combined_role_ids')->nullable()->after('inherit_permissions');

            // Internationalization
            $table->string('name_key')->nullable()->after('name')->comment('Translation key for i18n');
            $table->string('description_key')->nullable()->after('description')->comment('Translation key for i18n');

            // Role metadata
            $table->string('department')->nullable()->after('module');
            $table->json('metadata')->nullable()->after('department')->comment('Additional role configuration');

            // Role status
            $table->boolean('is_active')->default(true)->after('metadata');
            $table->boolean('is_system')->default(false)->after('is_active')->comment('System roles cannot be deleted');
        });

        // 2. Create role_templates table for reusable role configurations
        Schema::create('role_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->json('role_configurations')->comment('JSON of roles and permissions in this template');
            $table->boolean('is_public')->default(false)->comment('Available to all organizations');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['organization_id', 'is_public']);
        });

        // 3. Create role_requests table for permission escalation
        Schema::create('role_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['organization_id', 'status']);
        });

        // 4. Create module_permissions table for granular RBAC
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('actions')->comment('Available actions: create, read, update, delete, export, etc.');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Create role_module_permissions pivot table
        Schema::create('role_module_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_permission_id')->constrained('module_permissions')->cascadeOnDelete();
            $table->json('allowed_actions')->comment('Specific actions allowed for this role-module combination');
            $table->boolean('is_inherited')->default(false)->comment('Inherited from parent role');
            $table->timestamps();

            $table->unique(['role_id', 'module_permission_id']);
            $table->index(['role_id', 'is_inherited']);
        });

        // 6. Create audit_logs table specifically for role changes
        Schema::create('role_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // role_assigned, role_removed, permission_changed, etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['target_user_id', 'created_at']);
            $table->index(['organization_id', 'created_at']);
        });

        // 7. Update permissions table to add module grouping
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module')->nullable()->after('description');
            $table->string('action')->nullable()->after('module');
            $table->boolean('is_active')->default(true)->after('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['module', 'action', 'is_active']);
        });

        Schema::dropIfExists('role_audit_logs');
        Schema::dropIfExists('role_module_permissions');
        Schema::dropIfExists('module_permissions');
        Schema::dropIfExists('role_requests');
        Schema::dropIfExists('role_templates');

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'is_template',
                'organization_id',
                'parent_role_id',
                'inherit_permissions',
                'combined_role_ids',
                'name_key',
                'description_key',
                'department',
                'metadata',
                'is_active',
                'is_system',
            ]);
        });
    }
};
