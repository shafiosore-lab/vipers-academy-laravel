@extends('player.portal.layout')

@section('title', 'Schedule & Attendance - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-calendar-check me-3 text-primary"></i>Schedule & Attendance
                    </h1>
                    <p class="section-subtitle">Manage your training sessions and track attendance</p>
                </div>

                <div class="header-actions">
                    <button class="btn btn-outline-primary me-2">
                        <i class="fas fa-calendar-plus me-1"></i>Add Event
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Export Schedule
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats-row">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check text-success"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">24</div>
                        <div class="stat-label">Sessions Attended</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">92%</div>
                        <div class="stat-label">Attendance Rate</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-running text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">6</div>
                        <div class="stat-label">This Week</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <h3 class="section-title">Weekly Schedule</h3>
                <div class="calendar-controls">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="current-week">Nov 18 - Nov 24, 2024</span>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="calendar-grid">
                <!-- Days of week headers -->
                <div class="day-header">Sunday</div>
                <div class="day-header">Monday</div>
                <div class="day-header">Tuesday</div>
                <div class="day-header">Wednesday</div>
                <div class="day-header">Thursday</div>
                <div class="day-header">Friday</div>
                <div class="day-header">Saturday</div>

                <!-- Calendar cells -->
                <div class="day-cell">
                    <div class="day-number">17</div>
                    <div class="day-events">
                        <div class="event-item past">
                            <div class="event-time">4:00 PM</div>
                            <div class="event-title">Rest Day</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell">
                    <div class="day-number">18</div>
                    <div class="day-events">
                        <div class="event-item past attended">
                            <div class="event-time">4:00 PM</div>
                            <div class="event-title">Technical Training</div>
                            <div class="event-badge">Attended</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell">
                    <div class="day-number">19</div>
                    <div class="day-events">
                        <div class="event-item past attended">
                            <div class="event-time">3:30 PM</div>
                            <div class="event-title">Fitness Session</div>
                            <div class="event-badge">Attended</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell">
                    <div class="day-number">20</div>
                    <div class="day-events">
                        <div class="event-item past missed">
                            <div class="event-time">4:00 PM</div>
                            <div class="event-title">Team Tactics</div>
                            <div class="event-badge">Excused</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell today">
                    <div class="day-number">21</div>
                    <div class="day-events">
                        <div class="event-item upcoming">
                            <div class="event-time">4:00 PM</div>
                            <div class="event-title">Passing Drills</div>
                            <div class="event-badge">Confirmed</div>
                        </div>
                        <div class="event-item upcoming">
                            <div class="event-time">6:00 PM</div>
                            <div class="event-title">Parent Meeting</div>
                            <div class="event-badge">Required</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell">
                    <div class="day-number">22</div>
                    <div class="day-events">
                        <div class="event-item upcoming">
                            <div class="event-time">9:00 AM</div>
                            <div class="event-title">Match Day</div>
                            <div class="event-badge">Home</div>
                        </div>
                    </div>
                </div>

                <div class="day-cell">
                    <div class="day-number">23</div>
                    <div class="day-events">
                        <div class="event-item upcoming">
                            <div class="event-time">4:00 PM</div>
                            <div class="event-title">Recovery Session</div>
                            <div class="event-badge">Optional</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="200">
            <div class="section-header">
                <h3 class="section-title">Upcoming Sessions</h3>
                <span class="text-muted">Next 7 days</span>
            </div>

            <div class="upcoming-list">
                <div class="upcoming-item">
                    <div class="upcoming-time">
                        <div class="time-day">Today</div>
                        <div class="time-slot">4:00 PM - 5:30 PM</div>
                    </div>
                    <div class="upcoming-content">
                        <h5 class="upcoming-title">Passing Drills & Accuracy Training</h5>
                        <div class="upcoming-meta">
                            <span><i class="fas fa-map-marker-alt me-1"></i>Moi International Sports Centre</span>
                            <span><i class="fas fa-user me-1"></i>Coach James Wilson</span>
                        </div>
                        <div class="upcoming-type">
                            <span class="badge bg-primary">Technical Training</span>
                        </div>
                    </div>
                    <div class="upcoming-actions">
                        <button class="btn btn-success btn-sm">RSVP</button>
                    </div>
                </div>

                <div class="upcoming-item">
                    <div class="upcoming-time">
                        <div class="time-day">Tomorrow</div>
                        <div class="time-slot">6:00 PM - 7:00 PM</div>
                    </div>
                    <div class="upcoming-content">
                        <h5 class="upcoming-title">Parent-Player Meeting</h5>
                        <div class="upcoming-meta">
                            <span><i class="fas fa-map-marker-alt me-1"></i>Academy Conference Room</span>
                            <span><i class="fas fa-users me-1"></i>All Parents & Players</span>
                        </div>
                        <div class="upcoming-type">
                            <span class="badge bg-info">Meeting</span>
                        </div>
                    </div>
                    <div class="upcoming-actions">
                        <button class="btn btn-primary btn-sm">View Details</button>
                    </div>
                </div>

                <div class="upcoming-item">
                    <div class="upcoming-time">
                        <div class="time-day">Friday</div>
                        <div class="time-slot">9:00 AM - 11:00 AM</div>
                    </div>
                    <div class="upcoming-content">
                        <h5 class="upcoming-title">League Match vs. Nairobi Strikers</h5>
                        <div class="upcoming-meta">
                            <span><i class="fas fa-map-marker-alt me-1"></i>Nyayo Stadium</span>
                            <span><i class="fas fa-tshirt me-1"></i>Home Kit</span>
                        </div>
                        <div class="upcoming-type">
                            <span class="badge bg-success">Match</span>
                        </div>
                    </div>
                    <div class="upcoming-actions">
                        <button class="btn btn-warning btn-sm">Match Info</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="row">
            <div class="col-xl-8" data-aos="fade-up" data-aos-delay="300">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Attendance Statistics</h3>
                        <small class="text-muted">Last 30 days</small>
                    </div>

                    <div class="attendance-chart-container">
                        <canvas id="attendanceChart" height="200"></canvas>
                    </div>

                    <div class="attendance-summary">
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">28</div>
                                    <div class="summary-label">Present</div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-times-circle text-danger"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">1</div>
                                    <div class="summary-label">Absent</div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-question-circle text-warning"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">1</div>
                                    <div class="summary-label">Excused</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Quick Actions</h3>
                    </div>

                    <div class="quick-actions">
                        <button class="btn btn-outline-primary btn-block text-start mb-2">
                            <i class="fas fa-calendar-plus me-2"></i>Request Makeup Session
                        </button>
                        <button class="btn btn-outline-info btn-block text-start mb-2">
                            <i class="fas fa-envelope me-2"></i>Contact Coach
                        </button>
                        <button class="btn btn-outline-success btn-block text-start mb-2">
                            <i class="fas fa-clock me-2"></i>Change Availability
                        </button>
                        <button class="btn btn-outline-secondary btn-block text-start">
                            <i class="fas fa-bell me-2"></i>Set Reminders
                        </button>
                    </div>
                </div>

                <!-- Attendance Streak -->
                <div class="portal-section">
                    <div class="section-header">
                        <h4 class="section-title">Current Streak</h4>
                    </div>

                    <div class="streak-widget">
                        <div class="streak-number">12</div>
                        <div class="streak-label">Consecutive Sessions</div>
                        <div class="streak-flame">
                            <i class="fas fa-fire text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Quick Stats */
.quick-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 24px;
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
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--primary-red);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

