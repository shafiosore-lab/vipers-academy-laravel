@extends('player.portal.layout')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Left Column - Main Content -->
    <div class="col-xl-8">
        <!-- 1. Welcome & Identity Section -->
        <div class="portal-section" data-aos="fade-up">
            <div class="welcome-hero">
                <div class="welcome-content">
                    <div class="welcome-greeting">
                        <div class="greeting-icon">
                            <i class="fas fa-sun animate-pulse"></i>
                        </div>
                        <div class="greeting-text">
                            <h2>Good {{ date('l') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
                            <p class="text-muted">Ready to dominate the pitch today?</p>
                        </div>
                    </div>

                    <div class="motivational-quote">
                        <blockquote class="quote-text">
                            "The only way to do great work is to love what you do."
                        </blockquote>
                        <cite class="quote-author">- Steve Jobs</cite>
                        <div class="voice-control">
                            <button class="btn btn-sm btn-outline-primary" title="Listen to quote">
                                <i class="fas fa-volume-up"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="player-identity-card" data-aos="fade-left" data-aos-delay="200">
                    <div class="identity-avatar">
                        @if($player && $player->photo)
                            <img src="{{ asset('storage/' . $player->photo) }}" alt="Player Photo" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif

                        <div class="avatar-edit">
                            <button class="btn btn-sm btn-primary" title="Change Photo">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>

                        <!-- AR Filters Button (Concept) -->
                        <button class="ar-filters-btn" title="AR Filters (Coming Soon)">
                            <i class="fas fa-magic"></i>
                        </button>
                    </div>

                    <div class="identity-details">
                        <h3 class="player-name">{{ $player ? $player->name : auth()->user()->name }}</h3>
                        <div class="player-badges">
                            @if($player)
                                <span class="badge badge-position">
                                    <i class="fas fa-futbol"></i> {{ $player->position }}
                                </span>
                                <span class="badge badge-status {{ $player->isApproved() ? 'badge-active' : 'badge-pending' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $player->isApproved() ? 'Active Player' : 'Pending Approval' }}
                                </span>
                            @endif
                        </div>

                        <div class="player-stats">
                            <div class="stat-item">
                                <span class="stat-label">Age Group</span>
                                <span class="stat-value">{{ $player ? $player->age_group : 'N/A' }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Academy Join</span>
                                <span class="stat-value">{{ $player ? ($player->academy_join_date ? \Carbon\Carbon::parse($player->academy_join_date)->format('M Y') : 'Recent') : 'N/A' }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Level</span>
                                <span class="stat-value">{{ $player ? ($player->current_level ?: 'Beginner') : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="{{ route('player.portal.profile') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit Profile
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" title="Share Profile">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Quick Stats Widget -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <div>
                    <h3 class="section-title">Performance Overview</h3>
                    <p class="section-subtitle">{{ date('F Y') }} Activity Summary</p>
                </div>
                <div class="date-selector">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-icon training-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $quickStats['training_sessions'] }}</div>
                        <div class="stat-label">Training Sessions</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> +12% this week
                        </div>
                    </div>
                </div>

                <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-icon goals-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $quickStats['goals_scored'] }}</div>
                        <div class="stat-label">Goals Scored</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> +5% this month
                        </div>
                    </div>
                </div>

                <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="stat-icon time-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($quickStats['minutes_played'] / 60, 1) }}h</div>
                        <div class="stat-label">Minutes Played</div>
                        <div class="stat-trend neutral">
                            <i class="fas fa-minus"></i> Consistent
                        </div>
                    </div>
                </div>

                <div class="stat-card" data-aos="zoom-in" data-aos-delay="500">
                    <div class="stat-icon program-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $quickStats['programs_enrolled'] }}</div>
                        <div class="stat-label">Active Programs</div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> Enrolled
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Consistency Bar -->
            <div class="attendance-widget" data-aos="fade-up" data-aos-delay="600">
                <div class="attendance-header">
                    <h5><i class="fas fa-chart-line me-2 text-success"></i>Attendance & Consistency</h5>
                </div>
                <div class="attendance-bar">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 92%">
                            <span class="progress-text">92% This Month</span>
                        </div>
                    </div>
                </div>
                <div class="attendance-streak">
                    <div class="streak-badge">
                        🔥 <span class="streak-number">12</span> day streak
                    </div>
                    <span class="streak-text">Keep it up!</span>
                </div>
            </div>
        </div>

        <!-- 3. Recent Activity Feed -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <div>
                    <h3 class="section-title">Recent Activity</h3>
                    <p class="section-subtitle">Your latest achievements and updates</p>
                </div>
                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>

            <div class="activity-feed">
                @if($recentActivity && $recentActivity->count() > 0)
                    @foreach($recentActivity->take(4) as $activity)
                    <div class="activity-item" data-aos="fade-right" data-aos-delay="200">
                        <div class="activity-icon training-activity">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Training Session Completed</div>
                            <div class="activity-meta">
                                <span class="activity-date">{{ $activity->created_at->diffForHumans() }}</span>
                                <span class="activity-type">Skills Training</span>
                            </div>
                            @if($activity->rating)
                                <div class="activity-rating">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $activity->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-text">Performance: {{ $activity->rating }}/5</span>
                                </div>
                            @endif
                        </div>
                        <div class="activity-actions">
                            <button class="btn btn-sm btn-outline-secondary" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Placeholder Activities for Demo -->
                    <div class="activity-item" data-aos="fade-right" data-aos-delay="200">
                        <div class="activity-icon achievement-activity">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Skill Badge Unlocked: First Touch</div>
                            <div class="activity-meta">
                                <span class="activity-date">2 days ago</span>
                                <span class="activity-type">Achievement</span>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <span class="badge badge-achievement">New Badge</span>
                        </div>
                    </div>

                    <div class="activity-item" data-aos="fade-right" data-aos-delay="300">
                        <div class="activity-icon match-activity">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Team Meeting: Strategy Discussion</div>
                            <div class="activity-meta">
                                <span class="activity-date">3 days ago</span>
                                <span class="activity-type">Team Event</span>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <button class="btn btn-sm btn-outline-secondary" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- More Activities Placeholder -->
                <div class="activity-load-more" data-aos="fade-up">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Load More Activities
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Sidebar -->
    <div class="col-xl-4">
        <!-- 4. Action Items -->
        <div class="portal-section" data-aos="fade-left" data-aos-delay="100">
            <div class="section-header">
                <h4 class="section-title">Action Items</h4>
                <div class="priority-indicator">
                    <span class="badge bg-warning text-dark">2 Urgent</span>
                </div>
            </div>

            <div class="action-items">
                <div class="action-item priority-high" data-aos="fade-up">
                    <div class="action-icon">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Medical Form Due</div>
                        <div class="action-description">Annual medical clearance expires soon</div>
                        <div class="action-deadline">Due: Tomorrow</div>
                    </div>
                    <div class="action-button">
                        <a href="#" class="btn btn-warning btn-sm">Complete</a>
                    </div>
                </div>

                <div class="action-item priority-medium" data-aos="fade-up" data-aos-delay="100">
                    <div class="action-icon">
                        <i class="fas fa-file-signature text-info"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Guardian Consent</div>
                        <div class="action-description">Parent approval needed for tournament</div>
                        <div class="action-deadline">Due: 3 days</div>
                    </div>
                    <div class="action-button">
                        <a href="#" class="btn btn-outline-info btn-sm">Sign</a>
                    </div>
                </div>

                <div class="action-item priority-low" data-aos="fade-up" data-aos-delay="200">
                    <div class="action-icon">
                        <i class="fas fa-dumbbell text-success"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Practice Drill</div>
                        <div class="action-description">Master passing technique</div>
                        <div class="action-deadline">Next session</div>
                    </div>
                    <div class="action-button">
                        <a href="{{ route('player.portal.resources') }}" class="btn btn-outline-success btn-sm">Practice</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Upcoming Schedule -->
        <div class="portal-section" data-aos="fade-left" data-aos-delay="200">
            <div class="section-header">
                <h4 class="section-title">Upcoming Schedule</h4>
                <a href="{{ route('player.portal.schedule') }}" class="btn btn-sm btn-outline-primary">View Calendar</a>
            </div>

            <div class="schedule-items">
                <div class="schedule-item" data-aos="fade-up">
                    <div class="schedule-date">
                        <div class="date-day">{{ date('d') + 2 }}</div>
                        <div class="date-month">{{ date('M') }}</div>
                    </div>
                    <div class="schedule-content">
                        <div class="schedule-title">Training Session</div>
                        <div class="schedule-meta">
                            <span class="schedule-time">4:00 PM - 6:00 PM</span>
                            <span class="schedule-type">Skills Training</span>
                        </div>
                        <div class="schedule-location">Moi International Sports Centre</div>
                    </div>
                    <div class="schedule-actions">
                        <button class="btn btn-success btn-sm" title="RSVP Confirmed">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>

                <div class="schedule-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="schedule-date">
                        <div class="date-day">{{ date('d') + 5 }}</div>
                        <div class="date-month">{{ date('M') }}</div>
                    </div>
                    <div class="schedule-content">
                        <div class="schedule-title">Team Match</div>
                        <div class="schedule-meta">
                            <span class="schedule-time">10:00 AM - 12:00 PM</span>
                            <span class="schedule-type">Friendly Match</span>
                        </div>
                        <div class="schedule-location">Bukhungu Stadium</div>
                    </div>
                    <div class="schedule-actions">
                        <button class="btn btn-outline-primary btn-sm" title="RSVP">
                            <i class="fas fa-calendar-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="schedule-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="schedule-date">
                        <div class="date-day">{{ date('d') + 7 }}</div>
                        <div class="date-month">{{ date('M') }}</div>
                    </div>
                    <div class="schedule-content">
                        <div class="schedule-title">Medical Checkup</div>
                        <div class="schedule-meta">
                            <span class="schedule-time">2:00 PM - 3:00 PM</span>
                            <span class="schedule-type">Health Assessment</span>
                        </div>
                        <div class="schedule-location">Academy Clinic</div>
                    </div>
                    <div class="schedule-actions">
                        <button class="btn btn-outline-secondary btn-sm" title="Reschedule">
                            <i class="fas fa-clock"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="schedule-footer">
                <a href="{{ route('player.portal.schedule') }}" class="btn btn-block btn-outline-primary btn-sm">
                    <i class="fas fa-calendar-alt me-2"></i>View Full Schedule
                </a>
            </div>
        </div>

                <!-- Profile Quick Settings -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="section-title">Profile Settings</h4>

                    <div class="profile-quick-settings">
                        <div class="setting-group">
                            <h6 class="setting-group-title">
                                <i class="fas fa-eye me-2"></i>Dashboard Display
                            </h6>
                            <div class="setting-item">
                                <div class="setting-content">
                                    <span class="setting-label">Performance Stats</span>
                                    <small class="text-muted">Show training metrics</small>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="showStatsPref" checked onchange="updateDashboardPreference('show_stats', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-content">
                                    <span class="setting-label">Upcoming Schedule</span>
                                    <small class="text-muted">Show next sessions</small>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="showSchedulePref" checked onchange="updateDashboardPreference('show_schedule', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-content">
                                    <span class="setting-label">Recent Activity</span>
                                    <small class="text-muted">Show activity feed</small>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="showActivityPref" checked onchange="updateDashboardPreference('show_activity', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="setting-group mt-4">
                            <h6 class="setting-group-title">
                                <i class="fas fa-bell me-2"></i>Notifications
                            </h6>
                            <div class="setting-item">
                                <div class="setting-content">
                                    <span class="setting-label">Motivational Quotes</span>
                                    <small class="text-muted">Daily inspiration</small>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="showQuotesPref" checked onchange="updateDashboardPreference('show_quotes', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-content">
                                    <span class="setting-label">Achievement Alerts</span>
                                    <small class="text-muted">New badges & awards</small>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="showAchievementsPref" checked onchange="updateDashboardPreference('show_achievements', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="profile-actions mt-3">
                            <a href="{{ route('player.portal.profile') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-cog me-1"></i>Full Settings
                            </a>
                        </div>
                    </div>
                </div>

        <!-- Recent Orders Widget -->
        <div class="portal-section" data-aos="fade-left" data-aos-delay="250">
            <div class="section-header">
                <h4 class="section-title">Recent Orders</h4>
                <a href="{{ route('player.portal.orders') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
            </div>

            <div class="recent-orders-list">
                @foreach($recentOrders as $order)
                <div class="order-item" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="order-item-icon">
                        <i class="fas fa-shopping-bag text-primary"></i>
                    </div>
                    <div class="order-item-content">
                        <div class="order-item-title">Order #{{ $order->order_number }}</div>
                        <div class="order-item-meta">
                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                            <span class="text-muted">•</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="order-item-status">
                        @if($order->order_status === 'delivered')
                            <span class="badge bg-success">Delivered</span>
                        @elseif($order->order_status === 'shipped')
                            <span class="badge bg-info">Shipped</span>
                        @elseif($order->order_status === 'processing')
                            <span class="badge bg-warning">Processing</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
                @if($recentOrders->isEmpty())
                <div class="empty-orders-placeholder" data-aos="fade-up">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h6>No Recent Orders</h6>
                    <p class="text-muted mb-3">Your order history will appear here</p>
                    <a href="{{ route('products') }}" class="btn btn-outline-primary btn-sm" target="_blank">Start Shopping</a>
                </div>
                @endif
            </div>
        </div>

        <!-- 6. Announcements Carousel -->
        <div class="portal-section" data-aos="fade-left" data-aos-delay="300">
            <div class="section-header">
                <h4 class="section-title">Latest Announcements</h4>
            </div>

            <div class="announcements-carousel">
                <div class="announcement-item" data-aos="fade-in">
                    <div class="announcement-header">
                        <div class="announcement-badge">
                            <i class="fas fa-trophy text-warning"></i>
                        </div>
                        <div class="announcement-date">{{ date('M d') }}</div>
                    </div>
                    <div class="announcement-content">
                        <h5 class="announcement-title">New Tournament Registration</h5>
                        <p class="announcement-text">Youth Football Cup 2025 registration now open</p>
                    </div>
                    <div class="announcement-actions">
                        <button class="btn btn-primary btn-sm">Register Now</button>
                    </div>
                </div>

                <div class="announcement-item" data-aos="fade-in" data-aos-delay="100">
                    <div class="announcement-header">
                        <div class="announcement-badge">
                            <i class="fas fa-gift text-success"></i>
                        </div>
                        <div class="announcement-date">{{ date('M d', strtotime('-1 day')) }}</div>
                    </div>
                    <div class="announcement-content">
                        <h5 class="announcement-title">Player of the Month</h5>
                        <p class="announcement-text">February awards ceremony postponed</p>
                    </div>
                    <div class="announcement-actions">
                        <button class="btn btn-outline-secondary btn-sm">Learn More</button>
                    </div>
                </div>

                <div class="announcement-item" data-aos="fade-in" data-aos-delay="200">
                    <div class="announcement-header">
                        <div class="announcement-badge">
                            <i class="fas fa-shield-alt text-info"></i>
                        </div>
                        <div class="announcement-date">{{ date('M d', strtotime('-2 days')) }}</div>
                    </div>
                    <div class="announcement-content">
                        <h5 class="announcement-title">Updated Policy</h5>
                        <p class="announcement-text">New safety protocols effective immediately</p>
                    </div>
                    <div class="announcement-actions">
                        <button class="btn btn-outline-info btn-sm">Read Policy</button>
                    </div>
                </div>
            </div>

            <div class="announcement-navigation">
                <button class="nav-btn" data-slide="prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="nav-dots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                </div>
                <button class="nav-btn" data-slide="next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Hero Section */
.welcome-hero {
    position: relative;
    background: linear-gradient(135deg, var(--primary-red-light), var(--accent-blue-light));
    border-radius: 16px;
    padding: 32px;
    overflow: hidden;
}

.welcome-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="30" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

.welcome-content {
    position: relative;
    z-index: 2;
    color: white;
}

.welcome-greeting {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.greeting-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    backdrop-filter: blur(10px);
}

.greeting-text h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
}

.greeting-text p {
    margin: 0;
    opacity: 0.9;
}

.motivational-quote {
    position: relative;
}

.quote-text {
    font-size: 18px;
    font-style: italic;
    margin-bottom: 8px;
    padding-left: 20px;
    border-left: 3px solid rgba(255, 255, 255, 0.5);
    line-height: 1.4;
}

.quote-author {
    font-size: 14px;
    opacity: 0.8;
    margin-bottom: 12px;
}

.voice-control {
    text-align: right;
}

.voice-control .btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
}

