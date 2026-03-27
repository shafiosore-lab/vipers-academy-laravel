@extends('layouts.admin')

@section('title', 'Standings - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-list-ol text-primary"></i>{{ $tournament->name }}
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>View Options
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Display Options</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.standings.index', $tournament->id) }}">
                        <i class="fas fa-table text-primary me-2"></i>Table View
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.standings.card', $tournament->id) }}">
                        <i class="fas fa-th-large text-info me-2"></i>Card View
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Export Options</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.standings.export', [$tournament->id, 'csv']) }}">
                        <i class="fas fa-file-csv text-success me-2"></i>Export CSV
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.standings.export', [$tournament->id, 'pdf']) }}">
                        <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
                    </a></li>
                </ul>
            </div>
            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-futbol me-2"></i>Matches
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Standings Summary Cards -->
<div class="tournament-card-row mb-4">
    <!-- League Table Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'League Table'"
        :subtitle="'Current tournament standings'"
        :value="$standings->count()"
        :subvalue="'teams competing'"
        :icon="'fa-list-ol'"
        :color="'primary'"
        :trend="[
            'color' => $standings->count() >= 8 ? 'success' : 'warning',
            'icon' => $standings->count() >= 8 ? 'check-circle' : 'exclamation-triangle',
            'text' => $standings->count() >= 8 ? 'Full league' : 'Incomplete'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Format: {{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</small>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-table me-1"></i>Table
                    </a>
                    <a href="{{ route('admin.tournaments.standings.card', $tournament->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-th-large me-1"></i>Card
                    </a>
                </div>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Top Team Card -->
    @if($standings->count() > 0)
        <x-tournament-cards.tournament-summary-card
            :title="'League Leaders'"
            :subtitle="'Current table toppers'"
            :value="$standings->first()->team->team_name ?? 'N/A'"
            :subvalue="$standings->first()->points . ' points'"
            :icon="'fa-crown'"
            :color="'warning'"
            :trend="[
                'color' => $standings->first()->points > 0 ? 'success' : 'secondary',
                'icon' => $standings->first()->points > 0 ? 'chart-line' : 'chart-line',
                'text' => $standings->first()->points > 0 ? 'Leading' : 'No points yet'
            ]"
        >
            <x-slot:footer>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Position: 1st</small>
                    <small class="text-muted">Form: {{ $standings->first()->form ?? 'N/A' }}</small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @else
        <x-tournament-cards.tournament-summary-card
            :title="'No Leader'"
            :subtitle="'Standings not available'"
            :value="'N/A'"
            :subvalue="'No matches played yet'"
            :icon="'fa-question'"
            :color="'secondary'"
        >
            <x-slot:footer>
                <div class="text-center">
                    <small class="text-muted">Standings will appear once matches are played</small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @endif

    <!-- Relegation Zone Card -->
    @if($standings->count() > 0)
        @php
            $relegationCount = min(3, $standings->count());
            $relegationTeams = $standings->slice(-$relegationCount);
        @endphp
        <x-tournament-cards.tournament-summary-card
            :title="'Relegation Zone'"
            :subtitle="'Bottom teams at risk'"
            :value="$relegationTeams->count()"
            :subvalue="'teams in danger'"
            :icon="'fa-exclamation-triangle'"
            :color="'danger'"
            :trend="[
                'color' => $relegationTeams->count() > 0 ? 'danger' : 'success',
                'icon' => $relegationTeams->count() > 0 ? 'exclamation-triangle' : 'check-circle',
                'text' => $relegationTeams->count() > 0 ? 'At risk' : 'Safe'
            ]"
        >
            <x-slot:footer>
                <div class="text-center">
                    <small class="text-muted">
                        @if($relegationTeams->count() > 0)
                            Bottom: {{ $relegationTeams->last()->team->team_name ?? 'N/A' }}
                        @else
                            No teams at risk
                        @endif
                    </small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @else
        <x-tournament-cards.tournament-summary-card
            :title="'No Relegation'"
            :subtitle="'All teams safe'"
            :value="'0'"
            :subvalue="'teams at risk'"
            :icon="'fa-shield-alt'"
            :color="'success'"
        >
            <x-slot:footer>
                <div class="text-center">
                    <small class="text-muted">No matches played yet</small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @endif

    <!-- Promotion Zone Card -->
    @if($standings->count() > 0)
        @php
            $promotionCount = min(3, $standings->count());
            $promotionTeams = $standings->take($promotionCount);
        @endphp
        <x-tournament-cards.tournament-summary-card
            :title="'Promotion Zone'"
            :subtitle="'Top teams in contention'"
            :value="$promotionTeams->count()"
            :subvalue="'teams in contention'"
            :icon="'fa-trophy'"
            :color="'info'"
            :trend="[
                'color' => $promotionTeams->count() > 0 ? 'info' : 'secondary',
                'icon' => $promotionTeams->count() > 0 ? 'trophy' : 'trophy',
                'text' => $promotionTeams->count() > 0 ? 'Contending' : 'No contenders'
            ]"
        >
            <x-slot:footer>
                <div class="text-center">
                    <small class="text-muted">
                        @if($promotionTeams->count() > 0)
                            Leader: {{ $promotionTeams->first()->team->team_name ?? 'N/A' }}
                        @else
                            No teams contending
                        @endif
                    </small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @else
        <x-tournament-cards.tournament-summary-card
            :title="'No Promotion'"
            :subtitle="'No teams contending'"
            :value="'0'"
            :subvalue="'teams in contention'"
            :icon="'fa-trophy'"
            :color="'secondary'"
        >
            <x-slot:footer>
                <div class="text-center">
                    <small class="text-muted">No matches played yet</small>
                </div>
            </x-slot:footer>
        </x-tournament-cards.tournament-summary-card>
    @endif
