@extends('layouts.admin')

@section('title', __('Dashboard - GameSuite Admin'))

@section('breadcrumb')
<nav aria-label="{{ __('Breadcrumb navigation') }}">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-th-large me-1" aria-hidden="true"></i>{{ __('Dashboard') }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Welcome Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                        <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        <p class="mb-0">{{ now()->format('g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compact Analytics Dashboard -->
<div class="row mb-4">
    <div class="col-12">
        @include('components.compact-analytics')
    </div>
</div>

<!-- Permission-Based Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚡ Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @can('create_players')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.players.create') }}" class="btn btn-outline-primary w-100">
                            <div class="mb-1">👤</div>
                            <small>Add Player</small>
                        </a>
                    </div>
                    @endcan

                    @can('create_programs')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.programs.create') }}" class="btn btn-outline-success w-100">
                            <div class="mb-1">🎯</div>
                            <small>Add Program</small>
                        </a>
                    </div>
                    @endcan

                    @can('manage_documents')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-warning w-100">
                            <div class="mb-1">📄</div>
                            <small>Documents</small>
                        </a>
                    </div>
                    @endcan

                    @can('create_blog')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-info w-100">
                            <div class="mb-1">📰</div>
                            <small>Add Blog</small>
                        </a>
                    </div>
                    @endcan

                    @can('manage_website_players')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.website-players.create') }}" class="btn btn-outline-secondary w-100">
                            <div class="mb-1">🖼️</div>
                            <small>Website Players</small>
                        </a>
                    </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tournament Management (Permission-Based) -->
@can('manage_tournaments')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🏆 Tournament Management</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Core Tournament Actions -->
                    @can('create_tournaments')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-outline-primary w-100 h-100">
                            <div class="mb-1">🏁</div>
                            <small>Create Tournament</small>
                        </a>
                    </div>
                    @endcan

                    @can('view_tournaments')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-primary w-100 h-100">
                            <div class="mb-1">📋</div>
                            <small>All Tournaments</small>
                        </a>
                    </div>
                    @endcan

                    @can('manage_tournament_teams')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-success w-100 h-100">
                            <div class="mb-1">👥</div>
                            <small>Manage Teams</small>
                        </a>
                    </div>
                    @endcan

                    @can('manage_tournament_venues')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-warning w-100 h-100">
                            <div class="mb-1">📍</div>
                            <small>Venue Management</small>
                        </a>
                    </div>
                    @endcan

                    <!-- Match Management -->
                    @can('manage_tournament_matches')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-info w-100 h-100">
                            <div class="mb-1">⚽</div>
                            <small>Match Management</small>
                        </a>
                    </div>
                    @endcan

                    @can('create_tournament_matches')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-info w-100 h-100">
                            <div class="mb-1">➕</div>
                            <small>Create Match</small>
                        </a>
                    </div>
                    @endcan

                    <!-- Pool & Standings -->
                    @can('manage_tournament_pools')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary w-100 h-100">
                            <div class="mb-1">🏊</div>
                            <small>Pool Management</small>
                        </a>
                    </div>
                    @endcan

                    @can('view_standings')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.standings.index') }}" class="btn btn-outline-secondary w-100 h-100">
                            <div class="mb-1">📊</div>
                            <small>Standings</small>
                        </a>
                    </div>
                    @endcan

                    <!-- Smart Tournament Engine -->
                    @can('manage_tournament_squads')
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-dark w-100 h-100">
                            <div class="mb-1">📝</div>
                            <small>Squad Management</small>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Player Ecosystem (Permission-Based) -->
@can('manage_players')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🎓 Digital Player Ecosystem</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Player Management -->
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.players.index') }}" class="btn btn-outline-success w-100 h-100">
                            <div class="mb-1">👥</div>
                            <small>All Players</small>
                        </a>
                    </div>

                    <!-- Injury Tracking & Availability -->
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-info w-100 h-100" onclick="alert('Injury Tracking - Available in player profile'); return false;">
                            <div class="mb-1">🏥</div>
                            <small>Injury Tracking</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-warning w-100 h-100" onclick="alert('Player Availability - Configure in player profile'); return false;">
                            <div class="mb-1">✅</div>
                            <small>Availability</small>
                        </a>
                    </div>

                    <!-- Transfers & Contracts -->
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-primary w-100 h-100" onclick="alert('Transfers - Configure in team management'); return false;">
                            <div class="mb-1">🔄</div>
                            <small>Player Transfers</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 h-100" onclick="alert('Contracts - Configure in team management'); return false;">
                            <div class="mb-1">📜</div>
                            <small>Contracts</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-dark w-100 h-100" onclick="alert('Transfer Windows - Configure in tournament settings'); return false;">
                            <div class="mb-1">🪟</div>
                            <small>Transfer Windows</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Governance & Compliance (Permission-Based) -->
