@extends('layouts.admin')

@section('title', __('Dashboard - Vipers Academy Admin'))

@section('content')
<!-- Modern Hero Header with Glassmorphism -->
<div class="dashboard-hero mb-4">
    <div class="glass-card border-0">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-3">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="status-indicator"></div>
                        </div>
                        <div>
                            <h1 class="h3 fw-bold mb-1 text-white">{{ __('Welcome back') }}, {{ Auth::user()->name }}! 👋</h1>
                            <p class="mb-0 text-white-75">{{ __('Here\'s what\'s happening with your academy today') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <!-- Advanced Search -->
                        <div class="search-container-modern position-relative flex-grow-1">
                            <input type="text" id="globalSearch" class="search-input-modern"
                                   placeholder="🔍 Search anything...">
                            <div id="searchResults" class="search-dropdown" style="display: none;">
                                <div class="search-loading" id="searchLoading" style="display: none;">
                                    <div class="spinner-modern"></div>
                                    <span>Searching...</span>
                                </div>
                                <div id="searchResultsContent"></div>
                            </div>
                        </div>
                        <!-- Date Display -->
                        <div class="date-card glass-mini">
                            <div class="text-white fw-semibold small">{{ now()->format('M d, Y') }}</div>
                            <div class="text-white-50 x-small">{{ now()->format('l') }}</div>
                        </div>
                        <!-- Action Buttons -->
                        <button class="header-action-btn" onclick="refreshDashboard()" title="Refresh Dashboard">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <a href="{{ route('home') }}" class="header-action-btn" title="View Website" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics with Modern Design -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="metric-card gradient-blue">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Players</div>
                <div class="metric-value">{{ $totalPlayers }}</div>
                <div class="metric-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $playerGrowth }}% vs last month
                </div>
            </div>
            <div class="metric-chart">
                <canvas id="playersSparkline" height="40"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="metric-card gradient-green">
            <div class="metric-icon">
                <i class="fas fa-football-ball"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Active Programs</div>
                <div class="metric-value">{{ $totalPrograms }}</div>
                <div class="metric-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $programGrowth }}% growth
                </div>
            </div>
            <div class="metric-chart">
                <canvas id="programsSparkline" height="40"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="metric-card gradient-purple">
            <div class="metric-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Partnerships</div>
                <div class="metric-value">{{ $totalPartners }}</div>
                <div class="metric-change neutral">
                    <i class="fas fa-minus"></i> {{ $pendingPartners }} pending
                </div>
            </div>
            <div class="metric-chart">
                <canvas id="partnersSparkline" height="40"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="metric-card gradient-orange">
            <div class="metric-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">News Articles</div>
                <div class="metric-value">{{ $totalNews }}</div>
                <div class="metric-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $newsGrowth }}% engagement
                </div>
            </div>
            <div class="metric-chart">
                <canvas id="newsSparkline" height="40"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Main Analytics Section -->
<div class="row g-3 mb-4">
    <!-- Performance Chart -->
    <div class="col-xl-8">
        <div class="modern-card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="card-title-modern">
                            <i class="fas fa-chart-line text-primary"></i>
                            Performance Analytics
                        </h5>
                        <p class="card-subtitle-modern">Track your academy's growth over time</p>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active">12M</button>
                        <button type="button" class="btn btn-outline-primary">6M</button>
                        <button type="button" class="btn btn-outline-primary">30D</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="col-xl-4">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-bell text-warning"></i>
                    Recent Activity
                </h5>
                <p class="card-subtitle-modern">Latest updates from your academy</p>
            </div>
            <div class="card-body p-0">
                <div class="activity-feed-modern">
                    @forelse($recentPartners as $partner)
                        <div class="activity-item-modern">
                            <div class="activity-icon bg-primary">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $partner->name }}</div>
                                <div class="activity-meta">New partnership • {{ $partner->created_at->diffForHumans() }}</div>
                            </div>
                            @if($partner->status === 'pending')
                                <span class="badge-modern badge-warning">Pending</span>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state-mini">
                            <i class="fas fa-handshake"></i>
                            <span>No recent partnerships</span>
                        </div>
                    @endforelse

                    @forelse($recentPlayers->take(3) as $player)
                        <div class="activity-item-modern">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $player->first_name }} {{ $player->last_name }}</div>
                                <div class="activity-meta">New registration • {{ $player->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-mini">
                            <i class="fas fa-users"></i>
                            <span>No recent registrations</span>
                        </div>
                    @endforelse

                    @php $recentNews = \App\Models\News::latest()->take(2)->get(); @endphp
                    @forelse($recentNews as $news)
                        <div class="activity-item-modern">
                            <div class="activity-icon bg-info">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ Str::limit($news->title, 30) }}</div>
                                <div class="activity-meta">Published • {{ $news->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-mini">
                            <i class="fas fa-newspaper"></i>
                            <span>No recent news</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Management Section -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-file-alt text-primary"></i>
                    Document Management
                </h5>
                <p class="card-subtitle-modern">Academy policies, regulations, and compliance materials</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="document-overview-item">
                            <div class="doc-icon">
                                <i class="fas fa-shield-alt text-primary"></i>
                            </div>
                            <div class="doc-content">
                                <h6 class="doc-title">{{ \App\Models\Document::where('category', 'safety_protection')->active()->count() }} Safety Policies</h6>
                                <p class="doc-description">Child protection, incident reporting, and safety guidelines</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="document-overview-item">
                            <div class="doc-icon">
                                <i class="fas fa-file-contract text-success"></i>
                            </div>
                            <div class="doc-content">
                                <h6 class="doc-title">{{ \App\Models\Document::where('category', 'contracts_agreements')->active()->count() }} Contracts & Agreements</h6>
                                <p class="doc-description">Registration forms, terms, and legal agreements</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="document-overview-item">
                            <div class="doc-icon">
                                <i class="fas fa-check-square text-info"></i>
                            </div>
                            <div class="doc-content">
                                <h6 class="doc-title">{{ \App\Models\Document::where('is_mandatory', true)->active()->count() }} Mandatory Documents</h6>
                                <p class="doc-description">Required documentation for compliance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="document-overview-item">
                            <div class="doc-icon">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div class="doc-content">
                                @php
                                    $expiringSoon = \App\Models\Document::whereNotNull('expiry_days')->active()->count();
                                @endphp
                                <h6 class="doc-title">{{ $expiringSoon }} Documents to Review</h6>
                                <p class="doc-description">Documents requiring periodic renewal</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary btn-modern">
                            <i class="fas fa-plus me-2"></i>Create Document
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-primary btn-modern">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>
                        <a href="{{ route('admin.documents.index', ['status' => 'mandatory']) }}" class="btn btn-outline-success btn-modern">
                            <i class="fas fa-shield-alt me-2"></i>Compliance Docs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Statistics -->
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-chart-pie text-info"></i>
                    Usage Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="statistic-item">
                    <div class="stat-number">
                        @try
                            {{ \App\Models\Document::sum(\DB::raw('COALESCE((
                                SELECT SUM(download_count)
                                FROM user_documents ud
                                WHERE ud.document_id = documents.id
                            ), 0)')) }}
                        @catch
                            0
                        @endtry
                    </div>
                    <div class="stat-label">Total Downloads</div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
                <div class="statistic-item">
                    <div class="stat-number">
                        @try
                            {{ \App\Models\Document::sum(\DB::raw('COALESCE((
                                SELECT COUNT(*)
                                FROM user_documents ud
                                WHERE ud.document_id = documents.id AND ud.status = "signed"
                            ), 0)')) }}
                        @catch
                            0
                        @endtry
                    </div>
                    <div class="stat-label">Completed Signatures</div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                <div class="statistic-item">
                    <div class="stat-number">
                        @try
                            {{ \App\Models\UserDocument::expiringSoon(7)->count() }}
                        @catch
                            0
                        @endtry
                    </div>
                    <div class="stat-label">Expiring This Week</div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Grid -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="section-header-modern">
            <h5 class="section-title">Quick Actions</h5>
            <p class="section-subtitle">Frequently used features and shortcuts</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.players.create') }}" class="action-card">
            <div class="action-icon bg-primary">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Add Player</div>
                <div class="action-description">Register new athlete</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.programs.create') }}" class="action-card">
            <div class="action-icon bg-success">
                <i class="fas fa-football-ball"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Add Program</div>
                <div class="action-description">Create training program</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.documents.index') }}" class="action-card">
            <div class="action-icon bg-info">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Manage Documents</div>
                <div class="action-description">Policies & regulations</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.news.create') }}" class="action-card">
            <div class="action-icon bg-warning">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Add News</div>
                <div class="action-description">Publish academy news</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.website-players.create') }}" class="action-card">
            <div class="action-icon bg-purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Add Website Player</div>
                <div class="action-description">Upload player images</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="{{ route('admin.gallery.create') }}" class="action-card">
            <div class="action-icon bg-orange">
                <i class="fas fa-images"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Add Gallery</div>
                <div class="action-description">Upload photos</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>

<!-- Analytics Dashboard -->
<div class="row g-3 mb-4">
    <!-- Development Stages -->
    <div class="col-lg-4 col-md-6">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-chart-pie text-success"></i>
                    Development Stages
                </h5>
            </div>
            <div class="card-body">
                <canvas id="developmentChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-lg-4 col-md-6">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-star text-warning"></i>
                    Top Performers
                </h5>
            </div>
            <div class="card-body">
                @forelse($topPerformers as $performer)
                    <div class="performer-item">
                        <div class="performer-avatar">
                            {{ strtoupper(substr($performer->first_name, 0, 1) . substr($performer->last_name, 0, 1)) }}
                        </div>
                        <div class="performer-info">
                            <div class="performer-name">{{ $performer->first_name }} {{ $performer->last_name }}</div>
                            <div class="performer-position">{{ $performer->position }}</div>
                        </div>
                        <div class="performer-rating">
                            <span class="rating-value">{{ $performer->performance_rating }}</span>
                            <span class="rating-max">/10</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <p>No performance data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- School Distribution -->
    <div class="col-lg-4 col-md-6">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-school text-info"></i>
                    School Distribution
                </h5>
            </div>
            <div class="card-body">
                @forelse($schools as $school)
                    <div class="distribution-item">
                        <div class="distribution-label">
                            <i class="fas fa-graduation-cap"></i>
                            {{ Str::limit($school->school_name, 20) }}
                        </div>
                        <div class="distribution-value">{{ $school->student_count }}</div>
                        <div class="distribution-bar">
                            <div class="distribution-fill" style="width: {{ ($school->student_count / $totalPlayers) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-school"></i>
                        <p>No school data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- FIFA Compliance & System Status -->
<div class="row g-3 mb-4">
    <!-- FIFA Compliance -->
    <div class="col-lg-6">
        <div class="modern-card compliance-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-shield-alt text-primary"></i>
                    FIFA Compliance Status
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="compliance-metric">
                            <div class="compliance-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="compliance-value">{{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}</div>
                            <div class="compliance-label">FIFA Registered</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="compliance-metric">
                            <div class="compliance-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="compliance-value">{{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}</div>
                            <div class="compliance-label">Safeguarding</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.compliance.report') }}" class="btn-modern btn-primary-modern w-100" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        Download Compliance Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-lg-6">
        <div class="modern-card">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-server text-success"></i>
                    System Health
                </h5>
            </div>
            <div class="card-body">
                <div class="health-item">
                    <div class="health-info">
                        <span class="health-label">Database</span>
                        <span class="health-status success">Healthy</span>
                    </div>
                    <div class="health-bar">
                        <div class="health-fill success" style="width: 100%"></div>
                    </div>
                </div>
                <div class="health-item">
                    <div class="health-info">
                        <span class="health-label">Storage</span>
                        <span class="health-status success">Optimal</span>
                    </div>
                    <div class="health-bar">
                        <div class="health-fill success" style="width: 85%"></div>
                    </div>
                </div>
                <div class="health-item">
                    <div class="health-info">
                        <span class="health-label">Performance</span>
                        <span class="health-status warning">Good</span>
                    </div>
                    <div class="health-bar">
                        <div class="health-fill warning" style="width: 78%"></div>
                    </div>
                </div>
                <div class="health-item mb-0">
                    <div class="health-info">
                        <span class="health-label">API Status</span>
                        <span class="health-status success">Online</span>
                    </div>
                    <div class="health-bar">
                        <div class="health-fill success" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary: #6366f1;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --purple: #a855f7;
        --orange: #f97316;
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    body {
        background: var(--bg-primary);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Dashboard Hero */
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        opacity: 0.3;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .glass-mini {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px 16px;
        border-radius: 12px;
    }

    .avatar-wrapper {
        position: relative;
    }

    .avatar-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100
