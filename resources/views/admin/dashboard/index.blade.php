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
<x-dashboard-card variant="default" spacing="lg" class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-text">
            <h2 class="welcome-title">Welcome back, {{ Auth::user()->name }}! 👋</h2>
            <p class="welcome-subtitle">{{ now()->format('l, F j, Y') }} • {{ now()->format('g:i A') }}</p>
        </div>
        <div class="welcome-actions">
            <span class="welcome-date">{{ now()->format('M d, Y') }}</span>
        </div>
    </div>
</x-dashboard-card>

<!-- Compact Analytics Dashboard -->
<div class="mb-4">
    @include('components.compact-analytics')
</div>

<!-- Key Metrics Grid -->
<x-dashboard-grid columns="4" gap="lg" variant="default" class="metrics-grid">
    <!-- Total Players -->
    <x-dashboard-card variant="compact" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ \App\Models\Player::count() }}</div>
            <div class="stat-label">Total Players</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +12% vs last month
            </div>
        </div>
    </x-dashboard-card>

    <!-- Active Programs -->
    <x-dashboard-card variant="compact" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ \App\Models\Program::where('status', 'active')->count() }}</div>
            <div class="stat-label">Active Programs</div>
            <div class="stat-change neutral">
                {{ \App\Models\Program::count() }} total
            </div>
        </div>
    </x-dashboard-card>

    <!-- Pending Orders -->
    <x-dashboard-card variant="compact" spacing="md">
        <div class="stat-content">
            <div class="stat-value">{{ \App\Models\Order::where('order_status', 'pending')->count() }}</div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-change warning">
                <i class="fas fa-clock"></i> Review needed
            </div>
        </div>
    </x-dashboard-card>

    <!-- Revenue -->
    <x-dashboard-card variant="compact" spacing="md">
        <div class="stat-content">
            <div class="stat-value">KES {{ number_format(\App\Models\Payment::where('payment_status', 'completed')->sum('amount'), 0) }}</div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +8% vs last month
            </div>
        </div>
    </x-dashboard-card>
</x-dashboard-grid>

<!-- Permission-Based Quick Actions -->
<x-dashboard-card variant="default" spacing="md">
    <div class="card-header">
        <h3 class="card-title">⚡ Quick Actions</h3>
    </div>
    <div class="card-body">
        <x-dashboard-grid columns="6" gap="md" variant="minimal" class="quick-actions-grid">
            @can('create_players')
            <a href="{{ route('admin.players.create') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Add Player</div>
                    <div class="quick-action-desc">Register new player</div>
                </div>
            </a>
            @endcan

            @can('create_programs')
            <a href="{{ route('admin.programs.create') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Add Program</div>
                    <div class="quick-action-desc">Create training program</div>
                </div>
            </a>
            @endcan

            @can('manage_documents')
            <a href="{{ route('admin.documents.index') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Documents</div>
                    <div class="quick-action-desc">Manage documents</div>
                </div>
            </a>
            @endcan

            @can('create_blog')
            <a href="{{ route('admin.blog.create') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Add Blog</div>
                    <div class="quick-action-desc">Create news post</div>
                </div>
            </a>
            @endcan

            @can('manage_website_players')
            <a href="{{ route('admin.website-players.create') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Website Players</div>
                    <div class="quick-action-desc">Manage gallery</div>
                </div>
            </a>
            @endcan

            <a href="{{ route('admin.dashboard') }}" class="quick-action-btn">
                <div class="quick-action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="quick-action-content">
                    <div class="quick-action-title">Analytics</div>
                    <div class="quick-action-desc">View reports</div>
                </div>
            </a>
        </x-dashboard-grid>
    </div>
</x-dashboard-card>

<!-- Permission-Based Sections -->
@can('manage_tournaments')
<x-dashboard-card variant="default" spacing="md" class="tournament-section">
    <div class="card-header">
        <h3 class="card-title">🏆 Tournament Management</h3>
    </div>
    <div class="card-body">
        <x-dashboard-grid columns="4" gap="md" variant="minimal" class="tournament-actions-grid">
            @can('create_tournaments')
            <a href="{{ route('admin.tournaments.create') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Create Tournament</div>
                    <div class="action-desc">New competition</div>
                </div>
            </a>
            @endcan

            @can('view_tournaments')
            <a href="{{ route('admin.tournaments.index') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">All Tournaments</div>
                    <div class="action-desc">Manage events</div>
                </div>
            </a>
            @endcan

            @can('manage_tournament_teams')
            <a href="{{ route('admin.tournaments.index') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Manage Teams</div>
                    <div class="action-desc">Team registration</div>
                </div>
            </a>
            @endcan

            @can('manage_tournament_matches')
            <a href="{{ route('admin.tournaments.index') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Match Management</div>
                    <div class="action-desc">Schedule matches</div>
                </div>
            </a>
            @endcan
        </x-dashboard-grid>
    </div>
