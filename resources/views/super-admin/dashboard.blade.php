@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Super Admin Dashboard</h1>
        <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Organization
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Organizations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_organizations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Organizations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_organizations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Trial Organizations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['trial_organizations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Subscriptions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_subscriptions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                </div>
                <div class="card-body">
                    <h4 class="small font-weight-bold">Monthly Revenue</h4>
                    <h2 class="mb-0">KES {{ number_format($stats['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="mr-2">Total Users:</span>
                        <strong>{{ $stats['total_users'] }}</strong>
                    </div>
                    <div>
                        <span class="mr-2">Total Players:</span>
                        <strong>{{ $stats['total_players'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Organizations -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m- text-primary">Recent Organizations</h60 font-weight-bold>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Plan</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrganizations as $org)
                        <tr>
                            <td>{{ $org->name }}</td>
                            <td>{{ $org->email }}</td>
                            <td>
                                <span class="badge bg-{{ $org->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($org->status) }}
                                </span>
                            </td>
                            <td>{{ $org->subscriptionPlan->name ?? 'No Plan' }}</td>
                            <td>{{ $org->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('super-admin.organizations.show', $org) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No organizations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('super-admin.organizations.index') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-building fa-3x mb-3 text-primary"></i>
                    <h5>Manage Organizations</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('super-admin.plans.index') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-tags fa-3x mb-3 text-success"></i>
                    <h5>Subscription Plans</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('super-admin.analytics') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-chart-bar fa-3x mb-3 text-info"></i>
                    <h5>Analytics</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
