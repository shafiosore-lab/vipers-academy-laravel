@extends('layouts.admin')

@section('title', $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-2">
                    {{ $tournament->name }}
                    @php $statusColors = ['draft' => 'secondary', 'open' => 'success', 'closed' => 'warning', 'ongoing' => 'primary', 'completed' => 'info', 'cancelled' => 'danger']; @endphp
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
    </div>
@endsection

@section('content')
<!-- Action Buttons -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <!-- Match Creation Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus-circle me-1"></i> Create Match
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']) }}">
                        <i class="fas fa-trophy text-primary me-2"></i>Tournament Match
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'league']) }}">
                        <i class="fas fa-list-ol text-success me-2"></i>League Match
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']) }}">
                        <i class="fas fa-handshake text-info me-2"></i>Friendly Match
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'knockout']) }}">
                        <i class="fas fa-bolt text-danger me-2"></i>Knockout Round
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'group_stage']) }}">
                        <i class="fas fa-layer-group text-warning me-2"></i>Group Stage
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'exhibition']) }}">
                        <i class="fas fa-star text-secondary me-2"></i>Exhibition
                    </a></li>
                </ul>
            </div>

            <!-- Generate Fixtures -->
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-magic me-1"></i> Generate Fixtures
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <form action="{{ route('admin.tournaments.matches.generate-league', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item" {{ $tournament->matches()->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-list-ol text-success me-2"></i>League Schedule
                            </button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('admin.tournaments.matches.generate-knockout', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item" {{ $tournament->matches()->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trophy text-danger me-2"></i>Knockout Bracket
                            </button>
                        </form>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.pools.index', $tournament->id) }}">
                        <i class="fas fa-layer-group text-warning me-2"></i>Group Stage (Requires Pools)
                    </a></li>
                </ul>
            </div>

            <!-- View Matches -->
            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-futbol me-1"></i> All Matches ({{ $tournament->matches()->count() }})
            </a>

            <!-- Reshuffle Teams Button - Prominently displayed before tournament starts -->
            @if(in_array($tournament->status, ['open', 'closed']) && $tournament->getApprovedTeamsCount() >= 2)
                <a href="{{ route('admin.tournaments.pools.reshuffle', $tournament->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-shuffle me-1"></i> Reshuffle Teams
                </a>
            @endif

            <!-- Manage Pools -->
            <a href="{{ route('admin.tournaments.pools.index', $tournament->id) }}" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-layer-group me-1"></i> Pools
            </a>

            <!-- Manage Venues -->
            <a href="{{ route('admin.tournaments.venues.index', $tournament) }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-map-marker-alt me-1"></i> Venues
            </a>

            <div class="border-start mx-2"></div>

            @if(in_array($tournament->status, ['draft', 'closed']))
                <form action="{{ route('admin.tournaments.open-registration', $tournament->id) }}" method="POST" class="d-inline">
                    @csrf <button type="submit" class="btn btn-success btn-sm">Open Registration</button>
                </form>
            @endif
            @if($tournament->status === 'open')
                <form action="{{ route('admin.tournaments.close-registration', $tournament->id) }}" method="POST" class="d-inline">
                    @csrf <button type="submit" class="btn btn-warning btn-sm">Close Registration</button>
                </form>
            @endif
            @if($tournament->status === 'closed')
                <form action="{{ route('admin.tournaments.start', $tournament->id) }}" method="POST" class="d-inline">
                    @csrf <button type="submit" class="btn btn-primary btn-sm">Start Tournament</button>
                </form>
            @endif
            @if($tournament->status === 'ongoing')
                <form action="{{ route('admin.tournaments.complete', $tournament->id) }}" method="POST" class="d-inline">
                    @csrf <button type="submit" class="btn btn-info btn-sm">Complete</button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Match Creation Cards - Six Pathways -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Create Match</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Tournament Match -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']) }}" class="text-decoration-none">
                    <div class="card h-100 border-primary match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-trophy fa-2x text-primary"></i>
                            </div>
                            <h6 class="card-title">Tournament Match</h6>
                            <p class="card-text small text-muted">Standard competitive match for the main tournament</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- League Match -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'league']) }}" class="text-decoration-none">
                    <div class="card h-100 border-success match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-list-ol fa-2x text-success"></i>
                            </div>
                            <h6 class="card-title">League Match</h6>
                            <p class="card-text small text-muted">Points-based league competition match</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Friendly Match -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']) }}" class="text-decoration-none">
                    <div class="card h-100 border-info match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-handshake fa-2x text-info"></i>
                            </div>
                            <h6 class="card-title">Friendly Match</h6>
                            <p class="card-text small text-muted">Non-competitive practice match</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Knockout Round -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'knockout']) }}" class="text-decoration-none">
                    <div class="card h-100 border-danger match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-bolt fa-2x text-danger"></i>
                            </div>
                            <h6 class="card-title">Knockout Round</h6>
                            <p class="card-text small text-muted">Elimination match with overtime & penalties</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Group Stage Match -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'group_stage']) }}" class="text-decoration-none">
                    <div class="card h-100 border-warning match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-layer-group fa-2x text-warning"></i>
                            </div>
                            <h6 class="card-title">Group Stage Match</h6>
                            <p class="card-text small text-muted">Match within a tournament pool/group</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Exhibition Match -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'exhibition']) }}" class="text-decoration-none">
                    <div class="card h-100 border-secondary match-type-card">
                        <div class="card-body text-center">
                            <div class="match-type-icon mb-2">
                                <i class="fas fa-star fa-2x text-secondary"></i>
                            </div>
                            <h6 class="card-title">Exhibition Match</h6>
                            <p class="card-text small text-muted">Special showcase or demo match</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <!-- Generate League Schedule -->
                    <div class="col-auto">
                        <form action="{{ route('admin.tournaments.matches.generate-league', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm" {{ $tournament->matches()->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-list-ol me-1"></i> Generate League
                            </button>
                        </form>
                    </div>

                    <!-- Generate Knockout Bracket -->
                    <div class="col-auto">
                        <form action="{{ route('admin.tournaments.matches.generate-knockout', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" {{ $tournament->matches()->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trophy me-1"></i> Generate Knockout
                            </button>
                        </form>
                    </div>

                    <!-- Create Single Match -->
                    <div class="col-auto">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-plus-circle me-1"></i> Create Match
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']) }}">
                                    <i class="fas fa-trophy text-primary me-2"></i>Tournament Match
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'league']) }}">
                                    <i class="fas fa-list-ol text-success me-2"></i>League Match
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']) }}">
                                    <i class="fas fa-handshake text-info me-2"></i>Friendly Match
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'knockout']) }}">
                                    <i class="fas fa-bolt text-danger me-2"></i>Knockout Round
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'group_stage']) }}">
                                    <i class="fas fa-layer-group text-warning me-2"></i>Group Stage
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'exhibition']) }}">
                                    <i class="fas fa-star text-secondary me-2"></i>Exhibition
                                </a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Reshuffle Teams Button - Prominent quick action -->
                    @if(in_array($tournament->status, ['open', 'closed']) && $tournament->getApprovedTeamsCount() >= 2)
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.pools.reshuffle', $tournament->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-shuffle me-1"></i> Reshuffle Teams
                        </a>
                    </div>
                    @endif

                    <!-- Manage Pools -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.pools.index', $tournament->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-layer-group me-1"></i> Manage Pools
                        </a>
                    </div>

                    <!-- Manage Venues -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.venues.index', $tournament) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-map-marker-alt me-1"></i> Manage Venues
                        </a>
                    </div>

                    <!-- Check Conflicts -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.matches.check-conflicts', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-exclamation-triangle me-1"></i> Check Conflicts
                        </a>
                    </div>

                    <!-- Schedule Management -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-calendar-alt me-1"></i> Schedule
                        </a>
                    </div>

                    <!-- Auto Schedule -->
                    <div class="col-auto">
                        <form action="{{ route('admin.tournaments.schedule.auto-schedule', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" {{ $tournament->matches()->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-magic me-1"></i> Auto Schedule
                            </button>
                        </form>
                    </div>

                    <!-- Manual Schedule -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.schedule.bulk-schedule', $tournament->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-pencil-alt me-1"></i> Manual Schedule
                        </a>
                    </div>

                    <!-- View All Matches -->
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-futbol me-1"></i> All Matches ({{ $tournament->matches()->count() }})
                        </a>
                    </div>

                    <!-- View Standings -->
                    @if($tournament->teams()->count() > 0)
                    <div class="col-auto">
                        <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-list-ol me-1"></i> Standings
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Fixtures</h6>
            </div>
            <div class="card-body py-2">
                @php
                    $upcomingMatches = $tournament->matches()
                        ->with(['homeTeam.team', 'awayTeam.team'])
                        ->where('status', 'scheduled')
                        ->where('kickoff_time', '>', now())
                        ->orderBy('kickoff_time', 'asc')
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($upcomingMatches as $match)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div class="small">
                            <strong>{{ $match->homeTeam->team_name ?? 'TBD' }}</strong>
                            <span class="text-muted">vs</span>
                            <strong>{{ $match->awayTeam->team_name ?? 'TBD' }}</strong>
                        </div>
                        <span class="badge bg-info">{{ $match->kickoff_time ? $match->kickoff_time->format('M d, H:i') : 'TBD' }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0 text-center py-3">
                        <i class="fas fa-calendar-times me-1"></i> No upcoming matches scheduled
                    </p>
                @endforelse
                @if($upcomingMatches->count() > 0)
                    <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-link btn-sm px-0 mt-2">View all matches →</a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Match Statistics</h6>
            </div>
            <div class="card-body py-2">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h4 mb-0">{{ $tournament->matches()->count() }}</div>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0">{{ $tournament->matches()->where('status', 'completed')->count() }}</div>
                        <small class="text-muted">Completed</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0">{{ $tournament->matches()->where('status', 'scheduled')->count() }}</div>
                        <small class="text-muted">Scheduled</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tournament Details -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Details</h6></div>
            <div class="card-body py-2">
                <dl class="row mb-0">
                    @if($tournament->competition_format)
                    <dt class="col-sm-5">Format</dt><dd class="col-sm-7"><span class="badge bg-primary">{{ $tournament->getFormatInfo()['name'] ?? $tournament->competition_format }}</span></dd>
                    <dt class="col-sm-5">Est. Matches</dt><dd class="col-sm-7">{{ $tournament->estimated_matches ?? $tournament->calculateEstimatedMatches() }}</dd>
                    @endif
                    <dt class="col-sm-5">Venue</dt><dd class="col-sm-7">{{ $tournament->venue ?? 'Not specified' }}</dd>
                    <dt class="col-sm-5">Dates</dt><dd class="col-sm-7">{{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M d') : '-' }} - {{ $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') : '-' }}</dd>
                    <dt class="col-sm-5">Max Teams</dt><dd class="col-sm-7">{{ $tournament->max_teams ?? 'Unlimited' }}</dd>
                    <dt class="col-sm-5">Squad Limit</dt><dd class="col-sm-7">{{ $tournament->squad_limit }} players</dd>
                    <dt class="col-sm-5">Min Players</dt><dd class="col-sm-7">{{ $tournament->min_players }} players</dd>
                </dl>
                @if($tournament->description)<p class="small text-muted mt-2 mb-0">{{ $tournament->description }}</p>@endif
            </div>
        </div>
    </div>

    <!-- Teams -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Teams</h6>
                <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="btn btn-sm btn-outline-primary">+ Add</a>
            </div>
            <div class="card-body py-2">
                @forelse($approvedTeams as $team)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span>{{ $team->team_name }}</span>
                        <span class="badge bg-success">{{ $team->squads()->count() }} players</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No teams registered yet.</p>
                @endforelse
                @if($pendingTeams->count() > 0)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-warning fw-bold">Pending ({{ $pendingTeams->count() }})</small>
                        @foreach($pendingTeams as $team)
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="small">{{ $team->team_name }}</span>
                                <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                    @csrf <button type="submit" class="btn btn-xs btn-success">Approve</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-link btn-sm px-0">View all teams →</a>
            </div>
        </div>
    </div>

    <!-- Standings -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Standings</h6></div>
            <div class="card-body py-2">
                @if($standings->count() > 0)
                    <table class="table table-sm mb-0">
                        <thead><tr><th>#</th><th>Team</th><th>P</th><th>Pts</th></tr></thead>
                        <tbody>
                            @foreach($standings as $standing)
                                <tr><td>{{ $standing->position }}</td><td>{{ $standing->team->team_name ?? 'N/A' }}</td><td>{{ $standing->played }}</td><td>{{ $standing->points }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted small mb-0">No standings yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Matches -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recent Matches</h6>
        <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-sm btn-primary">View All Matches</a>
    </div>
    <div class="table-responsive">
        @php
            $recentMatches = $tournament->matches()
                ->with(['homeTeam.team', 'awayTeam.team'])
                ->orderBy('kickoff_time', 'desc')
                ->limit(5)
                ->get();
        @endphp
        @if($recentMatches->count() > 0)
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Date</th><th>Home</th><th class="text-center">Score</th><th>Away</th><th class="text-center">Status</th><th></th></tr></thead>
                <tbody>
                    @foreach($recentMatches as $match)
                        <tr>
                            <td>{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, H:i') : 'TBD' }}</td>
                            <td>{{ $match->homeTeam->team_name ?? 'TBD' }}</td>
                            <td class="text-center">{{ $match->isCompleted() ? $match->home_score . ' - ' . $match->away_score : 'vs' }}</td>
                            <td>{{ $match->awayTeam->team_name ?? 'TBD' }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = match($match->status) {
                                        'completed' => 'success',
                                        'in_progress' => 'primary',
                                        'postponed' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($match->status) }}</span>
                            </td>
                            <td><a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="card-body text-center text-muted">No matches scheduled yet.</div>
        @endif
    </div>
</div>

<style>
.match-type-card {
    transition: all 0.2s ease;
    cursor: pointer;
}
.match-type-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.match-type-icon {
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
