@extends('layouts.admin')

@section('title', 'Tournament Overview - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Tournament Management</h1>
            <small class="text-muted">Competition Overview Dashboard</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.tournaments.overview') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-chart-line"></i> Overview
            </a>
            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-list"></i> All Tournaments
            </a>
            <a href="{{ route('super-admin.tournaments.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> New Tournament
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Total</p>
                            <h5 class="mb-0">{{ $stats['total_tournaments'] }}</h5>
                            <small class="text-muted">Tournaments</small>
                        </div>
                        <div class="stat-icon bg-primary-subtle">
                            <i class="fas fa-trophy text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Active</p>
                            <h5 class="mb-0">{{ $stats['active_tournaments'] }}</h5>
                            <small class="text-muted">Open/Ongoing</small>
                        </div>
                        <div class="stat-icon bg-success-subtle">
                            <i class="fas fa-play-circle text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Draft</p>
                            <h5 class="mb-0">{{ $stats['draft_tournaments'] }}</h5>
                        </div>
                        <div class="stat-icon bg-secondary-subtle">
                            <i class="fas fa-edit text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Completed</p>
                            <h5 class="mb-0">{{ $stats['completed_tournaments'] }}</h5>
                        </div>
                        <div class="stat-icon bg-info-subtle">
                            <i class="fas fa-check-circle text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Cancelled</p>
                            <h5 class="mb-0">{{ $stats['cancelled_tournaments'] }}</h5>
                        </div>
                        <div class="stat-icon bg-danger-subtle">
                            <i class="fas fa-times-circle text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Teams</p>
                            <h5 class="mb-0">{{ $stats['total_teams'] }}</h5>
                        </div>
                        <div class="stat-icon bg-warning-subtle">
                            <i class="fas fa-users text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Players</p>
                            <h5 class="mb-0">{{ $stats['total_players'] }}</h5>
                        </div>
                        <div class="stat-icon bg-purple-subtle">
                            <i class="fas fa-user-friends text-purple"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Matches</p>
                            <h5 class="mb-0">{{ $stats['total_matches'] }}</h5>
                        </div>
                        <div class="stat-icon bg-dark-subtle">
                            <i class="fas fa-futbol text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-3">
        <!-- Ongoing Tournaments -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 small font-weight-bold">
                        <i class="fas fa-play text-success me-1"></i> Ongoing Tournaments
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($ongoingTournaments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2 px-2">Tournament</th>
                                        <th class="py-2 px-2">Organization</th>
                                        <th class="py-2 px-2">Teams</th>
                                        <th class="py-2 px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ongoingTournaments as $tournament)
                                    <tr>
                                        <td class="py-2 align-middle small">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="fw-bold text-decoration-none">
                                                {{ $tournament->name }}
                                            </a>
                                        </td>
                                        <td class="py-2 align-middle small">{{ $tournament->organization->name ?? 'N/A' }}</td>
                                        <td class="py-2 align-middle small">{{ $tournament->teams()->count() }}</td>
                                        <td class="py-2 align-middle">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-xs btn-primary py-0 px-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-trophy fa-2x mb-2 d-block opacity-50"></i>
                            <small>No ongoing tournaments</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Tournaments -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 small font-weight-bold">
                        <i class="fas fa-clock text-warning me-1"></i> Open for Registration
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($upcomingTournaments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2 px-2">Tournament</th>
                                        <th class="py-2 px-2">Deadline</th>
                                        <th class="py-2 px-2">Teams</th>
                                        <th class="py-2 px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingTournaments as $tournament)
                                    <tr>
                                        <td class="py-2 align-middle small">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="fw-bold text-decoration-none">
                                                {{ $tournament->name }}
                                            </a>
                                        </td>
                                        <td class="py-2 align-middle small">
                                            @if($tournament->registration_deadline)
                                                <span class="{{ $tournament->registration_deadline->isPast() ? 'text-danger' : 'text-success' }}">
                                                    {{ $tournament->registration_deadline->format('M d, Y') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 align-middle small">{{ $tournament->teams()->count() }} / {{ $tournament->max_teams ?? '∞' }}</td>
                                        <td class="py-2 align-middle">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-xs btn-primary py-0 px-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block opacity-50"></i>
                            <small>No upcoming tournaments</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Tournaments by Org -->
    <div class="row g-3 mt-2">
        <!-- Recent Tournaments -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 small font-weight-bold">
                        <i class="fas fa-history text-info me-1"></i> Recent Activity
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($recentTournaments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2 px-2">Tournament</th>
                                        <th class="py-2 px-2">Status</th>
                                        <th class="py-2 px-2">Updated</th>
                                        <th class="py-2 px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTournaments as $tournament)
                                    <tr>
                                        <td class="py-2 align-middle small">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="fw-bold text-decoration-none">
                                                {{ $tournament->name }}
                                            </a>
                                        </td>
                                        <td class="py-2 align-middle">
                                            @php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'open' => 'success',
                                                    'closed' => 'warning',
                                                    'ongoing' => 'primary',
                                                    'completed' => 'info',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">
                                                {{ ucfirst($tournament->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 align-middle small">{{ $tournament->updated_at->diffForHumans() }}</td>
                                        <td class="py-2 align-middle">
                                            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-xs btn-primary py-0 px-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <small>No tournaments found</small>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('super-admin.tournaments.index') }}" class="small">View All Tournaments <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Tournaments by Organization -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 small font-weight-bold">
                        <i class="fas fa-building text-purple me-1"></i> Tournaments by Organization
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($tournamentsByOrg->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2 px-2">Organization</th>
                                        <th class="py-2 px-2 text-center">Tournaments</th>
                                        <th class="py-2 px-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tournamentsByOrg as $item)
                                    <tr>
                                        <td class="py-2 align-middle small">{{ $item->organization->name ?? 'Unknown' }}</td>
                                        <td class="py-2 align-middle text-center">
                                            <span class="badge bg-primary">{{ $item->count }}</span>
                                        </td>
                                        <td class="py-2 align-middle text-center">
                                            <a href="{{ route('super-admin.tournaments.index', ['organization_id' => $item->organization_id]) }}" class="btn btn-xs btn-outline-primary py-0 px-1">
                                                <i class="fas fa-filter"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <small>No data available</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 small font-weight-bold">
                        <i class="fas fa-bolt text-warning me-1"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body py-3">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.tournaments.create') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-plus d-block mb-1"></i>
                                Create Tournament
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.tournaments.index', ['status' => 'draft']) }}" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-edit d-block mb-1"></i>
                                Draft Tournaments
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.tournaments.index', ['status' => 'open']) }}" class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-door-open d-block mb-1"></i>
                                Open Registration
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.tournaments.index', ['status' => 'ongoing']) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-play d-block mb-1"></i>
                                Ongoing Tournaments
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.tournaments.index', ['status' => 'completed']) }}" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-check-circle d-block mb-1"></i>
                                Completed
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-outline-purple btn-sm w-100">
                                <i class="fas fa-building d-block mb-1"></i>
                                Manage Organizations
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
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .bg-primary-subtle { background-color: rgba(0, 123, 255, 0.1); }
    .bg-success-subtle { background-color: rgba(40, 167, 69, 0.1); }
    .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1); }
    .bg-info-subtle { background-color: rgba(23, 162, 184, 0.1); }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    .bg-purple-subtle { background-color: rgba(111, 66, 193, 0.1); }
    .bg-dark-subtle { background-color: rgba(33, 37, 41, 0.1); }
    .text-purple { color: #6f42c1; }
    .btn-outline-purple { border-color: #6f42c1; color: #6f42c1; }
    .btn-outline-purple:hover { background-color: #6f42c1; color: white; }
</style>
@endpush