</x-dashboard-card>
@endcan

@can('manage_players')
<x-dashboard-card variant="default" spacing="md" class="player-section">
    <div class="card-header">
        <h3 class="card-title">🎓 Digital Player Ecosystem</h3>
    </div>
    <div class="card-body">
        <x-dashboard-grid columns="3" gap="md" variant="minimal" class="player-actions-grid">
            <a href="{{ route('admin.players.index') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">All Players</div>
                    <div class="action-desc">Player management</div>
                </div>
            </a>

            <a href="#" class="action-card" onclick="alert('Injury Tracking - Available in player profile'); return false;">
                <div class="action-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Injury Tracking</div>
                    <div class="action-desc">Medical records</div>
                </div>
            </a>

            <a href="#" class="action-card" onclick="alert('Player Availability - Configure in player profile'); return false;">
                <div class="action-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Availability</div>
                    <div class="action-desc">Status management</div>
                </div>
            </a>
        </x-dashboard-grid>
    </div>
</x-dashboard-card>
@endcan

@can('manage_governance')
<x-dashboard-card variant="default" spacing="md" class="governance-section">
    <div class="card-header">
        <h3 class="card-title">⚖️ Governance & Compliance</h3>
    </div>
    <div class="card-body">
        <x-dashboard-grid columns="3" gap="md" variant="minimal" class="governance-actions-grid">
            <a href="#" class="action-card" onclick="alert('Age Verification - Configure in tournament settings'); return false;">
                <div class="action-icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Age Verification</div>
                    <div class="action-desc">Compliance checks</div>
                </div>
            </a>

            <a href="#" class="action-card" onclick="alert('Disciplinary Cases - Configure in tournament settings'); return false;">
                <div class="action-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Disciplinary</div>
                    <div class="action-desc">Case management</div>
                </div>
            </a>

            <a href="#" class="action-card" onclick="alert('Appeals - Configure in tournament settings'); return false;">
                <div class="action-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Appeals</div>
                    <div class="action-desc">Review process</div>
                </div>
            </a>
        </x-dashboard-grid>
    </div>
</x-dashboard-card>
@endcan