.stat-icon:nth-child(1) { background: var(--accent-green-light); color: var(--accent-green); }
.stat-icon:nth-child(2) { background: var(--accent-blue-light); color: var(--accent-blue); }
.stat-icon:nth-child(3) { background: var(--warning-yellow-light); color: var(--warning-yellow); }

/* Calendar Grid */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    background: var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
}

.day-header {
    background: var(--bg-tertiary);
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.day-cell {
    background: white;
    min-height: 120px;
    padding: 8px;
    position: relative;
}

.day-number {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.day-cell.today {
    background: rgba(234, 28, 77, 0.05);
}

.day-cell.today .day-number {
    color: var(--primary-red);
    background: var(--primary-red);
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.day-events {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.event-item {
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 1.3;
}

.event-item.upcoming {
    background: var(--primary-red-light);
    color: var(--primary-red);
}

.event-item.past.attended {
    background: var(--accent-green-light);
    color: var(--accent-green);
}

.event-item.past.missed {
    background: var(--danger-light, #fecaca);
    color: var(--danger);
}

.event-time {
    font-weight: 600;
}

.event-title {
    font-size: 11px;
    margin-top: 2px;
}

.event-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
    margin-top: 4px;
}

.event-item.upcoming .event-badge {
    background: var(--primary-red);
    color: white;
}

/* Upcoming List */
.upcoming-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.upcoming-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    transition: var(--transition-normal);
}

.upcoming-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.upcoming-time {
    flex-shrink: 0;
    text-align: center;
    min-width: 80px;
}

.time-day {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.time-slot {
    font-size: 12px;
    color: var(--text-secondary);
}

.upcoming-content {
    flex: 1;
}

.upcoming-title {
    margin-bottom: 8px;
    font-size: 18px;
    color: var(--text-primary);
}

.upcoming-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 12px;
    font-size: 14px;
    color: var(--text-secondary);
}

.upcoming-type {
    margin-bottom: 12px;
}

.upcoming-actions {
    flex-shrink: 0;
}

/* Attendance Chart */
.attendance-chart-container {
    position: relative;
    height: 200px;
    margin-bottom: 24px;
}

.attendance-summary {
    padding: 20px;
    background: var(--bg-tertiary);
    border-radius: 12px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 16px;
    text-align: center;
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    font-size: 20px;
}

.summary-content {
    flex: 1;
}

.summary-number {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}

.summary-label {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}

/* Quick Actions */
.quick-actions .btn {
    justify-content: flex-start !important;
    margin-bottom: 8px;
}

/* Streak Widget */
.streak-widget {
    text-align: center;
    padding: 24px;
    background: linear-gradient(135deg, var(--warning-yellow-light), rgba(255, 255, 255, 0.8));
    border-radius: 12px;
}

.streak-number {
    font-size: 48px;
    font-weight: 800;
    color: var(--warning-yellow);
    line-height: 1;
    margin-bottom: 8px;
}

.streak-label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 12px;
}

.streak-flame {
    font-size: 32px;
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-grid {
        grid-template-columns: 1fr;
    }

    .day-header {
        display: none;
    }

    .quick-stats-row {
        grid-template-columns: 1fr;
    }

    .upcoming-item {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 1024px) {
    .calendar-grid {
        font-size: 14px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple attendance chart placeholder
    const canvas = document.getElementById('attendanceChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = 'rgba(234, 28, 77, 0.1)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = 'var(--primary-red)';
        ctx.font = '16px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('Attendance Graph Coming Soon', canvas.width / 2, canvas.height / 2);
    }
});
</script>
@endpush
@endsection
