@extends('layouts.staff')

@section('title', __('Dashboard - ') . $organizationName)

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
<div class="dashboard-container">
    {{-- Welcome Header --}}
    <div class="welcome-section">
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="welcome-text">
            <h1>{{ __('Welcome back') }}, {{ Auth::user()->name }}! 👋</h1>
            <p>{{ __("Here's what's happening with") }} {{ $organizationName }} {{ __('today') }}</p>
        </div>
        <div class="current-date">
            <span>{{ now()->format('M d, Y') }}</span>
            <span>{{ now()->format('l') }}</span>
        </div>
    </div>

    {{-- Key Metrics Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon players">
                <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">{{ __('Total Players') }}</span>
                <span class="stat-value">{{ $totalPlayers }}</span>
                <span class="stat-change {{ $playerGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas {{ $playerGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}" aria-hidden="true"></i>
                    {{ abs($playerGrowth) }}% {{ __('vs last month') }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-user-check" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">{{ __('Active Players') }}</span>
                <span class="stat-value">{{ $activePlayers }}</span>
                <span class="stat-change neutral">
                    {{ $pendingPlayers }} {{ __('pending') }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon programs">
                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">{{ __('Active Programs') }}</span>
                <span class="stat-value">{{ $activePrograms }}</span>
                <span class="stat-change neutral">
                    {{ $totalPrograms }} {{ __('total') }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">{{ __('Monthly Revenue') }}</span>
                <span class="stat-value">${{ number_format($monthlyRevenue, 0) }}</span>
                <span class="stat-change neutral">
                    ${{ number_format($totalRevenue, 0) }} {{ __('total') }}
                </span>
            </div>
        </div>
    </div>

    {{-- AI Insights --}}
    @if(!empty($aiInsights))
    <div class="insights-section">
        <div class="section-header">
            <h2><i class="fas fa-robot me-2" aria-hidden="true"></i>{{ __('AI Insights') }}</h2>
        </div>
        <div class="insights-grid">
            @foreach($aiInsights as $insight)
            <div class="insight-card insight-{{ $insight['type'] }}">
                <div class="insight-icon">
                    <i class="{{ $insight['icon'] }}" aria-hidden="true"></i>
                </div>
                <div class="insight-content">
                    <h4>{{ __($insight['title']) }}</h4>
                    <p>{{ __($insight['message']) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="dashboard-grid">
        {{-- Recent Players --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-users me-2" aria-hidden="true"></i>{{ __('Recent Players') }}</h3>
                <a href="{{ route('admin.players.index') }}" class="card-link">{{ __('View All') }}</a>
            </div>
            <div class="card-body">
                @forelse($recentPlayers as $player)
                <div class="player-item">
                    <div class="player-avatar">
                        {{ strtoupper(substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1)) }}
                    </div>
                    <div class="player-info">
                        <h4>{{ $player->first_name }} {{ $player->last_name }}</h4>
                        <p>{{ $player->position ?? __('No position') }} • {{ $player->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge badge-{{ $player->registration_status === 'Active' ? 'success' : 'warning' }}">
                        {{ __($player->registration_status) }}
                    </span>
                </div>
                @empty
                <p class="no-data">{{ __('No players yet') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Enrollments --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-user-plus me-2" aria-hidden="true"></i>{{ __('Recent Enrollments') }}</h3>
                <a href="{{ route('admin.enrollment.index') }}" class="card-link">{{ __('View All') }}</a>
            </div>
            <div class="card-body">
                @forelse($recentEnrollments as $enrollment)
                <div class="enrollment-item">
                    <div class="enrollment-icon">
                        <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                    </div>
                    <div class="enrollment-info">
                        <h4>{{ $enrollment->player->first_name ?? __('Unknown') }} {{ $enrollment->player->last_name ?? '' }}</h4>
                        <p>{{ $enrollment->program->name ?? __('Unknown Program') }} • {{ $enrollment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="no-data">{{ __('No enrollments yet') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie me-2" aria-hidden="true"></i>{{ __('Quick Stats') }}</h3>
            </div>
            <div class="card-body">
                <div class="quick-stat">
                    <div class="quick-stat-icon">
                        <i class="fas fa-user-friends" aria-hidden="true"></i>
                    </div>
                    <div class="quick-stat-info">
                        <span class="quick-stat-value">{{ $totalStaff }}</span>
                        <span class="quick-stat-label">{{ __('Staff Members') }}</span>
                    </div>
                </div>

                <div class="quick-stat">
                    <div class="quick-stat-icon">
                        <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                    </div>
                    <div class="quick-stat-info">
                        <span class="quick-stat-value">{{ $totalEnrollments }}</span>
                        <span class="quick-stat-label">{{ __('Enrollments') }}</span>
                    </div>
                </div>

                <div class="quick-stat">
                    <div class="quick-stat-icon">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    </div>
                    <div class="quick-stat-info">
                        <span class="quick-stat-value">{{ $enrollmentsThisWeek }}</span>
                        <span class="quick-stat-label">{{ __('This Week') }}</span>
                    </div>
                </div>

                <div class="quick-stat">
                    <div class="quick-stat-icon warning">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                    </div>
                    <div class="quick-stat-info">
                        <span class="quick-stat-value">${{ number_format($pendingPayments, 0) }}</span>
                        <span class="quick-stat-label">{{ __('Pending Payments') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Today --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-day me-2" aria-hidden="true"></i>{{ __('Attendance Today') }}</h3>
            </div>
            <div class="card-body attendance-card">
                <div class="attendance-circle">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg"
                            d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                        <path class="circle"
                            stroke-dasharray="{{ $averageAttendance }}, 100"
                            d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                    </svg>
                    <div class="attendance-value">
                        <span class="percentage">{{ $averageAttendance }}%</span>
                        <span class="label">{{ __('Present') }}</span>
                    </div>
                </div>
                <div class="attendance-stats">
                    <div class="attendance-stat">
                        <span class="stat-number">{{ $presentToday }}</span>
                        <span class="stat-label">{{ __('Present') }}</span>
                    </div>
                    <div class="attendance-stat">
                        <span class="stat-number">{{ $totalAttendance }}</span>
                        <span class="stat-label">{{ __('Total Records') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="quick-actions">
        <h2><i class="fas fa-bolt me-2" aria-hidden="true"></i>{{ __('Quick Actions') }}</h2>
        <div class="actions-grid">
            <a href="{{ route('admin.players.create') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('Add Player') }}</h4>
                    <p>{{ __('Register new player') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.programs.create') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('Create Program') }}</h4>
                    <p>{{ __('New training program') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.staff.create') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('Add Staff') }}</h4>
                    <p>{{ __('Invite team member') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.enrollment.create') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('New Enrollment') }}</h4>
                    <p>{{ __('Enroll player in program') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.attendance.index') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('Take Attendance') }}</h4>
                    <p>{{ __('Record session attendance') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.settings.company') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                </div>
                <div class="action-content">
                    <h4>{{ __('Settings') }}</h4>
                    <p>{{ __('Organization settings') }}</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ========================================
       DASHBOARD STYLES
       ======================================== */

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    box-shadow: var(--shadow-lg);
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 600;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.welcome-text h1 {
    font-size: 28px;
    margin-bottom: 0.5rem;
    color: var(--white);
}

.welcome-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.current-date {
    text-align: right;
    font-size: 14px;
    opacity: 0.9;
}

.current-date span:first-child {
    display: block;
    font-weight: 600;
    font-size: 16px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--white);
}

.stat-icon.players {
    background: linear-gradient(135deg, var(--primary), #ff6b8a);
}

.stat-icon.active {
    background: linear-gradient(135deg, var(--secondary), #8dd97e);
}

.stat-icon.programs {
    background: linear-gradient(135deg, #6366f1, #818cf8);
}

.stat-icon.revenue {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

.stat-content {
    flex: 1;
}

.stat-label {
    display: block;
    font-size: 13px;
    color: var(--gray-600);
    margin-bottom: 4px;
}

.stat-value {
    display: block;
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
}

.stat-change {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    margin-top: 4px;
}

.stat-change.positive {
    color: var(--secondary);
}

.stat-change.negative {
    color: var(--danger);
}

.stat-change.neutral {
    color: var(--gray-600);
}

/* Insights Section */
.insights-section {
    margin-bottom: 2rem;
}

.insights-section .section-header h2 {
    font-size: 20px;
    color: var(--gray-900);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.insight-card {
    background: var(--white);
    border-radius: 10px;
    padding: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
}

.insight-card.success {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-color: #bbf7d0;
}

.insight-card.warning {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-color: #fde68a;
}

.insight-card.info {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-color: #bfdbfe;
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: var(--white);
    box-shadow: var(--shadow-sm);
}

.insight-card.success .insight-icon {
    color: var(--secondary);
}

.insight-card.warning .insight-icon {
    color: #d97706;
}

.insight-card.info .insight-icon {
    color: #3b82f6;
}

.insight-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 4px;
}

.insight-content p {
    font-size: 13px;
    color: var(--gray-600);
    margin: 0;
    line-height: 1.4;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 992px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

.dashboard-card {
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-300);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
}

.card-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
    display: flex;
    align-items: center;
}

.card-link {
    font-size: 13px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.card-link:hover {
    text-decoration: underline;
}

.card-body {
    padding: 1rem;
}

/* Player Item */
.player-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    transition: var(--transition);
}

.player-item:hover {
    background: #f8f9fa;
}

.player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 14px;
    font-weight: 600;
    margin-right: 12px;
}

.player-info {
    flex: 1;
}

.player-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 2px 0;
}

.player-info p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.badge-success {
    background: #dcfce7;
    color: #16a34a;
}

.badge-warning {
    background: #fef3c7;
    color: #d97706;
}

/* Enrollment Item */
.enrollment-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    transition: var(--transition);
}

.enrollment-item:hover {
    background: #f8f9fa;
}

.enrollment-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #e0e7ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    margin-right: 12px;
}

.enrollment-info {
    flex: 1;
}

.enrollment-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 2px 0;
}

.enrollment-info p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

/* Quick Stats */
.quick-stat {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.quick-stat:last-child {
    margin-bottom: 0;
}

.quick-stat:hover {
    background: #f8f9fa;
}

.quick-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    margin-right: 12px;
}

.quick-stat-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

.quick-stat-info {
    flex: 1;
}

.quick-stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-900);
}

.quick-stat-label {
    font-size: 12px;
    color: var(--gray-600);
}

/* Attendance Card */
.attendance-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
}

.attendance-circle {
    position: relative;
    width: 140px;
    height: 140px;
    margin-bottom: 1rem;
}

.circular-chart {
    display: block;
    max-width: 100%;
}

.circle-bg {
    fill: none;
    stroke: #eee;
    stroke-width: 2.5;
}

.circle {
    fill: none;
    stroke-width: 2.5;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
    stroke: var(--secondary);
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.attendance-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.attendance-value .percentage {
    display: block;
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
}

.attendance-value .label {
    font-size: 12px;
    color: var(--gray-600);
}

.attendance-stats {
    display: flex;
    gap: 2rem;
}

.attendance-stat {
    text-align: center;
}

.attendance-stat .stat-number {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-900);
}

.attendance-stat .stat-label {
    font-size: 12px;
    color: var(--gray-600);
}

/* No Data */
.no-data {
    text-align: center;
    color: var(--gray-600);
    font-style: italic;
    padding: 2rem;
}

/* Quick Actions */
.quick-actions {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
}

.quick-actions h2 {
    font-size: 20px;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid var(--gray-300);
}

.action-card:hover {
    background: var(--white);
    box-shadow: var(--shadow);
    transform: translateY(-2px);
    border-color: var(--primary);
}

.action-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 18px;
    margin-right: 12px;
}

.action-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 4px 0;
}

.action-content p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-section {
        flex-direction: column;
        text-align: center;
    }

    .current-date {
        text-align: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 1rem;
    }

    .stat-value {
        font-size: 24px;
    }
}
</style>
@endpush