<!-- System Status Grid -->
<x-dashboard-grid columns="3" gap="lg" variant="default" class="system-status-grid">
    <!-- FIFA Compliance -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <h3 class="card-title">⚽ FIFA Compliance</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="2" gap="md" variant="minimal">
                <div class="compliance-item">
                    <div class="compliance-icon">✅</div>
                    <div class="compliance-content">
                        <div class="compliance-value">{{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}</div>
                        <div class="compliance-label">FIFA Registered</div>
                    </div>
                </div>
                <div class="compliance-item">
                    <div class="compliance-icon">🛡️</div>
                    <div class="compliance-content">
                        <div class="compliance-value">{{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}</div>
                        <div class="compliance-label">Safeguarding</div>
                    </div>
                </div>
            </x-dashboard-grid>
        </div>
    </x-dashboard-card>

    <!-- Subscription Status -->
    <x-dashboard-card variant="default" spacing="md">
        <div class="card-header">
            <h3 class="card-title">💳 Subscription Status</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="2" gap="md" variant="minimal">
                <div class="subscription-item">
                    <div class="subscription-value text-success">{{ $subscriptionStats['active_subscriptions'] ?? 0 }}</div>
                    <div class="subscription-label">Active</div>
                </div>
                <div class="subscription-item">
                    <div class="subscription-value text-info">{{ $subscriptionStats['trialing_subscriptions'] ?? 0 }}</div>
                    <div class="subscription-label">Trials</div>
                </div>
                <div class="subscription-item">
                    <div class="subscription-value text-warning">{{ $subscriptionStats['past_due_subscriptions'] ?? 0 }}</div>
                    <div class="subscription-label">Past Due</div>
                </div>
                <div class="subscription-item">
                    <div class="subscription-value text-secondary">{{ $subscriptionStats['canceled_subscriptions'] ?? 0 }}</div>
                    <div class="subscription-label">Canceled</div>
                </div>
            </x-dashboard-grid>
            <div class="mt-2">
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
    </x-dashboard-card>

    <!-- System Health -->
    <x-dashboard-card variant="minimal" spacing="md" class="health-card">
        <div class="card-header">
            <h3 class="card-title">💚 System Health</h3>
        </div>
        <div class="card-body">
            <x-dashboard-grid columns="4" gap="md" variant="minimal">
                <div class="health-item">
                    <div class="health-status">🟢</div>
                    <div class="health-label">Database</div>
                </div>
                <div class="health-item">
                    <div class="health-status">🟢</div>
                    <div class="health-label">Storage</div>
                </div>
                <div class="health-item">
                    <div class="health-status">🟢</div>
                    <div class="health-label">Performance</div>
                </div>
                <div class="health-item">
                    <div class="health-status">🟢</div>
                    <div class="health-label">API</div>
                </div>
            </x-dashboard-grid>
            <div class="mt-2 text-center">
                <small class="text-muted">MRR: KES {{ number_format($subscriptionStats['mrr'] ?? 0, 2) }}</small>
            </div>
        </div>
    </x-dashboard-card>
</x-dashboard-grid>
@endsection

@push('styles')
<style>
/* Admin Dashboard Specific Styles */
.welcome-card {
    background: linear-gradient(135deg, #ea1c4d, #c0173f);
    color: white;
    border: none;
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.welcome-title {
    font-size: 1.5rem;
    margin: 0 0 0.5rem 0;
    color: white;
}

.welcome-subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.welcome-actions {
    text-align: right;
}

.welcome-date {
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Metrics Grid */
.metrics-grid {
    margin-bottom: var(--spacing-lg);
}

/* Quick Actions */
.quick-actions-grid {
    gap: var(--spacing-md);
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--card-padding-sm);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: var(--transition);
    background: var(--white);
    color: var(--gray-900);
}

.quick-action-btn:hover {
    border-color: var(--primary);
    background: #fff5f0;
    transform: translateY(-1px);
}

.quick-action-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.quick-action-content {
    flex: 1;
}

.quick-action-title {
    font-size: var(--font-size-sm);
    font-weight: 600;
    margin: 0 0 2px 0;
}

.quick-action-desc {
    font-size: var(--font-size-xs);
    color: var(--gray-600);
    margin: 0;
}

/* Action Cards */
.action-card {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--card-padding-sm);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: var(--transition);
    background: var(--white);
    color: var(--gray-900);
}

.action-card:hover {
    border-color: var(--primary);
    background: #fff5f0;
    transform: translateY(-1px);
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: var(--font-size-sm);
    font-weight: 600;
    margin: 0 0 2px 0;
}

.action-desc {
    font-size: var(--font-size-xs);
    color: var(--gray-600);
    margin: 0;
}

/* Compliance Items */
.compliance-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    background: var(--white);
}

.compliance-icon {
    font-size: 1.5rem;
}

.compliance-value {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--gray-900);
}

.compliance-label {
    font-size: var(--font-size-xs);
    color: var(--gray-600);
}

/* Subscription Items */
.subscription-item {
    text-align: center;
    padding: var(--spacing-sm);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    background: var(--white);
}

.subscription-value {
    font-size: var(--font-size-lg);
    font-weight: 700;
    margin-bottom: var(--spacing-xs);
}

.subscription-label {
    font-size: var(--font-size-xs);
    color: var(--gray-600);
}

/* Health Items */
.health-card {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.health-item {
    text-align: center;
    padding: var(--spacing-sm);
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius);
    backdrop-filter: blur(10px);
}

.health-status {
    font-size: 1.5rem;
    margin-bottom: var(--spacing-xs);
}

.health-label {
    font-size: var(--font-size-xs);
    opacity: 0.9;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .welcome-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .system-status-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .metrics-grid {
        grid-template-columns: 1fr;
    }

    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .tournament-actions-grid,
    .player-actions-grid,
    .governance-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .compliance-item,
    .subscription-item {
        text-align: left;
    }
}

@media (max-width: 576px) {
    .quick-actions-grid,
    .tournament-actions-grid,
    .player-actions-grid,
    .governance-actions-grid {
        grid-template-columns: 1fr;
    }

    .health-item {
        text-align: left;
    }
}
</style>
@endpush

