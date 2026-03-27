@extends('layouts.admin')

@section('title', 'Schedule Constraints - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-exclamation-triangle"></i> Schedule Constraints
            </h1>
            <p class="text-muted mb-0">{{ $tournament->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Schedule
            </a>
        </div>
    </div>

    <!-- Overall Status -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card {{ $constraints['is_valid'] ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-check-circle"></i> Overall Status</h6>
                    <h2 class="mb-0">{{ $constraints['is_valid'] ? 'Valid' : 'Violations Found' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-exclamation-circle"></i> Total Violations</h6>
                    <h2 class="mb-0">{{ count($constraints['violations'] ?? []) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-calendar-check"></i> Scheduled Matches</h6>
                    <h2 class="mb-0">{{ $constraints['total_matches'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Violations -->
    @if(count($constraints['violations'] ?? []) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Constraint Violations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Severity</th>
                                    <th>Match</th>
                                    <th>Description</th>
                                    <th>Suggested Fix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($constraints['violations'] as $violation)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $violation['type'] ?? 'Unknown' }}</span>
                                    </td>
                                    <td>
                                        @switch($violation['severity'] ?? 'medium')
                                            @case('high')
                                                <span class="badge bg-danger">High</span>
                                                @break
                                            @case('medium')
                                                <span class="badge bg-warning text-dark">Medium</span>
                                                @break
                                            @case('low')
                                                <span class="badge bg-info">Low</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">Unknown</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $violation['home_team'] ?? 'TBD' }} vs {{ $violation['away_team'] ?? 'TBD' }}
                                    </td>
                                    <td>{{ $violation['message'] ?? 'No description available' }}</td>
                                    <td>{{ $violation['suggestion'] ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle-fill"></i> No Violations Found</h5>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle display-1 text-success"></i>
                    <h4 class="mt-3">Schedule is Valid!</h4>
                    <p class="text-muted">All scheduling constraints have been satisfied.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Constraint Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Constraint Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Matches</strong></td>
                                    <td>{{ $constraints['total_matches'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Scheduled Matches</strong></td>
                                    <td>{{ $constraints['scheduled_matches'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Venues Used</strong></td>
                                    <td>{{ $constraints['venues_used'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Days Used</strong></td>
                                    <td>{{ $constraints['days_used'] ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Min Team Rest</strong></td>
                                    <td>{{ $constraints['min_team_rest'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Max Venue/Day</strong></td>
                                    <td>{{ $config['max_games_per_venue_per_day'] ?? 4 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Match Duration</strong></td>
                                    <td>{{ $config['match_duration'] ?? 90 }} min</td>
                                </tr>
                                <tr>
                                    <td><strong>Rest Between</strong></td>
                                    <td>{{ $config['rest_between_matches'] ?? 30 }} min</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
