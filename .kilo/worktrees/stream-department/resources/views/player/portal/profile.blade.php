@extends('player.portal.layout')

@section('title', 'Profile & Settings - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-user me-3 text-primary"></i>Profile & Settings
                    </h1>
                    <p class="section-subtitle">Manage your personal information, preferences, and account settings</p>
                </div>

                <div class="header-actions">
                    <button class="btn btn-outline-primary me-2" onclick="printProfile()">
                        <i class="fas fa-print me-1"></i>Print Profile
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile Overview & Photo -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="portal-section">
                    <div class="profile-card">
                        <div class="profile-photo-section">
                            <div class="profile-photo">
                                @if($player && $player->photo)
                                    <img src="{{ asset('storage/' . $player->photo) }}" alt="Player Photo" class="profile-img">
                                @else
                                    <div class="profile-placeholder">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <button class="btn btn-outline-primary btn-sm change-photo-btn">
                                <i class="fas fa-camera me-1"></i>Change Photo
                            </button>
                            <input type="file" id="photoInput" accept="image/*" style="display: none;">
                        </div>

                        <div class="profile-info">
                            <h4 class="profile-name">{{ $player ? $player->name : $user->name ?? 'Player' }}</h4>
                            <div class="profile-details">
                                <div class="profile-detail">
                                    <span class="label">Player ID:</span>
                                    <span class="value">#{{ $player->id ?? 'N/A' }}</span>
                                </div>
                                <div class="profile-detail">
                                    <span class="label">Position:</span>
                                    <span class="value">{{ $player->position ?? 'Not Set' }}</span>
                                </div>
                                <div class="profile-detail">
                                    <span class="label">Academy Status:</span>
                                    <span class="badge {{ $player && $player->isApproved() ? 'bg-success' : 'bg-warning' }}">
                                        {{ $player && $player->isApproved() ? 'Active' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="section-title">Player Stats</h4>

                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $player->gameStatistics->count() ?? 0 }}</div>
                                <div class="stat-label">Trainings</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-trophy text-warning"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $player->gameStatistics->sum('goals_scored') ?? 0 }}</div>
                                <div class="stat-label">Goals</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt text-success"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $player->age ?? 'N/A' }}</div>
                                <div class="stat-label">Age</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-weight text-primary"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $player->weight_kg ?? 0 }}</div>
                                <div class="stat-label">Weight (kg)</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-ruler-vertical text-danger"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $player->height_cm ?? 0 }}</div>
                                <div class="stat-label">Height (cm)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connected Accounts -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="section-title">Connected Accounts</h4>

                    <div class="connected-accounts">
                        <div class="account-item">
                            <div class="account-logo">
                                <span class="account-initial">GM</span>
                            </div>
                            <div class="account-info">
                                <div class="account-name">Google Mail</div>
                                <div class="account-status">Connected</div>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary">Disconnect</button>
                        </div>

                        <div class="account-item">
                            <div class="account-logo">
                                <i class="fab fa-twitter"></i>
                            </div>
                            <div class="account-info">
                                <div class="account-name">Twitter</div>
                                <div class="account-status">Not Connected</div>
                            </div>
                            <button class="btn btn-sm btn-primary">Connect</button>
                        </div>

                        <div class="account-item">
                            <div class="account-logo">
                                <span class="account-initial">FA</span>
                            </div>
                            <div class="account-info">
                                <div class="account-name">FIFA Connect</div>
                                <div class="account-status">Verifying</div>
                            </div>
                            <button class="btn btn-sm btn-outline-warning" disabled>Verify</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <!-- Profile Tabs -->
                <div class="portal-section">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                                <i class="fas fa-user me-2"></i>Personal Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="physical-tab" data-bs-toggle="tab" data-bs-target="#physical" type="button" role="tab" aria-controls="physical" aria-selected="false">
                                <i class="fas fa-heartbeat me-2"></i>Physical Stats
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="false">
                                <i class="fas fa-cog me-2"></i>Account Settings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="fas fa-sliders-h me-2"></i>Preferences
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                            <form class="profile-form">
                                <h5 class="tab-title">Personal Information</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="firstName" value="{{ $player->first_name ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" value="{{ $player->last_name ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                        <small class="form-text text-muted">Contact admin to change email</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" value="{{ $player->phone ?? '' }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="dateOfBirth" value="{{ $player->date_of_birth ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" value="{{ $player->nationality ?? '' }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" rows="3">{{ $player->address ?? '' }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="guardianName" class="form-label">Guardian Name</label>
                                        <input type="text" class="form-control" id="guardianName" value="{{ $player->guardian_name ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="guardianPhone" class="form-label">Guardian Phone</label>
                                        <input type="tel" class="form-control" id="guardianPhone" value="{{ $player->guardian_phone ?? '' }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Physical Stats Tab -->
                        <div class="tab-pane fade" id="physical" role="tabpanel" aria-labelledby="physical-tab">
                            <form class="profile-form">
                                <h5 class="tab-title">Physical Statistics</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="height" class="form-label">Height (cm)</label>
                                        <input type="number" class="form-control" id="height" value="{{ $player->height_cm ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" id="weight" value="{{ $player->weight_kg ?? '' }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dominantFoot" class="form-label">Dominant Foot</label>
                                        <select class="form-select" id="dominantFoot">
                                            <option value="">Select foot...</option>
                                            <option value="right" {{ ($player->dominant_foot ?? '') === 'Right' ? 'selected' : '' }}>Right</option>
                                            <option value="left" {{ ($player->dominant_foot ?? '') === 'Left' ? 'selected' : '' }}>Left</option>
                                            <option value="both" {{ ($player->dominant_foot ?? '') === 'Both' ? 'selected' : '' }}>Both</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="position" class="form-label">Preferred Position</label>
                                        <select class="form-select" id="position">
                                            <option value="">Select position...</option>
                                            <option value="goalkeeper" {{ ($player->position ?? '') === 'goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                            <option value="defender" {{ ($player->position ?? '') === 'defender' ? 'selected' : '' }}>Defender</option>
                                            <option value="midfielder" {{ ($player->position ?? '') === 'midfielder' ? 'selected' : '' }}>Midfielder</option>
                                            <option value="forward" {{ ($player->position ?? '') === 'forward' ? 'selected' : '' }}>Forward</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="physical-metrics mb-4">
                                    <h6>Performance Metrics</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="metric-item">
                                                <label>40m Sprint Time (seconds)</label>
                                                <input type="number" step="0.01" class="form-control" value="{{ $player->sprint_time_40m ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="metric-item">
                                                <label>Vertical Jump (cm)</label>
                                                <input type="number" class="form-control" value="{{ $player->vertical_jump_cm ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="metric-item">
                                                <label>Max VO2 (ml/kg/min)</label>
                                                <input type="number" step="0.1" class="form-control" value="{{ $player->vo2_max ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="metric-item">
                                                <label>Reaction Time (ms)</label>
                                                <input type="number" class="form-control" value="{{ $player->reaction_time_ms ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="medical-info">
                                    <h6>Medical Information</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bloodType" class="form-label">Blood Type</label>
                                            <select class="form-select" id="bloodType">
                                                <option value="">Select...</option>
                                                <option value="A+" {{ ($player->blood_type ?? '') === 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ ($player->blood_type ?? '') === 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ ($player->blood_type ?? '') === 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ ($player->blood_type ?? '') === 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ ($player->blood_type ?? '') === 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ ($player->blood_type ?? '') === 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ ($player->blood_type ?? '') === 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ ($player->blood_type ?? '') === 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastMedical" class="form-label">Last Medical Check</label>
                                            <input type="date" class="form-control" id="lastMedical" value="{{ $player->last_medical_check ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="medicalConditions" class="form-label">Medical Conditions/Allergies</label>
                                        <textarea class="form-control" id="medicalConditions" rows="3" placeholder="List any medical conditions, allergies, or medications...">{{ $player->medical_conditions ?? '' }}</textarea>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Account Settings Tab -->
                        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <form class="profile-form">
                                <h5 class="tab-title">Account Security</h5>

                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword">
                                    </div>
                                </div>

                                <h5 class="tab-title mt-4">Notification Preferences</h5>

                                <div class="notification-settings">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="emailTraining" checked>
                                        <label class="form-check-label" for="emailTraining">
                                            Email notifications for training sessions
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="emailMatches" checked>
                                        <label class="form-check-label" for="emailMatches">
                                            Email notifications for matches and results
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="emailAnnouncements" checked>
                                        <label class="form-check-label" for="emailAnnouncements">
                                            Email notifications for academy announcements
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="smsUpdates">
                                        <label class="form-check-label" for="smsUpdates">
                                            SMS notifications for urgent updates
                                        </label>
                                    </div>
                                </div>

                                <div class="danger-zone mt-5">
                                    <h5 class="text-danger">Danger Zone</h5>
                                    <p class="text-muted">These actions cannot be undone. Please be certain.</p>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-download me-1"></i>Export My Data
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="confirmAccountDelete()">
                                            <i class="fas fa-trash me-1"></i>Delete Account
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                            <form class="profile-form">
                                <h5 class="tab-title">Display Preferences</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="theme" class="form-label">Theme</label>
                                        <select class="form-select" id="theme">
                                            <option value="light" selected>Light</option>
                                            <option value="dark">Dark</option>
                                            <option value="auto">Auto (System)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <select class="form-select" id="language">
                                            <option value="en" selected>English</option>

                                        </select>
                                    </div>
                                </div>

                                <h5 class="tab-title mt-4">Dashboard Preferences</h5>

                                <div class="dashboard-preferences">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showStats" checked>
                                        <label class="form-check-label" for="showStats">
                                            Show performance statistics on dashboard
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showSchedule" checked>
                                        <label class="form-check-label" for="showSchedule">
                                            Show upcoming schedule on dashboard
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showGoals" checked>
                                        <label class="form-check-label" for="showGoals">
                                            Show development goals on dashboard
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showAnnouncements" checked>
                                        <label class="form-check-label" for="showAnnouncements">
                                            Show academy announcements on dashboard
                                        </label>
                                    </div>
                                </div>

                                <h5 class="tab-title mt-4">Data & Privacy</h5>

                                <div class="privacy-settings">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="analyticsOptIn" checked>
                                        <label class="form-check-label" for="analyticsOptIn">
                                            Allow anonymous usage analytics to improve the platform
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="marketingEmails">
                                        <label class="form-check-label" for="marketingEmails">
                                            Receive marketing emails and updates from Vipers Academy
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dataSharing">
                                        <label class="form-check-label" for="dataSharing">
                                            Allow sharing performance data with partners (optional)
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Card */
.profile-card {
    text-align: center;
    padding-bottom: 20px;
}

.profile-photo-section {
    margin-bottom: 20px;
}

.profile-photo {
    width: 120px;
    height: 120px;
    margin: 0 auto 16px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--primary-red-light);
    position: relative;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-red), var(--accent-green));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.change-photo-btn {
    font-size: 12px;
    padding: 6px 12px;
}

.profile-name {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 16px;
}

.profile-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: center;
}

.profile-detail {
    display: flex;
    justify-content: space-between;
    width: 100%;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-light);
}

.profile-detail:last-child {
    border-bottom: none;
}

.profile-detail .label {
    font-weight: 600;
    color: var(--text-secondary);
}

.profile-detail .value {
    font-weight: 500;
    color: var(--text-primary);
}

.profile-detail .badge {
    font-size: 11px;
    padding: 4px 8px;
}

/* Stats List */
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: white;
    border: var(--border-light);
    border-radius: 8px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-number {
    font-size: 24px;
    font-weight: 800;
    color: var(--primary-red);
    margin-bottom: 2px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    font-weight: 500;
}

.stat-icon:nth-child(1) { background: var(--accent-blue-light); color: var(--accent-blue); }
.stat-icon:nth-child(2) { background: var(--warning-yellow-light); color: var(--warning-yellow); }
.stat-icon:nth-child(3) { background: var(--success-green-light); color: var(--success-green); }
.stat-icon:nth-child(4) { background: var(--primary-red-light); color: var(--primary-red); }
.stat-icon:nth-child(5) { background: var(--info-light, rgba(14, 165, 233, 0.1)); color: var(--info); }

/* Connected Accounts */
.connected-accounts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.account-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border: var(--border-light);
    border-radius: 8px;
}

.account-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-red), var(--accent-blue));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.account-logo i {
    font-size: 18px;
}

.account-info {
    flex: 1;
}

.account-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.account-status {
    font-size: 12px;
    color: var(--text-secondary);
}

/* Forms */
.profile-form {
    padding: 24px 0;
}

.tab-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border-light);
}