@can('manage_governance')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">⚖️ Governance & Compliance</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Age Verification -->
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-warning w-100 h-100" onclick="alert('Age Verification - Configure in tournament settings'); return false;">
                            <div class="mb-1">🎂</div>
                            <small>Age Verification</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-danger w-100 h-100" onclick="alert('Age Fraud Alerts - Configure in tournament settings'); return false;">
                            <div class="mb-1">⚠️</div>
                            <small>Age Fraud Alerts</small>
                        </a>
                    </div>

                    <!-- Disciplinary -->
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-dark w-100 h-100" onclick="alert('Disciplinary Cases - Configure in tournament settings'); return false;">
                            <div class="mb-1">📋</div>
                            <small>Disciplinary Cases</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 h-100" onclick="alert('Player Suspensions - Configure in tournament settings'); return false;">
                            <div class="mb-1">🚫</div>
                            <small>Suspensions</small>
                        </a>
                    </div>

                    <!-- Appeals & Protests -->
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-primary w-100 h-100" onclick="alert('Appeals - Configure in tournament settings'); return false;">
                            <div class="mb-1">🗣️</div>
                            <small>Appeals</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-info w-100 h-100" onclick="alert('Protests - Configure in tournament settings'); return false;">
                            <div class="mb-1">📢</div>
                            <small>Protests</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Venue & Referee Management (Permission-Based) -->
@canany(['manage_venues', 'manage_referees'])
<div class="row mb-4">
    @can('manage_venues')
    <div class="col-md-6">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📍 Venue Management</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                            <i class="fas fa-plus"></i> Add Venue
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-info w-100 btn-sm">
                            <i class="fas fa-list"></i> All Venues
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-success w-100 btn-sm" onclick="alert('Venue Availability Calendar - Configure in venue details'); return false;">
                            <i class="fas fa-calendar"></i> Availability
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-warning w-100 btn-sm" onclick="alert('Venue Booking Requests - Configure in venue management'); return false;">
                            <i class="fas fa-bookmark"></i> Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('manage_referees')
    <div class="col-md-6">
        <div class="card border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">👨‍⚖️ Referee Management</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 btn-sm" onclick="alert('Add Referee - Configure in tournament settings'); return false;">
                            <i class="fas fa-plus"></i> Add Referee
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 btn-sm" onclick="alert('All Referees - Configure in tournament settings'); return false;">
                            <i class="fas fa-list"></i> All Referees
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-primary w-100 btn-sm" onclick="alert('Training Sessions - Configure in referee management'); return false;">
                            <i class="fas fa-graduation-cap"></i> Training
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-warning w-100 btn-sm" onclick="alert('Performance Reviews - Configure in referee management'); return false;">
                            <i class="fas fa-star"></i> Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endcan

<!-- System Status Row -->
<div class="row g-4">
    <!-- FIFA Compliance -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚽ FIFA Compliance Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <div class="h2 mb-0 text-success">✅</div>
                            <div class="fw-bold">{{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}</div>
                            <small class="text-muted">FIFA Registered</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <div class="h2 mb-0 text-warning">🛡️</div>
                            <div class="fw-bold">{{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}</div>
                            <small class="text-muted">Safeguarding</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Status -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">💳 Subscription Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-2 border rounded mb-2">
                            <div class="h4 mb-0 text-success">{{ $subscriptionStats['active_subscriptions'] ?? 0 }}</div>
                            <small class="text-muted">Active</small>
                        </div>
                        <div class="p-2 border rounded">
                            <div class="h4 mb-0 text-info">{{ $subscriptionStats['trialing_subscriptions'] ?? 0 }}</div>
                            <small class="text-muted">Trials</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded mb-2">
                            <div class="h4 mb-0 text-warning">{{ $subscriptionStats['past_due_subscriptions'] ?? 0 }}</div>
                            <small class="text-muted">Past Due</small>
                        </div>
                        <div class="p-2 border rounded">
                            <div class="h4 mb-0 text-secondary">{{ $subscriptionStats['canceled_subscriptions'] ?? 0 }}</div>
                            <small class="text-muted">Canceled</small>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <span class="badge bg-primary">
                        <i class="fas fa-building me-1"></i>
                        {{ $subscriptionStats['active_organizations'] ?? 0 }} Organizations
                    </span>
                    @if(($subscriptionStats['subscriptions_ending_soon'] ?? 0) > 0)
                    <span class="badge bg-warning ms-1">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $subscriptionStats['subscriptions_ending_soon'] }} ending soon
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-md-4">
        <div class="card h-100 bg-success text-white">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">💚 System Health</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Database</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Storage</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Performance</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>API</small>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <small class="opacity-75">MRR: KES {{ number_format($subscriptionStats['mrr'] ?? 0, 2) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

