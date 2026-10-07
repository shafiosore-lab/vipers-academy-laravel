@extends('player.portal.layout')

@section('title', 'Training & Progress - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-chart-line me-3 text-primary"></i>Training & Progress
                    </h1>
                    <p class="section-subtitle">Track your development and performance metrics</p>
                </div>
                <div class="btn-toolbar">
                    <button class="btn btn-outline-primary me-2">
                        <i class="fas fa-download me-1"></i>Export Report
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Log Training
                    </button>
                </div>
            </div>
        </div>

        <!-- Performance Overview Charts -->
        <div class="row">
            <!-- Skills Assessment Chart -->
            <div class="col-xl-6" data-aos="fade-up" data-aos-delay="100">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Skills Assessment</h3>
                        <small class="text-muted">Last updated: 2 days ago</small>
                    </div>

                    <div class="chart-container">
                        <canvas id="skillsChart" height="250"></canvas>
                    </div>

                    <div class="skill-breakdown mt-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="skill-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="skill-name">Technical</span>
                                        <span class="skill-score">8.2</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 82%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="skill-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="skill-name">Physical</span>
                                        <span class="skill-score">7.8</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: 78%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="skill-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="skill-name">Mental</span>
                                        <span class="skill-score">8.5</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="skill-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="skill-name">Tactical</span>
                                        <span class="skill-score">6.9</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 69%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Goals -->
            <div class="col-xl-6" data-aos="fade-up" data-aos-delay="200">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Development Goals</h3>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Add Goal
                        </button>
                    </div>

                    <div class="goals-list">
                        <!-- Active Goals -->
                        <div class="goal-group mb-4">
                            <h5 class="goal-group-title">
                                <i class="fas fa-bullseye me-2 text-success"></i>In Progress
                            </h5>

                            <div class="goal-item active mb-3">
                                <div class="goal-header">
                                    <span class="goal-title">Improve Passing Accuracy</span>
                                    <span class="badge bg-warning text-dark">Week 3/6</span>
                                </div>
                                <div class="goal-progress">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: 50%"></div>
                                    </div>
                                    <small class="text-muted">Current: 78% → Target: 85%</small>
                                </div>
                                <div class="goal-actions">
                                    <button class="btn btn-sm btn-outline-success me-1">Update Progress</button>
                                    <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                </div>
                            </div>

                            <div class="goal-item active mb-3">
                                <div class="goal-header">
                                    <span class="goal-title">Increase Sprint Speed</span>
                                    <span class="badge bg-info">Week 2/4</span>
                                </div>
                                <div class="goal-progress">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-info" style="width: 75%"></div>
                                    </div>
                                    <small class="text-muted">Current: 8.2s → Target: 7.8s</small>
                                </div>
                                <div class="goal-actions">
                                    <button class="btn btn-sm btn-outline-info me-1">Update Progress</button>
                                    <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Goals -->
                        <div class="goal-group">
                            <h5 class="goal-group-title">
                                <i class="fas fa-check-circle me-2 text-success"></i>Completed
                            </h5>

                            <div class="goal-item completed">
                                <div class="goal-header">
                                    <span class="goal-title">Master Corner Kicks</span>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <small class="text-muted">Achieved target accuracy of 75%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training History & Stats -->
        <div class="row">
            <!-- Training Sessions -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="300">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Training Sessions</h3>
                        <div class="date-filter">
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                            </select>
                        </div>
                    </div>

                    <div class="training-sessions">
                        <div class="session-item" data-aos="fade-right">
                            <div class="session-header">
                                <div class="session-info">
                                    <h5 class="session-title">Technical Training - Passing Drills</h5>
                                    <div class="session-meta">
                                        <span class="session-date"><i class="fas fa-calendar me-1"></i>Yesterday</span>
                                        <span class="session-duration"><i class="fas fa-clock me-1"></i>90 min</span>
                                        <span class="session-type badge bg-primary">Technical</span>
                                    </div>
                                </div>
                                <div class="session-rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    </div>
                                    <span class="rating-text">4.5/5</span>
                                </div>
                            </div>

                            <div class="session-stats">
                                <div class="stat-grid">
                                    <div class="stat-box">
                                        <span class="stat-num">87%</span>
                                        <span class="stat-label">Passing Acc.</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">12</span>
                                        <span class="stat-label">Successful</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">5</span>
                                        <span class="stat-label">Failed</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">8.2</span>
                                        <span class="stat-label">Rating</span>
                                    </div>
                                </div>
                            </div>

                            <div class="session-feedback">
                                <p class="feedback-text">
                                    <strong>Coach Notes:</strong> Great improvement in short passing. Keep working on long-range passes - too many going out of play.
                                </p>
                            </div>
                        </div>

                        <div class="session-item" data-aos="fade-right" data-aos-delay="100">
                            <div class="session-header">
                                <div class="session-info">
                                    <h5 class="session-title">Fitness Training - Sprint Intervals</h5>
                                    <div class="session-meta">
                                        <span class="session-date"><i class="fas fa-calendar me-1"></i>3 days ago</span>
                                        <span class="session-duration"><i class="fas fa-clock me-1"></i>60 min</span>
                                        <span class="session-type badge bg-info">Fitness</span>
                                    </div>
                                </div>
                                <div class="session-rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-muted"></i>
                                    </div>
                                    <span class="rating-text">4.0/5</span>
                                </div>
                            </div>

                            <div class="session-stats">
                                <div class="stat-grid">
                                    <div class="stat-box">
                                        <span class="stat-num">7.9s</span>
                                        <span class="stat-label">40m Sprint</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">185</span>
                                        <span class="stat-label">Max BPM</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">92%</span>
                                        <span class="stat-label">Effort</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-num">7.8</span>
                                        <span class="stat-label">Rating</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Log New Training Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Achievements & Badges -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Achievements & Badges</h3>
                    </div>

                    <div class="achievements-list">
                        <!-- Recent Badge -->
                        <div class="achievement-item unlocked mb-3" data-aos="zoom-in">
                            <div class="achievement-badge large">
                                <i class="fas fa-award text-warning"></i>
                            </div>
                            <div class="achievement-info">
                                <h6 class="achievement-title">Passing Master</h6>
                                <p class="achievement-desc">Achieved 80% passing accuracy</p>
                                <small class="text-muted">Unlocked 2 hours ago</small>
                            </div>
                        </div>

                        <!-- Locked Badges -->
                        <div class="achievement-item locked" data-aos="zoom-in" data-aos-delay="100">
                            <div class="achievement-badge locked">
                                <i class="fas fa-lock text-muted"></i>
                            </div>
                            <div class="achievement-info">
                                <h6 class="achievement-title">Speed Demon</h6>
                                <p class="achievement-desc">40m sprint under 7.5 seconds</p>
                                <small class="text-muted">78% progress</small>
                            </div>
                        </div>

                        <div class="achievement-item locked" data-aos="zoom-in" data-aos-delay="200">
                            <div class="achievement-badge locked">
                                <i class="fas fa-lock text-muted"></i>
                            </div>
                            <div class="achievement-info">
                                <h6 class="achievement-title">Team Player</h6>
                                <p class="achievement-desc">20 consecutive sessions</p>
                                <small class="text-muted">50% progress</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-header">
                        <h4 class="section-title">Quick Actions</h4>
                    </div>

                    <div class="quick-actions">
                        <button class="btn btn-outline-primary btn-block text-start mb-2">
                            <i class="fas fa-plus-circle me-2"></i>Log Training Session
                        </button>
                        <button class="btn btn-outline-info btn-block text-start mb-2">
                            <i class="fas fa-chart-line me-2"></i>View Detailed Stats
                        </button>
                        <button class="btn btn-outline-success btn-block text-start mb-2">
                            <i class="fas fa-trophy me-2"></i>Set New Goal
                        </button>
                        <button class="btn btn-outline-secondary btn-block text-start">
                            <i class="fas fa-envelope me-2"></i>Contact Coach
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Skills Chart */
.chart-container {
    position: relative;
    height: 250px;
    margin-bottom: 20px;
}

.skill-breakdown .skill-item {
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
}

.skill-name {
    font-weight: 600;
    color: var(--text-primary);
}

.skill-score {
    font-weight: 700;
    font-size: 16px;
    color: var(--primary-red);
}

.skill-breakdown .progress {
    margin-top: 6px;
}

/* Goals */
.goals-list .goal-group-title {
    font-weight: 600;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--border-light);
}

