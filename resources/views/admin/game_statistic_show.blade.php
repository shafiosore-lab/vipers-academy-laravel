@extends('layouts.admin')

@section('title', 'Game Statistics Details - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-eye fa-lg me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">Game Statistics Details</h4>
                                <small class="opacity-75">View detailed performance statistics</small>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('admin.game-statistics.edit', $statistic) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('admin.game-statistics.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-list me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Player and Game Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Player Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            @if($statistic->player->photo)
                                                <img src="{{ asset('storage/' . $statistic->player->photo) }}"
                                                     alt="{{ $statistic->player->name }}"
                                                     class="img-fluid rounded-circle mb-2"
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-2"
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-user fa-2x text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-8">
                                            <h5 class="mb-1">{{ $statistic->player->name }}</h5>
                                            <p class="text-muted mb-1">{{ $statistic->player->position }}</p>
                                            @if($statistic->player->first_name && $statistic->player->last_name)
                                                <small class="text-muted">{{ $statistic->player->first_name }} {{ $statistic->player->last_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-futbol me-2"></i>Game Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Opponent:</strong><br>
                                            {{ $statistic->opponent }}
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Game Date:</strong><br>
                                            {{ $statistic->game_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Tournament:</strong><br>
                                            {{ $statistic->tournament ?? 'N/A' }}
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Minutes Played:</strong><br>
                                            {{ $statistic->minutes_played }}'
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Display -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Performance Statistics</h6>
                                    @if($statistic->ai_generated)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-robot me-1"></i>AI Generated
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user me-1"></i>Manual Entry
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Offensive Stats -->
                                        <div class="col-md-6">
                                            <h6 class="text-success mb-3"><i class="fas fa-bullseye me-1"></i>Offensive</h6>
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <span class="stat-label">Goals Scored:</span>
                                                    <span class="stat-value badge bg-success">{{ $statistic->goals_scored }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Assists:</span>
                                                    <span class="stat-value badge bg-info">{{ $statistic->assists }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Shots on Target:</span>
                                                    <span class="stat-value">{{ $statistic->shots_on_target }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Passes Completed:</span>
                                                    <span class="stat-value">{{ $statistic->passes_completed }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Defensive Stats -->
                                        <div class="col-md-6">
                                            <h6 class="text-warning mb-3"><i class="fas fa-shield-alt me-1"></i>Defensive</h6>
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <span class="stat-label">Tackles:</span>
                                                    <span class="stat-value">{{ $statistic->tackles }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Interceptions:</span>
                                                    <span class="stat-value">{{ $statistic->interceptions }}</span>
                                                </div>
                                                @if($statistic->player->position && strtolower($statistic->player->position) === 'goalkeeper')
                                                    <div class="stat-item">
                                                        <span class="stat-label">Saves:</span>
                                                        <span class="stat-value badge bg-primary">{{ $statistic->saves }}</span>
                                                    </div>
                                                @endif
                                                <div class="stat-item">
                                                    <span class="stat-label">Yellow Cards:</span>
                                                    <span class="stat-value text-warning">{{ $statistic->yellow_cards }}</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-label">Red Cards:</span>
                                                    <span class="stat-value text-danger">{{ $statistic->red_cards }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    @if($statistic->rating)
                                        <hr>
                                        <div class="text-center">
                                            <h5 class="mb-2">Performance Rating</h5>
                                            <div class="rating-display">
                                                <span class="rating-number">{{ number_format($statistic->rating, 1) }}</span>
                                                <span class="rating-text">/ 10</span>
                                            </div>
                                            <div class="progress mt-2" style="height: 20px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                     style="width: {{ ($statistic->rating / 10) * 100 }}%"
                                                     aria-valuenow="{{ $statistic->rating }}"
                                                     aria-valuemin="0" aria-valuemax="10">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Game Summary -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Game Summary</h6>
                                </div>
                                <div class="card-body">
                                    @if($statistic->game_summary)
                                        <p class="text-justify">{{ $statistic->game_summary }}</p>
                                    @else
                                        <p class="text-muted"><em>No game summary provided.</em></p>
                                    @endif

                                    @if($statistic->additional_stats)
                                        <hr>
                                        <h6>Additional Statistics:</h6>
                                        <div class="additional-stats">
                                            @foreach($statistic->additional_stats as $key => $value)
                                                <div class="stat-item">
                                                    <span class="stat-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                    <span class="stat-value">{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Record Info -->
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Record Information</h6>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        <strong>Created:</strong> {{ $statistic->created_at->format('M d, Y H:i') }}<br>
                                        <strong>Last Updated:</strong> {{ $statistic->updated_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.game-statistics.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Statistics List
                                </a>
                                <div class="btn-group">
                                    <a href="{{ route('admin.game-statistics.edit', $statistic) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i>Edit Statistics
                                    </a>
                                    <form action="{{ route('admin.game-statistics.destroy', $statistic) }}" method="POST" class="d-inline ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this game statistic? This action cannot be undone.')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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

.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    border-bottom: 1px solid #f0f0f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: 500;
    color: #666;
}

.stat-value {
    font-weight: bold;
    color: #333;
}

.rating-display {
    display: inline-block;
}

.rating-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #ffc107;
}

.rating-text {
    font-size: 1.2rem;
    color: #666;
}

.additional-stats {
    margin-top: 10px;
}

.btn-group .btn {
    margin-left: 5px;
}
</style>

<script>
// Add any client-side enhancements here
document.addEventListener('DOMContentLoaded', function() {
    console.log('Game Statistics Details page loaded');
});
</script>
@endsection
