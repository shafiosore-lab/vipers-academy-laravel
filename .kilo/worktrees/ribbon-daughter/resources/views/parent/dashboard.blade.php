@extends('layouts.admin')

@section('title', 'Parent Dashboard')

@section('content')
<!-- Welcome Section -->
<x-dashboard-card variant="default" spacing="lg" class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-text">
            <h2 class="welcome-title">Welcome, {{ $user->name ?? 'Parent' }}!</h2>
            <p class="welcome-subtitle">Here's an overview of your child's progress at GameSuite.</p>
        </div>
        @if($selectedPlayer)
        <div class="welcome-actions">
            <span class="badge bg-primary fs-6">{{ $selectedPlayer->full_name }}</span>
        </div>
        @endif
    </div>
</x-dashboard-card>

@if(isset($noPlayers) && $noPlayers)
<!-- No Players State -->
<x-dashboard-card variant="default" spacing="lg">
    <div class="text-center py-5">
        <div class="w-20 h-20 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4">
            <i class="fas fa-child text-3xl text-primary"></i>
        </div>
        <h3 class="mb-2">No Players Found</h3>
        <p class="text-muted mb-4">
            We couldn't find any players associated with your account. Please contact the academy administration to link your account with your child/children.
        </p>
        <a href="{{ route('parent.announcements') }}" class="btn btn-primary">
            View Announcements
        </a>
    </div>
</x-dashboard-card>
@else
@if($selectedPlayer)
<!-- Player Quick Info Card -->
<x-dashboard-card variant="default" spacing="md" class="player-info-card" style="background: linear-gradient(135deg, #ea1c4d, #c0173f);">
    <div class="card-body text-white">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $selectedPlayer->image_url ?? asset('assets/img/default-player.png') }}"
                     alt="{{ $selectedPlayer->full_name }}"
                     class="rounded-circle border-3 border-white"
                     style="width: 80px; height: 80px; object-fit: cover;">
            </div>
            <div class="col">
                <h3 class="text-white mb-2">{{ $selectedPlayer->full_name }}</h3>
                <div class="d-flex flex-wrap gap-3">
                    <span><i class="fas fa-layer-group me-1"></i> {{ $selectedPlayer->category ?? 'N/A' }}</span>
                    <span><i class="fas fa-running me-1"></i> {{ $selectedPlayer->position ?? 'N/A' }}</span>
                    <span><i class="fas fa-tshirt me-1"></i> #{{ $selectedPlayer->jersey_number ?? 'N/A' }}</span>
                    <span><i class="fas fa-birthday-cake me-1"></i> {{ $selectedPlayer->age ?? 'N/A' }} years</span>
                </div>
            </div>
            <div class="col-auto text-center">
                <div class="display-4 fw-bold">{{ $quickStats['attendance_rate'] ?? 0 }}%</div>
                <div class="opacity-75">Attendance</div>
            </div>
        </div>
    </div>
</x-dashboard-card>
@endif

<!-- Quick Stats Grid -->
<x-dashboard-grid columns="4" gap="lg" variant="default" class="stats-grid">
    <x-dashboard-card variant="default" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ $quickStats['training_sessions'] ?? 0 }}</div>
            <div class="stat-label">Training Sessions</div>
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </x-dashboard-card>

    <x-dashboard-card variant="default" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ $quickStats['goals_scored'] ?? 0 }}</div>
            <div class="stat-label">Goals Scored</div>
            <div class="stat-icon">
                <i class="fas fa-futbol"></i>
            </div>
        </div>
    </x-dashboard-card>

    <x-dashboard-card variant="default" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ $quickStats['assists'] ?? 0 }}</div>
            <div class="stat-label">Assists</div>
            <div class="stat-icon">
                <i class="fas fa-hands-helping"></i>
            </div>
        </div>
    </x-dashboard-card>

    <x-dashboard-card variant="default" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ number_format($quickStats['minutes_played'] ?? 0) }}</div>
            <div class="stat-label">Minutes Played</div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </x-dashboard-card>
</x-dashboard-grid>