.goal-item {
    padding: 16px;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    background: white;
}

.goal-item.active {
    border-left: 4px solid var(--accent-green);
    background: rgba(101, 198, 110, 0.03);
}

.goal-item.completed {
    background: rgba(16, 185, 129, 0.05);
    opacity: 0.8;
}

.goal-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 8px;
}

.goal-title {
    font-weight: 600;
    font-size: 16px;
}

.goal-progress small {
    color: var(--text-secondary);
    font-size: 13px;
}

.goal-actions {
    margin-top: 12px;
}

/* Training Sessions */
.training-sessions .session-item {
    padding: 20px;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    margin-bottom: 16px;
    background: white;
    transition: var(--transition-normal);
}

.training-sessions .session-item:hover {
    box-shadow: var(--shadow);
    transform: translateY(-2px);
}

.session-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.session-title {
    margin-bottom: 8px;
    font-size: 18px;
}

.session-meta {
    display: flex;
    gap: 16px;
    font-size: 14px;
    color: var(--text-secondary);
}

.session-rating {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.rating-stars {
    font-size: 16px;
}

.rating-text {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}

.session-stats {
    margin-bottom: 16px;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.stat-box {
    text-align: center;
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
}

.stat-num {
    display: block;
    font-size: 20px;
    font-weight: 800;
    color: var(--primary-red);
}

.stat-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    margin-top: 2px;
}

.session-feedback {
    padding-top: 16px;
    border-top: 1px solid var(--border-light);
}

/* Achievements */
.achievements-list .achievement-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    background: white;
}

.achievement-item.unlocked {
    border-left: 4px solid var(--success-green);
    background: rgba(16, 185, 129, 0.05);
}

.achievement-item.locked {
    opacity: 0.6;
}

.achievement-badge {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--primary-red-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.achievement-badge.locked {
    background: var(--bg-tertiary);
}

.achievement-title {
    font-weight: 600;
    margin-bottom: 4px;
}

.achievement-desc {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

/* Quick Actions */
.quick-actions .btn {
    justify-content: flex-start !important;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .session-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .goal-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Skills Radar Chart
    const skillsCtx = document.getElementById('skillsChart').getContext('2d');
    new Chart(skillsCtx, {
        type: 'radar',
        data: {
            labels: ['Technical Skills', 'Physical Fitness', 'Mental Toughness', 'Tactical Awareness', 'Team Work', 'Leadership'],
            datasets: [{
                label: 'Current Level',
                data: [8.2, 7.8, 8.5, 6.9, 8.7, 7.2],
                fill: true,
                backgroundColor: 'rgba(234, 28, 77, 0.1)',
                borderColor: 'var(--primary-red)',
                borderWidth: 2,
                pointBackgroundColor: 'var(--primary-red)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 10,
                    ticks: {
                        stepSize: 2
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    angleLines: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    pointLabels: {
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
@endsection
