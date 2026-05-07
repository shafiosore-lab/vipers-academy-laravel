@extends('layouts.admin')

@section('title', 'Tournament Schedule - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-calendar-event"></i> Tournament Schedule
            </h1>
            <p class="text-muted mb-0">{{ $tournament->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Tournament
            </a>
        </div>
    </div>

    <!-- Tournament Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-people"></i> Total Teams</h6>
                    <h2 class="mb-0">{{ $tournament->teams->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-calendar-check"></i> Scheduled Matches</h6>
                    <h2 class="mb-0">{{ $scheduledMatches }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-exclamation-circle"></i> Pending Matches</h6>
                    <h2 class="mb-0">{{ $pendingMatches }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-check-circle"></i> Completed</h6>
                    <h2 class="mb-0">{{ $completedMatches }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.tournaments.schedule.config', $tournament->id) }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-gear"></i> Configure Settings
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.tournaments.schedule.time-slots', $tournament->id) }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-clock"></i> View Time Slots
                            </a>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('admin.tournaments.schedule.auto-schedule', $tournament->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" {{ $tournament->teams->count() < 2 ? 'disabled' : '' }}>
                                    <i class="bi bi-magic"></i> Auto Schedule
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.tournaments.schedule.bulk-schedule', $tournament->id) }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-pencil-square"></i> Manual Schedule
                            </a>
                        </div>
                    </div>
                    @if($tournament->teams->count() < 2)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> You need at least 2 teams to generate a schedule.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Configuration Summary -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Scheduling Configuration</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Default Match Duration</strong></td>
                            <td>{{ $config['match_duration'] ?? 90 }} minutes</td>
                        </tr>
                        <tr>
                            <td><strong>Rest Between Matches</strong></td>
                            <td>{{ $config['rest_between_matches'] ?? 30 }} minutes</td>
                        </tr>
                        <tr>
                            <td><strong>Max Games Per Venue/Day</strong></td>
                            <td>{{ $config['max_games_per_venue_per_day'] ?? 4 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Min Rest Hours</strong></td>
                            <td>{{ $config['min_rest_hours'] ?? 24 }} hours</td>
                        </tr>
                        <tr>
                            <td><strong>Scheduling Algorithm</strong></td>
                            <td>{{ ucfirst($config['algorithm'] ?? 'balanced') }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('admin.tournaments.schedule.config', $tournament->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit Configuration
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Schedule Statistics</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Tournament Start</strong></td>
                            <td>{{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M d, Y') : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tournament End</strong></td>
                            <td>{{ $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Days Available</strong></td>
                            <td>{{ $totalDays }}</td>
                        </tr>
                        <tr>
                            <td><strong>Venues</strong></td>
                            <td>{{ $tournament->venues->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Required Matches</strong></td>
                            <td>{{ $requiredMatches }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Schedule -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar3"></i> Match Schedule</h5>
                    <div>
                        <a href="{{ route('admin.tournaments.schedule.constraints', $tournament->id) }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-exclamation-triangle"></i> Check Constraints
                        </a>
                        @if($scheduledMatches > 0)
                            <form action="{{ route('admin.tournaments.schedule.clear', $tournament->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to clear all scheduled matches?')">
                                    <i class="bi bi-trash"></i> Clear Schedule
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($matches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Home Team</th>
                                        <th>Away Team</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches->sortBy('match_date') as $match)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}</td>
                                        <td>{{ $match->start_time ? \Carbon\Carbon::parse($match->start_time)->format('H:i') : 'TBD' }}</td>
                                        <td>{{ $match->homeTeam->name ?? 'TBD' }}</td>
                                        <td>{{ $match->awayTeam->name ?? 'TBD' }}</td>
                                        <td>{{ $match->venue->name ?? 'TBD' }}</td>
                                        <td>
                                            @switch($match->status)
                                                @case('scheduled')
                                                    <span class="badge bg-info">Scheduled</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-warning">In Progress</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @case('postponed')
                                                    <span class="badge bg-secondary">Postponed</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $match->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <h4 class="mt-3">No Matches Scheduled</h4>
                            <p class="text-muted">Use Auto Schedule or Manual Schedule to create your match schedule.</p>
                            <div class="mt-3">
                                <form action="{{ route('admin.tournaments.schedule.auto-schedule', $tournament->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" {{ $tournament->teams->count() < 2 ? 'disabled' : '' }}>
                                        <i class="bi bi-magic"></i> Auto Schedule
                                    </button>
                                </form>
                                <a href="{{ route('admin.tournaments.schedule.bulk-schedule', $tournament->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil-square"></i> Manual Schedule
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
