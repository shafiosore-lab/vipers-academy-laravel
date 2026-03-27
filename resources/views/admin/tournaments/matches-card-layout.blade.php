@extends('layouts.admin')

@section('title', 'Matches - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-futbol text-primary"></i>{{ $tournament->name }}
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filters
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Status Filters</h6></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                        <i class="fas fa-circle text-secondary me-2"></i>All Matches
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'scheduled']) }}">
                        <i class="fas fa-circle text-info me-2"></i>Scheduled
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}">
                        <i class="fas fa-circle text-warning me-2"></i>In Progress
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}">
                        <i class="fas fa-circle text-success me-2"></i>Completed
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'postponed']) }}">
                        <i class="fas fa-circle text-info me-2"></i>Postponed
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}">
                        <i class="fas fa-circle text-danger me-2"></i>Cancelled
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Quick Actions</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', $tournament->id) }}">
                        <i class="fas fa-plus me-2"></i>Create Match
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.index', $tournament->id) }}">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a></li>
                </ul>
            </div>
            <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-list-ol me-2"></i>Standings
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Matches Summary Cards -->
<div class="tournament-card-row mb-4">
    <!-- Total Matches Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Total Matches'"
        :subtitle="'All scheduled matches'"
        :value="$matches->count()"
        :subvalue="'matches in tournament'"
        :icon="'fa-futbol'"
        :color="'primary'"
        :trend="[
            'color' => $matches->count() > 0 ? 'success' : 'warning',
            'icon' => $matches->count() > 0 ? 'calendar-check' : 'calendar-plus',
            'text' => $matches->count() > 0 ? 'Fixtures created' : 'No fixtures yet'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Format: {{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</small>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>All Matches
                    </a>
                    <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="btn btn-outline-success">
                        <i class="fas fa-plus me-1"></i>Add Match
                    </a>
                </div>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Completed Matches Card -->
    @php
        $completedCount = $matches->where('status', 'completed')->count();
        $scheduledCount = $matches->where('status', 'scheduled')->count();
        $inProgressCount = $matches->where('status', 'in_progress')->count();
        $postponedCount = $matches->where('status', 'postponed')->count();
        $cancelledCount = $matches->where('status', 'cancelled')->count();
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Completed Matches'"
        :subtitle="'Matches with final results'"
        :value="$completedCount"
        :subvalue="'matches finished'"
        :icon="'fa-check-circle'"
        :color="'success'"
        :trend="[
            'color' => $completedCount > 0 ? 'success' : 'secondary',
            'icon' => $completedCount > 0 ? 'check-circle' : 'clock',
            'text' => $completedCount > 0 ? 'Action packed' : 'No results yet'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Goals: {{ $matches->sum('home_score') + $matches->sum('away_score') }}</small>
                <small class="text-muted">Avg: {{ $matches->count() > 0 ? round(($matches->sum('home_score') + $matches->sum('away_score')) / $matches->count(), 2) : 0 }} per match</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Upcoming Matches Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Upcoming Matches'"
        :subtitle="'Scheduled and in-progress'"
        :value="$scheduledCount + $inProgressCount"
        :subvalue="'matches to play'"
        :icon="'fa-clock'"
        :color="'info'"
        :trend="[
            'color' => ($scheduledCount + $inProgressCount) > 0 ? 'info' : 'warning',
            'icon' => ($scheduledCount + $inProgressCount) > 0 ? 'clock' : 'exclamation-triangle',
            'text' => ($scheduledCount + $inProgressCount) > 0 ? 'Matches scheduled' : 'No upcoming matches'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Scheduled: {{ $scheduledCount }}</small>
                <small class="text-muted">In Progress: {{ $inProgressCount }}</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Disciplinary Stats Card -->
    @php
        $totalYellowCards = $matches->sum('home_yellow_cards') + $matches->sum('away_yellow_cards');
        $totalRedCards = $matches->sum('home_red_cards') + $matches->sum('away_red_cards');
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Disciplinary Stats'"
        :subtitle="'Cards issued in matches'"
        :value="$totalYellowCards + $totalRedCards"
        :subvalue="'total cards'"
        :icon="'fa-exclamation-triangle'"
        :color="'warning'"
        :trend="[
            'color' => ($totalYellowCards + $totalRedCards) < 20 ? 'success' : ($totalYellowCards + $totalRedCards) < 50 ? 'warning' : 'danger',
            'icon' => ($totalYellowCards + $totalRedCards) < 20 ? 'thumbs-up' : ($totalYellowCards + $totalRedCards) < 50 ? 'exclamation-triangle' : 'exclamation-circle',
            'text' => ($totalYellowCards + $totalRedCards) < 20 ? 'Fair play' : ($totalYellowCards + $totalRedCards) < 50 ? 'Some issues' : 'High discipline'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Y: {{ $totalYellowCards }}</small>
                <small class="text-muted">R: {{ $totalRedCards }}</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>
</div>

<!-- Matches Cards Grid -->
@if($matches->count() > 0)
    <div class="tournament-card-grid">
        @foreach($matches as $match)
            <div class="card tournament-card shadow-sm border-0 h-100">
                <!-- Card Header with Match Info -->
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-start gap-3">
                            <div class="match-badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'in_progress' ? 'warning' : ($match->status == 'scheduled' ? 'info' : 'secondary')) }} rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-{{ $match->status == 'completed' ? 'check' : ($match->status == 'in_progress' ? 'play' : ($match->status == 'scheduled' ? 'clock' : 'ban')) }} text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">
                                    {{ $match->homeTeam->team_name ?? 'TBD' }} vs {{ $match->awayTeam->team_name ?? 'TBD' }}
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">{{ ucfirst($match->match_type ?? 'league') }}</span>
                                    <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'in_progress' ? 'warning' : ($match->status == 'scheduled' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($match->status) }}
                                    </span>
                                    @if($match->venue)
                                        <span class="badge bg-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $match->venue }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            @if($match->isCompleted())
                                <div class="h3 mb-0 fw-bold text-success">{{ $match->home_score }} - {{ $match->away_score }}</div>
                                <small class="text-muted">Final Score</small>
                            @elseif($match->isInProgress())
                                <div class="h3 mb-0 fw-bold text-warning">LIVE</div>
                                <small class="text-muted">In Progress</small>
                            @else
                                <div class="h3 mb-0 fw-bold text-info">VS</div>
                                <small class="text-muted">Upcoming</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body with Match Details -->
                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- Match Information -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-info"></i>
                                <span class="fw-semibold">Match Details</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small text-muted">Date</div>
                                        <div class="h6 mb-0">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, Y') : 'TBD' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Time</div>
                                        <div class="h6 mb-0">{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') : 'TBD' }}</div>
                                    </div>
                                </div>
                                @if($match->venue)
                                    <div class="mt-2">
                                        <div class="small text-muted">Venue</div>
                                        <div class="h6 mb-0">{{ $match->venue }}</div>
                                    </div>
                                @endif
                                @if($match->referee)
                                    <div class="mt-2">
                                        <div class="small text-muted">Referee</div>
                                        <div class="h6 mb-0">{{ $match->referee }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Score Information -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-trophy text-warning"></i>
                                <span class="fw-semibold">Score & Stats</span>
                            </div>
                            <div class="ms-4">
                                @if($match->isCompleted())
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="small text-muted">{{ $match->homeTeam->team_name ?? 'Home' }}</div>
                                            <div class="h5 mb-0">{{ $match->home_score }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">{{ $match->awayTeam->team_name ?? 'Away' }}</div>
                                            <div class="h5 mb-0">{{ $match->away_score }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-{{ $match->home_score > $match->away_score ? 'success' : ($match->home_score < $match->away_score ? 'danger' : 'warning') }}">
                                            {{ $match->home_score > $match->away_score ? 'Home Win' : ($match->home_score < $match->away_score ? 'Away Win' : 'Draw') }}
                                        </span>
                                    </div>
                                @elseif($match->isInProgress())
                                    <div class="text-center">
                                        <div class="spinner-border text-warning me-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="text-warning fw-bold">Live Match</span>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span class="text-muted">Match not started</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Disciplinary Information -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-gavel text-warning"></i>
                                <span class="fw-semibold">Disciplinary Record</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="small text-muted">{{ $match->homeTeam->team_name ?? 'Home' }} YC</div>
                                        <div class="h6 mb-0 text-warning">{{ $match->home_yellow_cards ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">{{ $match->homeTeam->team_name ?? 'Home' }} RC</div>
                                        <div class="h6 mb-0 text-danger">{{ $match->home_red_cards ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">{{ $match->awayTeam->team_name ?? 'Away' }} YC</div>
                                        <div class="h6 mb-0 text-warning">{{ $match->away_yellow_cards ?? 0 }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">{{ $match->awayTeam->team_name ?? 'Away' }} RC</div>
                                        <div class="h6 mb-0 text-danger">{{ $match->away_red_cards ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Match Notes -->
                        @if($match->notes)
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-sticky-note text-info"></i>
                                    <span class="fw-semibold">Match Notes</span>
                                </div>
                                <div class="ms-4">
                                    <p class="text-muted mb-0">{{ $match->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer bg-light border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Match Details
                            </a>
                            @if($match->status == 'scheduled')
                                <a href="{{ route('admin.tournaments.matches.edit', [$tournament->id, $match->id]) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @elseif($match->status == 'completed')
                                <a href="{{ route('admin.tournaments.matches.edit', [$tournament->id, $match->id]) }}" class="btn btn-outline-info">
                                    <i class="fas fa-edit me-1"></i>Update
                                </a>
                            @endif
                            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}?team={{ $match->home_team_id }}" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i>Team Stats
                            </a>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                Updated: {{ \Carbon\Carbon::parse($match->updated_at)->format('M d, Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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
            <i class="fas fa-futbol fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No Matches Scheduled</h4>
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
                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Create Match'"
                            :subtitle="'Add individual match'"
                            :icon="'fa-plus-circle'"
                            :color="'primary'"
                            :description="'Create single matches or friendly games'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']), 'label' => 'Tournament Match', 'icon' => 'fa-trophy', 'style' => 'primary'],
                                ['url' => route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']), 'label' => 'Friendly Match', 'icon' => 'fa-futbol', 'style' => 'info']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Manual match creation</small>
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
                            :title="'Live Updates'"
                            :subtitle="'Update match results'"
                            :icon="'fa-refresh'"
                            :color="'warning'"
                            :description="'Update scores and match status in real-time'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.index', $tournament->id) . '?status=in_progress', 'label' => 'In Progress', 'icon' => 'fa-play', 'style' => 'warning'],
                                ['url' => route('admin.tournaments.matches.index', $tournament->id) . '?status=scheduled', 'label' => 'Scheduled', 'icon' => 'fa-clock', 'style' => 'info']
                            ]"
                            :badge="['color' => $inProgressCount > 0 ? 'warning' : 'secondary', 'text' => $inProgressCount . ' live']"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Real-time updates</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Export Schedule'"
                            :subtitle="'Download match data'"
                            :icon="'fa-download'"
                            :color="'info'"
                            :description="'Export match schedule for external use'"
                            :actions="[
                                ['url' => route('admin.tournaments.matches.export', [$tournament->id, 'csv']), 'label' => 'Export CSV', 'icon' => 'fa-file-csv', 'style' => 'info'],
                                ['url' => route('admin.tournaments.matches.export', [$tournament->id, 'pdf']), 'label' => 'Export PDF', 'icon' => 'fa-file-pdf', 'style' => 'danger']
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

<!-- Match Statistics Summary -->
@if($matches->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card tournament-card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Match Statistics
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-success">{{ $completedCount }}</div>
                            <small class="text-muted">Completed</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-info">{{ $scheduledCount }}</div>
                            <small class="text-muted">Scheduled</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-warning">{{ $inProgressCount }}</div>
                            <small class="text-muted">In Progress</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-info">{{ $postponedCount }}</div>
                            <small class="text-muted">Postponed</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-danger">{{ $cancelledCount }}</div>
                            <small class="text-muted">Cancelled</small>
                        </div>
                        <div class="col-md-2">
                            <div class="h4 mb-0 text-warning">{{ $totalYellowCards + $totalRedCards }}</div>
                            <small class="text-muted">Total Cards</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
/* Additional styles for matches cards */
.tournament-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
    gap: 1.5rem;
}

.match-badge {
    border: 3px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

    .match-badge {
        width: 40px;
        height: 40px;
    }

    .match-badge i {
        font-size: 1.2rem;
    }
}

/* Hover effects for matches cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Status-specific styling */
.match-badge.bg-success {
    border-color: #28a745;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.match-badge.bg-warning {
    border-color: #ffc107;
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.match-badge.bg-info {
    border-color: #17a2b8;
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
}

.match-badge.bg-secondary {
    border-color: #6c757d;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

/* Animation for match cards */
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

/* Status badge animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}
</style>

<script>
// Matches page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Matches card layout initialized');

    // Add click functionality to match cards
    const matchCards = document.querySelectorAll('.tournament-card');
    matchCards.forEach(card => {
        const matchLink = card.querySelector('.btn-outline-primary');
        if (matchLink) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons or dropdowns
                if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.dropdown')) {
                    return;
                }
                window.location.href = matchLink.href;
            });
        }
    });

    // Add confirmation for match deletion actions
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Delete this match? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Auto-refresh functionality for live matches
    setInterval(function() {
        // This could be used to refresh live match data
        console.log('Auto-refreshing match data...');
    }, 30000); // Refresh every 30 seconds

    // Add live match indicators
    const liveMatches = document.querySelectorAll('.match-badge.bg-warning');
    liveMatches.forEach(badge => {
        badge.style.animation = 'pulse 2s infinite';
    });
});
</script>

@endsection
