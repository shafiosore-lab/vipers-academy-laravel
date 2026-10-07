@extends('layouts.admin')

@section('title', __('Role Management'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Role Management') }}</h1>
                    <p class="text-muted">{{ __('Manage roles, permissions, and access control') }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('super-admin.roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Create Role') }}
                    </a>
                    <a href="{{ route('super-admin.roles.hierarchy') }}" class="btn btn-info">
                        <i class="fas fa-sitemap me-2"></i>{{ __('Hierarchy') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_roles'] }}</h4>
                            <p class="mb-0">Total Roles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users-cog fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['system_roles'] }}</h4>
                            <p class="mb-0">System Roles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cog fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['template_roles'] }}</h4>
                            <p class="mb-0">Templates</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-copy fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending_requests'] }}</h4>
                            <p class="mb-0">Pending Requests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                <a href="{{ route('super-admin.roles.templates.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-copy me-2"></i>{{ __('Role Templates') }}
                </a>
                <a href="{{ route('super-admin.roles.requests.index') }}" class="btn btn-outline-warning">
                    <i class="fas fa-user-plus me-2"></i>{{ __('Role Requests') }}
                </a>
                <a href="{{ route('super-admin.roles.audit') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i>{{ __('Audit Logs') }}
                </a>
                <a href="{{ route('super-admin.roles.modules.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-th-large me-2"></i>{{ __('Module Permissions') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>{{ __('All Roles') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Slug') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Users') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->name }}</strong>
                                        @if($role->is_system)
                                            <span class="badge bg-dark ms-1">System</span>
                                        @endif
                                        @if($role->is_template)
                                            <span class="badge bg-info ms-1">Template</span>
                                        @endif
                                        @if($role->parentRole)
                                            <br><small class="text-muted">Inherits from: {{ $role->parentRole->name }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $role->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ $role->type === 'hybrid' ? 'purple' : 'secondary' }}">
                                            {{ ucfirst($role->type ?? 'custom') }}
                                        </span>
                                    </td>
                                    <td>{{ $role->module ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role->users()->count() }}</span>
                                    </td>
                                    <td>
                                        @if($role->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('super-admin.roles.show', $role) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$role->is_system)
                                            <a href="{{ route('super-admin.roles.edit', $role) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No roles found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
