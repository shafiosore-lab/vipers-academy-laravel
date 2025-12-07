@extends('player.portal.layout')

@section('portal-content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-banner" data-aos="fade-down">
        <div class="welcome-content">
            <div class="greeting-section">
                <div class="greeting-icon">
                    <i class="fas fa-sun"></i>
                </div>
                <div class="greeting-text">
                    <h1>Good {{ date('A') === 'AM' ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                    <p>Ready to achieve greatness today?</p>
                </div>
            </div>

            @if($player && !$player->isApproved())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Your profile is pending approval. You'll have full access once approved by our team.
            </div>
            @endif
        </div>

        <div class="player-quick-card">
            <div class="player-avatar">
                @if($player && $player->photo)
                    <img src="{{ asset('storage/' . $player->photo) }}" alt="Player Photo">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <a href="{{ route('player.portal.profile') }}" class="edit-avatar" title="Edit Profile">
                    <i class="fas fa-camera"></i>
                </a>
            </div>
            <div class="player-info">
                <h3>{{ $player ? $player->name : auth()->user()->name }}</h3>
                @if($player)
                <div class="player-meta">
                    <span class="badge badge-position"><i class="fas fa-futbol"></i> {{ $player->position }}</span>
                    <span class="badge badge-level">{{ $player->current_level ?: 'Beginner' }}</span>
                </div>
                <div class="player-stats-mini">
                    <div class="stat-mini">
                        <span class="label">Age Group</span>
                        <span class="value">{{ $player->age_group }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="label">Joined</span>
                        <span class="value">{{ $player->academy_join_date ? \Carbon\Carbon::parse($player->academy_join_date)->format('M Y') : 'Recent' }}</span>
                    </div>
                </div>
                @endif
                <a href="{{ route('player.portal.profile') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="fas fa-edit me-1"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Performance Overview -->
        <div class="dashboard-section performance-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h2><i class="fas fa-chart-line"></i> Performance Overview</h2>
                    <p>{{ date('F Y') }} Summary</p>
                </div>
                <select class="form-select form-select-sm" style="width: auto;">
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>

            <div class="stats-row">
                <div class="stat-box" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-icon training">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">{{ $quickStats['training_sessions'] }}</div>
                        <div class="stat-label">Training Sessions</div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +12% vs last month
                        </div>
                    </div>
                </div>

                <div class="stat-box" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-icon goals">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">{{ $quickStats['goals_scored'] }}</div>
                        <div class="stat-label">Goals Scored</div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +5% improvement
                        </div>
                    </div>
                </div>

                <div class="stat-box" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-icon time">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">{{ number_format($quickStats['minutes_played'] / 60, 1) }}h</div>
                        <div class="stat-label">Minutes Played</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-minus"></i> Consistent
                        </div>
                    </div>
                </div>

                <div class="stat-box" data-aos="zoom-in" data-aos-delay="400">
                    <div class="stat-icon programs">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">{{ $quickStats['programs_enrolled'] }}</div>
                        <div class="stat-label">Active Programs</div>
                        <div class="stat-trend up">
                            <i class="fas fa-check"></i> Enrolled
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Progress -->
            <div class="attendance-card" data-aos="fade-up" data-aos-delay="500">
                <div class="attendance-header">
                    <div>
                        <h4><i class="fas fa-fire text-warning"></i> Attendance Streak</h4>
                        <p>Keep up the great work!</p>
                    </div>
                    <div class="streak-badge">
                        <span class="streak-number">12</span>
                        <span class="streak-label">days</span>
                    </div>
                </div>
                <div class="progress-bar-wrapper">
                    <div class="progress-info">
                        <span>Monthly Attendance</span>
                        <span class="percentage">92%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 92%"></div>
                    </div>
                </div>
            </div>

            <!-- Advanced Analytics Access -->
            @php
                $sampleWebsitePlayer = \App\Models\WebsitePlayer::first();
            @endphp
            @if($sampleWebsitePlayer)
            <div class="analytics-card" data-aos="fade-up" data-aos-delay="600">
                <div class="analytics-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="analytics-content">
                    <h4>Advanced Analytics</h4>
                    <p>Access detailed performance insights and AI-powered analysis</p>
                </div>
                <div class="analytics-buttons">
                    <a href="{{ route('players.overview', $sampleWebsitePlayer->id) }}" class="btn btn-primary btn-sm" target="_blank">
                        <i class="fas fa-chart-bar"></i> Statistics
                    </a>
                    <a href="{{ route('players.ai-insights', $sampleWebsitePlayer->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fas fa-robot"></i> AI Insights
                    </a>
                    <a href="{{ route('players.career', $sampleWebsitePlayer->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="fas fa-trophy"></i> Career
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Items & Schedule -->
        <div class="dashboard-section sidebar-section">
            <!-- Action Items -->
            <div class="action-items-card" data-aos="fade-left">
                <div class="section-header">
                    <h3><i class="fas fa-tasks"></i> Action Items</h3>
                    <span class="badge bg-warning text-dark">2 Urgent</span>
                </div>

                <div class="action-list">
                    <div class="action-item urgent">
                        <div class="action-indicator"></div>
                        <div class="action-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="action-content">
                            <h5>Medical Form Due</h5>
                            <p>Annual medical clearance expires soon</p>
                            <span class="deadline">Due: Tomorrow</span>
                        </div>
                        <button class="btn btn-warning btn-sm">Complete</button>
                    </div>

                    <div class="action-item important">
                        <div class="action-indicator"></div>
                        <div class="action-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="action-content">
                            <h5>Guardian Consent</h5>
                            <p>Parent approval needed for tournament</p>
                            <span class="deadline">Due: 3 days</span>
                        </div>
                        <button class="btn btn-outline-info btn-sm">Sign</button>
                    </div>

                    <div class="action-item normal">
                        <div class="action-indicator"></div>
                        <div class="action-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <div class="action-content">
                            <h5>Practice Drill</h5>
                            <p>Master passing technique</p>
                            <span class="deadline">Next session</span>
                        </div>
                        <a href="{{ route('player.portal.resources') }}" class="btn btn-outline-success btn-sm">Practice</a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Schedule -->
            <div class="schedule-card" data-aos="fade-left" data-aos-delay="100">
                <div class="section-header">
                    <h3><i class="fas fa-calendar-alt"></i> Upcoming Schedule</h3>
                    <a href="{{ route('player.portal.schedule') }}" class="view-all">View All</a>
                </div>

                <div class="schedule-list">
                    <div class="schedule-item">
                        <div class="schedule-date">
                            <div class="date-box">
                                <span class="day">{{ date('d') + 2 }}</span>
                                <span class="month">{{ date('M') }}</span>
                            </div>
                        </div>
                        <div class="schedule-details">
                            <h5>Training Session</h5>
                            <p><i class="fas fa-clock"></i> 4:00 PM - 6:00 PM</p>
                            <p><i class="fas fa-map-marker-alt"></i> Mumias Sports Centre</p>
                            <span class="badge badge-training">Skills Training</span>
                        </div>
                        <div class="schedule-status">
                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <div class="schedule-item">
                        <div class="schedule-date">
                            <div class="date-box">
                                <span class="day">{{ date('d') + 5 }}</span>
                                <span class="month">{{ date('M') }}</span>
                            </div>
                        </div>
                        <div class="schedule-details">
                            <h5>Team Match</h5>
                            <p><i class="fas fa-clock"></i> 10:00 AM - 12:00 PM</p>
                            <p><i class="fas fa-map-marker-alt"></i> Bukhungu Stadium</p>
                            <span class="badge badge-match">Friendly Match</span>
                        </div>
                        <div class="schedule-status">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="schedule-item">
                        <div class="schedule-date">
                            <div class="date-box">
                                <span class="day">{{ date('d') + 7 }}</span>
                                <span class="month">{{ date('M') }}</span>
                            </div>
                        </div>
                        <div class="schedule-details">
                            <h5>Medical Checkup</h5>
                            <p><i class="fas fa-clock"></i> 2:00 PM - 3:00 PM</p>
                            <p><i class="fas fa-map-marker-alt"></i> Academy Clinic</p>
                            <span class="badge badge-health">Health Assessment</span>
                        </div>
                        <div class="schedule-status">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-clock"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <a href="{{ route('player.portal.schedule') }}" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="fas fa-calendar-alt me-2"></i>View Full Calendar
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="orders-card" data-aos="fade-left" data-aos-delay="200">
                <div class="section-header">
                    <h3><i class="fas fa-shopping-bag"></i> Recent Orders</h3>
                    <a href="{{ route('player.portal.orders') }}" class="view-all">View All</a>
                </div>

                <div class="orders-list">
                    @forelse($recentOrders as $order)
                    <div class="order-item">
                        <div class="order-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="order-details">
                            <h5>Order #{{ $order->order_number }}</h5>
                            <p>{{ $order->created_at->format('M d, Y') }} • ${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <span class="badge
                            @if($order->order_status === 'delivered') bg-success
                            @elseif($order->order_status === 'shipped') bg-info
                            @elseif($order->order_status === 'processing') bg-warning text-dark
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>No Recent Orders</h5>
                        <p>Your order history will appear here</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm" target="_blank">Start Shopping</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Activity Feed & Announcements -->
        <div class="dashboard-section full-width-section">
            <!-- Recent Activity -->
            <div class="activity-card" data-aos="fade-up">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Recent Activity</h3>
                    <a href="#" class="view-all">View All</a>
                </div>

                <div class="activity-timeline">
                    @forelse($recentActivity->take(4) as $activity)
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-icon training">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="activity-content">
                            <h5>Training Session Completed</h5>
                            <p>{{ $activity->created_at->diffForHumans() }} • Skills Training</p>
                            @if($activity->rating)
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $activity->rating ? 'filled' : '' }}"></i>
                                @endfor
                                <span>Performance: {{ $activity->rating }}/5</span>
                            </div>
                            @endif
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @empty
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-icon achievement">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="activity-content">
                            <h5>Skill Badge Unlocked: First Touch</h5>
                            <p>2 days ago • Achievement</p>
                        </div>
                        <span class="badge bg-warning">New Badge</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-icon match">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="activity-content">
                            <h5>Team Meeting: Strategy Discussion</h5>
                            <p>3 days ago • Team Event</p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @endforelse
                </div>

                <button class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="fas fa-plus me-1"></i>Load More Activities
                </button>
            </div>

            <!-- Announcements -->
            <div class="announcements-card" data-aos="fade-up" data-aos-delay="100">
                <div class="section-header">
                    <h3><i class="fas fa-bullhorn"></i> Announcements</h3>
                </div>

                <div class="announcements-list">
                    <div class="announcement-item">
                        <div class="announcement-icon tournament">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="announcement-content">
                            <h5>New Tournament Registration</h5>
                            <p>Youth Football Cup 2025 registration now open</p>
                            <span class="date">{{ date('M d') }}</span>
                        </div>
                        <button class="btn btn-primary btn-sm">Register</button>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-icon award">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="announcement-content">
                            <h5>Player of the Month</h5>
                            <p>February awards ceremony postponed</p>
                            <span class="date">{{ date('M d', strtotime('-1 day')) }}</span>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm">Learn More</button>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-icon policy">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="announcement-content">
                            <h5>Updated Safety Protocols</h5>
                            <p>New safety protocols effective immediately</p>
                            <span class="date">{{ date('M d', strtotime('-2 days')) }}</span>
                        </div>
                        <button class="btn btn-outline-info btn-sm">Read Policy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ea1c4d;
    --primary-light: #fef2f4;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --info: #3b82f6;
    --info-light: #dbeafe;
    --danger: #ef4444;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;
    --bg-primary: #ffffff;
    --bg-secondary: #f9fafb;
    --border: #e5e7eb;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.dashboard-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Welcome Banner */
.welcome-banner {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    margin-bottom: 32px;
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    border-radius: 16px;
    padding: 32px;
    color: white;
}

.greeting-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 16px;
}

.greeting-icon {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.greeting-text h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.greeting-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.player-quick-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    min-width: 280px;
    text-align: center;
    box-shadow: var(--shadow-lg);
}

.player-avatar {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 16px;
}

.player-avatar img,
.avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background: linear-gradient(135deg, var(--primary), #c0173f);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
}

.edit-avatar {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 32px;
    height: 32px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    border: 3px solid white;
    transition: all 0.3s;
}

.edit-avatar:hover {
    transform: scale(1.1);
}

.player-info h3 {
    margin: 0 0 12px 0;
    font-size: 18px;
    color: var(--text-primary);
}

.player-meta {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.player-meta .badge {
    font-size: 11px;
    padding: 4px 10px;
}

.badge-position {
    background: var(--warning-light);
    color: var(--warning);
}

.badge-level {
    background: var(--info-light);
    color: var(--info);
}

.player-stats-mini {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.stat-mini {
    text-align: center;
}

.stat-mini .label {
    display: block;
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 4px;
}

.stat-mini .value {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.dashboard-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.full-width-section {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    padding: 0;
    background: transparent;
    box-shadow: none;
}

.full-width-section > div {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-header h2,
.section-header h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
}

.section-header h2 i,
.section-header h3 i {
    color: var(--primary);
}

.section-header p {
    margin: 4px 0 0 0;
    font-size: 14px;
    color: var(--text-secondary);
}

.view-all {
    font-size: 14px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.view-all:hover {
    text-decoration: underline;
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--bg-secondary);
    border-radius: 12px;
    border: 2px solid var(--border);
    transition: all 0.3s;
}

.stat-box:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-icon.training {
    background: var(--success-light);
    color: var(--success);
}

.stat-icon.goals {
    background: var(--warning-light);
    color: var(--warning);
}

.stat-icon.time {
    background: var(--info-light);
    color: var(--info);
}

.stat-icon.programs {
    background: var(--primary-light);
    color: var(--primary);
}

.stat-data {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--text-secondary);
    font-weight: 600;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
}

.stat-trend.up {
    color: var(--success);
}

.stat-trend.neutral {
    color: var(--text-muted);
}

/* Attendance Card */
.attendance-card {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.attendance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.attendance-header h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 4px 0;
    font-size: 16px;
}

.attendance-header p {
    margin: 0;
    font-size: 13px;
    color: var(--text-secondary);
}

.streak-badge {
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
    padding: 12px 16px;
    border-radius: 12px;
    text-align: center
    ;
box-shadow: var(--shadow-md);
}
.streak-number {
font-size: 24px;
font-weight: 800;
display: block;
}
.streak-label {
font-size: 11px;
text-transform: uppercase;
opacity: 0.9;
}
.progress-bar-wrapper {
margin-top: 16px;
}
.progress-info {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 8px;
font-size: 14px;
}
.percentage {
font-weight: 700;
color: var(--success);
}
.progress {
height: 8px;
border-radius: 4px;
background: var(--border);
}
.progress-bar {
height: 8px;
border-radius: 4px;
}
/* Analytics Card */
.analytics-card {
display: flex;
align-items: center;
gap: 16px;
padding: 20px;
background: linear-gradient(135deg, var(--info-light), white);
border-radius: 12px;
border: 2px solid var(--info);
}
.analytics-icon {
width: 48px;
height: 48px;
background: var(--info);
border-radius: 12px;
display: flex;
align-items: center;
justify-content: center;
font-size: 22px;
color: white;
flex-shrink: 0;
}
.analytics-content {
flex: 1;
}
.analytics-content h4 {
margin: 0 0 4px 0;
font-size: 16px;
}
.analytics-content p {
margin: 0;
font-size: 13px;
color: var(--text-secondary);
}
.analytics-buttons {
display: flex;
gap: 8px;
flex-wrap: wrap;
}
/* Action Items */
.action-list {
display: flex;
flex-direction: column;
gap: 12px;
}
.action-item {
position: relative;
display: flex;
align-items: flex-start;
gap: 12px;
padding: 16px;
padding-left: 24px;
background: var(--bg-secondary);
border-radius: 12px;
transition: all 0.3s;
}
.action-item:hover {
transform: translateX(4px);
box-shadow: var(--shadow-sm);
}
.action-indicator {
position: absolute;
left: 8px;
top: 50%;
transform: translateY(-50%);
width: 4px;
height: 80%;
border-radius: 2px;
}
.action-item.urgent .action-indicator {
background: var(--danger);
}
.action-item.important .action-indicator {
background: var(--warning);
}
.action-item.normal .action-indicator {
background: var(--success);
}
.action-icon {
width: 40px;
height: 40px;
background: white;
border-radius: 10px;
display: flex;
align-items: center;
justify-content: center;
flex-shrink: 0;
}
.action-content {
flex: 1;
}
.action-content h5 {
margin: 0 0 4px 0;
font-size: 14px;
font-weight: 700;
}
.action-content p {
margin: 0 0 6px 0;
font-size: 13px;
color: var(--text-secondary);
}
.deadline {
font-size: 12px;
font-weight: 600;
color: var(--danger);
}
/* Schedule */
.schedule-list {
display: flex;
flex-direction: column;
gap: 12px;
}
.schedule-item {
display: flex;
align-items: center;
gap: 16px;
padding: 16px;
background: var(--bg-secondary);
border-radius: 12px;
transition: all 0.3s;
}
.schedule-item:hover {
box-shadow: var(--shadow-sm);
}
.date-box {
text-align: center;
min-width: 50px;
}
.date-box .day {
display: block;
font-size: 24px;
font-weight: 800;
color: var(--primary);
line-height: 1;
}
.date-box .month {
display: block;
font-size: 12px;
color: var(--text-muted);
text-transform: uppercase;
font-weight: 600;
margin-top: 4px;
}
.schedule-details {
flex: 1;
}
.schedule-details h5 {
margin: 0 0 8px 0;
font-size: 14px;
font-weight: 700;
}
.schedule-details p {
margin: 0 0 6px 0;
font-size: 12px;
color: var(--text-secondary);
display: flex;
align-items: center;
gap: 6px;
}
.schedule-details .badge {
font-size: 10px;
padding: 3px 8px;
}
.badge-training {
background: var(--success-light);
color: var(--success);
}
.badge-match {
background: var(--warning-light);
color: var(--warning);
}
.badge-health {
background: var(--info-light);
color: var(--info);
}
/* Orders */
.orders-list {
display: flex;
flex-direction: column;
gap: 12px;
}
.order-item {
display: flex;
align-items: center;
gap: 12px;
padding: 16px;
background: var(--bg-secondary);
border-radius: 12px;
transition: all 0.3s;
}
.order-item:hover {
box-shadow: var(--shadow-sm);
}
.order-icon {
width: 40px;
height: 40px;
background: var(--primary-light);
border-radius: 10px;
display: flex;
align-items: center;
justify-content: center;
color: var(--primary);
flex-shrink: 0;
}
.order-details {
flex: 1;
}
.order-details h5 {
margin: 0 0 4px 0;
font-size: 14px;
font-weight: 700;
}
.order-details p {
margin: 0;
font-size: 12px;
color: var(--text-secondary);
}
.empty-state {
text-align: center;
padding: 40px 20px;
}
.empty-state i {
font-size: 48px;
color: var(--text-muted);
margin-bottom: 16px;
}
.empty-state h5 {
margin: 0 0 8px 0;
font-size: 16px;
}
.empty-state p {
margin: 0 0 16px 0;
color: var(--text-secondary);
}
/* Activity Timeline */
.activity-timeline {
position: relative;
}
.activity-timeline::before {
content: '';
position: absolute;
left: 32px;
top: 20px;
bottom: 20px;
width: 2px;
background: var(--border);
}
.activity-item {
position: relative;
display: flex;
align-items: flex-start;
gap: 16px;
padding: 16px 0;
border-bottom: 1px solid var(--border);
}
.activity-item:last-child {
border-bottom: none;
}
.activity-dot {
position: absolute;
left: 28px;
top: 26px;
width: 10px;
height: 10px;
background: white;
border: 3px solid var(--primary);
border-radius: 50%;
z-index: 1;
}
.activity-icon {
width: 48px;
height: 48px;
border-radius: 12px;
display: flex;
align-items: center;
justify-content: center;
font-size: 20px;
flex-shrink: 0;
}
.activity-icon.training {
background: var(--success-light);
color: var(--success);
}
.activity-icon.achievement {
background: var(--warning-light);
color: var(--warning);
}
.activity-icon.match {
background: var(--primary-light);
color: var(--primary);
}
.activity-content {
flex: 1;
}
.activity-content h5 {
margin: 0 0 6px 0;
font-size: 14px;
font-weight: 700;
}
.activity-content p {
margin: 0 0 8px 0;
font-size: 13px;
color: var(--text-secondary);
}
.rating {
display: flex;
align-items: center;
gap: 8px;
}
.rating i {
color: var(--text-muted);
font-size: 14px;
}
.rating i.filled {
color: var(--warning);
}
.rating span {
font-size: 12px;
color: var(--text-secondary);
margin-left: 4px;
}
/* Announcements */
.announcements-list {
display: flex;
flex-direction: column;
gap: 16px;
}
.announcement-item {
display: flex;
align-items: flex-start;
gap: 16px;
padding: 16px;
background: var(--bg-secondary);
border-radius: 12px;
transition: all 0.3s;
}
.announcement-item:hover {
box-shadow: var(--shadow-sm);
}
.announcement-icon {
width: 48px;
height: 48px;
border-radius: 12px;
display: flex;
align-items: center;
justify-content: center;
font-size: 20px;
flex-shrink: 0;
}
.announcement-icon.tournament {
background: var(--warning-light);
color: var(--warning);
}
.announcement-icon.award {
background: var(--success-light);
color: var(--success);
}
.announcement-icon.policy {
background: var(--info-light);
color: var(--info);
}
.announcement-content {
flex: 1;
}
.announcement-content h5 {
margin: 0 0 6px 0;
font-size: 14px;
font-weight: 700;
}
.announcement-content p {
margin: 0 0 8px 0;
font-size: 13px;
color: var(--text-secondary);
}
.announcement-content .date {
font-size: 12px;
color: var(--text-muted);
font-weight: 600;
}
/* Responsive */
@media (max-width: 1200px) {
.dashboard-grid {
grid-template-columns: 1fr;
}
.full-width-section {
    grid-template-columns: 1fr;
}
}
@media (max-width: 768px) {
.dashboard-container {
padding: 16px;
}
.welcome-banner {
    grid-template-columns: 1fr;
    padding: 24px;
}

.player-quick-card {
    min-width: auto;
}

.stats-row {
    grid-template-columns: 1fr;
}

.dashboard-section {
    padding: 16px;
}

.activity-timeline::before {
    display: none;
}

.activity-dot {
    display: none;
}
}
@media (max-width: 576px) {
.greeting-section {
flex-direction: column;
text-align: center;
}
.greeting-text h1 {
    font-size: 22px;
}

.section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
}

.analytics-buttons {
    flex-direction: column;
    width: 100%;
}

.analytics-buttons .btn {
    width: 100%;
}
}
</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh stats every 5 minutes
    setInterval(function() {
        console.log('Stats would refresh here');
    }, 300000);

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
@endpush
@endsection
