@extends('layouts.staff')

@section('title', 'Partner Dashboard - Vipers Academy')

@section('content')
<div class="container-fluid">
    <!-- Status Alert for Pending Users -->
    @if(auth()->user()->isPending())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Partnership Application Under Review:</strong> Your partnership application is currently being reviewed by our team.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 text-white">
                                <i class="fas fa-handshake me-2"></i>
                                Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name ?? 'Partner' }}!
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(auth()->user()->isPending())
                                    Your partnership application is under review. You'll have full access to our platform once approved.
                                @else
                                    Manage your players, view analytics, and access partnership tools.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-white text-{{ auth()->user()->isPending() ? 'warning' : 'success' }} fs-6 px-3 py-2">
                                <i class="fas {{ auth()->user()->isPending() ? 'fa-clock' : 'fa-check-circle' }} me-1"></i>
                                {{ auth()->user()->isPending() ? 'Under Review' : 'Active Partner' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isPending())
        <!-- Pending Approval Dashboard -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
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
                        <small class="text-muted">Typically takes 2-3 business days</small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users text-primary fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Players</h6>
                                <h3 class="mb-0">{{ $stats['total_players'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Active Players</h6>
                                <h3 class="mb-0">{{ $stats['active_players'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-clock text-warning fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Pending</h6>
                                <h3 class="mb-0">{{ $stats['pending_players'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-calendar-check text-info fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Partner Since</h6>
                                <h5 class="mb-0">{{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('M Y') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features and Quick Actions -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('partner.player.create') }}" class="text-decoration-none">
                                    <div class="card border-0 bg-primary bg-opacity-10 h-100 p-3">
                                        <div class="text-center">
                                            <i class="fas fa-user-plus text-primary fa-2x mb-2"></i>
                                            <h6 class="text-dark">Register Player</h6>
                                            <small class="text-muted">Add new player</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('partner.players') }}" class="text-decoration-none">
                                    <div class="card border-0 bg-success bg-opacity-10 h-100 p-3">
                                        <div class="text-center">
                                            <i class="fas fa-users text-success fa-2x mb-2"></i>
                                            <h6 class="text-dark">View Players</h6>
                                            <small class="text-muted">Manage roster</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('partner.analytics') }}" class="text-decoration-none">
                                    <div class="card border-0 bg-info bg-opacity-10 h-100 p-3">
                                        <div class="text-center">
                                            <i class="fas fa-chart-line text-info fa-2x mb-2"></i>
                                            <h6 class="text-dark">Analytics</h6>
                                            <small class="text-muted">View reports</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partnership Benefits -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-star me-2 text-success"></i>Partnership Features</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-users text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Player Management</h6>
                                        <p class="text-muted small mb-0">Register and manage players from your organization</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-chart-bar text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Analytics Dashboard</h6>
                                        <p class="text-muted small mb-0">Access detailed performance analytics</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-envelope text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Communication</h6>
                                        <p class="text-muted small mb-0">Stay connected with academy</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-info bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-headset text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Support</h6>
                                        <p class="text-muted small mb-0">Dedicated partner support</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Organization Profile -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-building me-2"></i>Organization Profile</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fas fa-building text-success fa-2x"></i>
                            </div>
                            <h5 class="mb-1">{{ auth()->user()->partner_details['organization_name'] ?? auth()->user()->name }}</h5>
                            <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', auth()->user()->partner_details['partnership_type'] ?? 'platform_access')) }}</span>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <small class="text-muted d-block">Contact Person</small>
                            <strong>{{ auth()->user()->partner_details['contact_person'] ?? auth()->user()->name }}</strong>
                        </div>
                        @if(auth()->user()->partner_details['city'] ?? false)
                        <div class="mb-2">
                            <small class="text-muted d-block">Location</small>
                            <strong>{{ auth()->user()->partner_details['city'] ?? '' }}, {{ auth()->user()->partner_details['country'] ?? '' }}</strong>
                        </div>
                        @endif
                        <div class="mb-0">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ auth()->user()->email }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-life-ring me-2"></i>Need Help?</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Contact our partnership team for assistance.</p>
                        <a href="mailto:partners@vipersacademy.com" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-envelope me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