/* Player Identity Card */
.player-identity-card {
    background: white;
    border-radius: 16px;
    margin-top: 24px;
    padding: 24px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.player-identity-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-red), var(--accent-green), var(--accent-blue));
}

.identity-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: var(--shadow);
}

.avatar-placeholder {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-red), var(--accent-green));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
}

.avatar-edit {
    position: absolute;
    bottom: -8px;
    right: -8px;
}

.ar-filters-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, var(--warning-yellow), var(--accent-blue));
    border: none;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    opacity: 0.8;
}

.identity-details {
    flex: 1;
}

.player-name {
    margin: 0 0 12px 0;
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
}

.player-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.player-badges .badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-position {
    background: var(--warning-yellow);
    color: var(--text-primary);
}

.badge-status.badge-active {
    background: var(--success-green);
    color: white;
}

.badge-status.badge-pending {
    background: var(--warning-yellow);
    color: var(--text-primary);
}

.player-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.stat-item {
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 4px;
}

.stat-value {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}

.profile-actions {
    display: flex;
    gap: 8px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    border: var(--border-light);
    transition: var(--transition-normal);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.training-icon {
    background: var(--accent-green-light);
    color: var(--accent-green);
}

.goals-icon {
    background: var(--warning-yellow-light);
    color: var(--accent-green);
}

.time-icon {
    background: var(--accent-blue-light);
    color: var(--accent-blue);
}

.program-icon {
    background: var(--primary-red-light);
    color: var(--primary-red);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 6px;
}

.stat-trend.positive {
    color: var(--success);
}

.stat-trend.neutral {
    color: var(--warning);
}

/* Attendance Widget */
.attendance-widget {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: var(--border-light);
}

.attendance-header h5 {
    margin-bottom: 16px;
    font-weight: 600;
}

.progress {
    height: 8px;
    margin-bottom: 12px;
    border-radius: 4px;
}

.progress-bar {
    border-radius: 4px;
    position: relative;
}

.progress-text {
    position: absolute;
    right: 8px;
    top: -22px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
}

.attendance-streak {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.streak-badge {
    background: linear-gradient(135deg, var(--danger), #ff6b35);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
}

.streak-number {
    font-size: 16px;
}

.streak-text {
    color: var(--text-muted);
    font-size: 14px;
}

/* Activity Feed */
.activity-feed {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    transition: var(--transition-normal);
}

.activity-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateX(4px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.training-activity {
    background: var(--accent-green-light);
    color: var(--accent-green);
}

.achievement-activity {
    background: var(--warning-yellow-light);
    color: var(--accent-green);
}

.match-activity {
    background: var(--primary-red-light);
    color: var(--primary-red);
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-primary);
}

.activity-meta {
    display: flex;
    gap: 12px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.activity-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--text-secondary);
}

.rating-stars .fa-star {
    margin-right: 2px;
}

.rating-text {
    font-size: 12px;
}

.activity-actions {
    display: flex;
    align-items: center;
}

.activity-load-more {
    text-align: center;
    padding: 16px 0;
}

/* Action Items */
.action-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: white;
    border-left: 4px solid var(--warning);
    border-radius: 0 8px 8px 0;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-normal);
}

