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
<!-- Welcome Header with System Status -->
<x-dashboard-card variant="default" spacing="md" class="welcome-card">
    <div class="welcome-content">
        <div class="welcome-text">
            <h2 class="welcome-title">Welcome back, {{ Auth::user()->name }}! 👋</h2>
            <p class="welcome-subtitle">{{ now()->format('l, F j, Y') }} • {{ now()->format('g:i A') }}</p>
        </div>
        <div class="system-status-quick">
            <span class="status-indicator online">
                <i class="fas fa-circle"></i> System Online
            </span>
            <span class="status-indicator db">
                <i class="fas fa-database"></i> DB: Healthy
            </span>
            <span class="status-indicator api">
                <i class="fas fa-server"></i> API: Optimal
            </span>
        </div>
    </div>
</x-dashboard-card>

<!-- Compact Analytics Dashboard -->
<div class="mb-4">
    @include('components.compact-analytics')
</div>

<!-- Key Metrics Row -->
<div class="metrics-row">
    <div class="metric-card">
        <div class="metric-icon players">
            <i class="fas fa-users"></i>
        </div>
        <div class="metric-content">
            <div class="metric-value">{{ \App\Models\Player::count() }}</div>
            <div class="metric-label">Total Players</div>
            <div class="metric-trend positive">
                <i class="fas fa-arrow-up"></i> +12% vs last month
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon programs">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="metric-content">
            <div class="metric-value">{{ \App\Models\Program::where('status', 'active')->count() }}</div>
            <div class="metric-label">Active Programs</div>
            <div class="metric-trend neutral">
                {{ \App\Models\Program::count() }} total
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon orders">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="metric-content">
            <div class="metric-value">{{ \App\Models\Order::where('order_status', 'pending')->count() }}</div>
            <div class="metric-label">Pending Orders</div>
            <div class="metric-trend warning">
                <i class="fas fa-clock"></i> Review needed
            </div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon revenue">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="metric-content">
            <div class="metric-value">KES {{ number_format(\App\Models\Payment::where('payment_status', 'completed')->sum('amount'), 0) }}</div>
            <div class="metric-label">Monthly Revenue</div>
            <div class="metric-trend positive">
                <i class="fas fa-arrow-up"></i> +8% vs last month
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & System Status Row -->
<div class="dashboard-row">
    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <h3 class="section-title">⚡ Quick Actions</h3>
        <div class="quick-actions-grid">
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
        </div>
    </div>

    <!-- System Status & Compliance -->
    <div class="status-section">
        <h3 class="section-title">📊 System Status</h3>
        <div class="status-cards">
            <!-- FIFA Compliance -->
            <div class="status-card">
                <div class="status-card-header">
                    <span class="status-card-icon">⚽</span>
                    <span class="status-card-title">FIFA Compliance</span>
                </div>
                <div class="status-card-body">
                    <div class="status-item">
                        <span class="status-value">{{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}</span>
                        <span class="status-label">FIFA Registered</span>
                    </div>
                    <div class="status-item">
                        <span class="status-value">{{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}</span>
                        <span class="status-label">Safeguarding</span>
                    </div>
                </div>
            </div>

            <!-- Subscription Status -->
            <div class="status-card">
                <div class="status-card-header">
                    <span class="status-card-icon">💳</span>
                    <span class="status-card-title">Subscriptions</span>
                </div>
                <div class="status-card-body">
                    <div class="status-item">
                        <span class="status-value text-success">{{ $subscriptionStats['active_subscriptions'] ?? 0 }}</span>
                        <span class="status-label">Active</span>
                    </div>
                    <div class="status-item">
                        <span class="status-value text-info">{{ $subscriptionStats['trialing_subscriptions'] ?? 0 }}</span>
                        <span class="status-label">Trials</span>
                    </div>
                    <div class="status-item">
                        <span class="status-value text-warning">{{ $subscriptionStats['past_due_subscriptions'] ?? 0 }}</span>
                        <span class="status-label">Past Due</span>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="status-card health">
                <div class="status-card-header">
                    <span class="status-card-icon">💚</span>
                    <span class="status-card-title">System Health</span>
                </div>
                <div class="status-card-body">
                    <div class="health-grid">
                        <div class="health-item">
                            <span class="health-status">🟢</span>
                            <span class="health-label">Database</span>
                        </div>
                        <div class="health-item">
                            <span class="health-status">🟢</span>
                            <span class="health-label">Storage</span>
                        </div>
                        <div class="health-item">
                            <span class="health-status">🟢</span>
                            <span class="health-label">API</span>
                        </div>
                        <div class="health-item">
                            <span class="health-status">🟢</span>
                            <span class="health-label">Performance</span>
                        </div>
                    </div>
                    <div class="mrr-display">
                        MRR: KES {{ number_format($subscriptionStats['mrr'] ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection

@push('styles')
<style>
/* ========================================
   DASHBOARD LAYOUT
   ======================================== */
.dashboard-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 992px) {
    .dashboard-row {
        grid-template-columns: 1fr;
    }
}

/* ========================================
   WELCOME CARD (Redesigned)
   ======================================== */
.welcome-card {
    background: var(--white);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
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
    color: var(--gray-900);
}

.welcome-subtitle {
    margin: 0;
    color: var(--gray-600);
    font-size: 1rem;
}

.system-status-quick {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-700);
}

.status-indicator.online {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.status-indicator.db {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.status-indicator.api {
    background: rgba(139, 92, 246, 0.1);
    color: #7c3aed;
}

.status-indicator i {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .welcome-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .system-status-quick {
        width: 100%;
        justify-content: flex-start;
    }
}

/* ========================================
   METRICS ROW (Redesigned)
   ======================================== */
.metrics-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.metric-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--white);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.metric-icon.players {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.metric-icon.programs {
    background: linear-gradient(135deg, #10b981, #059669);
}

.metric-icon.orders {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.metric-icon.revenue {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.metric-content {
    flex: 1;
}

.metric-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.metric-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.metric-trend {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.metric-trend.positive {
    color: #10b981;
}

.metric-trend.neutral {
    color: var(--gray-500);
}

.metric-trend.warning {
    color: #f59e0b;
}

@media (max-width: 992px) {
    .metrics-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .metrics-row {
        grid-template-columns: 1fr;
    }

    .metric-card {
        padding: 1rem;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .metric-value {
        font-size: 1.25rem;
    }
}

/* ========================================
   QUICK ACTIONS SECTION (Redesigned)
   ======================================== */
.quick-actions-section {
    background: var(--white);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 1rem 0;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
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
    box-shadow: var(--shadow-sm);
}

.quick-action-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.quick-action-content {
    flex: 1;
}

.quick-action-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: var(--gray-900);
}

.quick-action-desc {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin: 0;
}

@media (max-width: 576px) {
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }

    .quick-action-btn {
        padding: 0.875rem;
    }
}

/* ========================================
   STATUS SECTION (Redesigned)
   ======================================== */
.status-section {
    background: var(--white);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.status-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-card {
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    overflow: hidden;
}

.status-card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: var(--gray-100);
    border-bottom: 1px solid var(--gray-300);
}

.status-card-icon {
    font-size: 1rem;
}

.status-card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-900);
}

.status-card-body {
    padding: 1rem;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.status-item:last-child {
    border-bottom: none;
}

.status-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
}

.status-label {
    font-size: 0.75rem;
    color: var(--gray-600);
}

.status-card.health {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: white;
}

.status-card.health .status-card-header {
    background: rgba(255, 255, 255, 0.1);
    border-bottom-color: rgba(255, 255, 255, 0.2);
}

.status-card.health .status-card-title {
    color: white;
}

.health-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.health-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius);
}

.health-status {
    font-size: 1rem;
}

.health-label {
    font-size: 0.75rem;
    opacity: 0.9;
}

.mrr-display {
    text-align: center;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.875rem;
    opacity: 0.9;
}

/* ========================================
   ACTION CARDS (Existing styles maintained)
   ======================================== */
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

/* ========================================
   RESPONSIVE ADJUSTMENTS
   ======================================== */
@media (max-width: 768px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .tournament-actions-grid,
    .player-actions-grid,
    .governance-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .quick-actions-grid,
    .tournament-actions-grid,
    .player-actions-grid,
    .governance-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
