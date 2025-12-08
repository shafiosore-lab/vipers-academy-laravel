@extends('layouts.academy')

@section('title', 'Partner Dashboard - Vipers Academy')

@section('content')
<div class="container-fluid">
    <!-- Migration Required Alert -->
    @if(!auth()->user()->isPending())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-database me-2"></i>
                    <strong>Database Update Required:</strong> To enable full partner player management features, please run the database migration.
                    <strong>Command:</strong> <code>php artisan migrate</code>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Alert for Pending Users -->
    @if(auth()->user()->isPending())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Partnership Application Under Review:</strong> Your partnership application is currently being reviewed by our team.
                    You will receive full access to our player database, analytics platform, and partnership benefits once approved.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-success text-white border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-handshake me-2"></i>
                                Welcome, {{ auth()->user()->partner_details['organization_name'] ?? 'Partner' }}!
                            </h2>
                            <p class="mb-0 opacity-75">
                                @if(auth()->user()->isPending())
                                    Your partnership application is under review. You'll have full access to our platform once approved.
                                @else
                                    Your partnership with Vipers Academy is active. Access our elite player network and advanced analytics platform.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-3">
                                    <small class="d-block opacity-75">Partnership Status</small>
                                    @if(auth()->user()->isPending())
                                        <span class="badge bg-warning fs-6 px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Under Review
                                        </span>
                                    @else
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active Partner
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <!-- View Website Button -->
                                    <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>View Website
                                    </a>
                                    <div class="avatar-circle bg-white text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%;">
                                        <i class="fas fa-building fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partnership Overview Stats -->
    @if(auth()->user()->isPending())
        <!-- Pending Approval Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-warning">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-clock fa-5x text-white mb-4"></i>
                            <h3 class="text-white mb-3">Partnership Application Under Review</h3>
                            <p class="text-white-50 mb-4 fs-5">Thank you for your interest in partnering with Vipers Academy. Our team is currently reviewing your application.</p>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-light border-0 shadow-sm">
                                        <h5 class="alert-heading mb-3"><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
                                        <ul class="text-start mb-0">
                                            <li>Our partnership team will review your application within 2-3 business days</li>
                                            <li>You'll receive an email notification once a decision is made</li>
                                            <li>If approved, you'll gain full access to our partner platform</li>
                                            <li>You can update your application details anytime before approval</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 justify-content-center">
                            <div class="col-md-3">
                                <div class="p-4 bg-white bg-opacity-10 rounded shadow-sm">
                                    <i class="fas fa-users text-white fa-3x mb-3"></i>
                                    <h5 class="text-white mb-2">Player Database</h5>
                                    <p class="text-white-75 mb-0 small">Access to comprehensive player profiles and performance data</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-white bg-opacity-10 rounded shadow-sm">
                                    <i class="fas fa-search text-white fa-3x mb-3"></i>
                                    <h5 class="text-white mb-2">Scouting Tools</h5>
                                    <p class="text-white-75 mb-0 small">Advanced scouting reports and player evaluation tools</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-white bg-opacity-10 rounded shadow-sm">
                                    <i class="fas fa-chart-line text-white fa-3x mb-3"></i>
                                    <h5 class="text-white mb-2">Analytics Platform</h5>
                                    <p class="text-white-75 mb-0 small">Real-time performance analytics and predictive modeling</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-4 bg-white bg-opacity-10 rounded shadow-sm">
                                    <i class="fas fa-handshake text-white fa-3x mb-3"></i>
                                    <h5 class="text-white mb-2">Partnership Portal</h5>
                                    <p class="text-white-75 mb-0 small">Dedicated partner dashboard and collaboration tools</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Status Card -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Your Partnership Application</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-building me-1"></i>Organization</h6>
                                    <p class="mb-0 fw-semibold">{{ auth()->user()->partner_details['organization_name'] ?? 'N/A' }}</p>
                                    <small class="text-muted">{{ ucfirst(auth()->user()->partner_details['organization_type'] ?? 'other') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-handshake me-1"></i>Partnership Type</h6>
                                    <p class="mb-0 fw-semibold">{{ ucwords(str_replace('_', ' ', auth()->user()->partner_details['partnership_type'] ?? 'platform_access')) }}</p>
                                    <small class="text-muted">{{ auth()->user()->partner_details['expected_users'] ?? '0' }} expected users</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-user me-1"></i>Contact Person</h6>
                                    <p class="mb-0 fw-semibold">{{ auth()->user()->partner_details['contact_person'] ?? 'N/A' }}</p>
                                    <small class="text-muted">{{ auth()->user()->partner_details['contact_position'] ?? '' }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted mb-2"><i class="fas fa-calendar me-1"></i>Application Date</h6>
                                    <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse(auth()->user()->partner_details['registration_date'] ?? now())->format('M d, Y') }}</p>
                                    <small class="text-muted">Submitted {{ \Carbon\Carbon::parse(auth()->user()->partner_details['registration_date'] ?? now())->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Application Status</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-4x text-warning mb-3"></i>
                            <h4 class="mb-2">Under Review</h4>
                            <p class="text-muted mb-3">Your application is being processed by our partnership team.</p>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                        </div>
                        <small class="text-muted">Typically takes 2-3 business days</small>
                    </div>
                </div>

                <!-- Quick Actions for Pending Partners -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Update Application
                            </a>
                            <a href="mailto:partners@vipersacademy.com" class="btn btn-outline-info">
                                <i class="fas fa-envelope me-2"></i>Contact Support
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>Back to Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Approved Partner Dashboard -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Managed Players</h6>
                                <h4 class="mb-0">{{ $stats['total_players'] ?? 0 }}</h4>
                                <small class="text-primary">
                                    <i class="fas fa-user-plus me-1"></i>{{ $stats['active_players'] ?? 0 }} Active
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Approved Players</h6>
                                <h4 class="mb-0">{{ $stats['approved_players'] ?? 0 }}</h4>
                                <small class="text-success">
                                    <i class="fas fa-thumbs-up me-1"></i>Academy Approved
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-clock text-warning fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Pending Approval</h6>
                                <h4 class="mb-0">{{ $stats['pending_players'] ?? 0 }}</h4>
                                <small class="text-warning">
                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Review
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-calendar-check text-info fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Partnership Since</h6>
                                <h4 class="mb-0">{{ \Carbon\Carbon::parse(auth()->user()->partner_details['registration_date'] ?? now())->format('M Y') }}</h4>
                                <small class="text-info">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse(auth()->user()->partner_details['registration_date'] ?? now())->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
    <!-- Partnership Benefits & Features -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light border-0">
            <h5 class="mb-0">
                <i class="fas fa-star me-2 text-success"></i>Your Partnership Benefits
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Player Management - Available to all partners -->
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-users text-primary fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Player Management</h6>
                                <p class="text-muted mb-2">Register and manage players from your organization. Track their progress and academy integration.</p>
                                <a href="{{ route('partner.players') }}" class="btn btn-sm btn-outline-primary">Manage Players</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics - Available to platform_access and above -->
                @if(in_array($partnershipType, ['platform_access', 'scouting_partnership', 'full_partnership']))
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-chart-bar text-success fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Performance Analytics</h6>
                                <p class="text-muted mb-2">Access detailed analytics and reports on your players' performance and development progress.</p>
                                <a href="{{ route('partner.analytics') }}" class="btn btn-sm btn-outline-success">View Analytics</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Scouting Network - Available to scouting_partnership and above -->
                @if(in_array($partnershipType, ['scouting_partnership', 'full_partnership']))
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-search text-info fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Scouting Network</h6>
                                <p class="text-muted mb-2">Connect with our professional scouting network and access exclusive player evaluation reports.</p>
                                <a href="#" class="btn btn-sm btn-outline-info">Scouting Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Training Programs - Available to full_partnership only -->
                @if($partnershipType === 'full_partnership')
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-graduation-cap text-warning fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Joint Training Programs</h6>
                                <p class="text-muted mb-2">Collaborate on training methodologies and participate in joint certification programs.</p>
                                <a href="#" class="btn btn-sm btn-outline-warning">Training Resources</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Partnership Type Specific Features -->
                @if($partnershipType === 'platform_access')
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-secondary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-cogs text-secondary fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Platform Access</h6>
                                <p class="text-muted mb-2">Basic platform access for player registration and progress tracking.</p>
                                <span class="badge bg-secondary">Current Level</span>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($partnershipType === 'scouting_partnership')
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-binoculars text-info fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Scouting Partnership</h6>
                                <p class="text-muted mb-2">Enhanced scouting capabilities and priority access to academy scouting reports.</p>
                                <span class="badge bg-info">Enhanced Access</span>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($partnershipType === 'full_partnership')
                <div class="col-md-6">
                    <div class="benefit-card p-3 border rounded h-100">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-crown text-warning fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Full Partnership</h6>
                                <p class="text-muted mb-2">Complete partnership with all academy resources, joint programs, and strategic collaboration.</p>
                                <span class="badge bg-warning">Premium Access</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

            <!-- Recent Activity -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">New Scouting Report Available</h6>
                                <p class="text-muted mb-1">U-17 midfielder from Nairobi region - Detailed analysis now available</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Analytics Dashboard Updated</h6>
                                <p class="text-muted mb-1">New performance metrics and comparative analysis features added</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Training Program Released</h6>
                                <p class="text-muted mb-1">Advanced tactical training module for U-18 players now available</p>
                                <small class="text-muted">3 days ago</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Partnership Milestones</h6>
                                <p class="text-muted mb-1">Congratulations! You've accessed 50+ player profiles this month</p>
                                <small class="text-muted">1 week ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Organization Profile -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-building me-2"></i>Organization Profile
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%;">
                        <i class="fas fa-building fa-3x"></i>
                    </div>
                    <h5 class="mb-1">{{ auth()->user()->partner_details['organization_name'] ?? 'Organization' }}</h5>
                    <p class="text-muted mb-2">{{ auth()->user()->partner_details['organization_type'] ?? 'Partner' }}</p>
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">{{ auth()->user()->partner_details['partnership_type'] ?? 'Platform Access' }}</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <a href="#" class="btn btn-outline-success btn-sm w-100">
                        <i class="fas fa-edit me-1"></i>Update Profile
                    </a>
                </div>
            </div>

            <!-- Partnership Details -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Partnership Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Contact Person</strong>
                        <span>{{ auth()->user()->partner_details['contact_person'] ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Position</strong>
                        <span>{{ auth()->user()->partner_details['contact_position'] ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Expected Users</strong>
                        <span>{{ auth()->user()->partner_details['expected_users'] ?? '0' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-1">Location</strong>
                        <span>{{ auth()->user()->partner_details['city'] ?? 'N/A' }}, {{ auth()->user()->partner_details['country'] ?? '' }}</span>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block mb-1">Partnership Type</strong>
                        <span class="badge bg-primary">{{ auth()->user()->partner_details['partnership_type'] ?? 'Platform Access' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-search me-2"></i>Browse Players
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-chart-bar me-2"></i>View Analytics
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Scouting Reports
                        </a>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-graduation-cap me-2"></i>Training Resources
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-headset me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Support Contact -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-life-ring me-2"></i>Need Help?
                    </h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">Our partnership team is here to support you</p>
                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary fa-2x mb-2"></i>
                        <p class="mb-1"><strong>Email Support</strong></p>
                        <a href="mailto:partners@vipersacademy.com" class="text-primary">partners@vipersacademy.com</a>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-phone text-success fa-2x mb-2"></i>
                        <p class="mb-1"><strong>Phone Support</strong></p>
                        <a href="tel:+254700000000" class="text-success">+254 700 000 000</a>
                    </div>
                    <a href="#" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-comments me-1"></i>Start Live Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }

    .benefit-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .benefit-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .avatar-circle {
        border: 3px solid rgba(255,255,255,0.8);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }

    @media (max-width: 768px) {
        .timeline {
            padding-left: 20px;
        }

        .timeline-marker {
            left: -17px;
        }
    }
</style>