.action-item.priority-high {
    border-left-color: var(--danger);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), white);
}

.action-item.priority-medium {
    border-left-color: var(--warning);
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), white);
}

.action-item.priority-low {
    border-left-color: var(--success);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), white);
}

.action-item:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow);
}

.action-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.action-content {
    flex: 1;
}

.action-title {
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text-primary);
}

.action-description {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 6px;
}

.action-deadline {
    font-size: 12px;
    color: var(--danger);
    font-weight: 600;
}

.action-button .btn {
    font-size: 12px;
    padding: 6px 12px;
}

/* Schedule Items */
.schedule-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.schedule-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    transition: var(--transition-normal);
}

.schedule-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateX(4px);
}

.schedule-date {
    text-align: center;
    flex-shrink: 0;
}

.date-day {
    font-size: 20px;
    font-weight: 800;
    color: var(--primary-red);
    line-height: 1;
}

.date-month {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    margin-top: 4px;
}

.schedule-content {
    flex: 1;
}

.schedule-title {
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-primary);
}

.schedule-meta {
    display: flex;
    gap: 12px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.schedule-location {
    font-size: 13px;
    color: var(--accent-blue);
    font-weight: 500;
}

.schedule-actions {
    display: flex;
    align-items: center;
}

/* Announcements */
.announcements-carousel {
    position: relative;
    overflow: hidden;
}

.announcement-item {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 18px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    margin-bottom: 12px;
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.announcement-badge {
    width: 36px;
    height: 36px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.announcement-date {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.announcement-content {
    flex: 1;
}

.announcement-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-primary);
    line-height: 1.3;
}

.announcement-text {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
}

.announcement-actions {
    text-align: right;
}

.announcement-navigation {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
}

.nav-btn {
    width: 32px;
    height: 32px;
    background: var(--bg-primary);
    border: var(--border);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-normal);
}

.nav-btn:hover {
    background: var(--primary-red);
    color: white;
    border-color: var(--primary-red);
}

.nav-dots {
    display: flex;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    background: var(--text-muted);
    opacity: 0.3;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-normal);
}

