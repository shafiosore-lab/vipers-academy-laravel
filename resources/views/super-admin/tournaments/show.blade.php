@extends('layouts.admin')

@section('title', $tournament->name . ' - Tournament Details')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">{{ $tournament->name }}</h1>
            <small class="text-muted">
                <i class="fas fa-building"></i> {{ $tournament->organization->name ?? 'N/A' }}
                @if($tournament->season)
                    <span class="mx-1">|</span> {{ $tournament->season }}
                @endif
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.tournaments.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.edit', $tournament->id) }}">
                        <i class="fas fa-edit me-1"></i> Edit Tournament
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}">
                        <i class="fas fa-users me-1"></i> Manage Teams
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.players.index', $tournament->id) }}">
                        <i class="fas fa-user-friends me-1"></i> View Players
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.matches.index', $tournament->id) }}">
                        <i class="fas fa-calendar-alt me-1"></i> Matches
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.standings.index', $tournament->id) }}">
                        <i class="fas fa-list-ol me-1"></i> Standings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.statistics.index', $tournament->id) }}">
                        <i class="fas fa-chart-bar me-1"></i> Tournament Statistics
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    @php
        $statusColors = [
            'draft' => ['bg' => 'bg-secondary', 'text' => 'Draft - Not yet visible'],
            'open' => ['bg' => 'bg-success', 'text' => 'Open for Registration'],
            'closed' => ['bg' => 'bg-warning', 'text' => 'Registration Closed'],
            'ongoing' => ['bg' => 'bg-primary', 'text' => 'Tournament In Progress'],
            'completed' => ['bg' => 'bg-info', 'text' => 'Tournament Completed'],
            'cancelled' => ['bg' => 'bg-danger', 'text' => 'Tournament Cancelled']
        ];
        $statusStyle = $statusColors[$tournament->status] ?? ['bg' => 'bg-secondary', 'text' => $tournament->status];
    @endphp
    <div class="alert {{ $statusStyle['bg'] }} text-white py-2 mb-3">
        <i class="fas fa-info-circle me-1"></i> {{ $statusStyle['text'] }}
        @if($tournament->registration_deadline)
            <span class="mx-2">|</span>
            <i class="fas fa-clock me-1"></i> Registration Deadline: {{ $tournament->registration_deadline->format('M d, Y H:i') }}
        @endif
    </div>

    <!-- Quick Actions for Status -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="small fw-bold me-2">Quick Actions:</span>

                @if($tournament->status === 'draft')
                    <form action="{{ route('super-admin.tournaments.open-registration', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-door-open"></i> Open Registration
                        </button>
                    </form>
                @elseif($tournament->status === 'open')
                    <form action="{{ route('super-admin.tournaments.close-registration', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-door-closed"></i> Close Registration
                        </button>
                    </form>
                @elseif($tournament->status === 'closed')
                    <form action="{{ route('super-admin.tournaments.start', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-play"></i> Start Tournament
                        </button>
                    </form>
                @elseif($tournament->status === 'ongoing')
                    <form action="{{ route('super-admin.tournaments.complete', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fas fa-check-circle"></i> Complete Tournament
                        </button>
                    </form>
                @endif

                @if($tournament->status !== 'cancelled' && $tournament->status !== 'completed')
                    <form action="{{ route('super-admin.tournaments.cancel', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this tournament?')">
                            <i class="fas fa-times-circle"></i> Cancel
                        </button>
                    </form>
                @endif

                @if(in_array($tournament->status, ['closed', 'ongoing']) && $tournament->matches()->count() == 0)
                    <form action="{{ route('super-admin.tournaments.generate-fixtures', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Generate fixtures for this tournament?')">
                            <i class="fas fa-random"></i> Generate Fixtures
                        </button>
                    </form>
                @endif

                <!-- Reshuffle Teams Button - Prominently displayed before tournament starts -->
                @if(in_array($tournament->status, ['open', 'closed']) && $tournament->getApprovedTeamsCount() >= 2)
                    <a href="{{ route('super-admin.tournaments.pools.reshuffle', $tournament->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-shuffle"></i> Reshuffle Teams
                    </a>
                @endif

                <!-- Shuffle Teams & Generate Matches Button -->
                @if($tournament->getApprovedTeamsCount() >= 2 && $tournament->matches()->count() == 0)
                    @if($tournament->status === 'open')
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#shuffleModal">
                            <i class="fas fa-random"></i> Shuffle & Generate Matches
                        </button>
                    @elseif(in_array($tournament->status, ['closed', 'ongoing']))
                        <form action="{{ route('super-admin.tournaments.generate-fixtures', $tournament->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Generate fixtures for this tournament?')">
                                <i class="fas fa-random"></i> Shuffle Teams
                            </button>
                        </form>
                    @endif
                @endif

                @if($tournament->status === 'ongoing')
                    <form action="{{ route('super-admin.tournaments.recalculate-standings', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fas fa-calculator"></i> Recalculate Standings
                        </button>
                    </form>
                @endif

                <!-- Schedule Management -->
                <a href="{{ route('super-admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </a>

                <!-- Auto Schedule -->
                @if($tournament->matches()->count() > 0)
                    <a href="{{ route('super-admin.tournaments.schedule.bulk-schedule', $tournament->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Manual Schedule
                    </a>
                @else
                    <form action="{{ route('super-admin.tournaments.schedule.auto-schedule', $tournament->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-magic"></i> Auto Schedule
                        </button>
                    </form>
                @endif

                <!-- View Standings - Always visible when there are teams -->
                @if($tournament->teams()->count() > 0)
                    <a href="{{ route('super-admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-list-ol"></i> Standings
                    </a>
                @endif

                <!-- Tournament Statistics Button -->
                <a href="{{ route('super-admin.tournaments.statistics.index', $tournament->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-chart-bar"></i> Statistics
                </a>

                <form action="{{ route('super-admin.tournaments.toggle-visibility', $tournament->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-eye{{ $tournament->is_public ? '-slash' : '' }}"></i> {{ $tournament->is_public ? 'Make Private' : 'Make Public' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tournament Statistics Section -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary small"><i class="fas fa-chart-bar me-1"></i> Tournament Statistics</h6>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" onclick="showStatsSection('overview')">Overview</button>
                <button type="button" class="btn btn-outline-primary" onclick="showStatsSection('top-scorers')">Top Scorers</button>
                <button type="button" class="btn btn-outline-primary" onclick="showStatsSection('discipline')">Discipline</button>
                <button type="button" class="btn btn-outline-primary" onclick="showStatsSection('groups')">Groups</button>
                <button type="button" class="btn btn-outline-primary" onclick="showStatsSection('rankings')">Rankings</button>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Statistics Overview -->
            <div id="stats-overview" class="stats-section">
                <div class="compact-stats-row p-3">
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ea1c4d 0%, #c31432 100%);">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['total_teams'] ?? 0 }}</div>
                            <div class="compact-stat-label">Registered Teams</div>
                        </div>
                    </div>
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['approved_teams'] ?? 0 }}</div>
                            <div class="compact-stat-label">Approved</div>
                        </div>
                    </div>
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                            <i class="fas fa-hourglass-half text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['pending_teams'] ?? 0 }}</div>
                            <div class="compact-stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                            <i class="fas fa-user-friends text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['total_players'] ?? 0 }}</div>
                            <div class="compact-stat-label">Registered Players</div>
                        </div>
                    </div>
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['verified_players'] ?? 0 }}</div>
                            <div class="compact-stat-label">Verified</div>
                        </div>
                    </div>
                    <div class="compact-stat-card">
                        <div class="compact-stat-icon" style="background: linear-gradient(135deg, #fd7e14 0%, #dc6502 100%);">
                            <i class="fas fa-futbol text-white"></i>
                        </div>
                        <div class="compact-stat-content">
                            <div class="compact-stat-value">{{ $stats['total_matches'] ?? 0 }}</div>
                            <div class="compact-stat-label">Matches</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row g-3 p-3">
                    <div class="col-md-6">
                        <div class="card border-0">
                            <div class="card-body">
                                <h6 class="card-title text-primary small">Match Status</h6>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="h4 mb-0 text-success">{{ $stats['completed_matches'] ?? 0 }}</div>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                    <div class="col">
                                        <div class="h4 mb-0 text-warning">{{ $stats['scheduled_matches'] ?? 0 }}</div>
                                        <small class="text-muted">Scheduled</small>
                                    </div>
                                    <div class="col">
                                        <div class="h4 mb-0 text-secondary">{{ $stats['pending_matches'] ?? 0 }}</div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0">
                            <div class="card-body">
                                <h6 class="card-title text-primary small">Goals & Cards</h6>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="h4 mb-0 text-info">{{ $stats['total_goals'] ?? 0 }}</div>
                                        <small class="text-muted">Total Goals</small>
                                    </div>
                                    <div class="col">
                                        <div class="h4 mb-0 text-warning">{{ $stats['yellow_cards'] ?? 0 }}</div>
                                        <small class="text-muted">Yellow Cards</small>
                                    </div>
                                    <div class="col">
                                        <div class="h4 mb-0 text-danger">{{ $stats['red_cards'] ?? 0 }}</div>
                                        <small class="text-muted">Red Cards</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Scorers Section -->
            <div id="stats-top-scorers" class="stats-section" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 px-3">Rank</th>
                                <th class="py-2 px-3">Player</th>
                                <th class="py-2 px-3">Team</th>
                                <th class="py-2 px-3 text-end">Goals</th>
                                <th class="py-2 px-3 text-end">Assists</th>
                                <th class="py-2 px-3 text-end">Matches</th>
                            </tr>
                        </thead>
                        <tbody id="top-scorers-body">
                            <!-- Top scorers will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Discipline Section -->
            <div id="stats-discipline" class="stats-section" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 px-3">Player</th>
                                <th class="py-2 px-3">Team</th>
                                <th class="py-2 px-3 text-center">Yellow</th>
                                <th class="py-2 px-3 text-center">Red</th>
                                <th class="py-2 px-3 text-center">Fouls</th>
                                <th class="py-2 px-3 text-center">Suspensions</th>
                            </tr>
                        </thead>
                        <tbody id="discipline-body">
                            <!-- Discipline data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Groups Section -->
            <div id="stats-groups" class="stats-section" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 px-3">Group</th>
                                <th class="py-2 px-3">Teams</th>
                                <th class="py-2 px-3 text-end">Matches</th>
                                <th class="py-2 px-3 text-end">Goals</th>
                                <th class="py-2 px-3 text-end">Avg Attendance</th>
                            </tr>
                        </thead>
                        <tbody id="groups-body">
                            <!-- Groups data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rankings Section -->
            <div id="stats-rankings" class="stats-section" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 px-3">Position</th>
                                <th class="py-2 px-3">Team</th>
                                <th class="py-2 px-3 text-end">Points</th>
                                <th class="py-2 px-3 text-end">W</th>
                                <th class="py-2 px-3 text-end">D</th>
                                <th class="py-2 px-3 text-end">L</th>
                                <th class="py-2 px-3 text-end">GD</th>
                            </tr>
                        </thead>
                        <tbody id="rankings-body">
                            <!-- Rankings data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Tournament Details -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Tournament Details</h6>
                </div>
                <div class="card-body py-2">
                    <table class="table table-sm table-borderless mb-0">
                        @if($tournament->competition_format)
                        <tr>
                            <td class="text-muted small py-1">Format</td>
                            <td class="py-1 text-end">
                                <span class="badge bg-primary-subtle text-primary">{{ $tournament->getFormatInfo()['name'] ?? $tournament->competition_format }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted small py-1">Est. Matches</td>
                            <td class="py-1 text-end">{{ $tournament->estimated_matches ?? $tournament->calculateEstimatedMatches() }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted small py-1">Max Teams</td>
                            <td class="py-1 text-end">{{ $tournament->max_teams ?? 'Unlimited' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small py-1">Squad Limit</td>
                            <td class="py-1 text-end">{{ $tournament->squad_limit }} players</td>
                        </tr>
                        <tr>
                            <td class="text-muted small py-1">Min Players</td>
                            <td class="py-1 text-end">{{ $tournament->min_players }} players</td>
                        </tr>
                        @if($tournament->start_date)
                        <tr>
                            <td class="text-muted small py-1">Start Date</td>
                            <td class="py-1 text-end">{{ $tournament->start_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($tournament->end_date)
                        <tr>
                            <td class="text-muted small py-1">End Date</td>
                            <td class="py-1 text-end">{{ $tournament->end_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($tournament->venue)
                        <tr>
                            <td class="text-muted small py-1">Venue</td>
                            <td class="py-1 text-end">{{ $tournament->venue }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted small py-1">Visibility</td>
                            <td class="py-1 text-end">{{ $tournament->is_public ? 'Public' : 'Private' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($tournament->description)
            <div class="card shadow-sm border-0 mt-2">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Description</h6>
                </div>
                <div class="card-body py-2">
                    <p class="small mb-0">{{ $tournament->description }}</p>
                </div>
            </div>
            @endif

            @if($tournament->rules)
            <div class="card shadow-sm border-0 mt-2">
                <div class="card-header py-2 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary small">Rules & Regulations</h6>
                </div>
                <div class="card-body py-2">
                    <p class="small mb-0" style="white-space: pre-wrap;">{{ $tournament->rules }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Registered Teams -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary small">Registered Teams</h6>
                    <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-light py-0">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($tournament->teams->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" width="100%">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-2 px-2">Team</th>
                                    <th class="py-2 px-2">Status</th>
                                    <th class="py-2 px-2">Players</th>
                                    <th class="py-2 px-2">Registered</th>
                                    <th class="py-2 px-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournament->teams->take(10) as $team)
                                <tr>
                                    <td class="py-2 align-middle small">
                                        <a href="{{ route('super-admin.tournaments.teams.players.index', [$tournament->id, $team->id]) }}" class="fw-bold text-decoration-none">{{ $team->team_name ?? ($team->team->name ?? 'N/A') }}</a>
                                    </td>
                                    <td class="py-2 align-middle">
                                        @php
                                            $approvalColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $approvalColors[$team->approval_status] ?? 'secondary' }}-subtle text-{{ $approvalColors[$team->approval_status] ?? 'secondary' }}">
                                            {{ ucfirst($team->approval_status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 align-middle small">{{ $team->squads->count() }}</td>
                                    <td class="py-2 align-middle small">{{ $team->registration_date->format('M d, Y') }}</td>
                                    <td class="py-2 align-middle">
                                        @if($team->approval_status === 'pending')
                                            <form action="{{ route('super-admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-success py-0 px-1" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-xs btn-danger py-0 px-1" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $team->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $team->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('super-admin.tournaments.teams.reject', [$tournament->id, $team->id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title small">Reject Team Registration</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small">You are about to reject the registration for <strong>{{ $team->team_name ?? ($team->team->name ?? 'N/A') }}</strong>.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea name="rejection_reason" class="form-control form-control-sm" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-users fa-2x mb-2 d-block opacity-50"></i>
                        <small>No teams registered yet</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Matches -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary small">Matches</h6>
                    <a href="{{ route('super-admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-sm btn-light py-0">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($tournament->matches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" width="100%">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-2 px-2">Home</th>
                                    <th class="py-2 px-2 text-center">Score</th>
                                    <th class="py-2 px-2">Away</th>
                                    <th class="py-2 px-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournament->matches->take(5) as $match)
                                <tr>
                                    <td class="py-2 align-middle small">{{ $match->homeTeam->team->name ?? $match->homeTeam->team_name ?? 'TBD' }}</td>
                                    <td class="py-2 align-middle text-center">
                                        @if($match->status === 'completed')
                                            <span class="fw-bold">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                        @else
                                            <span class="text-muted">vs</span>
                                        @endif
                                    </td>
                                    <td class="py-2 align-middle small">{{ $match->awayTeam->team->name ?? $match->awayTeam->team_name ?? 'TBD' }}</td>
                                    <td class="py-2 align-middle">
                                        <span class="badge bg-{{ $match->status === 'completed' ? 'success' : 'secondary' }}-subtle text-{{ $match->status === 'completed' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($match->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar-alt fa-2x mb-2 d-block opacity-50"></i>
                        <small>No matches scheduled yet</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Trail -->
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small"><i class="fas fa-history me-1"></i> Audit Trail</h6>
        </div>
        <div class="card-body py-2">
            <div class="row small">
                <div class="col-md-3">
                    <span class="text-muted">Created By:</span>
                    <span class="fw-bold">{{ $tournament->creator->name ?? 'System' }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted">Created:</span>
                    <span class="fw-bold">{{ $tournament->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted">Last Updated:</span>
                    <span class="fw-bold">{{ $tournament->updated_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted">Tournament ID:</span>
                    <span class="fw-bold">#{{ $tournament->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Shuffle Teams Modal -->
@if($tournament->getApprovedTeamsCount() >= 2)
<div class="modal fade" id="shuffleModal" tabindex="-1" aria-labelledby="shuffleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shuffleModalLabel">Shuffle Teams & Generate Matches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You have <strong>{{ $tournament->getApprovedTeamsCount() }}</strong> approved teams ready for this tournament.</p>
                <p class="text-muted small">This action will:</p>
                <ol class="small">
                    <li>Close tournament registration</li>
                    <li>Generate match fixtures based on the tournament format ({{ $tournament->getFormatInfo()['name'] ?? $tournament->competition_format }})</li>
                    <li>Create home and away matches for all teams</li>
                </ol>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>Warning:</strong> This will close registration and generate all matches. You won't be able to add more teams after this.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('super-admin.tournaments.close-and-generate', $tournament->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to close registration and generate matches?')">
                        <i class="fas fa-random me-1"></i> Close & Generate Matches
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    .compact-stats-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .compact-stat-card {
        flex: 1;
        min-width: 120px;
        background: white;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .compact-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .compact-stat-content {
        display: flex;
        flex-direction: column;
    }
    .compact-stat-value {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.2;
    }
    .compact-stat-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }
    .stats-section {
        transition: all 0.3s ease;
    }
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    .btn-group .btn.active {
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }
</style>
@endpush

@push('scripts')
<script>
let currentStatsSection = 'overview';
let statsTimer;

// Initialize statistics
document.addEventListener('DOMContentLoaded', function() {
    // Start real-time updates
    startStatsUpdates();

    // Load initial data
    loadStatsSection(currentStatsSection);
});

// Show different statistics sections
function showStatsSection(section) {
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Hide all sections
    document.querySelectorAll('.stats-section').forEach(section => {
        section.style.display = 'none';
    });

    // Show selected section
    document.getElementById('stats-' + section).style.display = 'block';

    // Load data for section
    loadStatsSection(section);

    currentStatsSection = section;
}

// Load statistics data for specific section
function loadStatsSection(section) {
    const tournamentId = {{ $tournament->id }};
    const url = `/super-admin/tournaments/${tournamentId}/statistics/api/live`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            switch(section) {
                case 'overview':
                    updateOverviewStats(data);
                    break;
                case 'top-scorers':
                    updateTopScorers(data.top_scorers);
                    break;
                case 'discipline':
                    updateDiscipline(data.discipline);
                    break;
                case 'groups':
                    updateGroups(data.groups);
                    break;
                case 'rankings':
                    updateRankings(data.rankings);
                    break;
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

// Update overview statistics
function updateOverviewStats(data) {
    // Update compact stats
    const stats = data.overview;
    document.querySelector('.compact-stat-value:nth-child(1)').textContent = stats.total_teams || 0;
    document.querySelector('.compact-stat-value:nth-child(2)').textContent = stats.approved_teams || 0;
    document.querySelector('.compact-stat-value:nth-child(3)').textContent = stats.pending_teams || 0;
    document.querySelector('.compact-stat-value:nth-child(4)').textContent = stats.total_players || 0;
    document.querySelector('.compact-stat-value:nth-child(5)').textContent = stats.verified_players || 0;
    document.querySelector('.compact-stat-value:nth-child(6)').textContent = stats.total_matches || 0;

    // Update quick stats
    const matchStats = data.match_status;
    const goalStats = data.goals_and_cards;

    // Match status
    document.querySelectorAll('.card-body .row .col')[0].querySelector('.h4').textContent = matchStats.completed || 0;
    document.querySelectorAll('.card-body .row .col')[1].querySelector('.h4').textContent = matchStats.scheduled || 0;
    document.querySelectorAll('.card-body .row .col')[2].querySelector('.h4').textContent = matchStats.pending || 0;

    // Goals & Cards
    document.querySelectorAll('.card-body .row .col')[3].querySelector('.h4').textContent = goalStats.total_goals || 0;
    document.querySelectorAll('.card-body .row .col')[4].querySelector('.h4').textContent = goalStats.yellow_cards || 0;
    document.querySelectorAll('.card-body .row .col')[5].querySelector('.h4').textContent = goalStats.red_cards || 0;
}

// Update top scorers table
function updateTopScorers(scorers) {
    const tbody = document.getElementById('top-scorers-body');
    tbody.innerHTML = '';

    if (!scorers || scorers.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">No scoring data available</td></tr>';
        return;
    }

    scorers.forEach((player, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-2 px-3">${index + 1}</td>
            <td class="py-2 px-3">${player.player_name}</td>
            <td class="py-2 px-3">${player.team_name}</td>
            <td class="py-2 px-3 text-end">${player.goals}</td>
            <td class="py-2 px-3 text-end">${player.assists}</td>
            <td class="py-2 px-3 text-end">${player.matches_played}</td>
        `;
        tbody.appendChild(row);
    });
}

// Update discipline table
function updateDiscipline(discipline) {
    const tbody = document.getElementById('discipline-body');
    tbody.innerHTML = '';

    if (!discipline || discipline.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">No discipline data available</td></tr>';
        return;
    }

    discipline.forEach(player => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-2 px-3">${player.player_name}</td>
            <td class="py-2 px-3">${player.team_name}</td>
            <td class="py-2 px-3 text-center">${player.yellow_cards}</td>
            <td class="py-2 px-3 text-center">${player.red_cards}</td>
            <td class="py-2 px-3 text-center">${player.fouls}</td>
            <td class="py-2 px-3 text-center">${player.suspensions}</td>
        `;
        tbody.appendChild(row);
    });
}

// Update groups table
function updateGroups(groups) {
    const tbody = document.getElementById('groups-body');
    tbody.innerHTML = '';

    if (!groups || groups.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No group data available</td></tr>';
        return;
    }

    groups.forEach(group => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-2 px-3">${group.name}</td>
            <td class="py-2 px-3">${group.team_count}</td>
            <td class="py-2 px-3 text-end">${group.match_count}</td>
            <td class="py-2 px-3 text-end">${group.total_goals}</td>
            <td class="py-2 px-3 text-end">${group.avg_attendance || 0}</td>
        `;
        tbody.appendChild(row);
    });
}

// Update rankings table
function updateRankings(rankings) {
    const tbody = document.getElementById('rankings-body');
    tbody.innerHTML = '';

    if (!rankings || rankings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">No ranking data available</td></tr>';
        return;
    }

    rankings.forEach(team => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-2 px-3">${team.position}</td>
            <td class="py-2 px-3">${team.team_name}</td>
            <td class="py-2 px-3 text-end">${team.points}</td>
            <td class="py-2 px-3 text-end">${team.wins}</td>
            <td class="py-2 px-3 text-end">${team.draws}</td>
            <td class="py-2 px-3 text-end">${team.losses}</td>
            <td class="py-2 px-3 text-end">${team.goal_difference}</td>
        `;
        tbody.appendChild(row);
    });
}

// Start real-time statistics updates
function startStatsUpdates() {
    // Update every 30 seconds
    statsTimer = setInterval(() => {
        if (currentStatsSection) {
            loadStatsSection(currentStatsSection);
        }
    }, 30000);
}

// Stop updates when page is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(statsTimer);
    } else {
        startStatsUpdates();
        loadStatsSection(currentStatsSection);
    }
});
</script>
@endpush
