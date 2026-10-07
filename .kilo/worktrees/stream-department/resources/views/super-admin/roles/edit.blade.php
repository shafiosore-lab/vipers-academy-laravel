@extends('layouts.admin')

@section('title', __('Edit Role'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Edit Role') }}</h1>
                    <p class="text-muted">{{ __('Modify role details and permissions') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    @if($role->is_system)
    <div class="alert alert-warning">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>{{ __('System Role') }}</strong> - {{ __('This is a system role and cannot be fully edited. Only custom permissions can be modified.') }}
    </div>
    @endif

    <!-- Role Details Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2 text-warning"></i>{{ __('Role Details') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('super-admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label">{{ __('Role Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $role->name) }}" {{ $role->is_system ? 'readonly' : '' }} required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="slug" class="form-label">{{ __('Slug') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                               id="slug" name="slug" value="{{ old('slug', $role->slug) }}" {{ $role->is_system ? 'readonly' : '' }} required>
                                        <div class="form-text">{{ __('Unique identifier (e.g., finance-manager)') }}</div>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="type" class="form-label">{{ __('Role Type') }}</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" {{ $role->is_system ? 'disabled' : '' }}>
                                            <option value="custom" {{ old('type', $role->type) == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                                            <option value="partner_staff" {{ old('type', $role->type) == 'partner_staff' ? 'selected' : '' }}>{{ __('Partner Staff') }}</option>
                                            <option value="admin" {{ old('type', $role->type) == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="department" class="form-label">{{ __('Department') }}</label>
                                        <select class="form-select @error('department') is-invalid @enderror" id="department" name="department" {{ $role->is_system ? 'disabled' : '' }}>
                                            <option value="">{{ __('Select Department') }}</option>
                                            <option value="coaching" {{ old('department', $role->department) == 'coaching' ? 'selected' : '' }}>{{ __('Coaching') }}</option>
                                            <option value="finance" {{ old('department', $role->department) == 'finance' ? 'selected' : '' }}>{{ __('Finance') }}</option>
                                            <option value="administration" {{ old('department', $role->department) == 'administration' ? 'selected' : '' }}>{{ __('Administration') }}</option>
                                            <option value="operations" {{ old('department', $role->department) == 'operations' ? 'selected' : '' }}>{{ __('Operations') }}</option>
                                            <option value="media" {{ old('department', $role->department) == 'media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                                            <option value="welfare" {{ old('department', $role->department) == 'welfare' ? 'selected' : '' }}>{{ __('Welfare') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="parent_role_id" class="form-label">{{ __('Parent Role (Inheritance)') }}</label>
                                        <select class="form-select @error('parent_role_id') is-invalid @enderror" id="parent_role_id" name="parent_role_id" {{ $role->is_system ? 'disabled' : '' }}>
                                            <option value="">{{ __('No Parent - Standalone Role') }}</option>
                                            @forelse($roles as $parentRole)
                                                @if($parentRole->id !== $role->id)
                                                <option value="{{ $parentRole->id }}" {{ old('parent_role_id', $role->parent_role_id) == $parentRole->id ? 'selected' : '' }}>
                                                    {{ $parentRole->name }} ({{ $parentRole->slug }})
                                                </option>
                                                @endif
                                            @empty
                                            @endforelse
                                        </select>
                                        <div class="form-text">{{ __('Select a parent role to inherit permissions') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="module" class="form-label">{{ __('Module') }}</label>
                                        <select class="form-select @error('module') is-invalid @enderror" id="module" name="module" {{ $role->is_system ? 'disabled' : '' }}>
                                            <option value="">{{ __('Select Module') }}</option>
                                            <option value="coaching" {{ old('module', $role->module) == 'coaching' ? 'selected' : '' }}>{{ __('Coaching') }}</option>
                                            <option value="finance" {{ old('module', $role->module) == 'finance' ? 'selected' : '' }}>{{ __('Finance') }}</option>
                                            <option value="management" {{ old('module', $role->module) == 'management' ? 'selected' : '' }}>{{ __('Management') }}</option>
                                            <option value="admin_operations" {{ old('module', $role->module) == 'admin_operations' ? 'selected' : '' }}>{{ __('Admin Operations') }}</option>
                                            <option value="media" {{ old('module', $role->module) == 'media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                                            <option value="welfare" {{ old('module', $role->module) == 'welfare' ? 'selected' : '' }}>{{ __('Welfare') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="inherit_permissions" name="inherit_permissions" value="1"
                                            {{ old('inherit_permissions', $role->inherit_permissions) ? 'checked' : '' }} {{ $role->is_system ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="inherit_permissions">
                                            {{ __('Inherit permissions from parent role') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Section - Full Width -->
                        @if($allPermissions->count() > 0)
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0 h5">{{ __('Permissions') }}</label>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="selectAllPermissions()">
                                        <i class="fas fa-check-square me-1"></i>{{ __('Select All') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="deselectAllPermissions()">
                                        <i class="fas fa-square me-1"></i>{{ __('Deselect All') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Permission Categories Accordion - Standardized 17 Categories -->
                            <div class="accordion" id="permissionsAccordion">
                                @php
                                // Standardized 17 categories in exact order
                                $permissionCategories = [
                                    'matches' => ['icon' => 'fa-futbol', 'color' => 'danger', 'label' => 'Matches', 'order' => 1],
                                    'documents' => ['icon' => 'fa-file-alt', 'color' => 'secondary', 'label' => 'Documents', 'order' => 2],
                                    'jobs' => ['icon' => 'fa-briefcase', 'color' => 'dark', 'label' => 'Jobs', 'order' => 3],
                                    'attendance' => ['icon' => 'fa-clock', 'color' => 'info', 'label' => 'Attendance', 'order' => 4],
                                    'communication' => ['icon' => 'fa-envelope', 'color' => 'teal', 'label' => 'Communication', 'order' => 5],
                                    'content' => ['icon' => 'fa-newspaper', 'color' => 'warning', 'label' => 'Content', 'order' => 6],
                                    'finance' => ['icon' => 'fa-credit-card', 'color' => 'success', 'label' => 'Finance', 'order' => 7],
                                    'players' => ['icon' => 'fa-running', 'color' => 'success', 'label' => 'Players', 'order' => 8],
                                    'partners' => ['icon' => 'fa-handshake', 'color' => 'info', 'label' => 'Partners', 'order' => 9],
                                    'orders' => ['icon' => 'fa-shopping-cart', 'color' => 'orange', 'label' => 'Orders', 'order' => 10],
                                    'programs' => ['icon' => 'fa-clipboard-list', 'color' => 'teal', 'label' => 'Programs', 'order' => 11],
                                    'reports' => ['icon' => 'fa-file-export', 'color' => 'indigo', 'label' => 'Reports', 'order' => 12],
                                    'sessions' => ['icon' => 'fa-clipboard', 'color' => 'primary', 'label' => 'Sessions', 'order' => 13],
                                    'statistics' => ['icon' => 'fa-chart-bar', 'color' => 'indigo', 'label' => 'Statistics', 'order' => 14],
                                    'system' => ['icon' => 'fa-cogs', 'color' => 'dark', 'label' => 'System', 'order' => 15],
                                    'teams' => ['icon' => 'fa-shield-alt', 'color' => 'warning', 'label' => 'Teams', 'order' => 16],
                                    'users' => ['icon' => 'fa-users', 'color' => 'primary', 'label' => 'Users', 'order' => 17],
                                ];

                                // Group permissions and sort by category order
                                $groupedPermissions = $allPermissions->groupBy('module');
                                $sortedGroups = $groupedPermissions->keys()->sortBy(function($group) use ($permissionCategories) {
                                    return $permissionCategories[$group]['order'] ?? 999;
                                });
                                @endphp

                                @foreach($sortedGroups as $group)
                                @php
                                $permissions = $groupedPermissions[$group] ?? collect();
                                $categoryInfo = $permissionCategories[$group] ?? ['icon' => 'fa-folder', 'color' => 'secondary', 'label' => ucfirst($group), 'order' => 999];
                                @endphp
                                @if($permissions->count() > 0)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $categoryInfo['order'] != 1 ? 'collapsed' : '' }} d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group }}">
                                            <span class="badge bg-light text-dark me-2 flex-shrink-0">{{ $categoryInfo['order'] }}</span>
                                            <i class="fas {{ $categoryInfo['icon'] }} me-2 text-{{ $categoryInfo['color'] }} flex-shrink-0"></i>
                                            <span class="flex-grow-1 text-truncate">{{ $categoryInfo['label'] }}</span>
                                            <span class="badge bg-{{ $categoryInfo['color'] }} me-2 flex-shrink-0">{{ $permissions->count() }}</span>
                                            <span class="badge bg-secondary flex-shrink-0 permission-count-{{ $group }}">0 selected</span>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $group }}" class="accordion-collapse collapse {{ $categoryInfo['order'] == 1 ? 'show' : '' }}" data-bs-parent="#permissionsAccordion">
                                        <div class="accordion-body p-0">
                                            <!-- Permission Grid - 4 Columns, Symmetrical Vertical Layout -->
                                            <div class="permission-grid">
                                                @foreach($permissions as $permission)
                                                <div class="permission-item">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input class="form-check-input me-2 permission-checkbox" type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               id="perm_{{ $permission->id }}"
                                                               data-group="{{ $group }}"
                                                               data-slug="{{ $permission->slug }}"
                                                               {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}" title="{{ $permission->slug }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ __('No permissions found. Please run the permission seeder.') }}
                        </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>{{ __('Update Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6><i class="fas fa-lightbulb me-2"></i>{{ __('Role Inheritance') }}</h6>
                        <p class="mb-0 small">{{ __('When you select a parent role, this role will automatically inherit all permissions from the parent. You can add additional permissions on top.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <h6><i class="fas fa-shield-alt me-2"></i>{{ __('System Roles') }}</h6>
                        <p class="mb-0 small">{{ __('System roles are protected and cannot be edited or deleted. Some fields may be read-only.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6>{{ __('Current Stats') }}</h6>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="display-6 text-primary"><i class="fas fa-users"></i> {{ $role->users()->count() }}</div>
                            <div class="text-muted">{{ __('Total Users') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="display-6 text-success"><i class="fas fa-shield-alt"></i> {{ $role->permissions()->count() }}</div>
                            <div class="text-muted">{{ __('Direct Permissions') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="display-6 text-info"><i class="fas fa-sitemap"></i> {{ $role->parentRole ? $role->parentRole->name : 'None' }}</div>
                            <div class="text-muted">{{ __('Parent Role') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Permission Grid - 4 Columns with Perfect Symmetry */
.permission-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    width: 100%;
    border-collapse: collapse;
}

.permission-item {
    padding: 14px 16px;
    border-bottom: 1px solid #e9ecef;
    border-right: 1px solid #e9ecef;
    min-height: 48px;
    display: flex;
    align-items: center;
    background-color: #fff;
    transition: background-color 0.15s ease;
}

.permission-item:nth-child(4n) {
    border-right: none;
}

.permission-item:hover {
    background-color: #f8f9fa;
}

/* Ensure consistent horizontal positioning */
.permission-item .form-check {
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    width: 100%;
}

.permission-item .form-check-input {
    margin: 0;
    flex-shrink: 0;
    cursor: pointer;
}

.permission-item .form-check-label {
    margin: 0;
    padding-left: 8px;
    cursor: pointer;
    font-size: 0.875rem;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsive Breakpoints */
@media (max-width: 1200px) {
    .permission-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .permission-item:nth-child(4n) {
        border-right: 1px solid #e9ecef;
    }

    .permission-item:nth-child(3n) {
        border-right: none;
    }
}

@media (max-width: 992px) {
    .permission-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .permission-item:nth-child(3n) {
        border-right: 1px solid #e9ecef;
    }

    .permission-item:nth-child(2n) {
        border-right: none;
    }
}

@media (max-width: 576px) {
    .permission-grid {
        grid-template-columns: 1fr;
    }

    .permission-item {
        border-right: none !important;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateAllPermissionCounts();

    // Add event listeners to all checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updatePermissionCount(this.dataset.group);
            handlePermissionDependencies(this);
        });
    });
});

// Update permission counts for all categories
function updateAllPermissionCounts() {
    const groups = {};
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        const group = checkbox.dataset.group;
        if (!groups[group]) groups[group] = 0;
        if (checkbox.checked) groups[group]++;
    });

    for (const [group, count] of Object.entries(groups)) {
        const badge = document.querySelector('.permission-count-' + group);
        if (badge) {
            badge.textContent = count + ' selected';
            badge.className = 'badge ' + (count > 0 ? 'bg-success' : 'bg-secondary') + ' permission-count-' + group;
        }
    }
}

// Update permission count for a specific category
function updatePermissionCount(group) {
    const checkboxes = document.querySelectorAll('.permission-checkbox[data-group="' + group + '"]');
    let count = 0;
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) count++;
    });

    const badge = document.querySelector('.permission-count-' + group);
    if (badge) {
        badge.textContent = count + ' selected';
        badge.className = 'badge ' + (count > 0 ? 'bg-success' : 'bg-secondary') + ' permission-count-' + group;
    }
}

// Handle permission dependencies
function handlePermissionDependencies(checkbox) {
    const slug = checkbox.dataset.slug;
    const group = checkbox.dataset.group;
    const action = slug.split('.')[1];

    if (checkbox.checked) {
        if (['create', 'edit', 'delete', 'approve'].includes(action)) {
            const viewSlug = group + '.view';
            const viewCheckbox = document.querySelector('.permission-checkbox[data-slug="' + viewSlug + '"]');
            if (viewCheckbox && !viewCheckbox.checked) {
                viewCheckbox.checked = true;
                updatePermissionCount(group);
            }
        }
    }
}

// Select all permissions
function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.checked = true;
    });
    updateAllPermissionCounts();
}

// Deselect all permissions
function deselectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.checked = false;
    });
    updateAllPermissionCounts();
}
</script>
@endpush
@endsection
