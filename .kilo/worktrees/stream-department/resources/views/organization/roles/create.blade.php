@extends('layouts.admin')

@section('title', __('Create Organization Role'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('Create Organization Role') }}</h1>
        <a href="{{ route('organization.roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Roles') }}
        </a>
    </div>

    {{-- Subscription Notice --}}
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle"></i>
        <strong>{{ __('Your subscription:') }}</strong> {{ $subscriptionPlan ? $subscriptionPlan->name : 'No Plan' }}
        <span class="mx-2">|</span>
        <strong>{{ __('Available permissions:') }}</strong>
        @if(isset($permissions) && count($permissions) > 0)
            {{ count($permissions) }} {{ __('categories available') }}
        @else
            {{ __('Limited by your subscription plan') }}
        @endif
    </div>

    <form action="{{ route('organization.roles.store') }}" method="POST" id="roleForm">
        @csrf
        @if(auth()->user()->isSuperAdmin() && isset($organization))
            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
        @endif

        <div class="row">
            {{-- Role Details --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Role Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Role Name') }} *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">{{ __('Slug') }} *</label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                            <small class="text-muted">{{ __('Unique identifier (e.g., team-manager)') }}</small>
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">{{ __('Department') }}</label>
                            <input type="text" class="form-control" id="department" name="department" placeholder="e.g., Operations">
                        </div>

                        <div class="mb-3">
                            <label for="parent_role_id" class="form-label">{{ __('Parent Role') }}</label>
                            <select class="form-select" id="parent_role_id" name="parent_role_id">
                                <option value="">{{ __('No Parent (Top Level)') }}</option>
                                @foreach($parentRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Inherit permissions from parent role') }}</small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="inherit_permissions" name="inherit_permissions" value="1" checked>
                            <label class="form-check-label" for="inherit_permissions">
                                {{ __('Inherit permissions from parent') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permissions --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Permissions') }}</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                {{ __('Select All') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                {{ __('Deselect All') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($permissions->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('No permissions available for your subscription plan.') }}
                                <a href="#">{{ __('Upgrade your plan') }}</a> {{ __('to access more permissions.') }}
                            </div>
                        @else
                            <p class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Select permissions for this role. Permissions are grouped by category.') }}
                            </p>

                            <div class="row">
                                @foreach($permissions as $category => $categoryPermissions)
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-header py-2">
                                                <div class="form-check">
                                                    <input class="form-check-input category-select" type="checkbox" data-category="{{ $category }}">
                                                    <label class="form-check-label fw-bold">
                                                        {{ ucfirst($category) }}
                                                        <span class="badge bg-secondary">{{ count($categoryPermissions) }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body py-2">
                                                @foreach($categoryPermissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            id="perm_{{ $permission->id }}"
                                                            data-category="{{ $category }}">
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                            <small class="text-muted d-block">{{ $permission->description }}</small>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @error('permissions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    <i class="fas fa-shield-alt"></i>
                                    {{ __('Organization:') }} <strong>{{ $organization->name }}</strong>
                                </span>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('Create Role') }}
                                </button>
                                <a href="{{ route('organization.roles.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
        slugInput.value = slug;
    });

    // Select All / Deselect All
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        document.querySelectorAll('.category-select').forEach(cb => cb.checked = true);
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.category-select').forEach(cb => cb.checked = false);
    });

    // Category select functionality
    document.querySelectorAll('.category-select').forEach(function(categoryCheckbox) {
        categoryCheckbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-category="${category}"]`);
            permissionCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // Update category checkbox when individual permission is checked
    document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
        permissionCheckbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const categoryCheckbox = document.querySelector(`.category-select[data-category="${category}"]`);
            const categoryPermissions = document.querySelectorAll(`.permission-checkbox[data-category="${category}"]`);
            const checkedCount = Array.from(categoryPermissions).filter(cb => cb.checked).length;

            categoryCheckbox.checked = checkedCount === categoryPermissions.length;
            categoryCheckbox.indeterminate = checkedCount > 0 && checkedCount < categoryPermissions.length;
        });
    });
});
</script>
@endpush
