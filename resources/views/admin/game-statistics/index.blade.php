@extends('layouts.admin')

@section('title', 'Game Statistics - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-lg me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">Game Statistics Management</h4>
                                <small class="opacity-75">Track and manage player performance statistics</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.game-statistics.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Statistics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $statistics->total() }}</h5>
                                    <p class="card-text">Total Game Records</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $statistics->where('ai_generated', true)->count() }}</h5>
                                    <p class="card-text">AI Generated</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $statistics->where('ai_generated', false)->count() }}</h5>
                                    <p class="card-text">Manual Entries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $statistics->unique('player_id')->count() }}</h5>
                                    <p class="card-text">Active Players</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Player</th>
                                    <th>Position</th>
                                    <th>Game Date</th>
                                    <th>Opponent</th>
                                    <th>Tournament</th>
                                    <th>Goals</th>
                                    <th>Assists</th>
                                    <th>Minutes</th>
                                    <th>Rating</th>
                                    <th>AI Generated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistics as $stat)
                                <tr>
                                    <td>
                                        <strong>{{ $stat->player->name }}</strong>
                                        <br><small class="text-muted">{{ $stat->player->first_name }} {{ $stat->player->last_name }}</small>
                                    </td>
                                    <td>{{ $stat->player->position }}</td>
                                    <td>{{ $stat->game_date->format('M d, Y') }}</td>
                                    <td>{{ $stat->opponent }}</td>
                                    <td>{{ $stat->tournament ?? 'N/A' }}</td>
                                    <td><span class="badge bg-success">{{ $stat->goals_scored }}</span></td>
                                    <td><span class="badge bg-info">{{ $stat->assists }}</span></td>
                                    <td>{{ $stat->minutes_played }}'</td>
                                    <td>
                                        @if($stat->rating)
                                            <span class="badge bg-warning">{{ number_format($stat->rating, 1) }}/10</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($stat->ai_generated)
                                            <span class="badge bg-primary"><i class="fas fa-robot me-1"></i>AI</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>Manual</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.game-statistics.show', $stat) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.game-statistics.edit', $stat) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.game-statistics.destroy', $stat) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this game statistic?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Game Statistics Found</h5>
                                        <p class="text-muted">Start by adding player performance data from recent games.</p>
                                        <a href="{{ route('admin.game-statistics.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Add First Statistics
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($statistics->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $statistics->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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
// Auto-refresh functionality (optional)
document.addEventListener('DOMContentLoaded', function() {
    // Add any client-side enhancements here
    console.log('Game Statistics page loaded');
});
</script>
@endsection
