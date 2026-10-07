@extends('layouts.admin')

@section('title', 'Dashboard - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Welcome Header -->
    <x-dashboard-card variant="default" spacing="lg" class="welcome-card">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2 class="welcome-title">Super Admin Dashboard</h2>
                <p class="welcome-subtitle">Manage organizations and system-wide settings</p>
            </div>
            <div class="welcome-actions">
                <span class="welcome-date">{{ now()->format('M d, Y') }}</span>
                <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> New Organization
                </a>
            </div>
        </div>
    </x-dashboard-card>

    <!-- Key Metrics Grid -->
    <x-dashboard-grid columns="4" gap="lg" variant="default" class="metrics-grid">
        <!-- Total Organizations -->
        <x-dashboard-card variant="compact" spacing="md">
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_organizations'] ?? 0 }}</div>
                <div class="stat-label">Total Organizations</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +5 this month
                </div>
            </div>
        </x-dashboard-card>

        <!-- Active Organizations -->
        <x-dashboard-card variant="compact" spacing="md">
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active_organizations'] ?? 0 }}</div>
                <div class="stat-label">Active Organizations</div>
                <div class="stat-change neutral">
                    {{ $stats['trial_organizations'] ?? 0 }} trials
                </div>
            </div>
        </x-dashboard-card>

        <!-- Active Subscriptions -->
        <x-dashboard-card variant="compact" spacing="md">
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active_subscriptions'] ?? 0 }}</div>
                <div class="stat-label">Active Subscriptions</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +12% vs last month
                </div>
            </div>
        </x-dashboard-card>

        <!-- Revenue -->
        <x-dashboard-card variant="compact" spacing="md">
            <div class="stat-content">
                <div class="stat-value">KES {{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +8% vs last month
                </div>
            </div>
        </x-dashboard-card>
    </x-dashboard-grid>

    <!-- Recent Organizations -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <h3 class="card-title">Recent Organizations</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                            <td>
                                <div class="fw-bold">{{ $org->name }}</div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $org->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $org->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($org->status) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $org->subscriptionPlan->name ?? 'No Plan' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $org->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('super-admin.organizations.show', $org) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                <i class="fas fa-building me-2"></i>No organizations found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-dashboard-card>

    <!-- System Management Quick Links -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <h3 class="card-title">System Management</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="4" gap="md" variant="minimal" class="quick-links-grid">
                <a href="{{ route('super-admin.organizations.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Organizations</div>
                        <div class="quick-link-desc">Manage all orgs</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.users.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Users</div>
                        <div class="quick-link-desc">User management</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.roles.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Roles & Permissions</div>
                        <div class="quick-link-desc">Access control</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.plans.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Subscription Plans</div>
                        <div class="quick-link-desc">Pricing & billing</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.page-content.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Website Content</div>
                        <div class="quick-link-desc">Manage pages</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.tournaments.overview') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Tournament Analytics</div>
                        <div class="quick-link-desc">System stats</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.finance.dashboard') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Financial Overview</div>
                        <div class="quick-link-desc">Revenue & budgets</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.organizations.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="quick-link-content">
                        <div class="quick-link-title">Organization Activity</div>
                        <div class="quick-link-desc">Manage & monitor</div>
                    </div>
                </a>
            </x-dashboard-grid>
        </div>
    </x-dashboard-card>

    <!-- Tournament Management Section -->
    <x-dashboard-card variant="default" spacing="md" class="tournament-section">
        <div class="card-header">
            <h3 class="card-title">🏆 Tournament Management</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="3" gap="md" variant="minimal" class="tournament-actions-grid">
                <a href="{{ route('super-admin.tournaments.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Create Tournament</div>
                        <div class="action-desc">New event setup</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.tournaments.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Manage Tournaments</div>
                        <div class="action-desc">All events</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.tournaments.overview') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Tournament Analytics</div>
                        <div class="action-desc">Performance stats</div>
                    </div>
                </a>
            </x-dashboard-grid>
        </div>
    </x-dashboard-card>

    <!-- System Tools & Features -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <h3 class="card-title">System Tools & Features</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="4" gap="md" variant="minimal">
                <a href="{{ route('admin.players.index') }}" class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="tool-content">
                        <div class="tool-title">Player Management</div>
                        <div class="tool-desc">All players & profiles</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.finance.budgets.index') }}" class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="tool-content">
                        <div class="tool-title">Financial Reports</div>
                        <div class="tool-desc">Budgets & expenses</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.tournaments.index') }}" class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="tool-content">
                        <div class="tool-title">Tournament Engine</div>
                        <div class="tool-desc">Advanced features</div>
                    </div>
                </a>

                <a href="{{ route('super-admin.organizations.index') }}" class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="tool-content">
                        <div class="tool-title">System Activity</div>
                        <div class="tool-desc">Monitor operations</div>
                    </div>
                </a>
            </x-dashboard-grid>
        </div>
    </x-dashboard-card>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #ea1c4d !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .compact-stats-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .compact-stat-card {
        flex: 1;
        min-width: 140px;
        background: white;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .compact-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .compact-stat-content {
        display: flex;
        flex-direction: column;
    }

    .compact-stat-value {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.2;
        color: #2c3e50;
    }

    .compact-stat-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }
</style>
@endpush

