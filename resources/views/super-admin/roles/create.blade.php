@extends('layouts.admin')

@section('title', __('Create Role'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Create New Role') }}</h1>
                    <p class="text-muted">{{ __('Define a new role with specific permissions') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>{{ __('Role Details') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('super-admin.roles.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Role Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">{{ __('Slug') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                       id="slug" name="slug" value="{{ old('slug') }}" required>
                                <div class="form-text">{{ __('Unique identifier (e.g., finance-manager)') }}</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="type" class="form-label">{{ __('Role Type') }}</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                                    <option value="partner_staff" {{ old('type') == 'partner_staff' ? 'selected' : '' }}>{{ __('Partner Staff') }}</option>
                                    <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">{{ __('Department') }}</label>
                                <select class="form-select @error('department') is-invalid @enderror" id="department" name="department">
                                    <option value="">{{ __('Select Department') }}</option>
                                    <option value="coaching" {{ old('department') == 'coaching' ? 'selected' : '' }}>{{ __('Coaching') }}</option>
                                    <option value="finance" {{ old('department') == 'finance' ? 'selected' : '' }}>{{ __('Finance') }}</option>
                                    <option value="administration" {{ old('department') == 'administration' ? 'selected' : '' }}>{{ __('Administration') }}</option>
                                    <option value="operations" {{ old('department') == 'operations' ? 'selected' : '' }}>{{ __('Operations') }}</option>
                                    <option value="media" {{ old('department') == 'media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                                    <option value="welfare" {{ old('department') == 'welfare' ? 'selected' : '' }}>{{ __('Welfare') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="parent_role_id" class="form-label">{{ __('Parent Role (Inheritance)') }}</label>
                                <select class="form-select @error('parent_role_id') is-invalid @enderror" id="parent_role_id" name="parent_role_id">
                                    <option value="">{{ __('No Parent - Standalone Role') }}</option>
                                    @forelse($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('parent_role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }} ({{ $role->slug }})
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="form-text">{{ __('Select a parent role to inherit permissions') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="module" class="form-label">{{ __('Module') }}</label>
                                <select class="form-select @error('module') is-invalid @endreire" id="module" name="module">
                                    <option value="">{{ __('Select Module') }}</option>
                                    <option value="coaching" {{ old('module') == 'coaching' ? 'selected' : '' }}>{{ __('Coaching') }}</option>
                                    <option value="finance" {{ old('module') == 'finance' ? 'selected' : '' }}>{{ __('Finance') }}</option>
                                    <option value="management" {{ old('module') == 'management' ? 'selected' : '' }}>{{ __('Management') }}</option>
                                    <option value="admin_operations" {{ old('module') == 'admin_operations' ? 'selected' : '' }}>{{ __('Admin Operations') }}</option>
                                    <option value="media" {{ old('module') == 'media' ? 'selected' : '' }}>{{ __('Media') }}</option>
                                    <option value="welfare" {{ old('module') == 'welfare' ? 'selected' : '' }}>{{ __('Welfare') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="inherit_permissions" name="inherit_permissions" value="1" {{ old('inherit_permissions', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="inherit_permissions">
                                    {{ __('Inherit permissions from parent role') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>{{ __('Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>{{ __('Role Inheritance') }}</h6>
                        <p class="mb-0 small">{{ __('When you select a parent role, this role will automatically inherit all permissions from the parent. You can add additional permissions on top.') }}</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-shield-alt me-2"></i>{{ __('System Roles') }}</h6>
                        <p class="mb-0 small">{{ __('System roles are protected and cannot be edited or deleted.') }}</p>
                    </div>

                    <div class="mt-3">
                        <h6>{{ __('Available Modules') }}</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-futbol me-2 text-success"></i>Coaching</li>
                            <li><i class="fas fa-coins me-2 text-warning"></i>Finance</li>
                            <li><i class="fas fa-users me-2 text-primary"></i>Management</li>
                            <li><i class="fas fa-cogs me-2 text-secondary"></i>Admin Operations</li>
                            <li><i class="fas fa-camera me-2 text-info"></i>Media</li>
                            <li><i class="fas fa-heart me-2 text-danger"></i>Welfare</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
