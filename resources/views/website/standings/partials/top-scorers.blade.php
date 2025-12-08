<div class="row g-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{ $league }} League Top Scorers</h2>
                <p class="text-muted mb-0">Season {{ $season }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('standings', ['season' => $season, 'league' => $league]) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Overview
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('standings.top-scorers', ['season' => $season, 'league' => $league]) }}?view=all">All Players</a></li>
                        <li><a class="dropdown-item" href="{{ route('standings.top-scorers', ['season' => $season, 'league' => $league]) }}?view=top">Top 10</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if($topScorers->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card standings-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 top-scorers-table">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center position-sticky" width="60">Rank</th>
                                <th class="position-sticky" style="left: 60px;">Player</th>
                                <th class="text-center" width="100">Team</th>
                                <th class="text-center" width="80" title="Goals">Goals</th>
                                <th class="text-center" width="80" title="Assists">Assists</th>
                                <th class="text-center" width="80" title="Minutes Played">Mins</th>
                                <th class="text-center" width="80" title="Goals Per Game">GPG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topScorers as $scorer)
                            <tr class="{{ $scorer->is_vipers_player ? 'table-primary' : '' }}">
                                <td class="text-center fw-bold position-sticky">
                                    <span class="rank-badge">{{ $scorer->ranking_position }}</span>
                                </td>
                                <td class="position-sticky player-cell" style="left: 60px;">
                                    <div class="d-flex align-items-center">
                                        @if($scorer->player_image)
                                            <img src="{{ asset('storage/' . $scorer->player_image) }}" alt="{{ $scorer->player_name }}" class="player-avatar me-3">
                                        @else
                                            <div class="player-avatar-placeholder me-3">{{ substr($scorer->player_name, 0, 2) }}</div>
                                        @endif
                                        <div>
                                            <div class="fw-bold player-name">{{ $scorer->player_name }}</div>
                                            @if($scorer->is_vipers_player)
                                                <small class="text-primary fw-semibold">★ Vipers Academy</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($scorer->team_logo)
                                            <img src="{{ asset('storage/' . $scorer->team_logo) }}" alt="{{ $scorer->team_name }}" class="team-logo-small me-2">
                                        @else
                                            <div class="team-logo-placeholder-small me-2">{{ substr($scorer->team_name, 0, 2) }}</div>
                                        @endif
                                        <span class="small">{{ $scorer->team_name }}</span>
                                    </div>
                                </td>
                                <td class="text-center fw-bold fs-5 text-success">{{ $scorer->goals }}</td>
                                <td class="text-center">{{ $scorer->assists ?? '-' }}</td>
                                <td class="text-center">{{ $scorer->minutes_played ?? '-' }}</td>
                                <td class="text-center">{{ $scorer->goals_per_game ? number_format($scorer->goals_per_game, 2) : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-futbol fa-4x mb-4"></i>
                                    <h4>No Scorer Data Available</h4>
                                    <p>Top scorer statistics for {{ $season }} will be updated soon.</p>
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
@endif

<style>
.top-scorers-table {
    font-size: 0.9rem;
}

.top-scorers-table .position-sticky {
    position: sticky;
    background: white;
    z-index: 10;
}

.top-scorers-table .player-cell {
    min-width: 200px;
}

.player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.player-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.player-name {
    font-size: 1rem;
    line-height: 1.2;
}

.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.team-logo-small {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.team-logo-placeholder-small {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 10px;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .top-scorers-table {
        font-size: 0.8rem;
    }

    .player-avatar,
    .player-avatar-placeholder {
        width: 32px;
        height: 32px;
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }

    .player-cell {
        min-width: 150px;
    }
}
</style>
