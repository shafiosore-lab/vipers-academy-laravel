@extends('layouts.admin')

@section('title', 'View Match - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-futbol fa-lg me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">Match Details</h4>
                                <small class="opacity-75">{{ $match->opponent }} - {{ ucfirst($match->type) }}</small>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('admin.matches.edit', $match) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit Match
                            </a>
                            <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-list me-1"></i>All Matches
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Match Overview -->
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Match Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Opponent</label>
                                                <div class="detail-value">{{ $match->opponent }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Type</label>
                                                <div class="detail-value">
                                                    <span class="badge bg-{{ $match->type === 'friendly' ? 'success' : 'info' }}">
                                                        {{ ucfirst($match->type) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Date & Time</label>
                                                <div class="detail-value">{{ $match->match_date->format('M d, Y H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Venue</label>
                                                <div class="detail-value">{{ $match->venue }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Status</label>
                                                <div class="detail-value">
                                                    <span class="badge bg-{{ $match->status === 'completed' ? 'success' : ($match->status === 'upcoming' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($match->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($match->tournament_name)
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Tournament</label>
                                                <div class="detail-value">{{ $match->tournament_name }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Match Results (if completed) -->
                                    @if($match->status === 'completed')
                                    <hr>
                                    <h6 class="text-center mb-3">Final Score</h6>
                                    <div class="score-display-large">
                                        <div class="team-large">
                                            <div class="team-logo-large">
                                                <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers Academy" class="img-fluid">
                                            </div>
                                            <div class="team-name-large">Vipers Academy</div>
                                            <div class="score-large {{ $match->vipers_score > $match->opponent_score ? 'winner' : '' }}">
                                                {{ $match->vipers_score ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="vs-large">
                                            <span class="badge bg-danger">VS</span>
                                        </div>
                                        <div class="team-large">
                                            <div class="score-large {{ $match->opponent_score > $match->vipers_score ? 'winner' : '' }}">
                                                {{ $match->opponent_score ?? 0 }}
                                            </div>
                                            <div class="team-name-large">{{ $match->opponent }}</div>
                                            <div class="team-logo-large">
                                                <div class="opponent-logo-large">
                                                    <i class="fas fa-shield-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Match Description -->
                            @if($match->description)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Description</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $match->description }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Match Summary -->
                            @if($match->match_summary)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Match Summary</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $match->match_summary }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Tournament Settings -->
                            @if($match->type === 'tournament')
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-crown me-2"></i>Tournament Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Registration Open</label>
                                                <div class="detail-value">
                                                    <span class="badge bg-{{ $match->registration_open ? 'success' : 'secondary' }}">
                                                        {{ $match->registration_open ? 'Yes' : 'No' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($match->registration_deadline)
                                        <div class="col-md-6">
                                            <div class="match-detail-item">
                                                <label class="detail-label">Registration Deadline</label>
                                                <div class="detail-value">{{ $match->registration_deadline->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Match Images -->
                            @if($match->images && count($match->images) > 0)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Match Images ({{ count($match->images) }})</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        @foreach($match->images as $image)
                                        <div class="col-6">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Match image" class="img-fluid rounded">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Links -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-link me-2"></i>Links</h5>
                                </div>
                                <div class="card-body">
                                    @if($match->live_link)
                                    <div class="mb-2">
                                        <strong>Live Stream:</strong><br>
                                        <a href="{{ $match->live_link }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-play me-1"></i>Watch Live
                                        </a>
                                    </div>
                                    @endif

                                    @if($match->highlights_link)
                                    <div class="mb-2">
                                        <strong>Highlights:</strong><br>
                                        <a href="{{ $match->highlights_link }}" target="_blank" class="btn btn-warning btn-sm">
                                            <i class="fas fa-video me-1"></i>View Highlights
                                        </a>
                                    </div>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('match-center.show', $match) }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>View Public Page
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Match Statistics -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Match Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="stat-item-sidebar">
                                        <span class="stat-label-sidebar">ID:</span>
                                        <span class="stat-value-sidebar">#{{ $match->id }}</span>
                                    </div>
                                    <div class="stat-item-sidebar">
                                        <span class="stat-label-sidebar">Created:</span>
                                        <span class="stat-value-sidebar">{{ $match->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="stat-item-sidebar">
                                        <span class="stat-label-sidebar">Updated:</span>
                                        <span class="stat-value-sidebar">{{ $match->updated_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($match->images)
                                    <div class="stat-item-sidebar">
                                        <span class="stat-label-sidebar">Images:</span>
                                        <span class="stat-value-sidebar">{{ count($match->images) }}</span>
                                    </div>
                                    @endif
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
}

.match-detail-item {
    margin-bottom: 1.5rem;
}

.detail-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 500;
}

.score-display-large {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 12px;
}

.team-large {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.team-logo-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    background: white;
    border: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-logo-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.opponent-logo-large {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    font-size: 1.5rem;
}

.team-name-large {
    font-weight: 600;
    color: #374151;
    text-align: center;
    font-size: 0.9rem;
}

.score-large {
    font-size: 2.5rem;
    font-weight: 900;
    color: #374151;
    transition: color 0.3s ease;
}

.score-large.winner {
    color: #10b981;
}

.vs-large {
    margin: 0 1rem;
}

.stat-item-sidebar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.stat-item-sidebar:last-child {
    border-bottom: none;
}

.stat-label-sidebar {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.stat-value-sidebar {
    font-size: 0.875rem;
    color: #1f2937;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
