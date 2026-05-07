@extends('layouts.admin')

@section('title', 'Matches - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Matches</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <!-- Quick Create Buttons -->
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-plus me-1"></i> Add Match
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'tournament']) }}">Tournament Match</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'league']) }}">League Match</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'friendly']) }}">Friendly Match</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'knockout']) }}">Knockout Round</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'group_stage']) }}">Group Stage</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.create', [$tournament->id, 'type' => 'exhibition']) }}">Exhibition Match</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
<!-- Filters -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Matches</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="postponed" {{ request('status') == 'postponed' ? 'selected' : '' }}>Postponed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="match_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="tournament" {{ request('match_type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                    <option value="league" {{ request('match_type') == 'league' ? 'selected' : '' }}>League</option>
                    <option value="friendly" {{ request('match_type') == 'friendly' ? 'selected' : '' }}>Friendly</option>
                    <option value="knockout" {{ request('match_type') == 'knockout' ? 'selected' : '' }}>Knockout</option>
                    <option value="group_stage" {{ request('match_type') == 'group_stage' ? 'selected' : '' }}>Group Stage</option>
                    <option value="exhibition" {{ request('match_type') == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
                </select>
            </div>
            @if($pools->count() > 0)
            <div class="col-md-2">
                <select name="pool_id" class="form-select form-select-sm">
                    <option value="">All Pools</option>
                    @foreach($pools as $pool)
                        <option value="{{ $pool->id }}" {{ request('pool_id') == $pool->id ? 'selected' : '' }}>{{ $pool->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($venues->count() > 0)
            <div class="col-md-2">
                <select name="venue_id" class="form-select form-select-sm">
                    <option value="">All Venues</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Matches Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-2">Match</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Pool</th>
                    <th class="py-2">Venue</th>
                    <th class="py-2 text-center">Day</th>
                    <th class="py-2 text-center">Status</th>
                    <th class="py-2 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matches as $match)
                    <tr class="{{ $match->isInProgress() ? 'table-warning' : '' }} {{ $match->isCancelled() ? 'table-danger' : '' }}">
                        <td class="py-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>{{ $match->homeTeam->team_name ?? 'TBD' }}</span>
                                <span class="mx-2 fw-bold">{{ $match->isCompleted() ? $match->home_score . ' - ' . $match->away_score : 'vs' }}</span>
                                <span>{{ $match->awayTeam->team_name ?? 'TBD' }}</span>
                            </div>
                            <div class="small text-muted text-center">
                                {{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, H:i') : 'Time TBD' }}
                                @if($match->timezone && $match->timezone !== 'UTC')
                                    <span class="badge bg-light text-dark ms-1">{{ $match->timezone }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-2">
                            @php
                                $typeClass = match($match->match_type) {
                                    'tournament' => 'primary',
                                    'league' => 'success',
                                    'friendly' => 'info',
                                    'knockout' => 'danger',
                                    'group_stage' => 'warning',
                                    'exhibition' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $typeClass }}">{{ ucfirst(str_replace('_', ' ', $match->match_type)) }}</span>
                        </td>
                        <td class="py-2">{{ $match->pool?->name ?? '-' }}</td>
                        <td class="py-2">{{ $match->venueModel?->name ?? $match->venue ?? '-' }}</td>
                        <td class="py-2 text-center">{{ $match->match_day ?? '-' }}</td>
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
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $match->status)) }}</span>
                        </td>
                        <td class="py-2 text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.edit', [$tournament->id, $match->id]) }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a></li>
                                    @if($match->isScheduled())
                                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.matches.start', [$tournament->id, $match->id]) }}" onclick="return confirm('Start this match?')">
                                        <i class="fas fa-play me-1"></i> Start Match
                                    </a></li>
                                    @endif
                                    @if($match->canEdit())
                                    <li>
                                        <form action="{{ route('admin.tournaments.matches.destroy', [$tournament->id, $match->id]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this match?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-futbol fa-2x mb-2 d-block"></i>
                        No matches found.
                        <br>
                        <a href="{{ route('admin.tournaments.matches.create', $tournament->id) }}" class="btn btn-primary btn-sm mt-2">
                            Create First Match
                        </a>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($matches->hasPages())
    <div class="card-footer">{{ $matches->links() }}</div>
    @endif
</div>

<!-- Quick Stats -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <h4 class="mb-0">{{ $tournament->matches()->scheduled()->count() }}</h4>
                <small class="text-muted">Scheduled</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <h4 class="mb-0">{{ $tournament->matches()->inProgress()->count() }}</h4>
                <small class="text-muted">In Progress</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <h4 class="mb-0">{{ $tournament->matches()->completed()->count() }}</h4>
                <small class="text-muted">Completed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <h4 class="mb-0">{{ $tournament->matches()->count() }}</h4>
                <small class="text-muted">Total Matches</small>
            </div>
        </div>
    </div>
</div>
@endsection
