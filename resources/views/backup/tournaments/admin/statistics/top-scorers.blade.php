@extends('layouts.admin')

@section('title', 'Top Scorers - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Top Scorers - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}">Statistics</a>
                <span class="mx-1">/</span>
                Top Scorers
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <button id="refresh-top-scorers-btn" class="btn btn-sm btn-outline-primary" title="Refresh Top Scorers">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Top scorers last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
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

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Top Scorer</h6>
                            <h4 class="mb-0">{{ $topScorers->first()->goals_count ?? 0 }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $topScorers->first()->name ?? 'N/A' }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Average Goals</h6>
                            <h4 class="mb-0">{{ $summary['avg_goals_per_match'] }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chart-line fa-2x"></i>
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
                            <h6 class="card-title text-muted">Matches</h6>
                            <h4 class="mb-0">{{ $summary['completed_matches'] }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-futbol fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="topScorersTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="goals-tab" data-bs-toggle="tab" data-bs-target="#goals" type="button" role="tab">
                <i class="fas fa-trophy me-2"></i>Goals
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="assists-tab" data-bs-toggle="tab" data-bs-target="#assists" type="button" role="tab">
                <i class="fas fa-handshake me-2"></i>Assists
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#contributions" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Contributions
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="topScorersTabContent">
        <!-- Goals Tab -->
        <div class="tab-pane fade show active" id="goals" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top Goalscorers</h6>
                </div>
                <div class="card-body">
                    @if($topScorers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Player</th>
                                        <th>Team</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Goals</th>
                                        <th class="text-center">Assists</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Goals per Match</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topScorers as $index => $player)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-lg bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($player->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $player->name }}</div>
                                                    <small class="text-muted">{{ $player->position }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $player->tournamentSquads->first()->tournamentTeam->team_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $player->position }}</span>
                                        </td>
                                        <td class="text-center fw-bold text-success">
                                            {{ $player->goals_count }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->assists_count ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played > 0 ? round($player->goals_count / $player->matches_played, 2) : 0 }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No goals scored yet</h5>
                            <p class="text-muted">Goals will appear here once matches are played and goals are scored.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assists Tab -->
        <div class="tab-pane fade" id="assists" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top Assist Providers</h6>
                </div>
                <div class="card-body">
                    @if($topAssists->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Player</th>
                                        <th>Team</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Assists</th>
                                        <th class="text-center">Goals</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Assists per Match</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topAssists as $index => $player)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-lg bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($player->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $player->name }}</div>
                                                    <small class="text-muted">{{ $player->position }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $player->tournamentSquads->first()->tournamentTeam->team_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $player->position }}</span>
                                        </td>
                                        <td class="text-center fw-bold text-info">
                                            {{ $player->assists_count }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->goals_count ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played > 0 ? round($player->assists_count / $player->matches_played, 2) : 0 }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No assists recorded yet</h5>
                            <p class="text-muted">Assists will appear here once matches are played and assists are recorded.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contributions Tab -->
        <div class="tab-pane fade" id="contributions" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top Goal Contributors</h6>
                </div>
                <div class="card-body">
                    @if($topGoalContributions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Player</th>
                                        <th>Team</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Goals</th>
                                        <th class="text-center">Assists</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Contribution Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topGoalContributions as $index => $player)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-lg bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($player->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $player->name }}</div>
                                                    <small class="text-muted">{{ $player->position }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $player->tournamentSquads->first()->tournamentTeam->team_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $player->position }}</span>
                                        </td>
                                        <td class="text-center fw-bold text-success">
                                            {{ $player->goals }}
                                        </td>
                                        <td class="text-center fw-bold text-info">
                                            {{ $player->assists }}
                                        </td>
                                        <td class="text-center fw-bold text-warning">
                                            {{ $player->total_contributions }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->total_contributions > 0 ? round(($player->total_contributions / $summary['total_goals']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No contributions recorded yet</h5>
                            <p class="text-muted">Goal contributions will appear here once matches are played.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Real-time Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-top-scorers-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshTopScorers();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshTopScorers();
    });

    function refreshTopScorers() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Top scorers last updated: ' + now.toLocaleString();

                // Update statistics (would need to implement DOM updates)
                console.log('Top scorers refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing top scorers:', error);
            });
    }
});
</script>
@endsection
