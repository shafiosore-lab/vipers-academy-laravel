@extends('layouts.admin')

@section('title', 'Statistics - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Tournament Statistics - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                Statistics
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Tournament
            </a>
            <button id="refresh-stats-btn" class="btn btn-sm btn-outline-primary" title="Refresh Statistics">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Statistics last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="statisticsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-tachometer-alt me-2"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="top-scorers-tab" data-bs-toggle="tab" data-bs-target="#top-scorers" type="button" role="tab">
                <i class="fas fa-trophy me-2"></i>Top Scorers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="discipline-tab" data-bs-toggle="tab" data-bs-target="#discipline" type="button" role="tab">
                <i class="fas fa-gavel me-2"></i>Discipline
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab">
                <i class="fas fa-layer-group me-2"></i>Groups/Pools
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rankings-tab" data-bs-toggle="tab" data-bs-target="#rankings" type="button" role="tab">
                <i class="fas fa-chart-line me-2"></i>Rankings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab">
                <i class="fas fa-file-alt me-2"></i>Summary
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="statisticsTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row">
                <!-- Tournament Summary Cards -->
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Teams</h6>
                                    <h4 class="mb-0">{{ $summary['total_teams'] }}</h4>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Matches</h6>
                                    <h4 class="mb-0">{{ $summary['total_matches'] }}</h4>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-futbol fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Completed</h6>
                                    <h4 class="mb-0">{{ $summary['completed_matches'] }}</h4>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Total Goals</h6>
                                    <h4 class="mb-0">{{ $summary['total_goals'] }}</h4>
                                </div>
                                <div class="text-warning">
                                    <i class="fas fa-football-ball fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Tournament Progress</h6>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-primary" style="width: {{ $summary['progress_percentage'] }}%">
                                    {{ $summary['progress_percentage'] }}%
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Current Stage: <span class="badge bg-secondary">{{ $summary['current_stage'] }}</span>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Top Scorers Preview -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Top Scorers</h6>
                        </div>
                        <div class="card-body">
                            @if($topScorers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Player</th>
                                                <th>Team</th>
                                                <th class="text-end">Goals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topScorers->take(5) as $player)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                            {{ substr($player->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $player->name }}</div>
                                                            <small class="text-muted">{{ $player->position }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $player->tournamentSquads->first()->tournamentTeam->team_name ?? 'N/A' }}</td>
                                                <td class="text-end fw-bold">{{ $player->goals_count }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament) }}" class="btn btn-sm btn-outline-primary">View All</a>
                            @else
                                <p class="text-muted text-center">No goals scored yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Discipline Preview -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Discipline Summary</h6>
                        </div>
                        <div class="card-body">
                            @if($teamCards->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Team</th>
                                                <th class="text-center">Yellow</th>
                                                <th class="text-center">Red</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teamCards->take(5) as $team)
                                            <tr>
                                                <td>{{ $team->team_name }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning">{{ $team->yellow_cards }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ $team->red_cards }}</span>
                                                </td>
                                                <td class="text-center fw-bold">{{ $team->yellow_cards + $team->red_cards }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('admin.tournaments.statistics.discipline', $tournament) }}" class="btn btn-sm btn-outline-primary">View All</a>
                            @else
                                <p class="text-muted text-center">No cards recorded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Groups/Pools Preview -->
                @if($groups->count() > 0)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Group Standings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($groups as $group)
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <h6 class="text-muted mb-2">Group {{ $group->name }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Team</th>
                                                    <th class="text-center">P</th>
                                                    <th class="text-center">Pts</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($group->teams->take(3) as $team)
                                                <tr>
                                                    <td>{{ $team->team_name }}</td>
                                                    <td class="text-center">{{ $team->standings->played ?? 0 }}</td>
                                                    <td class="text-center fw-bold">{{ $team->standings->points ?? 0 }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{ route('admin.tournaments.statistics.groups', $tournament) }}" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Top Scorers Tab -->
        <div class="tab-pane fade" id="top-scorers" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Top Scorers</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Detailed player statistics coming soon. <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament) }}">View Full Top Scorers</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discipline Tab -->
        <div class="tab-pane fade" id="discipline" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Discipline Statistics</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Detailed discipline statistics coming soon. <a href="{{ route('admin.tournaments.statistics.discipline', $tournament) }}">View Full Discipline</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups Tab -->
        <div class="tab-pane fade" id="groups" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Groups/Pools Standings</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Detailed group standings coming soon. <a href="{{ route('admin.tournaments.statistics.groups', $tournament) }}">View Full Groups</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rankings Tab -->
        <div class="tab-pane fade" id="rankings" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Tournament Rankings</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Detailed rankings coming soon. <a href="{{ route('admin.tournaments.statistics.rankings', $tournament) }}">View Full Rankings</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Tab -->
        <div class="tab-pane fade" id="summary" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Tournament Summary</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Detailed tournament summary coming soon. <a href="{{ route('admin.tournaments.statistics.summary', $tournament) }}">View Full Summary</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Real-time Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-stats-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshStatistics();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshStatistics();
    });

    function refreshStatistics() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Statistics last updated: ' + now.toLocaleString();

                // Update summary cards (would need to implement DOM updates)
                console.log('Statistics refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing statistics:', error);
            });
    }
});
</script>
@endsection
