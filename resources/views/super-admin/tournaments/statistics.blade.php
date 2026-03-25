@extends('layouts.admin')

@section('title', 'Statistics - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Statistics - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Overview Stats -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="h2 mb-0 text-primary">{{ $stats['overview']['total_teams'] }}</div>
                    <small class="text-muted">Total Teams</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="h2 mb-0 text-success">{{ $stats['overview']['approved_teams'] }}</div>
                    <small class="text-muted">Approved Teams</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="h2 mb-0 text-warning">{{ $stats['overview']['pending_teams'] }}</div>
                    <small class="text-muted">Pending Approval</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="h2 mb-0 text-info">{{ $stats['overview']['total_players'] }}</div>
                    <small class="text-muted">Total Players</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Registration Stats -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Registration Overview</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="py-2">Total Teams Registered</td>
                                    <td class="py-2 text-end fw-bold">{{ $stats['overview']['total_teams'] }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Approved Teams</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $stats['overview']['approved_teams'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Pending Teams</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $stats['overview']['pending_teams'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Rejected Teams</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-danger-subtle text-danger">
                                            {{ $stats['overview']['rejected_teams'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Total Players</td>
                                    <td class="py-2 text-end fw-bold">{{ $stats['overview']['total_players'] }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Verified Players</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $stats['overview']['verified_players'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Pending Verification</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $stats['overview']['pending_players'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Match Stats -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Match Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="py-2">Total Matches</td>
                                    <td class="py-2 text-end fw-bold">{{ $stats['matches']['total'] }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Completed</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $stats['matches']['completed'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Scheduled</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $stats['matches']['scheduled'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">In Progress</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $stats['matches']['in_progress'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Postponed</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-warning-subtle text-warning">
                                            {{ $stats['matches']['postponed'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">Cancelled</td>
                                    <td class="py-2 text-end">
                                        <span class="badge bg-danger-subtle text-danger">
                                            {{ $stats['matches']['cancelled'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Goals Statistics -->
    <div class="row g-3 mt-1">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Goals Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="h3 mb-0 text-danger">{{ $stats['goals']['total'] }}</div>
                            <small class="text-muted">Total Goals</small>
                        </div>
                        <div class="col-md-4">
                            <div class="h3 mb-0 text-info">{{ $stats['goals']['home_avg'] }}</div>
                            <small class="text-muted">Home Team Avg Goals</small>
                        </div>
                        <div class="col-md-4">
                            <div class="h3 mb-0 text-warning">{{ $stats['goals']['away_avg'] }}</div>
                            <small class="text-muted">Away Team Avg Goals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournament Info -->
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">Tournament Information</h6>
        </div>
        <div class="card-body py-2">
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">Tournament Name</small>
                    <div class="fw-bold">{{ $tournament->name }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Organization</small>
                    <div class="fw-bold">{{ $tournament->organization->name ?? 'N/A' }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Season</small>
                    <div class="fw-bold">{{ $tournament->season ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <small class="text-muted">Status</small>
                    <div>
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
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Start Date</small>
                    <div class="fw-bold">{{ $tournament->start_date?->format('M d, Y') ?? 'Not set' }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">End Date</small>
                    <div class="fw-bold">{{ $tournament->end_date?->format('M d, Y') ?? 'Not set' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