.dot.active {
    background: var(--primary-red);
    opacity: 1;
}

/* Profile Quick Settings */
.profile-quick-settings {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.setting-group {
    border-bottom: var(--border-light);
    padding-bottom: 16px;
}

.setting-group:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.setting-group-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 8px 0;
}

.setting-content {
    flex: 1;
}

.setting-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.setting-content small {
    color: var(--text-muted);
    font-size: 12px;
}

/* Toggle Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--primary-red);
}

input:focus + .slider {
    box-shadow: 0 0 1px var(--primary-red);
}

input:checked + .slider:before {
    transform: translateX(20px);
}

.slider.round {
    border-radius: 24px;
}

.slider.round:before {
    border-radius: 50%;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-hero {
        padding: 24px;
    }

    .welcome-greeting {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .welcome-hero .greeting-text h2 {
        font-size: 24px;
    }

    .player-identity-card {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }

    .player-stats {
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .schedule-items .schedule-item,
    .action-items .action-item {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .announcement-item {
        padding: 16px;
    }
}

/* Recent Orders Widget */
.recent-orders-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border: var(--border-light);
    border-radius: 8px;
    transition: var(--transition-normal);
}

.order-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateX(2px);
}

.order-item-icon {
    width: 36px;
    height: 36px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.order-item-content {
    flex: 1;
}

.order-item-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.order-item-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-secondary);
}

