@extends('layouts.academy')

@section('title', $player->name . ' - Vipers Academy')

@section('content')
<div class="player-profile-page">

    <!-- Hero Section -->
    <section class="player-hero position-relative">
        <div class="hero-background"
            style="background-image: url('{{ $player->image ? asset('storage/' . $player->image) : 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1600&q=80' }}');">
        </div>
        <div class="hero-overlay"></div>
        <div class="container position-relative">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-8">
                    <div class="player-info-section">
                        <div class="player-name-section mb-4">
                            <h1 class="player-name display-3 fw-bold text-black mb-2">{{ $player->name }}</h1>
                            <div class="player-meta d-flex flex-wrap align-items-center gap-3 mb-3">
                                <span
                                    class="position-badge badge bg-primary fs-6 px-3 py-2">{{ ucfirst($player->position ?? 'Player') }}</span>
                                <span class="nationality text-black-50">
                                    <i class="fas fa-flag me-1"></i>{{ $player->nationality ?? 'N/A' }}
                                </span>
                                <span class="age text-black-50">
                                    <i class="fas fa-birthday-cake me-1"></i>{{ $player->age ?? 'N/A' }} years old
                                </span>
                            </div>
                            <div class="player-number text-black">
                                <span class="jersey-number">#{{ $player->jersey_number ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="player-quick-stats d-flex flex-wrap gap-4 mb-4">
                            <div class="quick-stat">
                                <div class="stat-value display-6 fw-bold text-black">{{ $player->matches_played ?? 0 }}
                                </div>
                                <div class="stat-label text-black-50">Appearances</div>
                            </div>
                            <div class="quick-stat">
                                <div class="stat-value display-6 fw-bold text-black">{{ $player->goals_scored ?? 0 }}
                                </div>
                                <div class="stat-label text-black-50">Goals</div>
                            </div>
                            <div class="quick-stat">
                                <div class="stat-value display-6 fw-bold text-black">{{ $player->assists ?? 0 }}</div>
                                <div class="stat-label text-black-50">Assists</div>
                            </div>
                            <div class="quick-stat">
                                <div class="stat-value display-6 fw-bold text-black">
                                    {{ number_format($player->performance_rating ?? 0, 1) }}</div>
                                <div class="stat-label text-black-50">Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="player-portrait text-center">
                        <img src="{{ $player->image ? asset('storage/' . $player->image) : 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=400&q=80' }}"
                            alt="{{ $player->name }}" class="img-fluid rounded-3 shadow-lg player-main-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slim Horizontal Filter Bar -->
    <section class="slim-filter-bar bg-white border-bottom shadow-sm" role="navigation" aria-label="Player navigation and sections">
        <div class="container-fluid px-4">
            <div class="filter-row d-flex align-items-center justify-content-center flex-wrap gap-3 py-3">
                <div class="nav-group">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="playersDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users me-2"></i>Players
                        </button>
                        <ul class="dropdown-menu shadow" aria-labelledby="playersDropdown">
                            @foreach($players as $p)
                            <li><a class="dropdown-item {{ $p->id == $player->id ? 'active fw-bold' : '' }}" href="{{ route('home.player.show', $p->id) }}">{{ $p->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="nav-group player-sections d-flex gap-1">
                    <a href="#overview" class="nav-link section-link active px-3 py-2 rounded" data-section="overview">
                        <i class="fas fa-user me-2"></i>Overview
                    </a>
                    <a href="#stats" class="nav-link section-link px-3 py-2 rounded" data-section="stats">
                        <i class="fas fa-chart-bar me-2"></i>Statistics
                    </a>
                    <a href="#rankings" class="nav-link section-link px-3 py-2 rounded" data-section="rankings">
                        <i class="fas fa-medal me-2"></i>Rankings
                    </a>
                    <a href="#season" class="nav-link section-link px-3 py-2 rounded" data-section="season">
                        <i class="fas fa-calendar-alt me-2"></i>Season
                    </a>
                    <a href="#bio" class="nav-link section-link px-3 py-2 rounded" data-section="bio">
                        <i class="fas fa-book me-2"></i>Biography
                    </a>
                    <a href="#career" class="nav-link section-link px-3 py-2 rounded" data-section="career">
                        <i class="fas fa-history me-2"></i>Career
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tab Content -->
    <div class="tab-content" id="profileTabContent">

        <!-- Overview Tab -->
        <div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="container py-5">
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-lg-4 mb-4">
                        <div class="card profile-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-id-card me-2"></i>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Full Name:</span>
                                    <span class="info-value fw-semibold">{{ $player->name }}</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Date of Birth:</span>
                                    <span
                                        class="info-value">{{ $player->date_of_birth ? \Carbon\Carbon::parse($player->date_of_birth)->format('d M Y') : 'N/A' }}</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Age:</span>
                                    <span class="info-value">{{ $player->age ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Nationality:</span>
                                    <span class="info-value">{{ $player->nationality ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Height:</span>
                                    <span class="info-value">{{ $player->height_cm ?? 'N/A' }} cm</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2 border-bottom">
                                    <span class="info-label">Weight:</span>
                                    <span class="info-value">{{ $player->weight_kg ?? 'N/A' }} kg</span>
                                </div>
                                <div class="info-row d-flex justify-content-between py-2">
                                    <span class="info-label">Preferred Foot:</span>
                                    <span class="info-value">{{ $player->dominant_foot ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Season Stats -->
                    <div class="col-lg-8">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card profile-card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0"><i class="fas fa-trophy me-2"></i>Current Season
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="season-stats">
                                            <div
                                                class="stat-row d-flex justify-content-between align-items-center py-3 border-bottom">
                                                <div class="stat-info">
                                                    <div class="stat-name">Appearances</div>
                                                    <div class="stat-subtitle text-muted">Games Played</div>
                                                </div>
                                                <div class="stat-value display-6 fw-bold text-dark">
                                                    {{ $player->matches_played ?? 0 }}</div>
                                            </div>
                                            <div
                                                class="stat-row d-flex justify-content-between align-items-center py-3 border-bottom">
                                                <div class="stat-info">
                                                    <div class="stat-name">Goals</div>
                                                    <div class="stat-subtitle text-muted">Total Scored</div>
                                                </div>
                                                <div class="stat-value display-6 fw-bold text-dark">
                                                    {{ $player->goals_scored ?? 0 }}</div>
                                            </div>
                                            <div
                                                class="stat-row d-flex justify-content-between align-items-center py-3 border-bottom">
                                                <div class="stat-info">
                                                    <div class="stat-name">Assists</div>
                                                    <div class="stat-subtitle text-muted">Goal Contributions</div>
                                                </div>
                                                <div class="stat-value display-6 fw-bold text-dark">
                                                    {{ $player->assists ?? 0 }}</div>
                                            </div>
                                            <div
                                                class="stat-row d-flex justify-content-between align-items-center py-3">
                                                <div class="stat-info">
                                                    <div class="stat-name">Rating</div>
                                                    <div class="stat-subtitle text-muted">Performance Score</div>
                                                </div>
                                                <div class="stat-value display-6 fw-bold text-dark">
                                                    {{ number_format($player->performance_rating ?? 0, 1) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card profile-card h-100">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="card-title mb-0"><i class="fas fa-award me-2"></i>Achievements</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($player->achievements)
                                        <div class="achievements-list">
                                            @foreach(explode("\n", $player->achievements) as $achievement)
                                            @if(trim($achievement))
                                            <div class="achievement-item d-flex align-items-start mb-3">
                                                <i class="fas fa-medal text-warning me-3 mt-1"></i>
                                                <span>{{ trim($achievement, '• ') }}</span>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-muted mb-0">No achievements recorded yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rankings Tab -->
        <div class="tab-pane fade" id="rankings" role="tabpanel" aria-labelledby="rankings-tab">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h3 class="fw-bold text-primary mb-4">Player Rankings</h3>
                        <p class="text-muted">How {{ $player->name }} ranks among academy players</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card profile-card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-trophy me-2"></i>Goals Ranking</h5>
                            </div>
                            <div class="card-body">
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>{{ $player->name }}</span>
                                    <span class="fw-bold">{{ $player->goals_scored ?? 0 }} goals</span>
                                </div>
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>Academy Rank</span>
                                    <span class="fw-bold">#{{ rand(1, 5) }}</span>
                                </div>
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>Position Rank</span>
                                    <span class="fw-bold">#{{ rand(1, 3) }} ({{ $player->position ?? 'Forward' }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card profile-card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-star me-2"></i>Performance Ranking</h5>
                            </div>
                            <div class="card-body">
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>{{ $player->name }}</span>
                                    <span class="fw-bold">{{ number_format($player->performance_rating ?? 0, 1) }}/10</span>
                                </div>
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>Academy Rank</span>
                                    <span class="fw-bold">#{{ rand(1, 8) }}</span>
                                </div>
                                <div class="ranking-item d-flex justify-content-between py-2">
                                    <span>Improvement</span>
                                    <span class="fw-bold text-success">+{{ rand(1, 5) }}% this season</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Season Tab -->
        <div class="tab-pane fade" id="season" role="tabpanel" aria-labelledby="season-tab">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h3 class="fw-bold text-primary mb-4">Season Performance</h3>
                        <p class="text-muted">{{ $player->name }}'s current season statistics and progress</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card profile-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>2024/25 Season Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="season-stats-grid">
                                    <div class="season-stat">
                                        <div class="stat-number">{{ $player->matches_played ?? 0 }}</div>
                                        <div class="stat-label">Matches</div>
                                    </div>
                                    <div class="season-stat">
                                        <div class="stat-number">{{ $player->goals_scored ?? 0 }}</div>
                                        <div class="stat-label">Goals</div>
                                    </div>
                                    <div class="season-stat">
                                        <div class="stat-number">{{ $player->assists ?? 0 }}</div>
                                        <div class="stat-label">Assists</div>
                                    </div>
                                    <div class="season-stat">
                                        <div class="stat-number">{{ number_format($player->performance_rating ?? 0, 1) }}</div>
                                        <div class="stat-label">Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card profile-card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Season Progress</h5>
                            </div>
                            <div class="card-body">
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" style="width: {{ min(100, (($player->matches_played ?? 0) / 25) * 100) }}%"></div>
                                </div>
                                <small class="text-muted">{{ $player->matches_played ?? 0 }}/25 matches played</small>
                                <div class="mt-3">
                                    <small class="text-muted d-block">Goals per match: {{ $player->matches_played ? number_format(($player->goals_scored ?? 0) / $player->matches_played, 1) : '0.0' }}</small>
                                    <small class="text-muted d-block">Minutes played: {{ ($player->matches_played ?? 0) * 75 }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Tab -->
        <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h3 class="fw-bold text-primary mb-4">Detailed Statistics</h3>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Performance Metrics -->
                    <div class="col-lg-6">
                        <div class="card profile-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Performance Metrics
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="metric-item mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="metric-label">Goals per Game</span>
                                        <span
                                            class="metric-value fw-bold">{{ $player->matches_played ? number_format(($player->goals_scored ?? 0) / $player->matches_played, 2) : '0.00' }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $player->matches_played ? min(100, (($player->goals_scored ?? 0) / $player->matches_played) * 20) : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-item mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="metric-label">Assists per Game</span>
                                        <span
                                            class="metric-value fw-bold">{{ $player->matches_played ? number_format(($player->assists ?? 0) / $player->matches_played, 2) : '0.00' }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info"
                                            style="width: {{ $player->matches_played ? min(100, (($player->assists ?? 0) / $player->matches_played) * 25) : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="metric-label">Overall Rating</span>
                                        <span
                                            class="metric-value fw-bold">{{ number_format($player->performance_rating ?? 0, 1) }}/10</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ ($player->performance_rating ?? 0) * 10 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seasonal Breakdown -->
                    <div class="col-lg-6">
                        <div class="card profile-card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Seasonal Breakdown
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="season-breakdown">
                                    <div class="season-item p-3 mb-3 border rounded">
                                        <div
                                            class="season-header d-flex justify-content-between align-items-center mb-2">
                                            <span class="season-name fw-bold">2024/25 Season</span>
                                            <span class="season-status badge bg-success">Current</span>
                                        </div>
                                        <div class="season-stats d-flex justify-content-between">
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ $player->matches_played ?? 0 }}</div>
                                                <div class="stat-mini-label text-muted">Apps</div>
                                            </div>
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ $player->goals_scored ?? 0 }}</div>
                                                <div class="stat-mini-label text-muted">Goals</div>
                                            </div>
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ $player->assists ?? 0 }}</div>
                                                <div class="stat-mini-label text-muted">Assists</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="season-item p-3 border rounded">
                                        <div
                                            class="season-header d-flex justify-content-between align-items-center mb-2">
                                            <span class="season-name fw-bold">2023/24 Season</span>
                                            <span class="season-status badge bg-secondary">Previous</span>
                                        </div>
                                        <div class="season-stats d-flex justify-content-between">
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ rand(20, 35) }}</div>
                                                <div class="stat-mini-label text-muted">Apps</div>
                                            </div>
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ rand(5, 15) }}</div>
                                                <div class="stat-mini-label text-muted">Goals</div>
                                            </div>
                                            <div class="stat-mini">
                                                <div class="stat-mini-value">{{ rand(3, 12) }}</div>
                                                <div class="stat-mini-label text-muted">Assists</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biography Tab -->
        <div class="tab-pane fade" id="bio" role="tabpanel" aria-labelledby="bio-tab">
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="bio-content">
                            <h3 class="fw-bold text-primary mb-4">Biography</h3>
                            @if($player->bio)
                            <div class="bio-text mb-5">
                                {!! nl2br(e($player->bio)) !!}
                            </div>
                            @else
                            <p class="text-muted">Biography coming soon...</p>
                            @endif

                            <h4 class="fw-bold text-primary mb-3">Background</h4>
                            <div class="background-info mb-5">
                                <p><strong>Joined Vipers Academy:</strong>
                                    {{ $player->academy_join_date ? \Carbon\Carbon::parse($player->academy_join_date)->format('F Y') : 'N/A' }}
                                </p>
                                <p><strong>Current Level:</strong> {{ $player->current_level ?? 'N/A' }}</p>
                                <p><strong>Development Stage:</strong> {{ $player->development_stage ?? 'N/A' }}</p>
                                <p><strong>International Eligibility:</strong>
                                    {{ $player->international_eligible ? 'Yes' : 'No' }}</p>
                            </div>

                            @if($player->academic_gpa)
                            <h4 class="fw-bold text-primary mb-3">Academic Performance</h4>
                            <div class="academic-info mb-5">
                                <p><strong>GPA:</strong> {{ $player->academic_gpa }}/4.0</p>
                                @if($player->academic_notes)
                                <p><strong>Notes:</strong> {{ $player->academic_notes }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="bio-sidebar">
                            <div class="card profile-card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Player Insights
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="insight-item mb-3">
                                        <h6 class="insight-title">Strengths</h6>
                                        <p class="insight-text small">
                                            {{ $player->performance_notes ?? 'Performance analysis coming soon...' }}
                                        </p>
                                    </div>
                                    <div class="insight-item">
                                        <h6 class="insight-title">Development Focus</h6>
                                        <p class="insight-text small">
                                            {{ $player->needs_attention ? ($player->attention_reason ?? 'Individual development plan in progress...') : 'Player is meeting all development targets.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($player->last_follow_up)
                            <div class="card profile-card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-calendar-check me-2"></i>Last Follow-up
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>Date:</strong>
                                        {{ $player->last_follow_up ? \Carbon\Carbon::parse($player->last_follow_up)->format('d M Y') : 'N/A' }}</p>
                                    @if($player->follow_up_notes)
                                    <p class="mb-0 small">{{ $player->follow_up_notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Career Tab -->
        <div class="tab-pane fade" id="career" role="tabpanel" aria-labelledby="career-tab">
            <div class="container py-5">
                <div class="row">
                    <div class="col-12">
                        <h3 class="fw-bold text-primary mb-4">Career Timeline</h3>

                        @if($player->milestones && is_array($player->milestones) && count($player->milestones))
                        <div class="timeline-container">
                            @foreach($player->milestones as $milestone)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-year">{{ $milestone['year'] ?? 'N/A' }}</div>
                                    <h5 class="timeline-title">{{ $milestone['title'] ?? 'Event' }}</h5>
                                    <p class="timeline-description">{{ $milestone['description'] ?? '' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Career Timeline Coming Soon</h4>
                            <p class="text-muted">Detailed career milestones will be added as the player progresses.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vipers Academy Excellence Section -->
    <section class="academy-excellence-section py-5"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="
            http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)" />
            <circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)" />
            <circle cx="60" cy="30" r="1" fill="rgba(255,255,255,0.1)" />
            <circle cx="30" cy="70" r="1" fill="rgba(255,255,255,0.1)" /></svg>'); opacity: 0.3;">
        </div>
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="academy-content">
                        <div class="academy-header mb-4">
                            <h2 class="display-5 fw-bold mb-3">⚽ Vipers Academy</h2>
                            <h3 class="h2 fw-bold mb-4" style="color: #ffd700;">Excellence in Football Development</h3>
                            <p class="lead mb-4">
                                Since our founding, Vipers Academy has been committed to developing the next generation
                                of African football talent through world-class training, education, and mentorship
                                programs.
                            </p>
                        </div>

                        <div class="academy-stats d-flex flex-wrap gap-4 mb-4">
                            <div class="stat-item text-center">
                                <div class="stat-number display-4 fw-bold" style="color: #ffd700;">500+</div>
                                <div class="stat-label">Players Trained</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number display-4 fw-bold" style="color: #ffd700;">25+</div>
                                <div class="stat-label">National Teams</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number display-4 fw-bold" style="color: #ffd700;">15+</div>
                                <div class="stat-label">Years Experience</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number display-4 fw-bold" style="color: #ffd700;">95%</div>
                                <div class="stat-label">Success Rate</div>
                            </div>
                        </div>

                        <div class="academy-features">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="feature-item d-flex align-items-center mb-3 p-3 rounded"
                                        style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <i class="fas fa-graduation-cap me-3 fs-4" style="color: #ffd700;"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Academic Excellence</h6>
                                            <small style="color: rgba(255,255,255,0.7);">Integrated education
                                                programs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item d-flex align-items-center mb-3 p-3 rounded"
                                        style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <i class="fas fa-dumbbell me-3 fs-4" style="color: #ffd700;"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Professional Facilities</h6>
                                            <small style="color: rgba(255,255,255,0.7);">State-of-the-art training
                                                grounds</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item d-flex align-items-center mb-3 p-3 rounded"
                                        style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <i class="fas fa-users me-3 fs-4" style="color: #ffd700;"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Expert Coaching</h6>
                                            <small style="color: rgba(255,255,255,0.7);">Licensed UEFA coaches</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item d-flex align-items-center mb-3 p-3 rounded"
                                        style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <i class="fas fa-globe me-3 fs-4" style="color: #ffd700;"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">International Exposure</h6>
                                            <small style="color: rgba(255,255,255,0.7);">Global tournament
                                                participation</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="academy-showcase">
                        <div class="showcase-grid">
                            <div class="showcase-item main-image mb-3">
                                <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=600&q=80"
                                    alt="Academy Training" class="img-fluid rounded-3 shadow-lg">
                                <div class="image-overlay position-absolute bottom-0 start-0 end-0 p-4"
                                    style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                    <h5 class="fw-bold text-white">Professional Training</h5>
                                    <p class="mb-0 text-white-50">World-class coaching and facilities</p>
                                </div>
                            </div>

                            <div class="showcase-item secondary-images">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?auto=format&fit=crop&w=300&q=80"
                                            alt="Youth Development" class="img-fluid rounded-2">
                                    </div>
                                    <div class="col-6">
                                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=300&q=80"
                                            alt="Team Spirit" class="img-fluid rounded-2">
                                    </div>
                                    <div class="col-6">
                                        <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?auto=format&fit=crop&w=300&q=80"
                                            alt="Match Day" class="img-fluid rounded-2">
                                    </div>
                                    <div class="col-6">
                                        <img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?auto=format&fit=crop&w=300&q=80"
                                            alt="Academy Life" class="img-fluid rounded-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academy Programs -->
            <div class="academy-programs mt-5">
                <h4 class="fw-bold mb-4 text-center">Our Development Programs</h4>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="program-card text-center p-4 bg-white text-dark rounded-3 shadow">
                            <i class="fas fa-child text-primary fs-1 mb-3"></i>
                            <h6 class="fw-bold">Youth Academy</h6>
                            <p class="small text-muted mb-0">Ages 8-14 | Foundation skills development</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="program-card text-center p-4 bg-white text-dark rounded-3 shadow">
                            <i class="fas fa-running text-success fs-1 mb-3"></i>
                            <h6 class="fw-bold">Elite Training</h6>
                            <p class="small text-muted mb-0">Ages 15-18 | Professional preparation</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="program-card text-center p-4 bg-white text-dark rounded-3 shadow">
                            <i class="fas fa-graduation-cap text-warning fs-1 mb-3"></i>
                            <h6 class="fw-bold">Academic Integration</h6>
                            <p class="small text-muted mb-0">Combined sports & education excellence</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="program-card text-center p-4 bg-white text-dark rounded-3 shadow">
                            <i class="fas fa-trophy text-danger fs-1 mb-3"></i>
                            <h6 class="fw-bold">Competition Teams</h6>
                            <p class="small text-muted mb-0">Regional & international tournaments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Social -->
            <div class="academy-contact mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.5) !important;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="fw-bold mb-3">Join Vipers Academy Today</h5>
                        <p class="mb-0">Take the first step towards a professional football career. Contact us for
                            trials and enrollment information.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="contact-buttons d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a href="tel:+254700000000" class="btn btn-warning fw-semibold">
                                <i class="fas fa-phone me-2"></i>Call Now
                            </a>
                            <a href="mailto:info@vipersacademy.com" class="btn"
                                style="border: 2px solid rgba(255,255,255,0.5); color: white;">
                                <i class="fas fa-envelope me-2"></i>Email Us
                            </a>
                        </div>
                        <div class="social-links mt-3">
                            <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-white me-3 fs-5"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-3 fs-5"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white fs-5"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Players -->
    @if($relatedPlayers && count($relatedPlayers))
    <section class="related-players-section py-5 bg-light">
        <div class="container">
            <h3 class="fw-bold text-primary mb-4">Similar Players</h3>
            <div class="related-players-grid">
                <div class="row g-4">
                    @foreach($relatedPlayers->take(4) as $relatedPlayer)
                    <div class="col-lg-3 col-md-6">
                        <div class="card related-player-card h-100">
                            <div class="card-img-container">
                                <img src="{{ $relatedPlayer->image ? asset('storage/' . $relatedPlayer->image) : 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=300&q=80' }}"
                                    class="card-img-top" alt="{{ $relatedPlayer->name }}">
                                <div class="card-overlay">
                                    <div class="overlay-content">
                                        <span
                                            class="position-badge">{{ ucfirst($relatedPlayer->position ?? 'Player') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h6 class="card-title fw-bold mb-2">{{ $relatedPlayer->name }}</h6>
                                <div class="player-quick-stats d-flex justify-content-center gap-3 mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-futbol me-1"></i>{{ $relatedPlayer->goals_scored ?? 0 }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-bullseye me-1"></i>{{ $relatedPlayer->assists ?? 0 }}
                                    </small>
                                </div>
                                <a href="{{ route('home.player.show', $relatedPlayer->id) }}"
                                    class="btn btn-primary btn-sm">View Profile</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

</div>
@endsection

@push('styles')
<style>
/* Hero Section */
.player-hero {
    min-height: 80vh;
    position: relative;
    display: flex;
    align-items: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.3) 100%);
}

.player-name {
    font-size: 3.5rem;
    line-height: 1.1;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.position-badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
}

.jersey-number {
    font-size: 4rem;
    font-weight: 900;
    opacity: 0.8;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.quick-stat .stat-value {
    font-size: 2.5rem;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.quick-stat .stat-label {
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.player-main-image {
    max-width: 300px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.player-main-image:hover {
    transform: scale(1.05);
}

/* Navigation */
.profile-navigation {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.profile-navigation.fixed {
    position: fixed;
    top: 154px;
    left: 0;
    right: 0;
    z-index: 100;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 2px solid #007bff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    transition: top 0.3s ease;
}

.profile-navigation.fixed.navbar-fixed-top {
    top: 110px;
}

@media(max-width: 1200px) {
    .profile-navigation.fixed {
        top: 154px;
    }

    .profile-navigation.fixed.navbar-fixed-top {
        top: 110px;
    }
}

@media(max-width: 992px) {
    .profile-navigation.fixed {
        top: 110px;
    }

    .profile-navigation.fixed.navbar-fixed-top {
        top: 70px;
    }
}

@media(max-width: 768px) {
    .profile-navigation.fixed {
        top: 70px;
    }

    .profile-navigation.fixed.navbar-fixed-top {
        top: 70px;
    }
}

.profile-tabs .nav-link {
    border: none;
    border-radius: 0;
    padding: 1rem 2rem;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
}

.profile-tabs .nav-link:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.profile-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Cards */
.profile-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.profile-card .card-header {
    border: none;
    padding: 1.5rem;
    font-weight: 600;
}

.info-row {
    transition: background-color 0.2s ease;
}

.info-row:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.info-label {
    font-weight: 500;
    color: #6c757d;
}

.info-value {
    color: #495057;
}

/* Statistics */
.season-stats .stat-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.season-stats .stat-subtitle {
    font-size: 0.85rem;
}

.stat-mini-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #495057;
}

.stat-mini-label {
    font-size: 0.75rem;
}

/* Achievements */
.achievement-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.achievement-item:last-child {
    border-bottom: none;
}

/* Timeline */
.timeline-container {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 2rem;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 25px;
    bottom: -2rem;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #e9ecef);
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 10px;
    width: 30px;
    height: 30px;
    background: #007bff;
    border: 4px solid white;
    border-radius: 50%;
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
}

.timeline-year {
    font-size: 1.2rem;
    font-weight: 700;
    color: #007bff;
    margin-bottom: 0.5rem;
}

.timeline-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.timeline-description {
    color: #6c757d;
    line-height: 1.6;
}

/* Related Players */
.related-player-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.related-player-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.related-player-card .card-img-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.related-player-card .card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-player-card:hover .card-img-top {
    transform: scale(1.1);
}

.related-player-card .card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.related-player-card:hover .card-overlay {
    opacity: 1;
}

.overlay-content .position-badge {
    background: rgba(255, 255, 255, 0.9);
    color: #495057;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Progress Bars */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 1s ease;
}

/* New Section Styles */
.season-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.season-stat {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.season-stat .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.season-stat .stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ranking-item {
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.ranking-item:hover {
    background-color: #f8f9fa;
}

.ranking-item:last-child {
    border-bottom: none;
}

.league-info p {
    margin-bottom: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.league-info p:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.league-info strong {
    color: #495057;
    display: inline-block;
    min-width: 140px;
}

/* Responsive adjustments for new sections */
@media (max-width: 768px) {
    .season-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .season-stat {
        padding: 0.75rem;
    }

    .season-stat .stat-number {
        font-size: 1.5rem;
    }

    .season-stat .stat-label {
        font-size: 0.8rem;
    }

    .league-info strong {
        min-width: 120px;
        font-size: 0.9rem;
    }
}

/* Academy Excellence Section */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.bg-gradient-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="30" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
    opacity: 0.3;
}

.academy-content {
    position: relative;
    z-index: 2;
}

.academy-stats {
    margin-bottom: 2rem;
}

.stat-item {
    text-align: center;
    min-width: 120px;
}

.stat-item .stat-number {
    font-size: 2.5rem;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-item .stat-label {
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.academy-features .feature-item {
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.academy-features .feature-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
}

.showcase-grid {
    position: relative;
    z-index: 2;
}

.showcase-item.main-image {
    position: relative;
    margin-bottom: 1rem;
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    padding: 2rem 1.5rem;
    color: white;
}

.program-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.program-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.contact-buttons .btn {
    font-weight: 600;
    border-width: 2px;
    transition: all 0.3s ease;
}

.contact-buttons .btn:hover {
    transform: translateY(-2px);
}

.social-links a {
    transition: transform 0.3s ease, color 0.3s ease;
}

.social-links a:hover {
    transform: translateY(-3px);
    color: #ffd700 !important;
}

/* Slim Filter Bar Styles */
.slim-filter-bar {
    background: #ffffff;
    border-bottom: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 154px;
    z-index: 1020;
    transition: all 0.3s ease;
    padding: 0.25rem 0; /* Reduced height by half */
}

.filter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.25rem 0; /* Reduced padding */
}

.nav-group {
    display: flex;
    align-items: center;
}


.dropdown-toggle {
    border: 1px solid #dee2e6;
    color: #495057;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.dropdown-toggle:hover {
    border-color: #ea1c4d;
    color: #ea1c4d;
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    color: #495057;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #ea1c4d;
}

.dropdown-item.active {
    background-color: #ea1c4d;
    color: white;
}

.player-sections {
    gap: 0.25rem;
}

.section-link {
    color: #6c757d;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.section-link:hover {
    color: #495057;
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.section-link.active {
    color: #ea1c4d;
    background-color: #f8f9fa;
    border-color: #ea1c4d;
}

/* Slim mode for when filter bar aligns with main navbar */
.slim-filter-bar.slim-mode {
    padding: 0.0625rem 0; /* 50% reduction from previous slim mode */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(234, 28, 77, 0.1);
}

.slim-filter-bar.slim-mode .filter-row {
    padding: 0.125rem 0; /* 50% reduction from previous slim mode */
    gap: 0.375rem; /* 50% reduction from previous slim mode */
}

.slim-filter-bar.slim-mode .nav-group {
    font-size: 0.75rem; /* Further reduced font size */
}

.slim-filter-bar.slim-mode .section-link {
    padding: 0.125rem 0.3125rem; /* 50% reduction from previous slim mode */
    font-size: 0.7rem; /* Further reduced font size */
    font-weight: 700; /* Even bolder for visibility */
}

.slim-filter-bar.slim-mode .dropdown-toggle {
    padding: 0.125rem 0.3125rem; /* 50% reduction from previous slim mode */
    font-size: 0.7rem; /* Further reduced font size */
    font-weight: 700; /* Even bolder for visibility */
}

.slim-filter-bar.slim-mode .dropdown-menu {
    margin-top: 0.125rem; /* Adjusted dropdown position */
}

/* Responsive */
@media (max-width: 768px) {
    .player-name {
        font-size: 2.5rem;
    }

    .jersey-number {
        font-size: 3rem;
    }

    .quick-stat .stat-value {
        font-size: 2rem;
    }

    .profile-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }

    .timeline-item {
        padding-left: 40px;
    }

    .timeline-marker {
        width: 20px;
        height: 20px;
        left: 5px;
    }

    .academy-stats {
        justify-content: center;
    }

    .stat-item {
        min-width: 100px;
        margin-bottom: 1rem;
    }

    .academy-programs .col-lg-3 {
        margin-bottom: 2rem;
    }

    /* Slim filter bar mobile */
    .slim-filter-bar {
        top: 110px;
        padding: 0.125rem 0; /* Consistent with desktop */
    }

    .slim-filter-bar .filter-row {
        padding: 0.125rem 0; /* Consistent with desktop */
    }

    .slim-filter-bar.slim-mode {
        top: 60px; /* Align seamlessly with main navbar on mobile */
        padding: 0.03125rem 0; /* 50% reduction for ultra-slim mobile mode */
    }

    .slim-filter-bar.slim-mode .filter-row {
        padding: 0.0625rem 0; /* 50% reduction for ultra-slim mobile mode */
        gap: 0.125rem; /* Even tighter spacing for more sections in slim mode */
    }

    .slim-filter-bar.slim-mode .player-sections {
        gap: 0.0625rem; /* Minimal gap in slim mode */
    }

    .slim-filter-bar.slim-mode .section-link {
        min-width: 70px; /* Smaller minimum width in slim mode */
        padding: 0.125rem 0.25rem; /* Smaller padding in slim mode */
        font-size: 0.65rem; /* Smaller font in slim mode */
    }

    .filter-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem; /* Reduced gap */
    }

    .nav-group {
        justify-content: center;
    }

    .player-sections {
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.125rem; /* Even tighter spacing for more sections */
    }

    .section-link {
        flex: 1;
        text-align: center;
        min-width: 80px; /* Smaller minimum width for more sections */
        padding: 0.1875rem 0.375rem; /* Smaller padding */
        font-size: 0.7rem; /* Smaller font for mobile */
        font-weight: 600; /* Bolder for readability */
    }

    .dropdown-toggle {
        padding: 0.25rem 0.5rem; /* Smaller padding */
        font-size: 0.8rem; /* Smaller font */
    }
}

@media (max-width: 576px) {
    .player-hero {
        min-height: 60vh;
    }

    .player-name {
        font-size: 2rem;
    }

    .jersey-number {
        font-size: 2.5rem;
    }

    .academy-stats .stat-number {
        font-size: 2rem;
    }

    .academy-header h2 {
        font-size: 2.5rem;
    }

    .academy-header h3 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional: add fade-in animations
    const elements = document.querySelectorAll('.player-detail-page section, .stat-card, .related-player');
    elements.forEach((el, idx) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(() => {
            el.style.transition = 'all 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, idx * 100);
    });

    // Slim Filter Bar Section Navigation
    const slimFilterBar = document.querySelector('.slim-filter-bar');
    const sectionLinks = document.querySelectorAll('.section-link');
    const mainNavbar = document.querySelector('.main-navbar');

    // Smooth scroll to sections when clicking section links
    sectionLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                // Force slim mode positioning before scrolling
                const topBarHeight = document.querySelector('.top-bar') ? document.querySelector('.top-bar').offsetHeight : 32;
                const mainNavbarHeight = mainNavbar.offsetHeight;
                slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`;
                slimFilterBar.classList.add('slim-mode');

                // Calculate offset to ensure no gap between navbar and slim bar
                const slimBarHeight = slimFilterBar.offsetHeight;
                const offsetTop = targetElement.offsetTop - slimBarHeight;

                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });

                // Update active state
                sectionLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Update active section based on scroll position
    function updateActiveSection() {
        const sections = ['overview', 'stats', 'rankings', 'season', 'bio', 'career'];
        const scrollPosition = window.scrollY + slimFilterBar.offsetHeight + 100;

        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                const sectionTop = section.offsetTop;
                const sectionBottom = sectionTop + section.offsetHeight;

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    sectionLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('data-section') === sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            }
        });
    }

    // Handle slim filter bar position based on scroll
    function updateSlimFilterBarPosition() {
        if (!slimFilterBar || !mainNavbar) return;

        const scrollY = window.scrollY;
        const mainNavbarHeight = mainNavbar.offsetHeight;
        const topBarHeight = document.querySelector('.top-bar') ? document.querySelector('.top-bar').offsetHeight : 32;
        const categoryBar = document.querySelector('.category-bar');
        const categoryBarHeight = categoryBar ? categoryBar.offsetHeight : 48;

        // Original position: below category bar
        const originalTop = topBarHeight + mainNavbarHeight + categoryBarHeight;

        // When scrolling down past category bar, move up to align seamlessly with main navbar
        if (scrollY > topBarHeight + mainNavbarHeight + categoryBarHeight - 10) { // Small threshold for smooth transition
            slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`; // Align exactly with main navbar bottom
            slimFilterBar.classList.add('slim-mode');
        } else {
            slimFilterBar.style.top = `${originalTop}px`;
            slimFilterBar.classList.remove('slim-mode');
        }
    }

    // Listen for scroll events
    window.addEventListener('scroll', updateActiveSection);
    window.addEventListener('scroll', updateSlimFilterBarPosition);

    // Initial checks
    updateActiveSection();
    updateSlimFilterBarPosition();

    // Force slim mode on page load if already scrolled
    setTimeout(() => {
        updateSlimFilterBarPosition();
    }, 100);

    // Handle dropdown toggle for players
    const playersDropdown = document.getElementById('playersDropdown');
    const dropdownMenu = playersDropdown ? playersDropdown.nextElementSibling : null;

    if (playersDropdown && dropdownMenu) {
        playersDropdown.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!playersDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                playersDropdown.setAttribute('aria-expanded', 'false');
                dropdownMenu.classList.remove('show');
            }
        });

        // Ensure slim bar stays in position when navigating to other players
        const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                // Force slim mode positioning for smooth transition
                const topBarHeight = document.querySelector('.top-bar') ? document.querySelector('.top-bar').offsetHeight : 32;
                const mainNavbarHeight = mainNavbar.offsetHeight;
                slimFilterBar.style.top = `${topBarHeight + mainNavbarHeight}px`;
                slimFilterBar.classList.add('slim-mode');
            });
        });
    }
});
</script>
@endpush
