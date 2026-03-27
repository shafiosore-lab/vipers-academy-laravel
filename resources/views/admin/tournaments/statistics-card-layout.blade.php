@extends('layouts.admin')

@section('title', 'Statistics - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-chart-bar text-primary"></i>{{ $tournament->name }}
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-chart-bar me-2"></i>Statistics Views
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Statistics Views</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.index', $tournament->id) }}">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.top-scorers', $tournament->id) }}">
                        <i class="fas fa-trophy text-warning me-2"></i>Top Scorers
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.discipline', $tournament->id) }}">
                        <i class="fas fa-gavel text-danger me-2"></i>Discipline
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.groups', $tournament->id) }}">
                        <i class="fas fa-layer-group text-info me-2"></i>Groups
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.rankings', $tournament->id) }}">
                        <i class="fas fa-list-ol text-success me-2"></i>Rankings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Export Options</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'pdf']) }}">
                        <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'csv']) }}">
                        <i class="fas fa-file-csv text-success me-2"></i>Export CSV
                    </a></li>
                </ul>
            </div>
            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-futbol me-2"></i>Matches
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Statistics Overview Cards -->
<div class="tournament-card-row mb-4">
    <!-- Total Goals Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Total Goals'"
        :subtitle="'Goals scored across all matches'"
        :value="$totalGoals"
        :subvalue="'avg ' . number_format($avgGoalsPerMatch, 2) . ' per match'"
        :icon="'fa-football-ball'"
        :color="'success'"
        :trend="[
            'color' => $totalGoals > 0 ? 'success' : 'secondary',
            'icon' => $totalGoals > 0 ? 'chart-line' : 'chart-line',
            'text' => $totalGoals > 0 ? 'Action packed' : 'No goals yet'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Top scorer: {{ $topScorer ? $topScorer->name : 'N/A' }}</small>
                <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament->id) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-trophy me-1"></i>Top Scorers
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Total Cards Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Disciplinary Cards'"
        :subtitle="'Yellow and red cards issued'"
        :value="$totalCards"
        :subvalue="'Y: ' . $yellowCards . ' / R: ' . $redCards"
        :icon="'fa-exclamation-triangle'"
        :color="'warning'"
        :trend="[
            'color' => $totalCards < 20 ? 'success' : ($totalCards < 50 ? 'warning' : 'danger'),
            'icon' => $totalCards < 20 ? 'thumbs-up' : ($totalCards < 50 ? 'exclamation-triangle' : 'exclamation-circle'),
            'text' => $totalCards < 20 ? 'Fair play' : ($totalCards < 50 ? 'Some issues' : 'High discipline')
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Most disciplined: {{ $mostDisciplinedTeam ? $mostDisciplinedTeam->team_name : 'N/A' }}</small>
                <a href="{{ route('admin.tournaments.statistics.discipline', $tournament->id) }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-gavel me-1"></i>Discipline Report
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Attendance Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Average Attendance'"
        :subtitle="'Fans per match'"
        :value="$avgAttendance"
        :subvalue="'total: ' . $totalAttendance"
        :icon="'fa-users'"
        :color="'info'"
        :trend="[
            'color' => $avgAttendance > 1000 ? 'success' : ($avgAttendance > 500 ? 'warning' : 'secondary'),
            'icon' => $avgAttendance > 1000 ? 'users' : ($avgAttendance > 500 ? 'users' : 'users'),
            'text' => $avgAttendance > 1000 ? 'Great turnout' : ($avgAttendance > 500 ? 'Good attendance' : 'Low attendance')
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Highest: {{ $highestAttendance }} fans</small>
                <small class="text-muted">Lowest: {{ $lowestAttendance }} fans</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Match Completion Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Match Completion'"
        :subtitle="'Matches played vs scheduled'"
        :value="$completionPercentage . '%'"
        :subvalue="$completedMatches . ' of ' . $totalMatches . ' matches'"
        :icon="'fa-check-circle'"
        :color="'primary'"
        :trend="[
            'color' => $completionPercentage >= 100 ? 'success' : ($completionPercentage >= 75 ? 'warning' : 'danger'),
            'icon' => $completionPercentage >= 100 ? 'check-circle' : ($completionPercentage >= 75 ? 'clock' : 'exclamation-triangle'),
            'text' => $completionPercentage >= 100 ? 'Tournament complete' : ($completionPercentage >= 75 ? 'Mostly complete' : 'Early stage')
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Status: {{ $completionPercentage >= 100 ? 'Completed' : ($completionPercentage >= 75 ? 'Advanced' : 'Early') }}</small>
                <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-futbol me-1"></i>Matches
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>
</div>