<!-- Main Content Grid -->
<x-dashboard-grid columns="3" gap="lg" variant="default" class="main-grid">
    <!-- Financial Summary -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Financial Summary</h5>
                <a href="{{ route('parent.finances') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Monthly Fee</span>
                    <span class="fw-semibold">KSh {{ number_format($financialSummary['monthly_fee'] ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Paid</span>
                    <span class="fw-semibold text-success">KSh {{ number_format($financialSummary['total_paid'] ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Outstanding Balance</span>
                    <span class="fw-semibold {{ ($financialSummary['current_balance'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        KSh {{ number_format($financialSummary['current_balance'] ?? 0) }}
                    </span>
                </div>
            </div>
            @if(($financialSummary['current_balance'] ?? 0) > 0)
            <div class="alert alert-danger py-2 mb-0">
                <i class="fas fa-exclamation-circle me-1"></i>
                Please clear your outstanding balance.
            </div>
            @endif
        </div>
    </x-dashboard-card>

    <!-- Attendance Summary -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Attendance (30 Days)</h5>
                <a href="{{ route('parent.training') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Attendance Rate</span>
                    <span class="fw-semibold">{{ $attendanceSummary['attendance_rate'] ?? 0 }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: {{ $attendanceSummary['attendance_rate'] ?? 0 }}%"></div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col">
                    <h4 class="mb-0">{{ $attendanceSummary['attended_sessions'] ?? 0 }}</h4>
                    <small class="text-muted">Attended</small>
                </div>
                <div class="col">
                    <h4 class="mb-0">{{ $attendanceSummary['total_sessions'] ?? 0 }}</h4>
                    <small class="text-muted">Total</small>
                </div>
                <div class="col">
                    <h4 class="mb-0">{{ number_format($attendanceSummary['total_minutes'] ?? 0) }}</h4>
                    <small class="text-muted">Minutes</small>
                </div>
            </div>
        </div>
    </x-dashboard-card>

    <!-- AI Insights -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">AI Insights</h5>
                <a href="{{ route('parent.insights') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($aiInsights && $aiInsights->count() > 0)
                @foreach($aiInsights->take(3) as $insight)
                <div class="bg-light rounded p-3 mb-2">
                    <p class="mb-1 small">{{ $insight->title ?? 'Insight' }}</p>
                    <small class="text-muted">{{ $insight->created_at->diffForHumans() }}</small>
                </div>
                @endforeach
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-brain fs-1 mb-2 d-block opacity-25"></i>
                <p class="mb-0 small">No AI insights available yet.</p>
            </div>
            @endif
        </div>
    </x-dashboard-card>
</x-dashboard-grid>

<!-- Recent Activity & Upcoming -->
<x-dashboard-grid columns="2" gap="lg" variant="default" class="activity-grid">
    <!-- Recent Matches -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Matches</h5>
                <a href="{{ route('parent.matches') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($recentMatches && $recentMatches->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($recentMatches as $match)
                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-futbol text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $match->match->opponent ?? 'Match' }}</h6>
                            <small class="text-muted">{{ $match->match_date->format('M d, Y') ?? '' }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0">{{ $match->goals_scored ?? 0 }} - {{ $match->assists ?? 0 }}</h6>
                        <small class="text-muted">{{ $match->minutes_played ?? 0 }} min</small>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-futbol fs-1 mb-2 d-block opacity-25"></i>
                <p class="mb-0 small">No match records yet.</p>
            </div>
            @endif
        </div>
    </x-dashboard-card>

    <!-- Upcoming Training Sessions -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upcoming Training</h5>
                <a href="{{ route('parent.training') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($upcomingSessions && $upcomingSessions->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($upcomingSessions as $session)
                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-calendar-alt text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $session->title ?? 'Training Session' }}</h6>
                            <small class="text-muted">{{ $session->session_date->format('M d, Y') ?? '' }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0">{{ $session->start_time ?? '' }}</h6>
                        <small class="text-muted">{{ $session->venue ?? 'TBD' }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-calendar-alt fs-1 mb-2 d-block opacity-25"></i>
                <p class="mb-0 small">No upcoming sessions scheduled.</p>
            </div>
            @endif
        </div>
    </x-dashboard-card>
</x-dashboard-grid>

<!-- Quick Actions -->
<x-dashboard-grid columns="4" gap="md" variant="minimal" class="quick-actions-grid">
    <a href="{{ route('parent.profile') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="quick-action-content">
            <div class="quick-action-title">Profile</div>
        </div>
    </a>

    <a href="{{ route('parent.finances') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="quick-action-content">
            <div class="quick-action-title">Payments</div>
        </div>
    </a>

    <a href="{{ route('parent.media') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fas fa-images"></i>
        </div>
        <div class="quick-action-content">
            <div class="quick-action-title">Gallery</div>
        </div>
    </a>

    <a href="{{ route('parent.announcements') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="quick-action-content">
            <div class="quick-action-title">News</div>
        </div>
    </a>
</x-dashboard-grid>
@endif
@endsection

@push('styles')
<style>
    .player-info-card {
        background: linear-gradient(135deg, #ea1c4d, #c0173f);
    }

    .stat-icon {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 24px;
        opacity: 0.2;
    }

    .quick-action-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .quick-action-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: var(--primary-color);
        color: white;
    }

    .quick-action-content {
        display: flex;
        flex-direction: column;
    }

    .quick-action-title {
        font-weight: 600;
        color: var(--text-color);
    }
</style>
@endpush
