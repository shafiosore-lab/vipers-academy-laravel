@extends('layouts.admin')

@section('title', 'Dashboard - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 text-gray-800">Organisation Management</h1>
        <div>
            <span class="text-muted small me-3"><i class="fas fa-calendar"></i> {{ now()->format('M d, Y') }}</span>
            <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> New
            </a>
        </div>
    </div>

    <!-- Compact Stats Row -->
    <div class="compact-stats-row mb-3">
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ea1c4d 0%, #c31432 100%);">
                <i class="fas fa-building text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $stats['total_organizations'] }}</div>
                <div class="compact-stat-label">Total Organizations</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <i class="fas fa-check-circle text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $stats['active_organizations'] }}</div>
                <div class="compact-stat-label">Active</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <i class="fas fa-hourglass-half text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $stats['trial_organizations'] }}</div>
                <div class="compact-stat-label">Trial</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                <i class="fas fa-credit-card text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $stats['active_subscriptions'] }}</div>
                <div class="compact-stat-label">Subscriptions</div>
            </div>
        </div>
    </div>

    <!-- Recent Organizations -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">Recent Organizations</h6>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">Name</th>
                            <th class="py-1 px-2">Email</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2">Plan</th>
                            <th class="py-1 px-2">Created</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrganizations as $org)
                        <tr>
                            <td class="py-1 align-middle small">{{ $org->name }}</td>
                            <td class="py-1 align-middle small">{{ $org->email }}</td>
                            <td class="py-1 align-middle">
                                <span class="badge bg-{{ $org->status === 'active' ? 'success-subtle' : 'warning-subtle' }} text-{{ $org->status === 'active' ? 'success' : 'warning' }}" style="font-size: 10px;">
                                    {{ ucfirst($org->status) }}
                                </span>
                            </td>
                            <td class="py-1 align-middle small">{{ $org->subscriptionPlan->name ?? 'No Plan' }}</td>
                            <td class="py-1 align-middle small">{{ $org->created_at->format('M d, Y') }}</td>
                            <td class="py-1 align-middle">
                                <a href="{{ route('super-admin.organizations.show', $org) }}" class="btn btn-sm btn-info py-0 px-1" style="font-size: 11px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center small py-2">No organizations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <a href="{{ route('super-admin.tournaments.overview') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-chart-line fa-lg mb-2 text-info"></i>
                    <h6 class="small mb-0">Tournament Overview</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.page-content.index') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-globe fa-lg mb-2 text-primary"></i>
                    <h6 class="small mb-0">Website Content</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.tournaments.index') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-trophy fa-lg mb-2 text-danger"></i>
                    <h6 class="small mb-0">Tournaments</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.organizations.index') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-building fa-lg mb-2 text-primary"></i>
                    <h6 class="small mb-0">Manage Organizations</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.roles.index') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-user-shield fa-lg mb-2 text-warning"></i>
                    <h6 class="small mb-0">Role Management</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.plans.index') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-tags fa-lg mb-2 text-success"></i>
                    <h6 class="small mb-0">Subscription Plans</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('super-admin.analytics') }}" class="card text-center text-decoration-none border-0 shadow-sm">
                <div class="card-body py-2">
                    <i class="fas fa-chart-bar fa-lg mb-2 text-info"></i>
                    <h6 class="small mb-0">Analytics</h6>
                </div>
            </a>
        </div>
    </div>

    <!-- Tournament Management Section -->
    <div class="row g-2 mb-3">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>Tournament Management</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.create') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-plus"></i> New Tournament
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-list"></i> All Tournaments
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.overview') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-chart-pie"></i> Overview
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-success w-100 btn-sm">
                                <i class="fas fa-futbol"></i> Matches
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-map-marker-alt"></i> Venues
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                                <i class="fas fa-users"></i> Teams
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Tournament Engine & Match Scheduler -->
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Smart Tournament Engine</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-success w-100 btn-sm">
                                <i class="fas fa-puzzle-piece"></i> Custom Formats
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-star"></i> ELO Rankings
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-user-tie"></i> Referee Allocation
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-route"></i> Travel Optimization
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Match Scheduler</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-magic"></i> Auto Schedule
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-exclamation-triangle"></i> Conflict Detection
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-success w-100 btn-sm">
                                <i class="fas fa-sitemap"></i> Bracket Generator
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-list-ol"></i> League Schedule
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Digital Player Ecosystem & Governance -->
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Digital Player Ecosystem</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                                <i class="fas fa-user"></i> All Players
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-danger w-100 btn-sm">
                                <i class="fas fa-user-injured"></i> Injuries
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-exchange-alt"></i> Transfers
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-file-contract"></i> Contracts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark py-2">
                    <h6 class="mb-0"><i class="fas fa-gavel me-2"></i>Governance & Compliance</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-birthday-cake"></i> Age Verification
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-danger w-100 btn-sm">
                                <i class="fas fa-balance-scale"></i> Disciplinary
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-dark w-100 btn-sm">
                                <i class="fas fa-hands-helping"></i> Appeals
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-flag"></i> Protests
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Infrastructure & Operations -->
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Venue Management</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-plus-circle"></i> Add Venue
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-success w-100 btn-sm">
                                <i class="fas fa-calendar-check"></i> Availability
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-bookmark"></i> Bookings
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-building"></i> Facilities
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Referee Management</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                                <i class="fas fa-user-plus"></i> Add Referee
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-graduation-cap"></i> Training
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-star-half-alt"></i> Reviews
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-certificate"></i> Certifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Match Types</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="fas fa-trophy"></i> Tournament
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-success w-100 btn-sm">
                                <i class="fas fa-league"></i> League
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-warning w-100 btn-sm">
                                <i class="fas fa-handshake"></i> Friendly
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-danger w-100 btn-sm">
                                <i class="fas fa-eject"></i> Knockout
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                                <i class="fas fa-object-group"></i> Group Stage
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                                <i class="fas fa-theater-masks"></i> Exhibition
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

