@extends('layouts.admin')

@section('title', $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    {{ $tournament->name }}
                    @php $statusColors = ['draft' => 'secondary', 'open' => 'success', 'closed' => 'warning', 'ongoing' => 'primary', 'completed' => 'info', 'cancelled' => 'danger']; @endphp
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }} fs-6">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-2"></i>Edit Tournament
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.standings.index', $tournament->id) }}">
                        <i class="fas fa-list-ol text-primary me-2"></i>View Standings
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.index', $tournament->id) }}">
                        <i class="fas fa-futbol text-info me-2"></i>View Matches
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.teams.index', $tournament->id) }}">
                        <i class="fas fa-users text-success me-2"></i>View Teams
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Quick Actions</h6></li>
                    @if(in_array($tournament->status, ['draft', 'closed']))
                        <li>
                            <form action="{{ route('admin.tournaments.open-registration', $tournament->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-door-open text-success me-2"></i>Open Registration
                                </button>
                            </form>
                        </li>
                    @endif
                    @if($tournament->status == 'open')
                        <li>
                            <form action="{{ route('admin.tournaments.close-registration', $tournament->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-door-closed text-warning me-2"></i>Close Registration
                                </button>
                            </form>
                        </li>
                    @endif
                    @if($tournament->status == 'closed')
                        <li>
                            <form action="{{ route('admin.tournaments.start', $tournament->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-play text-primary me-2"></i>Start Tournament
                                </button>
                            </form>
                        </li>
                    @endif
                    @if($tournament->status == 'ongoing')
                        <li>
                            <form action="{{ route('admin.tournaments.complete', $tournament->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-flag-checkered text-info me-2"></i>Complete Tournament
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
<!-- Tournament Summary Cards -->
<div class="tournament-card-row mb-4">
    <!-- Teams Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Total Teams'"
        :subtitle="'Registered teams in tournament'"
        :value="$approvedTeams->count()"
        :subvalue="'of ' . ($tournament->max_teams ?? '∞') . ' max'"
        :icon="'fa-users'"
        :color="'primary'"
        :trend="[
            'color' => $approvedTeams->count() >= 8 ? 'success' : 'warning',
            'icon' => $approvedTeams->count() >= 8 ? 'check-circle' : 'exclamation-triangle',
            'text' => $approvedTeams->count() >= 8 ? 'Target met' : 'Need more teams'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Pending: {{ $pendingTeams->count() }}</small>
                <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>View Teams
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Matches Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Total Matches'"
        :subtitle="'Scheduled and completed matches'"
        :value="$tournament->matches()->count()"
        :subvalue="'in ' . $tournament->pools()->count() . ' pools'"
        :icon="'fa-futbol'"
        :color="'info'"
        :trend="[
            'color' => $tournament->matches()->count() > 0 ? 'success' : 'secondary',
            'icon' => $tournament->matches()->count() > 0 ? 'calendar-check' : 'calendar-plus',
            'text' => $tournament->matches()->count() > 0 ? 'Fixtures created' : 'No fixtures yet'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Completed: {{ $tournament->matches()->where('status', 'completed')->count() }}</small>
                <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye me-1"></i>View Matches
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Goals Card -->
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

    <!-- Cards Card -->
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
</div>