</div>

<!-- Standings Cards Grid -->
@if($standings->count() > 0)
    <div class="tournament-card-grid">
        @foreach($standings as $standing)
            <div class="card tournament-card shadow-sm border-0 h-100">
                <!-- Card Header with Position and Team -->
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-start gap-3">
                            <div class="position-badge bg-{{ $standing->position <= 3 ? 'warning' : ($standing->position > $standings->count() - 3 ? 'danger' : 'light') }} rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="h4 mb-0 fw-bold text-{{ $standing->position <= 3 ? 'dark' : 'dark' }}">{{ $standing->position }}</span>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $standing->team->team_name ?? 'Unknown Team' }}</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">{{ $standing->team->organization->name ?? 'N/A' }}</span>
                                    @if($standing->position <= 3)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-crown me-1"></i>Top 3
                                        </span>
                                    @elseif($standing->position > $standings->count() - 3)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Relegation
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="h3 mb-0 fw-bold text-{{ $standing->position <= 3 ? 'warning' : 'primary' }}">{{ $standing->points }}</div>
                            <small class="text-muted">Points</small>
                        </div>
                    </div>
                </div>

                <!-- Card Body with Statistics -->
                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- Match Statistics -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-futbol text-info"></i>
                                <span class="fw-semibold">Match Record</span>
                            </div>
                            <div class="ms-4">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="h6 mb-0 text-success">{{ $standing->won }}</div>
                                        <small class="text-muted">Won</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h6 mb-0 text-warning">{{ $standing->drawn }}</div>
                                        <small class="text-muted">Drawn</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h6 mb-0 text-danger">{{ $standing->lost }}</div>
                                        <small class="text-muted">Lost</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Goal Statistics -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-football-ball text-success"></i>
                                <span class="fw-semibold">Goal Statistics</span>
                            </div>
                            <div class="ms-4">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="h6 mb-0">{{ $standing->goals_for }}</div>
                                        <small class="text-muted">Scored</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h6 mb-0">{{ $standing->goals_against }}</div>
                                        <small class="text-muted">Conceded</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h6 mb-0 {{ $standing->goal_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $standing->goal_difference }}
                                        </div>
                                        <small class="text-muted">Diff</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Form -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-chart-line text-primary"></i>
                                <span class="fw-semibold">Recent Form</span>
                            </div>
                            <div class="ms-4">
                                @if($standing->recent_results && count($standing->recent_results) > 0)
                                    <div class="form-indicators d-flex gap-2">
                                        @foreach($standing->recent_results as $result)
                                            <span class="badge bg-{{ $result == 'W' ? 'success' : ($result == 'D' ? 'warning' : 'danger') }} px-2 py-1">
                                                {{ $result }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        Last 5: {{ count(array_filter($standing->recent_results, fn($r) => $r == 'W')) }}W,
                                        {{ count(array_filter($standing->recent_results, fn($r) => $r == 'D')) }}D,
                                        {{ count(array_filter($standing->recent_results, fn($r) => $r == 'L')) }}L
                                    </small>
                                @else
                                    <span class="text-muted">No recent results</span>
                                @endif
                            </div>
                        </div>

                        <!-- Performance Metrics -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-percentage text-info"></i>
                                <span class="fw-semibold">Performance</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small text-muted">Win Rate</div>
                                        <div class="h6 mb-0">{{ $standing->played > 0 ? round(($standing->won / $standing->played) * 100, 1) : 0 }}%</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Points/Game</div>
                                        <div class="h6 mb-0">{{ $standing->played > 0 ? round($standing->points / $standing->played, 2) : 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discipline -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-gavel text-warning"></i>
                                <span class="fw-semibold">Discipline</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small text-muted">Yellow Cards</div>
                                        <div class="h6 mb-0 text-warning">{{ $standing->yellow_cards ?? 0 }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Red Cards</div>
                                        <div class="h6 mb-0 text-danger">{{ $standing->red_cards ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer bg-light border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.teams.show', $standing->team->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Team Profile
                            </a>
                            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}?team={{ $standing->team->id }}" class="btn btn-outline-info">
                                <i class="fas fa-futbol me-1"></i>Matches
                            </a>
                            <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $standing->team->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i>Squad
                            </a>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Updated: {{ now()->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($standings->hasPages())
        <div class="card tournament-card mt-4 shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            Showing {{ $standings->firstItem() }} to {{ $standings->lastItem() }} of {{ $standings->total() }} teams
                        </span>
                    </div>
                    <div>
                        {{ $standings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <!-- Empty State -->
    <div class="card tournament-card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-table fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No Standings Available</h4>
            <p class="text-muted mb-4">No standings data is available yet. This could be because:</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-circle text-warning me-2"></i>No matches have been played yet</li>
                        <li><i class="fas fa-circle text-info me-2"></i>Matches are still in progress</li>
                        <li><i class="fas fa-circle text-secondary me-2"></i>Standings calculation is in progress</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-futbol me-2"></i>View Matches
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Quick Stats Summary -->
@if($standings->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card tournament-card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Tournament Statistics
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="h4 mb-0 text-primary">{{ $standings->sum('played') }}</div>
                            <small class="text-muted">Total Matches</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-0 text-success">{{ $standings->sum('goals_for') }}</div>
                            <small class="text-muted">Total Goals</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-0 text-warning">{{ $standings->sum('yellow_cards') }}</div>
                            <small class="text-muted">Yellow Cards</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-0 text-danger">{{ $standings->sum('red_cards') }}</div>
                            <small class="text-muted">Red Cards</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
/* Additional styles for standings cards */
.tournament-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.position-badge {
    border: 3px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-indicators .badge {
    font-size: 0.8rem;
    min-width: 25px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tournament-card-grid {
        grid-template-columns: 1fr;
    }

    .card-body .row.g-3 {
        flex-direction: column;
    }

    .card-body .row.g-3 .col-md-6, .card-body .row.g-3 .col-md-12 {
        width: 100%;
    }

    .position-badge {
        width: 40px;
        height: 40px;
    }

    .position-badge .h4 {
        font-size: 1.5rem;
    }
}

/* Hover effects for standings cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Position-specific styling */
.position-badge.bg-warning {
    border-color: #ffc107;
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}

.position-badge.bg-danger {
    border-color: #dc3545;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.position-badge.bg-light {
    border-color: #dee2e6;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Animation for position changes */
@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tournament-card {
    animation: slideInFromTop 0.5s ease-out;
}
</style>

<script>
// Standings page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Standings card layout initialized');

    // Add click functionality to standings cards
    const standingsCards = document.querySelectorAll('.tournament-card');
    standingsCards.forEach(card => {
        const teamLink = card.querySelector('.btn-outline-primary');
        if (teamLink) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons
                if (e.target.closest('button') || e.target.closest('a')) {
                    return;
                }
                window.location.href = teamLink.href;
            });
        }
    });

    // Add smooth animations for form indicators
    const formIndicators = document.querySelectorAll('.form-indicators .badge');
    formIndicators.forEach((badge, index) => {
        badge.style.animationDelay = `${index * 0.1}s`;
        badge.style.animation = 'fadeInUp 0.3s ease-out forwards';
    });

    // Auto-refresh functionality for live updates
    setInterval(function() {
        // This could be used to refresh standings data
        console.log('Auto-refreshing standings data...');
    }, 60000); // Refresh every minute
});
</script>

@endsection
