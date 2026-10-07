@extends('layouts.admin')

@section('title', 'Groups/Pools - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Groups/Pools Standings - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}">Statistics</a>
                <span class="mx-1">/</span>
                Groups/Pools
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <button id="refresh-groups-btn" class="btn btn-sm btn-outline-primary" title="Refresh Groups">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Last Updated Indicator -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-clock me-2"></i>
        <span id="last-updated">Groups standings last updated: {{ now()->format('M d, Y H:i') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Tournament Format Info -->
    @if($groups->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Tournament Format:</strong> {{ $formatInfo['name'] }} - {{ $formatInfo['description'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Groups/Pools Content -->
    @if($groups->count() > 0)
        <div class="row">
            @foreach($groups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-layer-group me-2"></i>
                            Group {{ $group->name }}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if($group->teams->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->teams as $team)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $team->standings->position <= 2 ? 'success' : 'secondary' }}">
                                                    {{ $team->standings->position }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        {{ substr($team->team_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $team->team_name }}</div>
                                                        <small class="text-muted">{{ $team->standings->played }} matches</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $team->standings->played }}</td>
                                            <td class="text-center">{{ $team->standings->won }}</td>
                                            <td class="text-center">{{ $team->standings->drawn }}</td>
                                            <td class="text-center">{{ $team->standings->lost }}</td>
                                            <td class="text-center">{{ $team->standings->goals_for }}</td>
                                            <td class="text-center">{{ $team->standings->goals_against }}</td>
                                            <td class="text-center">
                                                <span class="{{ $team->standings->goal_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $team->standings->goal_difference > 0 ? '+' : '' }}{{ $team->standings->goal_difference }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold">{{ $team->standings->points }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-layer-group fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No teams in this group yet</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted">Teams</small>
                                <div class="fw-bold">{{ $group->teams->count() }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Matches</small>
                                <div class="fw-bold">{{ $groupMatches->where('pool_id', $group->id)->count() }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Completed</small>
                                <div class="fw-bold">{{ $groupMatches->where('pool_id', $group->id)->where('status', 'completed')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Group Matches Summary -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Group Matches Summary</h6>
                    </div>
                    <div class="card-body">
                        @if($groupMatches->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Group</th>
                                            <th>Date</th>
                                            <th>Home Team</th>
                                            <th>Score</th>
                                            <th>Away Team</th>
                                            <th>Status</th>
                                            <th>Venue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupMatches->take(10) as $match)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">Group {{ $match->pool->name }}</span>
                                            </td>
                                            <td>{{ $match->kickoff_time->format('M d, Y H:i') }}</td>
                                            <td>{{ $match->homeTeam->team_name ?? 'TBD' }}</td>
                                            <td>
                                                @if($match->status === 'completed')
                                                    <span class="fw-bold">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $match->awayTeam->team_name ?? 'TBD' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $match->status === 'completed' ? 'success' : ($match->status === 'scheduled' ? 'info' : 'warning') }}">
                                                    {{ ucfirst($match->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $match->venueModel->name ?? 'TBD' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.tournaments.matches.index', $tournament) }}" class="btn btn-sm btn-outline-primary">
                                    View All Matches
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-futbol fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No group matches scheduled yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-layer-group fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-2">No Groups/Pools Available</h5>
                <p class="text-muted mb-4">
                    This tournament does not use a group or pool format.
                    <br>Standings will be displayed in the Rankings section.
                </p>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.tournaments.statistics.rankings', $tournament) }}" class="btn btn-primary">
                        <i class="fas fa-chart-line me-2"></i> View Rankings
                    </a>
                    <a href="{{ route('admin.tournaments.statistics.index', $tournament) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tachometer-alt me-2"></i> Back to Overview
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- JavaScript for Real-time Updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-groups-btn');
    const lastUpdatedSpan = document.getElementById('last-updated');

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshGroups();
    }, 30000);

    // Manual refresh on button click
    refreshBtn.addEventListener('click', function() {
        refreshGroups();
    });

    function refreshGroups() {
        fetch('{{ route('admin.tournaments.statistics.api.live', $tournament) }}')
            .then(response => response.json())
            .then(data => {
                // Update last updated time
                const now = new Date();
                lastUpdatedSpan.textContent = 'Groups standings last updated: ' + now.toLocaleString();

                // Update standings (would need to implement DOM updates)
                console.log('Groups standings refreshed:', data);
            })
            .catch(error => {
                console.error('Error refreshing groups standings:', error);
            });
    }
});
</script>
@endsection
