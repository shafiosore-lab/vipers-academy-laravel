@extends('player.portal.layout')

@section('title', 'My Programs - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-graduation-cap me-3 text-primary"></i>My Programs
                    </h1>
                    <p class="section-subtitle">Track your program enrollments and academy progress</p>
                </div>
                <button class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Browse Programs
                </button>
            </div>
        </div>

        <!-- Active Programs -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <h3 class="section-title">Active Programs</h3>
                <span class="badge bg-success badge-lg">{{ $activePrograms->count() }} Active</span>
            </div>

            @if($activePrograms->count() > 0)
                <div class="programs-grid">
                    @foreach($activePrograms as $program)
                    <div class="program-card active" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="program-header">
                            <div class="program-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="program-status">
                                <span class="badge bg-success">Enrolled</span>
                            </div>
                        </div>

                        <div class="program-content">
                            <h4 class="program-title">{{ $program->name }}</h4>
                            <p class="program-description">{{ $program->description ?? 'Comprehensive academy development program' }}</p>

                            <div class="program-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $program->start_date ? \Carbon\Carbon::parse($program->start_date)->format('M Y') : 'Ongoing' }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $program->duration_weeks ?? '12' }} weeks</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $program->max_students ?? '25' }} players</span>
                                </div>
                            </div>

                            <div class="program-progress">
                                <div class="progress-info">
                                    <span class="progress-label">Progress</span>
                                    <span class="progress-value">75%</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                </div>
                                <small class="text-muted">Week 9 of 12 completed</small>
                            </div>

                            <div class="program-actions">
                                <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                <a href="{{ route('player.portal.training') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-chart-line me-1"></i>View Progress
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>No Active Programs</h4>
                    <p>You are not currently enrolled in any academy programs.</p>
                    <button class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Browse Available Programs
                    </button>
                </div>
            @endif
        </div>

        <!-- Available Programs -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="200">
            <div class="section-header">
                <h3 class="section-title">Available Programs</h3>
                <div class="search-filter">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>All Categories</option>
                        <option>U-18 Development</option>
                        <option>Fitness & Conditioning</option>
                        <option>Technical Skills</option>
                        <option>Tactical Training</option>
                    </select>
                </div>
            </div>

            @if($availablePrograms->count() > 0)
                <div class="programs-grid">
                    @foreach($availablePrograms->take(6) as $program)
                    <div class="program-card available" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="program-header">
                            <div class="program-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="program-price">
                                <span class="price">${{ $program->fees ?? '250' }}/month</span>
                            </div>
                        </div>

                        <div class="program-content">
                            <h4 class="program-title">{{ $program->name }}</h4>
                            <p class="program-description">{{ Str::limit($program->description ?? 'Professional academy training program designed for aspiring football players.', 80) }}</p>

                            <div class="program-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $program->start_date ? \Carbon\Carbon::parse($program->start_date)->format('M d, Y') : 'Flexible Start' }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $program->duration_weeks ?? '12' }} weeks</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>Available spots</span>
                                </div>
                            </div>

                            <div class="program-features">
                                <div class="feature-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Professional coaching</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Weekly training sessions</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <span>Progress tracking</span>
                                </div>
                            </div>

                            <div class="program-actions">
                                <button class="btn btn-outline-info btn-sm me-2">
                                    <i class="fas fa-info-circle me-1"></i>Learn More
                                </button>
                                <button class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i>Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>No Programs Available</h4>
                    <p>There are currently no programs available for enrollment.</p>
                </div>
            @endif
        </div>

        <!-- Program History -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
            <div class="section-header">
                <h3 class="section-title">Program History</h3>
                <small class="text-muted">{{ $completedPrograms->count() }} completed programs</small>
            </div>

            @if($completedPrograms->count() > 0)
                <div class="completed-programs">
                    <div class="program-history-item">
                        <div class="program-history-content">
                            <div class="program-history-header">
                                <h5>U-16 Foundation Program</h5>
                                <span class="badge bg-success">Completed</span>
                            </div>
                            <div class="program-history-meta">
                                <span><i class="fas fa-calendar-check me-1"></i>May 2024 - Aug 2024</span>
                                <span><i class="fas fa-star text-warning me-1"></i>4.8/5 Rating</span>
                                <span><i class="fas fa-trophy text-success me-1"></i>Certificate Earned</span>
                            </div>
                        </div>
                        <div class="program-history-actions">
                            <button class="btn btn-sm btn-outline-primary">View Certificate</button>
                        </div>
                    </div>

                    <div class="program-history-item">
                        <div class="program-history-content">
                            <div class="program-history-header">
                                <h5>Technical Skills Intensive</h5>
                                <span class="badge bg-success">Completed</span>
                            </div>
                            <div class="program-history-meta">
                                <span><i class="fas fa-calendar-check me-1"></i>Feb 2024 - Apr 2024</span>
                                <span><i class="fas fa-star text-warning me-1"></i>4.9/5 Rating</span>
                                <span><i class="fas fa-award text-primary me-1"></i>Best Performer</span>
                            </div>
                        </div>
                        <div class="program-history-actions">
                            <button class="btn btn-sm btn-outline-primary">View Certificate</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-history">
                    <div class="empty-history-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <p>No completed programs yet. Keep working hard!</p>
                </div>
            @endif
        </div>

        <!-- Program Enrollment Help -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="400">
            <div class="help-section">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h4>Need Help with Program Enrollment?</h4>
                        <p>Our academic advisors are here to help you choose the right program for your development goals and ensure a smooth enrollment process.</p>
                        <div class="help-features">
                            <div class="help-feature">
                                <i class="fas fa-user-check text-primary me-2"></i>
                                <span>Free consultation</span>
                            </div>
                            <div class="help-feature">
                                <i class="fas fa-clipboard-check text-success me-2"></i>
                                <span>Eligibility assessment</span>
                            </div>
                            <div class="help-feature">
                                <i class="fas fa-calendar-alt text-info me-2"></i>
                                <span>Schedule planning</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <button class="btn btn-primary btn-lg">
                            <i class="fas fa-comments me-2"></i>Contact Advisor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Program Cards */
.programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.program-card {
    border: 1px solid var(--border-light);
    border-radius: 16px;
    background: white;
    overflow: hidden;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-sm);
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
}