<!-- Quick Actions Cards -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card tournament-card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <!-- Create Match -->
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Create Match'"
                            :subtitle="'Add new tournament match'"
                            :icon="'fa-plus-circle'"
                            :color="'primary'"
                            :description="'Create individual matches or generate full schedules'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']), 'label' => 'Tournament Match', 'icon' => 'fa-trophy', 'style' => 'primary'],
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'league']), 'label' => 'League Match', 'icon' => 'fa-list-ol', 'style' => 'success']
                            ]"
                            :secondaryActions="[
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']), 'label' => 'Friendly', 'style' => 'info'],
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'knockout']), 'label' => 'Knockout', 'style' => 'danger']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-end">
                                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list me-1"></i>View All Matches
                                    </a>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>

                    <!-- Generate Fixtures -->
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Generate Fixtures'"
                            :subtitle="'Auto-create tournament schedule'"
                            :icon="'fa-magic'"
                            :color="'success'"
                            :description="'Create league tables, knockout brackets, or group stages'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.generate-league', $tournament->id), 'label' => 'League Schedule', 'icon' => 'fa-list-ol', 'style' => 'success'],
                                ['url' => route('admin.tournaments.matches.generate-knockout', $tournament->id), 'label' => 'Knockout Bracket', 'icon' => 'fa-trophy', 'style' => 'danger']
                            ]"
                            :secondaryActions="[
                                ['url' => route('admin.tournaments.pools.index', $tournament->id), 'label' => 'Group Stage', 'style' => 'warning']
                            ]"
                            :badge="['color' => $tournament->matches()->count() > 0 ? 'secondary' : 'success', 'text' => $tournament->matches()->count() > 0 ? 'Fixtures exist' : 'No fixtures yet']"
                        >
                            <x-slot:footer>
                                <div class="text-end">
                                    <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-calendar-alt me-1"></i>Schedule Settings
                                    </a>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>

                    <!-- Manage Teams -->
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Manage Teams'"
                            :subtitle="'Team registration and approval'"
                            :icon="'fa-users'"
                            :color="'info'"
                            :description="'Review team applications and manage squad registrations'"
                            :actions="[
                                ['url' => route('admin.tournaments.teams.create', $tournament->id), 'label' => 'Add Team', 'icon' => 'fa-plus', 'style' => 'info'],
                                ['url' => route('admin.tournaments.teams.index', $tournament->id), 'label' => 'Team List', 'icon' => 'fa-list', 'style' => 'primary']
                            ]"
                            :secondaryActions="[
                                ['url' => route('admin.tournaments.squads.index', [$tournament->id, 0]), 'label' => 'Squad Management', 'style' => 'warning']
                            ]"
                            :badge="['color' => $approvedTeams->count() >= 8 ? 'success' : 'warning', 'text' => $approvedTeams->count() . ' teams approved']"
                        >
                            <x-slot:footer>
                                <div class="text-end">
                                    <span class="badge bg-secondary">{{ $pendingTeams->count() }} pending approvals</span>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>

                    <!-- Tournament Settings -->
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Tournament Settings'"
                            :subtitle="'Configuration and management'"
                            :icon="'fa-cog'"
                            :color="'secondary'"
                            :description="'Manage pools, venues, and tournament configuration'"
                            :actions="[
                                ['url' => route('admin.tournaments.pools.index', $tournament->id), 'label' => 'Manage Pools', 'icon' => 'fa-layer-group', 'style' => 'warning'],
                                ['url' => route('admin.tournaments.venues.index', $tournament), 'label' => 'Venues', 'icon' => 'fa-map-marker-alt', 'style' => 'info']
                            ]"
                            :secondaryActions="[
                                ['url' => route('admin.tournaments.schedule.config', $tournament->id), 'label' => 'Schedule Config', 'style' => 'primary']
                            ]"
                            :badge="['color' => $tournament->pools()->count() > 0 ? 'success' : 'secondary', 'text' => $tournament->pools()->count() . ' pools']"
                        >
                            <x-slot:footer>
                                <div class="text-end">
                                    <span class="badge bg-light text-dark">Format: {{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</span>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Cards -->
<div class="row">
    <!-- Standings Card -->
    <div class="col-lg-4">
        <x-tournament-cards.expandable-card
            :id="'standings-card'"
            :title="'Standings'"
            :subtitle="'Current tournament table'"
            :icon="'fa-list-ol'"
            :color="'primary'"
            :value="$standings->count() > 0 ? $standings->first()->team->team_name : 'No standings'"
            :subvalue="$standings->count() > 0 ? $standings->first()->points . ' points' : 'No data'"
            :summary="'
                <div class="row text-center">
                    <div class="col-4">
                        <div class=\"h6 mb-0\">' . $standings->count() . '</div>
                        <small class=\"text-muted\">Teams</small>
                    </div>
                    <div class=\"col-4\">
                        <div class=\"h6 mb-0\">' . $tournament->matches()->where('status', 'completed')->count() . '</div>
                        <small class=\"text-muted\">Played</small>
                    </div>
                    <div class=\"col-4\">
                        <div class=\"h6 mb-0\">' . $tournament->matches()->where('status', 'scheduled')->count() . '</div>
                        <small class=\"text-muted\">Remaining</small>
                    </div>
                </div>
            '"
        >
            <x-slot:content>
                @if($standings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">#</th>
                                    <th class="py-2">Team</th>
                                    <th class="py-2 text-center">P</th>
                                    <th class="py-2 text-center">Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($standings->take(5) as $standing)
                                    <tr>
                                        <td class="py-2">
                                            <span class="badge bg-{{ $standing->position <= 3 ? 'success' : 'secondary' }}">
                                                {{ $standing->position }}
                                            </span>
                                        </td>
                                        <td class="py-2">{{ $standing->team->team_name ?? 'N/A' }}</td>
                                        <td class="py-2 text-center">{{ $standing->played }}</td>
                                        <td class="py-2 text-center fw-bold">{{ $standing->points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Full Standings
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-table fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No standings available yet.</p>
                        <small class="text-muted">Standings will appear once matches are played.</small>
                    </div>
                @endif
            </x-slot:content>
            <x-slot:footer>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar me-1"></i>Standings
                        </a>
                        <a href="{{ route('admin.tournaments.statistics.index', $tournament->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-pie me-1"></i>Statistics
                        </a>
                    </div>
                </div>
            </x-slot:footer>
        </x-tournament-cards.expandable-card>
    </div>

    <!-- Recent Matches Card -->
    <div class="col-lg-4">
        <x-tournament-cards.expandable-card
            :id="'matches-card'"
            :title="'Recent Matches'"
            :subtitle="'Latest match results and upcoming fixtures'"
            :icon="'fa-futbol'"
            :color="'info'"
            :value="$recentMatches->count()"
            :subvalue="'matches this week'"
            :summary="'
                <div class=\"row text-center\">
                    <div class=\"col-4\">
                        <div class=\"h6 mb-0\">' . $tournament->matches()->where('status', 'completed')->count() . '</div>
                        <small class=\"text-muted\">Completed</small>
                    </div>
                    <div class=\"col-4\">
                        <div class=\"h6 mb-0\">' . $tournament->matches()->where('status', 'scheduled')->count() . '</div>
                        <small class=\"text-muted\">Scheduled</small>
                    </div>
                    <div class=\"col-4\">
                        <div class=\"h6 mb-0\">' . $tournament->matches()->where('status', 'in_progress')->count() . '</div>
                        <small class=\"text-muted\">In Progress</small>
                    </div>
                </div>
            '"
        >
            <x-slot:content>
                @if($recentMatches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">Date</th>
                                    <th class="py-2">Match</th>
                                    <th class="py-2 text-center">Score</th>
                                    <th class="py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMatches as $match)
                                    <tr>
                                        <td class="py-2 small">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d') : 'TBD' }}</td>
                                        <td class="py-2">
                                            <div class="small">{{ $match->homeTeam->team_name ?? 'TBD' }}</div>
                                            <div class="small text-muted">vs</div>
                                            <div class="small">{{ $match->awayTeam->team_name ?? 'TBD' }}</div>
                                        </td>
                                        <td class="py-2 text-center">
                                            @if($match->isCompleted())
                                                <span class="badge bg-success">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-center">
                                            @php
                                                $statusClass = match($match->status) {
                                                    'completed' => 'success',
                                                    'in_progress' => 'warning',
                                                    'postponed' => 'info',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($match->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Full Schedule
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-futbol fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No matches scheduled yet.</p>
                        <small class="text-muted">Create matches to get started.</small>
                    </div>
                @endif
            </x-slot:content>
            <x-slot:footer>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Next match: {{ $nextMatch ? \Carbon\Carbon::parse($nextMatch->kickoff_time)->format('M d, H:i') : 'TBD' }}</small>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-plus me-1"></i>Create Match
                        </a>
                        <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-1"></i>View All
                        </a>
                    </div>
                </div>
            </x-slot:footer>
        </x-tournament-cards.expandable-card>
    </div>

    <!-- Top Scorers Card -->
    <div class="col-lg-4">
        <x-tournament-cards.expandable-card
            :id="'scorers-card'"
            :title="'Top Scorers'"
            :subtitle="'Leading goal scorers in the tournament'"
            :icon="'fa-trophy'"
            :color="'warning'"
            :value="$topScorer ? $topScorer->goals_count : '0'"
            :subvalue="$topScorer ? $topScorer->name : 'No goals yet'"
            :summary="'
                <div class=\"row text-center\">
                    <div class=\"col-6\">
                        <div class=\"h6 mb-0\">' . $totalGoals . '</div>
                        <small class=\"text-muted\">Total Goals</small>
                    </div>
                    <div class=\"col-6\">
                        <div class=\"h6 mb-0\">' . number_format($avgGoalsPerMatch, 2) . '</div>
                        <small class=\"text-muted\">Avg per Match</small>
                    </div>
                </div>
            '"
        >
            <x-slot:content>
                @if($topScorers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">#</th>
                                    <th class="py-2">Player</th>
                                    <th class="py-2 text-center">Team</th>
                                    <th class="py-2 text-center">Goals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topScorers->take(5) as $index => $player)
                                    <tr>
                                        <td class="py-2">
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    {{ $player->name ? strtoupper(substr($player->name, 0, 1)) : '?' }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $player->name ?? 'Unknown' }}</div>
                                                    <small class="text-muted">{{ $player->position ?? 'Player' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-2 text-center small">{{ $player->team_name ?? 'N/A' }}</td>
                                        <td class="py-2 text-center fw-bold text-success">{{ $player->goals_count ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Full Rankings
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No goals scored yet.</p>
                        <small class="text-muted">Goals will appear once matches are played.</small>
                    </div>
                @endif
            </x-slot:content>
            <x-slot:footer>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Last goal: {{ $lastGoal ? \Carbon\Carbon::parse($lastGoal)->format('M d, Y') : 'N/A' }}</small>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.tournaments.statistics.top-scorers', $tournament->id) }}" class="btn btn-outline-warning">
                            <i class="fas fa-list-ol me-1"></i>Top Scorers
                        </a>
                        <a href="{{ route('admin.tournaments.statistics.discipline', $tournament->id) }}" class="btn btn-outline-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i>Discipline
                        </a>
                    </div>
                </div>
            </x-slot:footer>
        </x-tournament-cards.expandable-card>
    </div>
</div>

<!-- Progress and Status -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card tournament-card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Tournament Progress
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Tournament Progress</span>
                                <span class="text-primary fw-bold">{{ $progressPercentage }}%</span>
                            </div>
                            <div class="progress tournament-progress">
                                <div class="progress-bar bg-primary" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="h6 mb-0">{{ $tournament->teams()->count() }}</div>
                                <small class="text-muted">Teams</small>
                            </div>
                            <div class="col-3">
                                <div class="h6 mb-0">{{ $tournament->matches()->count() }}</div>
                                <small class="text-muted">Matches</small>
                            </div>
                            <div class="col-3">
                                <div class="h6 mb-0">{{ $totalGoals }}</div>
                                <small class="text-muted">Goals</small>
                            </div>
                            <div class="col-3">
                                <div class="h6 mb-0">{{ $totalCards }}</div>
                                <small class="text-muted">Cards</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                                    <div class="h6 mb-0">{{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M d') : 'TBD' }}</div>
                                    <small class="text-muted">Start Date</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                    <div class="h6 mb-0">{{ $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('M d') : 'TBD' }}</div>
                                    <small class="text-muted">End Date</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for the card layout */
.tournament-card-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

@media (max-width: 1200px) {
    .tournament-card-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .tournament-card-row {
        grid-template-columns: 1fr;
    }

    .card-body .row.g-3 {
        flex-direction: column;
    }

    .card-body .row.g-3 .col-md-3 {
        width: 100%;
    }
}

/* Animation for card loading */
.card-loading {
    position: relative;
    pointer-events: none;
}

.card-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.card-loading .spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>

<script>
// Initialize card system for this page
document.addEventListener('DOMContentLoaded', function() {
    // Add any tournament-specific card functionality here
    console.log('Tournament landing page cards initialized');

    // Auto-refresh functionality for live updates
    setInterval(function() {
        // This could be used to refresh match statuses, scores, etc.
        console.log('Auto-refreshing tournament data...');
    }, 30000); // Refresh every 30 seconds
});
</script>
@endsection
