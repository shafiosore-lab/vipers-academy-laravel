@extends('layouts.admin')

@section('title', 'Schedule - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-calendar-alt text-primary"></i>{{ $tournament->name }}
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
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Calendar View
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.schedule.list', $tournament->id) }}">
                        <i class="fas fa-list text-info me-2"></i>List View
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.schedule.card', $tournament->id) }}">
                        <i class="fas fa-th-large text-success me-2"></i>Card View
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Quick Actions</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', $tournament->id) }}">
                        <i class="fas fa-plus me-2"></i>Add Match
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.schedule.config', $tournament->id) }}">
                        <i class="fas fa-cog me-2"></i>Schedule Config
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
<!-- Schedule Summary Cards -->
<div class="tournament-card-row mb-4">
    <!-- Schedule Overview Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Schedule Overview'"
        :subtitle="'Tournament match schedule'"
        :value="$matches->count()"
        :subvalue="'matches scheduled'"
        :icon="'fa-calendar-alt'"
        :color="'primary'"
        :trend="[
            'color' => $matches->count() > 0 ? 'success' : 'warning',
            'icon' => $matches->count() > 0 ? 'calendar-check' : 'calendar-plus',
            'text' => $matches->count() > 0 ? 'Schedule created' : 'No matches yet'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Format: {{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</small>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-1"></i>Calendar
                    </a>
                    <a href="{{ route('admin.tournaments.schedule.list', $tournament->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-list me-1"></i>List
                    </a>
                    <a href="{{ route('admin.tournaments.schedule.card', $tournament->id) }}" class="btn btn-outline-success">
                        <i class="fas fa-th-large me-1"></i>Card
                    </a>
                </div>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Upcoming Matches Card -->
    @php
        $upcomingMatches = $matches->where('kickoff_time', '>', now())->count();
        $completedMatches = $matches->where('status', 'completed')->count();
        $inProgressMatches = $matches->where('status', 'in_progress')->count();
        $scheduledMatches = $matches->where('status', 'scheduled')->count();
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Upcoming Matches'"
        :subtitle="'Matches yet to be played'"
        :value="$upcomingMatches"
        :subvalue="'matches to play'"
        :icon="'fa-clock'"
        :color="'info'"
        :trend="[
            'color' => $upcomingMatches > 0 ? 'info' : 'warning',
            'icon' => $upcomingMatches > 0 ? 'clock' : 'exclamation-triangle',
            'text' => $upcomingMatches > 0 ? 'Matches scheduled' : 'No upcoming matches'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Today: {{ $matches->where('kickoff_time', '>=', now()->startOfDay())->where('kickoff_time', '<=', now()->endOfDay())->count() }}</small>
                <small class="text-muted">This Week: {{ $matches->whereBetween('kickoff_time', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Live Matches Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Live Matches'"
        :subtitle="'Currently in progress'"
        :value="$inProgressMatches"
        :subvalue="'matches live'"
        :icon="'fa-play'"
        :color="'warning'"
        :trend="[
            'color' => $inProgressMatches > 0 ? 'warning' : 'secondary',
            'icon' => $inProgressMatches > 0 ? 'play' : 'pause',
            'text' => $inProgressMatches > 0 ? 'Live action' : 'No live matches'
        ]"
    >
        <x-slot:footer>
            <div class="text-center">
                @if($inProgressMatches > 0)
                    <span class="badge bg-warning">Live Updates Active</span>
                @else
                    <small class="text-muted">No matches currently in progress</small>
                @endif
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Match Days Card -->
    @php
        $matchDays = $matches->groupBy(function($match) {
            return \Carbon\Carbon::parse($match->kickoff_time)->format('Y-m-d');
        })->count();
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Match Days'"
        :subtitle="'Days with scheduled matches'"
        :value="$matchDays"
        :subvalue="'active days'"
        :icon="'fa-calendar-day'"
        :color="'success'"
        :trend="[
            'color' => $matchDays > 0 ? 'success' : 'warning',
            'icon' => $matchDays > 0 ? 'calendar-day' : 'calendar-times',
            'text' => $matchDays > 0 ? 'Active schedule' : 'No match days'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Avg: {{ $matchDays > 0 ? round($matches->count() / $matchDays, 1) : 0 }} matches per day</small>
                <a href="{{ route('admin.tournaments.schedule.config', $tournament->id) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-cog me-1"></i>Configure
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>
</div>

<!-- Schedule Cards Grid -->
@if($matches->count() > 0)
    <!-- Date Grouping -->
    @foreach($matches->groupBy(function($match) {
        return \Carbon\Carbon::parse($match->kickoff_time)->format('Y-m-d');
    }) as $date => $dayMatches)
        <div class="card tournament-card mb-4 shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="date-badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <div class="text-center text-white">
                                <div class="h6 mb-0 fw-bold">{{ \Carbon\Carbon::parse($date)->format('d') }}</div>
                                <small>{{ \Carbon\Carbon::parse($date)->format('M') }}</small>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-info">{{ $dayMatches->count() }} matches</span>
                                <span class="badge bg-success">{{ $dayMatches->where('status', 'completed')->count() }} completed</span>
                                <span class="badge bg-warning">{{ $dayMatches->where('status', 'in_progress')->count() }} live</span>
                                <span class="badge bg-secondary">{{ $dayMatches->where('status', 'scheduled')->count() }} scheduled</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Day Actions</h6></li>
                            <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', $tournament->id) }}?date={{ $date }}">
                                <i class="fas fa-plus me-2"></i>Add Match
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}">
                                <i class="fas fa-calendar-alt me-2"></i>Full Calendar
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="row g-3">
                    @foreach($dayMatches as $match)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'in_progress' ? 'warning' : ($match->status == 'scheduled' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                            @if($match->venue)
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $match->venue }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <div class="small text-muted">{{ \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') }}</div>
                                        </div>
                                    </div>

                                    <div class="match-teams d-flex align-items-center justify-content-between mb-3">
                                        <div class="team-info text-center">
                                            <div class="team-name fw-bold">{{ $match->homeTeam->team_name ?? 'TBD' }}</div>
                                            @if($match->isCompleted())
                                                <div class="team-score h4 mb-0 text-success">{{ $match->home_score }}</div>
                                            @elseif($match->isInProgress())
                                                <div class="team-score h4 mb-0 text-warning">-</div>
                                            @else
                                                <div class="team-score h4 mb-0 text-muted">-</div>
                                            @endif
                                        </div>

                                        <div class="vs text-center">
                                            <span class="badge bg-secondary px-3 py-2">VS</span>
                                            @if($match->isCompleted())
                                                <div class="small text-muted mt-1">FT</div>
                                            @elseif($match->isInProgress())
                                                <div class="small text-warning mt-1">LIVE</div>
                                            @else
                                                <div class="small text-muted mt-1">UPCOMING</div>
                                            @endif
                                        </div>

                                        <div class="team-info text-center">
                                            <div class="team-name fw-bold">{{ $match->awayTeam->team_name ?? 'TBD' }}</div>
                                            @if($match->isCompleted())
                                                <div class="team-score h4 mb-0 text-success">{{ $match->away_score }}</div>
                                            @elseif($match->isInProgress())
                                                <div class="team-score h4 mb-0 text-warning">-</div>
                                            @else
                                                <div class="team-score h4 mb-0 text-muted">-</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="match-actions d-flex justify-content-between align-items-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </a>
                                            @if($match->status == 'scheduled')
                                                <a href="{{ route('admin.tournaments.matches.edit', [$tournament->id, $match->id]) }}" class="btn btn-outline-warning">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            @if($match->referee)
                                                <small class="text-muted">Ref: {{ $match->referee }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    @if($matches->hasPages())
        <div class="card tournament-card mt-4 shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            Showing {{ $matches->firstItem() }} to {{ $matches->lastItem() }} of {{ $matches->total() }} matches
                        </span>
                    </div>
                    <div>
                        {{ $matches->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <!-- Empty State -->
    <div class="card tournament-card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No Schedule Created</h4>
            <p class="text-muted mb-4">No matches have been scheduled for this tournament yet. You can:</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-circle text-primary me-2"></i>Create individual matches</li>
                        <li><i class="fas fa-circle text-success me-2"></i>Generate full tournament schedule</li>
                        <li><i class="fas fa-circle text-info me-2"></i>Import match data</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Create First Match
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Quick Actions Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card tournament-card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>Schedule Management
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Add Match'"
                            :subtitle="'Schedule individual match'"
                            :icon="'fa-plus-circle'"
                            :color="'primary'"
                            :description="'Add single matches to the schedule'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']), 'label' => 'Tournament Match', 'icon' => 'fa-trophy', 'style' => 'primary'],
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']), 'label' => 'Friendly Match', 'icon' => 'fa-futbol', 'style' => 'info']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Manual scheduling</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Generate Schedule'"
                            :subtitle="'Auto-create fixtures'"
                            :icon="'fa-magic'"
                            :color="'success'"
                            :description="'Generate full tournament schedule automatically'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.generate-league', $tournament->id), 'label' => 'League Schedule', 'icon' => 'fa-list-ol', 'style' => 'success'],
                                ['url' => route('admin.tournaments.matches.generate-knockout', $tournament->id), 'label' => 'Knockout Bracket', 'icon' => 'fa-trophy', 'style' => 'danger']
                            ]"
                            :badge="['color' => $matches->count() > 0 ? 'secondary' : 'success', 'text' => $matches->count() . ' matches']"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Automated scheduling</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Schedule Config'"
                            :subtitle="'Configure match settings'"
                            :icon="'fa-cog'"
                            :color="'warning'"
                            :description="'Set match times, venues, and scheduling rules'"
                            :actions="[
                                ['url' => route('admin.tournaments.schedule.config', $tournament->id), 'label' => 'Configure', 'icon' => 'fa-cog', 'style' => 'warning']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Scheduling preferences</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Export Schedule'"
                            :subtitle="'Download schedule data'"
                            :icon="'fa-download'"
                            :color="'info'"
                            :description="'Export match schedule for external use'"
                            :actions="[
                                ['url' => route('admin.tournaments.schedule.export', [$tournament->id, 'csv']), 'label' => 'Export CSV', 'icon' => 'fa-file-csv', 'style' => 'info'],
                                ['url' => route('admin.tournaments.schedule.export', [$tournament->id, 'pdf']), 'label' => 'Export PDF', 'icon' => 'fa-file-pdf', 'style' => 'danger']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Schedule export options</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Statistics -->
@if($matches->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card tournament-card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Schedule Statistics
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-info">{{ $scheduledMatches }}</div>
                            <small class="text-muted">Scheduled</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-warning">{{ $inProgressMatches }}</div>
                            <small class="text-muted">In Progress</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-success">{{ $completedMatches }}</div>
                            <small class="text-muted">Completed</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-info">{{ $matchDays }}</div>
                            <small class="text-muted">Match Days</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-primary">{{ $matches->groupBy(function($match) { return \Carbon\Carbon::parse($match->kickoff_time)->format('H'); })->count() }}</div>
                            <small class="text-muted">Time Slots</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-warning">{{ $matches->groupBy('venue')->count() }}</div>
                            <small class="text-muted">Venues</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
/* Additional styles for schedule cards */
.date-badge {
    border: 3px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.match-teams {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 15px;
}

.team-info .team-name {
    font-size: 0.9rem;
    color: #495057;
    margin-bottom: 5px;
}

.team-info .team-score {
    font-size: 1.5rem;
    margin-bottom: 0;
}

.vs .badge {
    font-size: 0.8rem;
    font-weight: 700;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .match-teams {
        flex-direction: column;
        gap: 10px;
    }

    .team-info {
        text-align: center;
    }

    .vs {
        align-self: center;
    }

    .date-badge {
        width: 40px;
        height: 40px;
    }

    .date-badge .h6 {
        font-size: 1.2rem;
    }
}

/* Hover effects for schedule cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Status-specific styling */
.date-badge.bg-primary {
    border-color: #0d6efd;
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
}

/* Animation for schedule cards */
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

/* Live match indicator animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.match-badge.bg-warning {
    animation: pulse 2s infinite;
}

/* Match card hover effects */
.match-teams {
    transition: all 0.3s ease;
}

.match-teams:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
// Schedule page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule card layout initialized');

    // Add click functionality to match cards
    const matchCards = document.querySelectorAll('.card.h-100');
    matchCards.forEach(card => {
        const matchLink = card.querySelector('.btn-outline-primary');
        if (matchLink) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons
                if (e.target.closest('button') || e.target.closest('a')) {
                    return;
                }
                window.location.href = matchLink.href;
            });
        }
    });

    // Add auto-refresh for live matches
    setInterval(function() {
        // This could be used to refresh live match data
        console.log('Auto-refreshing schedule data...');
    }, 30000); // Refresh every 30 seconds

    // Add live match indicators
    const liveMatches = document.querySelectorAll('.badge.bg-warning');
    liveMatches.forEach(badge => {
        if (badge.textContent.includes('LIVE')) {
            badge.style.animation = 'pulse 2s infinite';
        }
    });

    // Add date navigation functionality
    const dateBadges = document.querySelectorAll('.date-badge');
    dateBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            // Scroll to the corresponding date section
            const dateText = this.querySelector('.h6').textContent;
            const monthText = this.querySelector('small').textContent;
            console.log(`Navigating to ${dateText} ${monthText}`);
        });
    });
});
</script>

@endsection
