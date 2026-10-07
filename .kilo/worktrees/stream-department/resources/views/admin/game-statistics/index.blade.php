@extends('layouts.admin')

@section('title', 'Game Statistics - Admin Dashboard')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-chart-line fa-lg me-2 text-primary"></i>
            <div>
                <h5 class="mb-0">Game Statistics</h5>
                <small class="text-muted">Track player performance</small>
            </div>
        </div>
        <a href="{{ route('admin.game-statistics.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add
        </a>
    </div>

    <!-- Compact Stats Row -->
    <div class="compact-stats-row mb-3">
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <i class="fas fa-list text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics->total() }}</div>
                <div class="compact-stat-label">Total Records</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics->where('ai_generated', true)->count() }}</div>
                <div class="compact-stat-label">AI Generated</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics->where('ai_generated', false)->count() }}</div>
                <div class="compact-stat-label">Manual</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <i class="fas fa-users text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $statistics->unique('player_id')->count() }}</div>
                <div class="compact-stat-label">Active Players</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">Player</th>
                            <th class="py-1 px-2">Position</th>
                            <th class="py-1 px-2">Game Date</th>
                            <th class="py-1 px-2">Opponent</th>
                            <th class="py-1 px-2">Tournament</th>
                            <th class="py-1 px-2">Goals</th>
                            <th class="py-1 px-2">Assists</th>
                            <th class="py-1 px-2">Min</th>
                            <th class="py-1 px-2">Rating</th>
                            <th class="py-1 px-2">AI</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statistics as $stat)
                        <tr>
                            <td class="py-1 align-middle small">{{ $stat->player->name }}</td>
                            <td class="py-1 align-middle small">{{ $stat->player->position }}</td>
                            <td class="py-1 align-middle small">{{ $stat->game_date->format('M d, Y') }}</td>
                            <td class="py-1 align-middle small">{{ $stat->opponent }}</td>
                            <td class="py-1 align-middle small">{{ $stat->tournament ?? 'N/A' }}</td>
                            <td class="py-1 align-middle"><span class="badge bg-success-subtle text-success" style="font-size: 10px;">{{ $stat->goals_scored }}</span></td>
                            <td class="py-1 align-middle"><span class="badge bg-info-subtle text-info" style="font-size: 10px;">{{ $stat->assists }}</span></td>
                            <td class="py-1 align-middle small">{{ $stat->minutes_played }}'</td>
                            <td class="py-1 align-middle">
                                @if($stat->rating)
                                    <span class="badge bg-warning-subtle text-warning" style="font-size: 10px;">{{ number_format($stat->rating, 1) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @if($stat->ai_generated)
                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">AI</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Manual</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.game-statistics.show', $stat) }}" class="btn btn-sm btn-outline-info py-0 px-1" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.game-statistics.edit', $stat) }}" class="btn btn-sm btn-outline-warning py-0 px-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.game-statistics.destroy', $stat) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Delete"
                                                onclick="return confirm('Delete this statistic?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-3">
                                <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                <h6 class="text-muted">No Game Statistics Found</h6>
                                <p class="text-muted small mb-2">Add player performance data from recent games.</p>
                                <a href="{{ route('admin.game-statistics.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Add First
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($statistics->hasPages())
                <div class="d-flex justify-content-center mt-2">
                    {{ $statistics->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

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
    font-size: 16px;
}

.compact-stat-content {
    display: flex;
    flex-direction: column;
}

.compact-stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
    color: #2c3e50;
}

.compact-stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    vertical-align: middle;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Game Statistics page loaded');
});
</script>
@endsection
