@extends('layouts.admin')

@section('title', 'Rankings - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Tournament Rankings - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}">Statistics</a>
                <span class="mx-1">/</span>
                Rankings
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <button id="refresh-rankings-btn" class="btn btn-sm btn-outline-primary" title="Refresh Rankings">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Rankings last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Tournament Format Info -->
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-trophy me-2"></i>
        <strong>Tournament Format:</strong> {{ $formatInfo['name'] }} - {{ $formatInfo['description'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="rankingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="standings-tab" data-bs-toggle="tab" data-bs-target="#standings" type="button" role="tab">
                <i class="fas fa-list-ol me-2"></i>Standings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="elo-rankings-tab" data-bs-toggle="tab" data-bs-target="#elo-rankings" type="button" role="tab">
                <i class="fas fa-chess-knight me-2"></i>ELO Rankings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="form-table-tab" data-bs-toggle="tab" data-bs-target="#form-table" type="button" role="tab">
                <i class="fas fa-chart-line me-2"></i>Form Table
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="format-specific-tab" data-bs-toggle="tab" data-bs-target="#format-specific" type="button" role="tab">
                <i class="fas fa-layer-group me-2"></i>Format View
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="rankingsTabContent">
        <!-- Standings Tab -->
        <div class="tab-pane fade show active" id="standings" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Tournament Standings</h6>
                </div>
                <div class="card-body">
                    @if($rankings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Pos</th>
                                        <th>Team</th>
                                        <th class="text-center">P</th>
                                        <th class="text-center">W</th>
                                        <th class="text-center">D</th>
                                        <th class="text-center">L</th>
                                        <th class="text-center">GF</th>
                                        <th class="text-center">GA</th>
                                        <th class="text-center">GD</th>
                                        <th class="text-center">Pts</th>
                                        <th class="text-center">Form</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rankings as $standing)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $standing->position <= 3 ? 'success' : ($standing->position <= 6 ? 'warning' : 'secondary') }}">
                                                {{ $standing->position }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-lg bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($standing->team->team_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $standing->team->team_name }}</div>
                                                    <small class="text-muted">{{ $standing->team->organization->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $standing->played }}</td>
                                        <td class="text-center">{{ $standing->won }}</td>
                                        <td class="text-center">{{ $standing->drawn }}</td>
                                        <td class="text-center">{{ $standing->lost }}</td>
                                        <td class="text-center">{{ $standing->goals_for }}</td>
                                        <td class="text-center">{{ $standing->goals_against }}</td>
                                        <td class="text-center">
                                            <span class="{{ $standing->goal_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $standing->goal_difference > 0 ? '+' : '' }}{{ $standing->goal_difference }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $standing->points }}</td>
                                        <td class="text-center">
                                            {!! $standing->getFormDisplay() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list-ol fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No standings available</h5>
                            <p class="text-muted">Standings will appear here once matches are played.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ELO Rankings Tab -->
        <div class="tab-pane fade" id="elo-rankings" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">ELO Rankings</h6>
                </div>
                <div class="card-body">
                    @if($eloRankings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th>Team</th>
                                        <th class="text-center">ELO Rating</th>
                                        <th class="text-center">Matches</th>
                                        <th class="text-center">Win %</th>
                                        <th class="text-center">Strength</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eloRankings as $index => $team)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
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
                                                    <small class="text-muted">{{ $team->organization->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-primary">{{ $team->elo_rating }}</td>
                                        <td class="text-center">{{ $team->elo_matches }}</td>
                                        <td class="text-center">
                                            {{ $team->elo_matches > 0 ? round(($team->elo_wins / $team->elo_matches) * 100, 1) : 0 }}%
                                        </td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-primary" style="width: {{ min(100, ($team->elo_rating / 2000) * 100) }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chess-knight fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">ELO rankings not available</h5>
                            <p class="text-muted">ELO ratings will be calculated once matches are played.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Table Tab -->
        <div class="tab-pane fade" id="form-table" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Form Table (Last 5 Matches)</h6>
                </div>
                <div class="card-body">
                    @if($formTable->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Pos</th>
                                        <th>Team</th>
                                        <th class="text-center">Form</th>
                                        <th class="text-center">Points</th>
                                        <th class="text-center">Goals For</th>
                                        <th class="text-center">Goals Against</th>
                                        <th class="text-center">Win Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formTable as $standing)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $standing->points >= 12 ? 'success' : ($standing->points >= 6 ? 'warning' : 'danger') }}">
                                                {{ $standing->position }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                    {{ substr($standing->team->team_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $standing->team->team_name }}</div>
                                                    <small class="text-muted">{{ $standing->team->organization->name ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {!! $standing->getFormDisplay() !!}
                                        </td>
                                        <td class="text-center fw-bold">{{ $standing->points }}</td>
                                        <td class="text-center">{{ $standing->goals_for }}</td>
                                        <td class="text-center">{{ $standing->goals_against }}</td>
                                        <td class="text-center">
                                            {{ $standing->getWinPercentage() }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Form data not available</h5>
                            <p class="text-muted">Form will be calculated once teams have played matches.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Format-Specific Tab -->
        <div class="tab-pane fade" id="format-specific" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Format-Specific Rankings</h6>
                </div>
                <div class="card-body">
                    @if($formatInfo['has_groups'])
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This tournament uses a group format. View group standings in the Groups/Pools section.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('admin.tournaments.statistics.groups', $tournament) }}" class="btn btn-primary">
                                <i class="fas fa-layer-group me-2"></i> View Groups
                            </a>
                        </div>
                    @elseif($formatInfo['name'] === 'Knockout' || $formatInfo['name'] === 'Knockout with Third Place')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This tournament uses a knockout format. View bracket progression in the Matches section.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('admin.tournaments.matches.index', $tournament) }}" class="btn btn-primary">
                                <i class="fas fa-futbol me-2"></i> View Matches
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This tournament uses {{ $formatInfo['name'] }} format. Standard league table applies.
                        </div>
                        <div class="text-center">
                            <a href="#standings" class="btn btn-primary">
                                <i class="fas fa-list-ol me-2"></i> View Standings
                            </a>
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
    const refreshBtn = document.getElementById('refresh-rankings-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshRankings();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshRankings();
    });

    function refreshRankings() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Rankings last updated: ' + now.toLocaleString();

                // Update rankings (would need to implement DOM updates)
                console.log('Rankings refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing rankings:', error);
            });
    }
});
</script>
@endsection
