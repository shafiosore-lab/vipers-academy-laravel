@extends('layouts.admin')

@section('title', __('View Role'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('Role Details') }}</h1>
        <a href="{{ route('organization.roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Roles') }}
        </a>
    </div>

    <div class="row">
        {{-- Role Information --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Role Information') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Slug') }}</th>
                            <td><code>{{ $role->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>{{ __('Description') }}</th>
                            <td>{{ $role->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Department') }}</th>
                            <td>{{ $role->department ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                @if($role->is_active)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <td>
                                @if($role->is_system)
                                    <span class="badge bg-warning text-dark">{{ __('System') }}</span>
                                @else
                                    <span class="badge bg-info">{{ __('Organization') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="d-flex gap-2 mt-3">
                        @if(!$role->is_system)
                            <a href="{{ route('organization.roles.edit', $role->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('Edit Role') }}
                            </a>
                            <form action="{{ route('organization.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">
                                    <i class="fas fa-trash"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-lock"></i> {{ __('System role - cannot be modified') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Hierarchy --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Role Hierarchy') }}</h5>
                </div>
                <div class="card-body">
                    @if($role->parentRole)
                        <div class="mb-3">
                            <small class="text-muted d-block">{{ __('Parent Role') }}</small>
                            <a href="{{ route('organization.roles.show', $role->parentRole->id) }}">
                                {{ $role->parentRole->name }}
                            </a>
                        </div>
                    @else
                        <p class="text-muted">{{ __('This is a top-level role') }}</p>
                    @endif

                    @if($role->childRoles->count() > 0)
                        <div>
                            <small class="text-muted d-block">{{ __('Child Roles') }}</small>
                            @foreach($role->childRoles as $child)
                                <a href="{{ route('organization.roles.show', $child->id) }}" class="d-block">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Organization Info --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Organization') }}</h5>
                </div>
                <div class="card-body">
                    <strong>{{ $organization->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $organization->email }}</small>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Permissions') }}</h5>
                    <span class="badge bg-secondary">{{ $allPermissions->count() }} {{ __('permissions') }}</span>
                </div>
                <div class="card-body">
                    @if($allPermissions->isEmpty())
                        <p class="text-muted">{{ __('No permissions assigned to this role.') }}</p>
                    @else
                        <div class="row">
                            @php
                                $groupedPermissions = $allPermissions->groupBy(function($perm) {
                                    return explode('.', $perm->slug)[0] ?? 'other';
                                });
                            @endphp

                            @foreach($groupedPermissions as $category => $permissions)
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-header py-2">
                                            <strong>{{ ucfirst($category) }}</strong>
                                            <span class="badge bg-secondary float-end">{{ $permissions->count() }}</span>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($permissions as $permission)
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>{{ $permission->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Users with this role --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Users with this Role') }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $usersWithRole = $role->users()->where('organization_id', $organization->id)->get();
                    @endphp
                    @if($usersWithRole->isEmpty())
                        <p class="text-muted">{{ __('No users have been assigned this role.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usersWithRole as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                    {{ $user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->approval_status === 'approved')
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                @elseif($user->approval_status === 'pending')
                                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audit Logs --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Activity Log') }}</h5>
                </div>
                <div class="card-body">
                    @if($auditLogs->isEmpty())
                        <p class="text-muted">{{ __('No activity recorded for this role.') }}</p>
                    @else
                        <div class="timeline">
                            @foreach($auditLogs as $log)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-icon me-3">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <div>
                                            <strong>{{ __('Role') }} {{ $log->action }}</strong>
                                            @if($log->user)
                                                <span class="text-muted">by {{ $log->user->name }}</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $log->created_at->format('M d, Y H:i:s') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
