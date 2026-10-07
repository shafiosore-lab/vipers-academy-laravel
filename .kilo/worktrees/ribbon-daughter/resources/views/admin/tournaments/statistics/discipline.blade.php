@extends('layouts.admin')

@section('title', 'Discipline - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Discipline Statistics - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}">Statistics</a>
                <span class="mx-1">/</span>
                Discipline
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <button id="refresh-discipline-btn" class="btn btn-sm btn-outline-primary" title="Refresh Discipline">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Discipline statistics last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Cards</h6>
                            <h4 class="mb-0">{{ $cardDistribution['total_cards'] }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-gavel fa-2x"></i>
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
                            <h6 class="card-title text-muted">Yellow Cards</h6>
                            <h4 class="mb-0">{{ $cardDistribution['yellow_cards'] }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $cardDistribution['yellow_percentage'] }}% of total
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Red Cards</h6>
                            <h4 class="mb-0">{{ $cardDistribution['red_cards'] }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $cardDistribution['red_percentage'] }}% of total
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Cards per Match</h6>
                            <h4 class="mb-0">{{ $cardDistribution['cards_per_match'] }}</h4>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="disciplineTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="team-discipline-tab" data-bs-toggle="tab" data-bs-target="#team-discipline" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Team Discipline
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="player-discipline-tab" data-bs-toggle="tab" data-bs-target="#player-discipline" type="button" role="tab">
                <i class="fas fa-user me-2"></i>Player Discipline
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="card-distribution-tab" data-bs-toggle="tab" data-bs-target="#card-distribution" type="button" role="tab">
                <i class="fas fa-chart-pie me-2"></i>Card Distribution
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="suspensions-tab" data-bs-toggle="tab" data-bs-target="#suspensions" type="button" role="tab">
                <i class="fas fa-ban me-2"></i>Suspensions
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="disciplineTabContent">
        <!-- Team Discipline Tab -->
        <div class="tab-pane fade show active" id="team-discipline" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Team Discipline</h6>
                </div>
                <div class="card-body">
                    @if($teamCards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Team</th>
                                        <th class="text-center">Yellow Cards</th>
                                        <th class="text-center">Red Cards</th>
                                        <th class="text-center">Total Cards</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Cards per Match</th>
                                        <th class="text-center">Fair Play Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamCards as $index => $team)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'secondary' : 'light' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                    {{ substr($team->team_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $team->team_name }}</div>
                                                    <small class="text-muted">{{ $team->matches_played }} matches</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $team->yellow_cards }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $team->red_cards }}</span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $team->yellow_cards + $team->red_cards }}
                                        </td>
                                        <td class="text-center">
                                            {{ $team->matches_played }}
                                        </td>
                                        <td class="text-center">
                                            {{ $team->matches_played > 0 ? round(($team->yellow_cards + $team->red_cards) / $team->matches_played, 2) : 0 }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                {{ 100 - ($team->yellow_cards * 1) - ($team->red_cards * 3) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No discipline issues recorded</h5>
                            <p class="text-muted">Team discipline statistics will appear here once cards are issued.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Player Discipline Tab -->
        <div class="tab-pane fade" id="player-discipline" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Player Discipline</h6>
                </div>
                <div class="card-body">
                    @if($playerCards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Player</th>
                                        <th>Team</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Yellow Cards</th>
                                        <th class="text-center">Red Cards</th>
                                        <th class="text-center">Total Cards</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Card Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($playerCards as $index => $player)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'secondary' : 'light' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
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
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $player->yellow_cards }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $player->red_cards }}</span>
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $player->total_cards }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $player->matches_played > 0 ? round($player->total_cards / $player->matches_played, 2) : 0 }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No player discipline issues</h5>
                            <p class="text-muted">Player discipline statistics will appear here once cards are issued.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Distribution Tab -->
        <div class="tab-pane fade" id="card-distribution" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Card Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h6 class="card-title">Card Types Distribution</h6>
                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $cardDistribution['yellow_percentage'] }}%">
                                            Yellow: {{ $cardDistribution['yellow_cards'] }}
                                        </div>
                                        <div class="progress-bar bg-danger" style="width: {{ $cardDistribution['red_percentage'] }}%">
                                            Red: {{ $cardDistribution['red_cards'] }}
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <span class="badge bg-warning fs-4">{{ $cardDistribution['yellow_percentage'] }}%</span>
                                            <div class="text-muted">Yellow Cards</div>
                                        </div>
                                        <div class="col-6">
                                            <span class="badge bg-danger fs-4">{{ $cardDistribution['red_percentage'] }}%</span>
                                            <div class="text-muted">Red Cards</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h6 class="card-title">Discipline Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="display-6 text-warning">{{ $cardDistribution['yellow_cards'] }}</div>
                                            <div class="text-muted">Yellow</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="display-6 text-danger">{{ $cardDistribution['red_cards'] }}</div>
                                            <div class="text-muted">Red</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="display-6 text-info">{{ $cardDistribution['cards_per_match'] }}</div>
                                            <div class="text-muted">Per Match</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspensions Tab -->
        <div class="tab-pane fade" id="suspensions" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Suspensions</h6>
                </div>
                <div class="card-body">
                    @if($suspensions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Player</th>
                                        <th>Team</th>
                                        <th class="text-center">Suspension Type</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Start Date</th>
                                        <th class="text-center">End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspensions as $suspension)
                                    <tr>
                                        <td>{{ $suspension->player->name }}</td>
                                        <td>{{ $suspension->player->team->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $suspension->type }}</span>
                                        </td>
                                        <td class="text-center">{{ $suspension->matches }}</td>
                                        <td class="text-center">{{ $suspension->start_date->format('M d, Y') }}</td>
                                        <td class="text-center">{{ $suspension->end_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($suspension->is_active)
                                                <span class="badge bg-warning">Active</span>
                                            @else
                                                <span class="badge bg-success">Served</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No suspensions recorded</h5>
                            <p class="text-muted">Player suspensions will appear here when red cards or accumulation rules apply.</p>
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
    const refreshBtn = document.getElementById('refresh-discipline-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshDiscipline();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshDiscipline();
    });

    function refreshDiscipline() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Discipline statistics last updated: ' + now.toLocaleString();

                // Update statistics (would need to implement DOM updates)
                console.log('Discipline statistics refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing discipline statistics:', error);
            });
    }
});
</script>
@endsection
