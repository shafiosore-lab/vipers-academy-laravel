<!-- Training Session Standings Page -->
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-primary mb-1">
                        <i class="fas fa-futbol me-2"></i>Training Session Standings
                    </h1>
                    <p class="text-muted mb-0">Season {{ $season }} - {{ $league }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('standings') }}" class="btn btn-outline-primary">
                        <i class="fas fa-trophy me-2"></i>All Standings
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-calendar me-2"></i>{{ $season }}
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($availableSeasons as $availableSeason)
                            <li>
                                <a class="dropdown-item {{ $season === $availableSeason ? 'active' : '' }}"
                                   href="{{ route('standings.league-table', ['season' => $availableSeason, 'league' => $league]) }}">
                                    {{ $availableSeason }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- League Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>{{ $league }} League Table
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-white-50 small">
                                <i class="fas fa-clock me-1"></i>Last updated: {{ now()->format('M d, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center fw-bold" width="60">#</th>
                                    <th class="fw-bold">Team</th>
                                    <th class="text-center" width="60" title="Played">P</th>
                                    <th class="text-center" width="60" title="Won">W</th>
                                    <th class="text-center" width="60" title="Drawn">D</th>
                                    <th class="text-center" width="60" title="Lost">L</th>
                                    <th class="text-center" width="80" title="Goals For">GF</th>
                                    <th class="text-center" width="80" title="Goals Against">GA</th>
                                    <th class="text-center" width="80" title="Goal Difference">GD</th>
                                    <th class="text-center" width="80" title="Points">Pts</th>
                                    <th class="text-center" width="100" title="Form">Form</th>
                                    <th class="text-center" width="80" title="Clean Sheets">CS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($standings as $index => $team)
                                <tr class="position-relative
                                    @if($index < 4) table-warning @elseif($index < 6) table-info @elseif($index >= count($standings) - 3) table-danger @endif
                                    {{ $team->is_vipers_team ? 'table-primary' : '' }}">
                                    <td class="text-center fw-bold fs-5">
                                        <span class="badge bg-dark">{{ $team->position }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($team->team_logo)
                                                <img src="{{ asset('storage/' . $team->team_logo) }}"
                                                     alt="{{ $team->team_name }}"
                                                     class="rounded-circle me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold"
                                                     style="width: 40px; height: 40px; font-size: 14px;">
                                                    {{ substr($team->team_name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $team->team_name }}</div>
                                                @if($team->is_vipers_team)
                                                    <small class="text-primary">
                                                        <i class="fas fa-star me-1"></i>Vipers Academy
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold">{{ $team->played }}</td>
                                    <td class="text-center text-success fw-bold">{{ $team->won }}</td>
                                    <td class="text-center text-warning fw-semibold">{{ $team->drawn }}</td>
                                    <td class="text-center text-danger fw-bold">{{ $team->lost }}</td>
                                    <td class="text-center fw-semibold">{{ $team->goals_for }}</td>
                                    <td class="text-center">{{ $team->goals_against }}</td>
                                    <td class="text-center fw-bold
                                        {{ $team->goal_difference > 0 ? 'text-success' : ($team->goal_difference < 0 ? 'text-danger' : '') }}">
                                        {{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}
                                    </td>
                                    <td class="text-center fw-bold fs-5 text-primary">{{ $team->points }}</td>
                                    <td class="text-center">
                                        @if($team->form)
                                            <div class="d-flex justify-content-center gap-1">
                                                @php $formArray = str_split($team->form); @endphp
                                                @foreach(array_slice($formArray, -5) as $result)
                                                    <span class="badge
                                                        {{ $result === 'W' ? 'bg-success' : ($result === 'D' ? 'bg-warning text-dark' : 'bg-danger') }}
                                                        rounded-pill px-2 py-1 small">
                                                        {{ $result }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold">{{ $team->clean_sheets }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-table fa-4x mb-3 opacity-50"></i>
                                            <h4>No League Data Available</h4>
                                            <p>Training session standings for {{ $season }} will be updated soon.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($standings->count() > 0)
    <div class="row mt-4 g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-trophy fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $standings->first()->team_name }}</h4>
                    <p class="text-muted small mb-0">Current Leader</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-bullseye fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $standings->max('goals_for') }}</h4>
                    <p class="text-muted small mb-0">Most Goals Scored</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $standings->max('clean_sheets') }}</h4>
                    <p class="text-muted small mb-0">Most Clean Sheets</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ number_format($standings->avg('points_per_game'), 2) }}</h4>
                    <p class="text-muted small mb-0">Avg Points/Game</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Training Session Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">
                                <i class="fas fa-running me-2 text-primary"></i>Training Session Information
                            </h5>
                            <p class="text-muted mb-0">
                                Track your team's performance across training sessions. Points are awarded based on participation,
                                skill demonstration, and team contribution. Regular attendance and improvement are key to climbing the rankings.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('programs') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Join Training
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.table-info {
    background-color: rgba(13, 202, 240, 0.1);
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.table-primary {
    background-color: rgba(13, 110, 253, 0.15);
}

.badge {
    font-size: 0.75em;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.7em;
    }
}
</style>