.order-item-status .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.empty-orders-placeholder {
    text-align: center;
    padding: 32px 16px;
}

.empty-orders-placeholder .empty-icon {
    width: 48px;
    height: 48px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 20px;
    color: var(--text-muted);
}

.empty-orders-placeholder h6 {
    color: var(--text-primary);
    margin-bottom: 8px;
    font-weight: 600;
}
</style>

@push('scripts')
<script>
    // Dashboard preference management
    function updateDashboardPreference(setting, value) {
        // Save preference to localStorage for simplicity (would normally save to server)
        const preferences = JSON.parse(localStorage.getItem('dashboardPreferences') || '{}');
        preferences[setting] = value;
        localStorage.setItem('dashboardPreferences', JSON.stringify(preferences));

        // Show success feedback
        showPreferenceFeedback(setting, value);

        // Could save to server here in a real implementation
        console.log(`${setting}: ${value}`);
    }

    function showPreferenceFeedback(setting, value) {
        // Simple visual feedback for preference changes
        const settingLabels = {
            'show_stats': 'Performance Stats',
            'show_schedule': 'Upcoming Schedule',
            'show_activity': 'Recent Activity',
            'show_quotes': 'Motivational Quotes',
            'show_achievements': 'Achievement Alerts'
        };

        const label = settingLabels[setting] || setting;
        const status = value ? 'shown' : 'hidden';

        // Show temporary toast-like notification
        const notification = document.createElement('div');
        notification.className = 'preference-notification';
        notification.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${label} ${value ? 'enabled' : 'disabled'}
        `;
        document.body.appendChild(notification);

        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: value ? 'var(--success-green)' : 'var(--text-muted)',
            color: 'white',
            padding: '12px 20px',
            borderRadius: '8px',
            boxShadow: 'var(--shadow-lg)',
            zIndex: '9999',
            fontSize: '14px',
            opacity: '0',
            transform: 'translateY(-20px)',
            transition: 'all 0.3s ease'
        });

        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Load saved preferences on page load
    function loadDashboardPreferences() {
        const preferences = JSON.parse(localStorage.getItem('dashboardPreferences') || '{}');

        // Apply saved preferences to toggle states
        Object.keys(preferences).forEach(setting => {
            const checkbox = document.getElementById(`${setting}Pref`);
            if (checkbox) {
                checkbox.checked = preferences[setting];
            }
        });
    }

    // Greeting based on time of day
    document.addEventListener('DOMContentLoaded', function() {
        // Load user's dashboard preferences
        loadDashboardPreferences();

        // Announcement carousel functionality (basic)
        let currentSlide = 0;
        const slides = document.querySelectorAll('.announcement-item');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'flex' : 'none';
            });
        }

        // Navigation dots
        document.querySelectorAll('.dot').forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlides();
            });
        });

        function updateSlides() {
            document.querySelectorAll('.dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
            showSlide(currentSlide);
        }

        // Auto-rotate announcements (optional)
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlides();
        }, 8000);

        updateSlides();

        // Voice control simulation
        document.querySelector('.voice-control .btn')?.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-pause"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-volume-up"></i>';
            }, 3000);
        });
    });
</script>
@endpush
@endsection
