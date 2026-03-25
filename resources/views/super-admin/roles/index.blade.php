@extends('layouts.admin')

@section('title', __('Role Management'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h5 mb-0">{{ __('Role Management') }}</h1>
                    <p class="text-muted small mb-0">{{ __('Manage roles, permissions, and access control') }}</p>
                </div>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('super-admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>{{ __('Create Role') }}
                    </a>
                    <a href="{{ route('super-admin.roles.hierarchy') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-sitemap me-1"></i>{{ __('Hierarchy') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-2">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ $stats['total_roles'] }}</h5>
                            <p class="mb-0 small">Total Roles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users-cog fa-lg opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ $stats['system_roles'] }}</h5>
                            <p class="mb-0 small">System Roles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cog fa-lg opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ $stats['template_roles'] }}</h5>
                            <p class="mb-0 small">Templates</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-copy fa-lg opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ $stats['pending_requests'] }}</h5>
                            <p class="mb-0 small">Pending Requests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-lg opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('super-admin.roles.templates.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-copy me-1"></i>{{ __('Role Templates') }}
                </a>
                <a href="{{ route('super-admin.roles.requests.index') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-user-plus me-1"></i>{{ __('Role Requests') }}
                </a>
                <a href="{{ route('super-admin.roles.audit') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-history me-1"></i>{{ __('Audit Logs') }}
                </a>
                <a href="{{ route('super-admin.roles.modules.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-th-large me-1"></i>{{ __('Module Permissions') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0"><i class="fas fa-list me-1"></i>{{ __('All Roles') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">{{ __('Name') }}</th>
                                    <th class="py-2">{{ __('Slug') }}</th>
                                    <th class="py-2">{{ __('Type') }}</th>
                                    <th class="py-2">{{ __('Module') }}</th>
                                    <th class="py-2">{{ __('Users') }}</th>
                                    <th class="py-2">{{ __('Status') }}</th>
                                    <th class="py-2">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td class="py-2">
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
                                    <td class="py-2"><code>{{ $role->slug }}</code></td>
                                    <td class="py-2">
                                        <span class="badge bg-{{ $role->type === 'hybrid' ? 'purple' : 'secondary' }}">
                                            {{ ucfirst($role->type ?? 'custom') }}
                                        </span>
                                    </td>
                                    <td class="py-2">{{ $role->module ?? '-' }}</td>
                                    <td class="py-2">
                                        <span class="badge bg-primary">{{ $role->users()->count() }}</span>
                                    </td>
                                    <td class="py-2">
                                        @if($role->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('super-admin.roles.show', $role) }}" class="btn btn-outline-primary btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$role->is_system)
                                            <a href="{{ route('super-admin.roles.edit', $role) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        <p class="text-muted mb-0">No roles found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-3 py-2">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

