@extends('layouts.admin')

@section('title', __('Organization Roles & Permissions'))

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ __('Organization Roles & Permissions') }}</h1>
            <p class="text-muted mb-0">
                {{ __('Managing roles for') }} <strong>{{ $organization->name }}</strong>
            </p>
        </div>
        @if(isset($organizations) && $organizations->count() > 1)
        <div>
            <form method="GET" action="{{ route('organization.roles.index') }}" class="d-flex gap-2">
                <select name="organization_id" class="form-select" onchange="this.form.submit()">
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ $org->id == $organization->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif
        <div>
            @if($stats['can_create_role'])
                <a href="{{ route('organization.roles.create', ['organization_id' => $organization->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Create Role') }}
                </a>
            @else
                <button class="btn btn-secondary" disabled>
                    <i class="fas fa-lock"></i> {{ __('Role Limit Reached') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Subscription Info Banner --}}
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-info-circle"></i>
                <strong>{{ __('Subscription Plan') }}:</strong> {{ $stats['subscription_plan'] }}
                @if($organization->subscriptionPlan && $organization->subscriptionPlan->features)
                    <span class="badge bg-primary ms-2">{{ __('Tier') }}</span>
                @endif
            </div>
            <div>
                {{ __('Role Usage') }}: {{ $stats['total_roles'] }} / {{ $stats['max_roles'] }}
            </div>
        </div>
        @if(!$stats['can_create_role'])
            <div class="mt-2">
                <small>{{ __('You have reached your maximum role limit.') }}
                <a href="#" class="alert-link">{{ __('Upgrade your plan') }}</a> {{ __('to create more roles.') }}</small>
            </div>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">{{ __('Total Roles') }}</h6>
                            <h2 class="mb-0">{{ $stats['total_roles'] }}</h2>
                        </div>
                        <i class="fas fa-users-cog fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">{{ __('Available Slots') }}</h6>
                            <h2 class="mb-0">{{ $stats['max_roles'] - $stats['total_roles'] }}</h2>
                        </div>
                        <i class="fas fa-slots-h fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">{{ __('Subscription') }}</h6>
                            <h6 class="mb-0">{{ $stats['subscription_plan'] }}</h6>
                        </div>
                        <i class="fas fa-credit-card fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">{{ __('Your Organization') }}</h6>
                            <h6 class="mb-0">{{ $organization->name }}</h6>
                        </div>
                        <i class="fas fa-building fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Organization Roles') }}</h5>
        </div>
        <div class="card-body">
            @if($roles->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users-cog fa-4x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('No organization roles found. Create your first role to get started.') }}</p>
                    @if($stats['can_create_role'])
                        <a href="{{ route('organization.roles.create', ['organization_id' => $organization->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Create First Role') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Role Name') }}</th>
                                <th>{{ __('Slug') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Permissions') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if($role->description)
                                        <br><small class="text-muted">{{ $role->description }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $role->slug }}</code></td>
                                <td>{{ $role->department ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $role->permissions->count() }} {{ __('permissions') }}
                                    </span>
                                </td>
                                <td>
                                    @if($role->is_active)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('organization.roles.show', $role->id) }}" class="btn btn-sm btn-info" title="{{ __('View') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$role->is_system)
                                            <a href="{{ route('organization.roles.edit', $role->id) }}" class="btn btn-sm btn-primary" title="{{ __('Edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('organization.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}" onclick="return confirm('Are you sure you want to delete this role?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-warning text-dark" title="{{ __('System role - cannot be modified') }}">
                                                <i class="fas fa-lock"></i> {{ __('System') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $roles->links() }}
            @endif
        </div>
    </div>

    {{-- Help Section --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('About Organization Roles') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>{{ __('What you can do:') }}</h6>
                    <ul class="text-muted">
                        <li>{{ __('Create custom roles specific to your organization') }}</li>
                        <li>{{ __('Assign permissions to roles') }}</li>
                        <li>{{ __('Assign roles to users within your organization') }}</li>
                        <li>{{ __('Manage role hierarchies') }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>{{ __('Restrictions:') }}</h6>
                    <ul class="text-muted">
                        <li>{{ __('Cannot modify system roles') }}</li>
                        <li>{{ __('Cannot access roles from other organizations') }}</li>
                        <li>{{ __('Limited by your subscription plan') }}</li>
                        <li>{{ __('Cannot assign permissions not allowed by your plan') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