<!-- Top Scorers Section -->
@if($topScorers->count() > 0)
    <div class="card tournament-card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>Top Scorers
                    </h5>
                    <small class="text-muted">Leading goal scorers in the tournament</small>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament->id) }}" class="btn btn-outline-warning">
                        <i class="fas fa-list me-1"></i>Full Rankings
                    </a>
                    <a href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'top-scorers']) }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-1"></i>Export
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @foreach($topScorers->take(6) as $index => $player)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-badge bg-{{ $index < 3 ? 'warning' : 'secondary' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="h6 mb-0 text-{{ $index < 3 ? 'dark' : 'dark' }}">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <div class="h6 mb-0 fw-bold">{{ $player->name ?? 'Unknown' }}</div>
                                            <small class="text-muted">{{ $player->team_name ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="h4 mb-0 text-success">{{ $player->goals_count ?? 0 }}</div>
                                        <small class="text-muted">Goals</small>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="small text-muted">Assists</div>
                                        <div class="h6 mb-0">{{ $player->assists_count ?? 0 }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Matches</div>
                                        <div class="h6 mb-0">{{ $player->matches_played ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Discipline Summary Section -->
@if($disciplineSummary->count() > 0)
    <div class="card tournament-card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-gavel text-danger me-2"></i>Discipline Summary
                    </h5>
                    <small class="text-muted">Teams with most disciplinary issues</small>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.tournaments.statistics.discipline', $tournament->id) }}" class="btn btn-outline-danger">
                        <i class="fas fa-list me-1"></i>Full Report
                    </a>
                    <a href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'discipline']) }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-1"></i>Export
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @foreach($disciplineSummary->take(4) as $team)
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <div class="h6 mb-0 fw-bold">{{ $team->team_name }}</div>
                                        <small class="text-muted">{{ $team->organization_name ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-warning">{{ $team->yellow_cards ?? 0 }}</div>
                                        <small class="text-muted">Y</small>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="h5 mb-0 text-danger">{{ $team->red_cards ?? 0 }}</div>
                                    <small class="text-muted">Red Cards</small>
                                </div>
                                <div class="mt-2 text-center">
                                    <span class="badge bg-{{ ($team->yellow_cards + $team->red_cards) > 10 ? 'danger' : 'warning' }}">
                                        {{ $team->yellow_cards + $team->red_cards }} Total
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Team Performance Section -->
@if($teamPerformance->count() > 0)
    <div class="card tournament-card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-info me-2"></i>Team Performance
                    </h5>
                    <small class="text-muted">Team statistics and performance metrics</small>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-list-ol me-1"></i>Standings
                    </a>
                    <a href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'team-performance']) }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-1"></i>Export
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @foreach($teamPerformance->take(8) as $team)
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <div class="h6 mb-0 fw-bold">{{ $team->team_name }}</div>
                                        <small class="text-muted">Position: {{ $team->position ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="h4 mb-0 text-primary">{{ $team->points ?? 0 }}</div>
                                        <small class="text-muted">Points</small>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="small text-muted">W</div>
                                        <div class="h6 mb-0 text-success">{{ $team->won ?? 0 }}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">D</div>
                                        <div class="h6 mb-0 text-warning">{{ $team->drawn ?? 0 }}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">L</div>
                                        <div class="h6 mb-0 text-danger">{{ $team->lost ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <span class="badge bg-{{ $team->goal_difference >= 0 ? 'success' : 'danger' }}">
                                        GD: {{ $team->goal_difference ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Match Statistics Section -->
<div class="card tournament-card mb-4 shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-futbol text-primary me-2"></i>Match Statistics
                </h5>
                <small class="text-muted">Detailed match analysis and trends</small>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-1"></i>Matches
                </a>
                <a href="{{ route('admin.tournaments.statistics.export', [$tournament->id, 'match-stats']) }}" class="btn btn-outline-success">
                    <i class="fas fa-download me-1"></i>Export
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            <!-- Match Status Distribution -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6 class="mb-3 fw-semibold">
                            <i class="fas fa-chart-pie text-info me-2"></i>Match Status Distribution
                        </h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h4 mb-0 text-success">{{ $completedMatches }}</div>
                                <small class="text-muted">Completed</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0 text-info">{{ $scheduledMatches }}</div>
                                <small class="text-muted">Scheduled</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0 text-warning">{{ $inProgressMatches }}</div>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Goal Distribution -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6 class="mb-3 fw-semibold">
                            <i class="fas fa-chart-bar text-success me-2"></i>Goal Distribution
                        </h6>
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="h5 mb-0 text-primary">{{ $homeGoals }}</div>
                                <small class="text-muted">Home</small>
                            </div>
                            <div class="col-3">
                                <div class="h5 mb-0 text-info">{{ $awayGoals }}</div>
                                <small class="text-muted">Away</small>
                            </div>
                            <div class="col-3">
                                <div class="h5 mb-0 text-warning">{{ $draws }}</div>
                                <small class="text-muted">Draws</small>
                            </div>
                            <div class="col-3">
                                <div class="h5 mb-0 text-danger">{{ $cleanSheets }}</div>
                                <small class="text-muted">Clean Sheets</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Statistics -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6 class="mb-3 fw-semibold">
                            <i class="fas fa-calculator text-warning me-2"></i>Average Statistics
                        </h6>
                        <div class="row text-center">
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-primary">{{ number_format($avgGoalsPerMatch, 2) }}</div>
                                <small class="text-muted">Goals/Match</small>
                            </div>
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-warning">{{ number_format($avgCardsPerMatch, 2) }}</div>
                                <small class="text-muted">Cards/Match</small>
                            </div>
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-info">{{ number_format($avgAttendance, 0) }}</div>
                                <small class="text-muted">Fans/Match</small>
                            </div>
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-success">{{ number_format($avgPossession, 1) }}%</div>
                                <small class="text-muted">Avg Possession</small>
                            </div>
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-danger">{{ number_format($avgShotsPerMatch, 1) }}</div>
                                <small class="text-muted">Shots/Match</small>
                            </div>
                            <div class="col-md-2">
                                <div class="h5 mb-0 text-secondary">{{ number_format($avgCornersPerMatch, 1) }}</div>
                                <small class="text-muted">Corners/Match</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card tournament-card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>Statistics Actions
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Top Scorers'"
                            :subtitle="'Goal scoring leaders'"
                            :icon="'fa-trophy'"
                            :color="'warning'"
                            :description="'View and analyze top goal scorers'"
                            :actions="[
                                ['url' => route('admin.tournaments.statistics.top-scorers', $tournament->id), 'label' => 'View Top Scorers', 'icon' => 'fa-list', 'style' => 'warning']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">{{ $topScorer ? $topScorer->goals_count . ' goals by ' . $topScorer->name : 'No goals yet' }}</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Discipline Report'"
                            :subtitle="'Card statistics'"
                            :icon="'fa-gavel'"
                            :color="'danger'"
                            :description="'View disciplinary records and trends'"
                            :actions="[
                                ['url' => route('admin.tournaments.statistics.discipline', $tournament->id), 'label' => 'View Discipline', 'icon' => 'fa-list', 'style' => 'danger']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">{{ $totalCards }} total cards issued</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Team Rankings'"
                            :subtitle="'Performance analysis'"
                            :icon="'fa-chart-line'"
                            :color="'info'"
                            :description="'View team performance statistics'"
                            :actions="[
                                ['url' => route('admin.tournaments.standings.index', $tournament->id), 'label' => 'View Standings', 'icon' => 'fa-list-ol', 'style' => 'info']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">{{ $teamPerformance->count() }} teams analyzed</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Export Reports'"
                            :subtitle="'Download statistics'"
                            :icon="'fa-download'"
                            :color="'success'"
                            :description="'Export tournament statistics'"
                            :actions="[
                                ['url' => route('admin.tournaments.statistics.export', [$tournament->id, 'pdf']), 'label' => 'Export PDF', 'icon' => 'fa-file-pdf', 'style' => 'danger'],
                                ['url' => route('admin.tournaments.statistics.export', [$tournament->id, 'csv']), 'label' => 'Export CSV', 'icon' => 'fa-file-csv', 'style' => 'success']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Multiple export formats available</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for statistics cards */
.position-badge {
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .position-badge {
        width: 35px;
        height: 35px;
    }

    .position-badge .h6 {
        font-size: 1rem;
    }

    .card-body .row.g-3 .col-md-3, .card-body .row.g-3 .col-md-4, .card-body .row.g-3 .col-md-6 {
        width: 100%;
    }
}

/* Hover effects for statistics cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Status-specific styling */
.position-badge.bg-warning {
    border-color: #ffc107;
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}

.position-badge.bg-secondary {
    border-color: #6c757d;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

/* Animation for statistics cards */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tournament-card {
    animation: fadeInSlide 0.4s ease-out;
}

/* Chart-like styling for statistics */
.stat-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
}

/* Status badge animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}
</style>

<script>
// Statistics page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Statistics card layout initialized');

    // Add click functionality to statistics cards
    const statCards = document.querySelectorAll('.card.h-100');
    statCards.forEach(card => {
        const statLink = card.querySelector('.btn-outline-primary, .btn-outline-warning, .btn-outline-danger, .btn-outline-info');
        if (statLink) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons
                if (e.target.closest('button') || e.target.closest('a')) {
                    return;
                }
                window.location.href = statLink.href;
            });
        }
    });

    // Add smooth animations for statistics numbers
    const statNumbers = document.querySelectorAll('.h4, .h5, .h6');
    statNumbers.forEach((number, index) => {
        number.style.animationDelay = `${index * 0.1}s`;
        number.style.animation = 'fadeInUp 0.6s ease-out forwards';
    });

    // Add auto-refresh functionality for live statistics
    setInterval(function() {
        // This could be used to refresh live statistics
        console.log('Auto-refreshing statistics data...');
    }, 60000); // Refresh every minute

    // Add interactive tooltips for statistics
    const statItems = document.querySelectorAll('.card-body .row.text-center > div');
    statItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

@endsection