.program-card.active {
    border-left: 4px solid var(--success-green);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.02), white);
}

.program-card.available {
    border-left: 4px solid var(--primary-red);
    background: linear-gradient(135deg, rgba(234, 28, 77, 0.02), white);
}

.program-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 20px 0 20px;
}

.program-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-red-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--primary-red);
}

.program-status .badge,
.program-price .price {
    font-size: 12px;
    font-weight: 600;
    background: var(--success-green);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
}

.program-price .price {
    background: var(--warning-yellow);
    color: var(--text-primary);
}

.program-content {
    padding: 20px;
}

.program-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.program-description {
    color: var(--text-secondary);
    margin-bottom: 16px;
    line-height: 1.5;
}

.program-details {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-secondary);
    font-size: 14px;
}

.detail-item i {
    color: var(--primary-red);
}

.program-progress {
    margin-bottom: 20px;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.progress-label {
    color: var(--text-secondary);
}

.progress-value {
    font-weight: 600;
    color: var(--success-green);
}

.progress {
    height: 8px;
    margin-bottom: 8px;
}

.program-features {
    margin-bottom: 20px;
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 14px;
    color: var(--text-secondary);
}

.program-actions {
    display: flex;
    gap: 8px;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 32px;
    color: var(--text-muted);
}

.empty-state h4 {
    color: var(--text-primary);
    margin-bottom: 12px;
    font-weight: 600;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 24px;
}

/* Program History */
.completed-programs {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.program-history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    background: white;
}

.program-history-content {
    flex: 1;
}

.program-history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.program-history-header h5 {
    margin: 0;
    font-size: 18px;
    color: var(--text-primary);
}

.program-history-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.program-history-meta span {
    font-size: 14px;
    color: var(--text-secondary);
}

.program-history-actions {
    margin-left: 20px;
}

/* Empty History */
.empty-history {
    text-align: center;
    padding: 40px 20px;
}

.empty-history-icon {
    width: 60px;
    height: 60px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 24px;
    color: var(--text-muted);
}

/* Help Section */
.help-section {
    background: linear-gradient(135deg, var(--primary-red-light), rgba(255, 255, 255, 0.8));
    border-radius: 16px;
    padding: 32px;
}

.help-section h4 {
    color: var(--text-primary);
    margin-bottom: 12px;
    font-weight: 600;
}

.help-section p {
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.help-features {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.help-feature {
    display: flex;
    align-items: center;
    color: var(--text-secondary);
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .programs-grid {
        grid-template-columns: 1fr;
    }

    .program-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .program-details {
        flex-direction: column;
        gap: 8px;
    }

    .program-history-item {
        flex-direction: column;
        gap: 16px;
        text-align: left;
    }

    .help-section .row {
        text-align: center;
    }

    .help-features {
        align-items: center;
    }
}
</style>
@endsection
