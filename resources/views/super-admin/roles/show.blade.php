@extends('layouts.admin')

@section('title', __('Role Details'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ $role->name }}</h1>
                    <p class="text-muted">{{ $role->slug }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                    </a>
                    @if(!$role->is_system)
                    <a href="{{ route('super-admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit Role') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Role Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('Role Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">{{ __('Name') }}</dt>
                                <dd class="col-sm-8">{{ $role->name }}</dd>

                                <dt class="col-sm-4">{{ __('Slug') }}</dt>
                                <dd class="col-sm-8"><code>{{ $role->slug }}</code></dd>

                                <dt class="col-sm-4">{{ __('Type') }}</dt>
                                <dd class="col-sm-8">{{ ucfirst($role->type ?? 'custom') }}</dd>

                                <dt class="col-sm-4">{{ __('Department') }}</dt>
                                <dd class="col-sm-8">{{ $role->department ?? '-' }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">{{ __('Status') }}</dt>
                                <dd class="col-sm-8">
                                    @if($role->is_active)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ __('System Role') }}</dt>
                                <dd class="col-sm-8">
                                    @if($role->is_system)
                                    <span class="badge bg-warning">{{ __('Yes') }}</span>
                                    @else
                                    <span class="badge bg-info">{{ __('No') }}</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ __('Template') }}</dt>
                                <dd class="col-sm-8">
                                    @if($role->is_template)
                                    <span class="badge bg-primary">{{ __('Yes') }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ __('No') }}</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ __('Users') }}</dt>
                                <dd class="col-sm-8">{{ $role->users()->count() }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($role->description)
                    <div class="mt-3">
                        <strong>{{ __('Description') }}</strong>
                        <p class="mb-0">{{ $role->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Permissions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>{{ __('Permissions') }} ({{ $allPermissions->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($allPermissions->count() > 0)
                    <div class="row">
                        @foreach($allPermissions->groupBy('group') as $group => $permissions)
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                </div>
                                <div class="card-body py-2">
                                    <ul class="mb-0 list-unstyled">
                                        @foreach($permissions as $permission)
                                        <li><i class="fas fa-check text-success me-2"></i>{{ $permission->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">{{ __('No permissions assigned to this role.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Hierarchy -->
            @if($role->parentRole || $role->childRoles->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>{{ __('Role Hierarchy') }}</h5>
                </div>
                <div class="card-body">
                    @if($role->parentRole)
                    <div class="mb-3">
                        <strong>{{ __('Parent Role') }}</strong>
                        <div class="mt-2">
                            <a href="{{ route('super-admin.roles.show', $role->parentRole) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-up me-1"></i> {{ $role->parentRole->name }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($role->childRoles->count() > 0)
                    <div>
                        <strong>{{ __('Child Roles') }} ({{ $role->childRoles->count() }})</strong>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($role->childRoles as $childRole)
                            <a href="{{ route('super-admin.roles.show', $childRole) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-down me-1"></i> {{ $childRole->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>{{ __('Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Total Users') }}</span>
                        <strong>{{ $role->users()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Direct Permissions') }}</span>
                        <strong>{{ $role->permissions()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Inherited Permissions') }}</span>
                        <strong>{{ $role->getAllPermissions()->count() - $role->permissions()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ __('Child Roles') }}</span>
                        <strong>{{ $role->childRoles()->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Users with this role -->
            @if($role->users()->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>{{ __('Users') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($role->users()->limit(10)->get() as $user)
                        <li class="mb-2">
                            <a href="#">{{ $user->name }}</a>
                            <small class="text-muted d-block">{{ $user->email }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @if($role->users()->count() > 10)
                    <small class="text-muted">+{{ $role->users()->count() - 10 }} more users</small>
                    @endif
                </div>
            </div>
            @endif

            <!-- Audit Logs -->
            @if($auditLogs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('Recent Activity') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($auditLogs->take(5) as $log)
                        <li class="mb-2 small">
                            <span class="text-primary">{{ $log->action }}</span>
                            <br>
                            <small class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</small>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('super-admin.roles.audit') }}?role_id={{ $role->id }}" class="btn btn-link btn-sm">
                        {{ __('View All Logs') }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
