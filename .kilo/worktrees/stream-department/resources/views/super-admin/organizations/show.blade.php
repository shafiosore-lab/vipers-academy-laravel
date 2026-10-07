@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $organization->name }}</h1>
        <div>
            <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Organizations
            </a>
            <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if($organization->status === 'suspended')
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> This organization is currently <strong>suspended</strong>.
        <form method="POST" action="{{ route('super-admin.organizations.toggle-status', $organization) }}" class="d-inline ms-3">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">Activate</button>
        </form>
    </div>
    @elseif($organization->status === 'trial')
    <div class="alert alert-warning">
        <i class="fas fa-clock"></i> This organization is on a <strong>trial</strong> period.
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Players</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_players'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-futbol fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge bg-{{ $organization->status === 'active' ? 'success' : ($organization->status === 'trial' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($organization->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Organization Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Organization Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $organization->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td><code>{{ $organization->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:{{ $organization->email }}">{{ $organization->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $organization->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $organization->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Website</th>
                            <td>
                                @if($organization->website)
                                <a href="{{ $organization->website }}" target="_blank">{{ $organization->website }}</a>
                                @else
                                N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $organization->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $organization->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td>{{ $organization->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Subscription Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subscription</h6>
                </div>
                <div class="card-body">
                    @if($organization->subscriptionPlan)
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Plan</th>
                            <td>
                                <span class="badge bg-primary">{{ $organization->subscriptionPlan->name }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>KES {{ number_format($organization->subscriptionPlan->price) }} / {{ $organization->subscriptionPlan->billing_cycle }}</td>
                        </tr>
                        <tr>
                            <th>Max Users</th>
                            <td>{{ $organization->subscriptionPlan->max_users == -1 ? 'Unlimited' : $organization->subscriptionPlan->max_users }}</td>
                        </tr>
                        <tr>
                            <th>Max Players</th>
                            <td>{{ $organization->subscriptionPlan->max_players == -1 ? 'Unlimited' : $organization->subscriptionPlan->max_players }}</td>
                        </tr>
                    </table>
                    @else
                    <p class="text-muted">No subscription plan assigned.</p>
                    @endif

                    <div class="mt-3">
                        <h6>Usage Limits</h6>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="text-muted">Users: {{ $stats['total_users'] }} / {{ $organization->max_users ?? 'Unlimited' }}</small>
                                <div class="progress mb-3" style="height: 8px;">
                                    @php
                                    $userPercent = $organization->max_users ? ($stats['total_users'] / $organization->max_users) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar" role="progressbar" style="width: {{ min($userPercent, 100) }}%"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Players: {{ $stats['total_players'] }} / {{ $organization->max_players ?? 'Unlimited' }}</small>
                                <div class="progress mb-3" style="height: 8px;">
                                    @php
                                    $playerPercent = $organization->max_players ? ($stats['total_players'] / $organization->max_players) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($playerPercent, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Admin Users -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Organization Admins</h6>
                </div>
                <div class="card-body">
                    @php
                    $admins = $organization->users()->whereHas('roles', function($q) {
                        $q->where('slug', 'org-admin');
                    })->get();
                    @endphp

                    @forelse($admins as $admin)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $admin->name }}</div>
                            <small class="text-muted">{{ $admin->email }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No admin users found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit"></i> Edit Organization
                    </a>
                    <form method="POST" action="{{ route('super-admin.organizations.toggle-status', $organization) }}">
                        @csrf
                        @if($organization->status === 'active')
                        <button type="submit" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-ban"></i> Suspend Organization
                        </button>
                        @else
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-check"></i> Activate Organization
                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