.form-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 16px;
    transition: var(--transition-normal);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-red);
    box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
}

/* Physical Metrics */
.metric-item {
    margin-bottom: 16px;
}

.metric-item label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.metric-item .form-control {
    font-size: 16px;
    font-weight: 500;
}

/* Notification Settings */
.notification-settings {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-check {
    padding: 12px 16px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    border: var(--border-light);
}

.form-check-input {
    margin-top: 0.1rem;
}

/* Danger Zone */
.danger-zone {
    padding: 20px;
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
}

.danger-zone h5 {
    color: var(--danger);
    margin-bottom: 8px;
}

.danger-zone p {
    font-size: 14px;
    margin-bottom: 16px;
}

/* Preferences */
.dashboard-preferences, .privacy-settings {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 32px;
}

.dashboard-preferences .form-check,
.privacy-settings .form-check {
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-photo {
        width: 100px;
        height: 100px;
    }

    .profile-name {
        font-size: 20px;
    }

    .profile-details {
        align-items: flex-start;
    }

    .profile-detail {
        flex-direction: column;
        gap: 4px;
        align-items: flex-start;
    }

    .stat-item {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }

    .account-item {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .form-control, .form-select {
        padding: 10px 12px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo upload functionality
    const photoInput = document.getElementById('photoInput');
    const changePhotoBtn = document.querySelector('.change-photo-btn');

    changePhotoBtn.addEventListener('click', function() {
        photoInput.click();
    });

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type and size
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, or GIF).');
                return;
            }

            if (file.size > maxSize) {
                alert('File size must be less than 5MB.');
                return;
            }

            // Show preview (simulated)
            const reader = new FileReader();
            reader.onload = function(e) {
                // Would normally upload here and update profile image
                alert('Profile photo updated successfully!');
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    const profileForm = document.querySelectorAll('.profile-form');
    profileForm.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Basic validation
            const password = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('New passwords do not match.');
                return;
            }

            // Show success message
            const submitBtn = form.querySelector('button[type="submit"]') || document.querySelector('button.btn-success');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    alert('Profile updated successfully!');
                }, 1000);
            }
        });
    });
});

function confirmAccountDelete() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone and will permanently remove all your data.')) {
        if (confirm('This is your final warning. Click OK to permanently delete your account.')) {
            // Would normally call delete API
            alert('Account deletion request submitted. Contact support for final confirmation.');
        }
    }
}

function printProfile() {
    window.print();
}
</script>
@endpush
@endsection
