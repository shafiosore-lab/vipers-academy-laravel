<div class="row g-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{ $league }} League Clean Sheets</h2>
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
                        <li><a class="dropdown-item" href="{{ route('standings.clean-sheets', ['season' => $season, 'league' => $league]) }}?view=all">All Keepers</a></li>
                        <li><a class="dropdown-item" href="{{ route('standings.clean-sheets', ['season' => $season, 'league' => $league]) }}?view=top">Top 10</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if($cleanSheets->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card standings-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 clean-sheets-table">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center position-sticky" width="60">Rank</th>
                                <th class="position-sticky" style="left: 60px;">Goalkeeper</th>
                                <th class="text-center" width="100">Team</th>
                                <th class="text-center" width="80" title="Clean Sheets">CS</th>
                                <th class="text-center" width="80" title="Games Played">GP</th>
                                <th class="text-center" width="80" title="Save Percentage">Save %</th>
                                <th class="text-center" width="80" title="Goals Conceded">GC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cleanSheets as $keeper)
                            <tr class="{{ $keeper->is_vipers_keeper ? 'table-primary' : '' }}">
                                <td class="text-center fw-bold position-sticky">
                                    <span class="rank-badge">{{ $keeper->position }}</span>
                                </td>
                                <td class="position-sticky keeper-cell" style="left: 60px;">
                                    <div class="d-flex align-items-center">
                                        @if($keeper->goalkeeper_image)
                                            <img src="{{ asset('storage/' . $keeper->goalkeeper_image) }}" alt="{{ $keeper->goalkeeper_name }}" class="keeper-avatar me-3">
                                        @else
                                            <div class="keeper-avatar-placeholder me-3">{{ substr($keeper->goalkeeper_name, 0, 2) }}</div>
                                        @endif
                                        <div>
                                            <div class="fw-bold keeper-name">{{ $keeper->goalkeeper_name }}</div>
                                            @if($keeper->is_vipers_keeper)
                                                <small class="text-primary fw-semibold">★ Vipers Academy</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($keeper->team_logo)
                                            <img src="{{ asset('storage/' . $keeper->team_logo) }}" alt="{{ $keeper->team_name }}" class="team-logo-small me-2">
                                        @else
                                            <div class="team-logo-placeholder-small me-2">{{ substr($keeper->team_name, 0, 2) }}</div>
                                        @endif
                                        <span class="small">{{ $keeper->team_name }}</span>
                                    </div>
                                </td>
                                <td class="text-center fw-bold fs-5 text-success">{{ $keeper->clean_sheets }}</td>
                                <td class="text-center">{{ $keeper->games_played ?? '-' }}</td>
                                <td class="text-center">{{ $keeper->save_percentage ? number_format($keeper->save_percentage, 1) . '%' : '-' }}</td>
                                <td class="text-center">{{ $keeper->goals_conceded ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-shield-alt fa-4x mb-4"></i>
                                    <h4>No Clean Sheet Data Available</h4>
                                    <p>Clean sheet statistics for {{ $season }} will be updated soon.</p>
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
.clean-sheets-table {
    font-size: 0.9rem;
}

.clean-sheets-table .position-sticky {
    position: sticky;
    background: white;
    z-index: 10;
}

.clean-sheets-table .keeper-cell {
    min-width: 200px;
}

.keeper-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.keeper-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.keeper-name {
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
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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
    .clean-sheets-table {
        font-size: 0.8rem;
    }

    .keeper-avatar,
    .keeper-avatar-placeholder {
        width: 32px;
        height: 32px;
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }

    .keeper-cell {
        min-width: 150px;
    }
}
</style>
