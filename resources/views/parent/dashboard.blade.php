@extends('layouts.staff')

@section('title', 'Parent Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Welcome, {{ $user->name ?? 'Parent' }}!</h2>
        <p class="text-muted mb-0">Here's an overview of your child's progress at Vipers Academy.</p>
    </div>
    @if($selectedPlayer)
    <div class="text-end d-none d-md-block">
        <span class="badge bg-primary fs-6">{{ $selectedPlayer->full_name }}</span>
    </div>
    @endif
</div>

@if(isset($noPlayers) && $noPlayers)
<!-- No Players State -->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
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
</div>
@else
@if($selectedPlayer)
<!-- Player Quick Info Card -->
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #ea1c4d, #c0173f);">
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
</div>
@endif

<!-- Quick Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Training Sessions</p>
                        <h3 class="mb-0">{{ $quickStats['training_sessions'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded p-3">
                        <i class="fas fa-calendar-check text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Goals Scored</p>
                        <h3 class="mb-0">{{ $quickStats['goals_scored'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <i class="fas fa-futbol text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Assists</p>
                        <h3 class="mb-0">{{ $quickStats['assists'] ?? 0 }}</h3>
                    </div>
                    <div class="bg-purple bg-opacity-10 rounded p-3" style="background-color: #f3e8ff;">
                        <i class="fas fa-hands-helping fs-4" style="color: #9333ea;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Minutes Played</p>
                        <h3 class="mb-0">{{ number_format($quickStats['minutes_played'] ?? 0) }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <i class="fas fa-clock text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row g-4 mb-4">
    <!-- Financial Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
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
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
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
        </div>
    </div>

    <!-- AI Insights -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
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
        </div>
    </div>
</div>

<!-- Recent Activity & Upcoming -->
<div class="row g-4 mb-4">
    <!-- Recent Matches -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
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
        </div>
    </div>

    <!-- Upcoming Training Sessions -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
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
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    <div class="col-6 col-md-3">
        <a href="{{ route('parent.profile') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="bg-primary bg-opacity-10 rounded p-3 mb-2 mx-auto" style="width: fit-content;">
                    <i class="fas fa-user text-primary fs-4"></i>
                </div>
                <h6 class="text-dark">Profile</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('parent.finances') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="bg-success bg-opacity-10 rounded p-3 mb-2 mx-auto" style="width: fit-content;">
                    <i class="fas fa-credit-card text-success fs-4"></i>
                </div>
                <h6 class="text-dark">Payments</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('parent.media') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="bg-purple bg-opacity-10 rounded p-3 mb-2 mx-auto" style="width: fit-content; background-color: #f3e8ff;">
                    <i class="fas fa-images fs-4" style="color: #9333ea;"></i>
                </div>
                <h6 class="text-dark">Gallery</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('parent.announcements') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="bg-warning bg-opacity-10 rounded p-3 mb-2 mx-auto" style="width: fit-content;">
                    <i class="fas fa-bullhorn text-warning fs-4"></i>
                </div>
                <h6 class="text-dark">News</h6>
            </div>
        </a>
    </div>
</div>
@endif
@endsection
