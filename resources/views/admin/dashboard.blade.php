@extends('layouts.admin')

@section('title', __('Dashboard - Vipers Academy Admin'))

@section('content')
<!-- Modern Hero Header with Glassmorphism -->
<div class="dashboard-hero mb-4">
    <div class="glass-card border-0">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center mb-3">
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
                    <div class="d-flex align-items-center justify-content-lg-end gap-3 flex-wrap">
                        <!-- Advanced Search -->
                        <div class="search-container-modern position-relative flex-grow-1" style="max-width: 400px;">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics with Modern Design -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
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
<div class="row g-4 mb-4">
    <!-- Performance Chart -->
    <div class="col-xl-8">
        <div class="modern-card">
            <div class="card-header-modern">
                <div class="d-flex align-items-center justify-content-between">
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
    <div class="row g-4 mb-4">
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
                    <div class="row g-4">
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
                        <div class="d-flex gap-2 flex-wrap justify-content-start">
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
                        <div class="stat-number">{{ \App\Models\Document::sum(\DB::raw('COALESCE((
                            SELECT SUM(download_count)
                            FROM user_documents ud
                            WHERE ud.document_id = documents.id
                        ), 0)')) }}</div>
                        <div class="stat-label">Total Downloads</div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="stat-number">{{ \App\Models\Document::sum(\DB::raw('COALESCE((
                            SELECT COUNT(*)
                            FROM user_documents ud
                            WHERE ud.document_id = documents.id AND ud.status = "signed"
                        ), 0)')) }}</div>
                        <div class="stat-label">Completed Signatures</div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="stat-number">{{ \App\Models\UserDocument::expiringSoon(7)->count() }}</div>
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

    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
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

    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.news.create') }}" class="action-card">
            <div class="action-icon bg-info">
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

    <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.gallery.create') }}" class="action-card">
            <div class="action-icon bg-warning">
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
<div class="row g-4 mb-4">
    <!-- Development Stages -->
    <div class="col-lg-4">
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
    <div class="col-lg-4">
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
    <div class="col-lg-4">
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
<div class="row g-4 mb-4">
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

<!-- AI Insights -->
@if(count($aiInsights) > 0)
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="section-header-modern">
            <h5 class="section-title">
                <i class="fas fa-robot text-info"></i>
                AI Insights & Recommendations
            </h5>
            <p class="section-subtitle">Smart suggestions based on your academy data</p>
        </div>
    </div>

    @foreach($aiInsights as $insight)
        <div class="col-lg-6">
            <div class="insight-card insight-{{ $insight['type'] }}">
                <div class="insight-icon">
                    <i class="{{ $insight['icon'] }}"></i>
                </div>
                <div class="insight-content">
                    <h6 class="insight-title">{{ $insight['title'] }}</h6>
                    <p class="insight-message">{{ $insight['message'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection

@php
    $currentYear = date('Y');
    $playerRegistrations = [];
    $programCreations = [];

    for ($month = 1; $month <= 12; $month++) {
        $startDate = date("$currentYear-$month-01");
        $endDate = date("$currentYear-$month-t");
        $playerRegistrations[] = \App\Models\Player::whereBetween('created_at', [$startDate, $endDate])->count();
        $programCreations[] = \App\Models\Program::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
@endphp

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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .status-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 14px;
        height: 14px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    /* Modern Search */
    .search-input-modern {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input-modern::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .search-input-modern:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
    }

    .search-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-xl);
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;
    }

    .search-loading {
        padding: 20px;
        text-align: center;
        color: var(--text-secondary);
    }

    .spinner-modern {
        width: 20px;
        height: 20px;
        border: 3px solid #f3f4f6;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        margin-right: 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Metric Cards */
    .metric-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .gradient-blue::before { background: linear-gradient(90deg, #3b82f6, #8b5cf6); }
    .gradient-green::before { background: linear-gradient(90deg, #10b981, #14b8a6); }
    .gradient-purple::before { background: linear-gradient(90deg, #a855f7, #ec4899); }
    .gradient-orange::before { background: linear-gradient(90deg, #f97316, #fb923c); }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
    }

    .gradient-blue .metric-icon {
        background: linear-gradient(135deg, #dbeafe, #e0e7ff);
        color: #3b82f6;
    }

    .gradient-green .metric-icon {
        background: linear-gradient(135deg, #d1fae5, #ccfbf1);
        color: #10b981;
    }

    .gradient-purple .metric-icon {
        background: linear-gradient(135deg, #f3e8ff, #fce7f3);
        color: #a855f7;
    }

    .gradient-orange .metric-icon {
        background: linear-gradient(135deg, #ffedd5, #fed7aa);
        color: #f97316;
    }

    .metric-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .metric-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .metric-change {
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .metric-change.positive {
        background: #d1fae5;
        color: #059669;
    }

    .metric-change.negative {
        background: #fee2e2;
        color: #dc2626;
    }

    .metric-change.neutral {
        background: #f3f4f6;
        color: #6b7280;
    }

    .metric-chart {
        margin-top: 16px;
        height: 40px;
    }

    /* Modern Cards */
    .modern-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--shadow-md);
    }

    .card-header-modern {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: #fafbfc;
    }

    .card-title-modern {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-subtitle-modern {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 4px 0 0 0;
    }

    /* Activity Feed */
    .activity-feed-modern {
        max-height: 500px;
        overflow-y: auto;
    }

    .activity-item-modern {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.2s ease;
    }

    .activity-item-modern:hover {
        background: #f8fafc;
    }

    .activity-item-modern:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-meta {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .badge-modern {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    /* Section Headers */
    .section-header-modern {
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-subtitle {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 4px 0 0 0;
    }

    /* Action Cards */
    .action-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .action-card:hover {
        border-color: var(--primary);
        transform: translateX(4px);
        box-shadow: var(--shadow-md);
    }

    .action-card:hover::before {
        transform: scaleY(1);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .action-content {
        flex: 1;
    }

    .action-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .action-description {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .action-arrow {
        color: var(--text-secondary);
        font-size: 16px;
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s ease;
    }

    .action-card:hover .action-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    /* Performer Items */
    .performer-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .performer-item:last-child {
        border-bottom: none;
    }

    .performer-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .performer-info {
        flex: 1;
    }

    .performer-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .performer-position {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .performer-rating {
        text-align: right;
    }

    .rating-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--warning);
    }

    .rating-max {
        font-size: 13px;
        color: var(--text-secondary);
    }

    /* Distribution Items */
    .distribution-item {
        margin-bottom: 16px;
    }

    .distribution-item:last-child {
        margin-bottom: 0;
    }

    .distribution-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .distribution-label i {
        color: var(--info);
        font-size: 14px;
    }

    .distribution-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .distribution-bar {
        height: 6px;
        background: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
    }

    .distribution-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--info), var(--purple));
        border-radius: 3px;
        transition: width 0.6s ease;
    }

    /* Compliance Card */
    .compliance-card .card-body {
        padding: 24px;
    }

    .compliance-metric {
        text-align: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .compliance-metric:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .compliance-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dbeafe, #e0e7ff);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .compliance-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .compliance-label {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .btn-modern {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--purple));
        color: white;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    }

    /* Health Items */
    .health-item {
        margin-bottom: 20px;
    }

    .health-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .health-label {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .health-status {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 12px;
    }

    .health-status.success {
        background: #d1fae5;
        color: #059669;
    }

    .health-status.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .health-bar {
        height: 8px;
        background: #f1f5f9;
        border-radius: 4px;
        overflow: hidden;
    }

    .health-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .health-fill.success {
        background: linear-gradient(90deg, #10b981, #14b8a6);
    }

    .health-fill.warning {
        background: linear-gradient(90deg, #f59e0b, #f97316);
    }

    /* AI Insights */
    .insight-card {
        padding: 20px;
        border-radius: 14px;
        display: flex;
        gap: 16px;
        border: 2px solid;
        transition: all 0.3s ease;
    }

    .insight-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .insight-primary {
        background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
        border-color: #93c5fd;
    }

    .insight-success {
        background: linear-gradient(135deg, #d1fae5 0%, #ccfbf1 100%);
        border-color: #6ee7b7;
    }

    .insight-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
        border-color: #fcd34d;
    }

    .insight-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-color: #fca5a5;
    }

    .insight-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        background: white;
    }

    .insight-primary .insight-icon { color: var(--primary); }
    .insight-success .insight-icon { color: var(--success); }
    .insight-warning .insight-icon { color: var(--warning); }
    .insight-danger .insight-icon { color: var(--danger); }

    .insight-content {
        flex: 1;
    }

    .insight-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .insight-message {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    /* Empty States */
    .empty-state, .empty-state-mini {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-state-mini {
        padding: 20px;
        font-size: 13px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .empty-state i, .empty-state-mini i {
        font-size: 32px;
        opacity: 0.3;
        margin-bottom: 12px;
    }

    .empty-state-mini i {
        font-size: 24px;
        margin-bottom: 4px;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .metric-value {
            font-size: 28px;
        }

        .dashboard-hero .col-lg-6:last-child {
            margin-top: 20px;
        }
    }

    @media (max-width: 768px) {
        .metric-card {
            padding: 20px;
        }

        .metric-value {
            font-size: 24px;
        }

        .action-card {
            padding: 16px;
        }

        .card-header-modern {
            padding: 16px 20px;
        }

        .activity-item-modern {
            padding: 12px 20px;
        }

        .section-title {
            font-size: 16px;
        }
    }

    /* Scrollbar Styling */
    .activity-feed-modern::-webkit-scrollbar,
    .search-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .activity-feed-modern::-webkit-scrollbar-track,
    .search-dropdown::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .activity-feed-modern::-webkit-scrollbar-thumb,
    .search-dropdown::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .activity-feed-modern::-webkit-scrollbar-thumb:hover,
    .search-dropdown::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Animation Utilities */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';

    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'Player Registrations',
                data: @json($playerRegistrations),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }, {
                label: 'Programs Created',
                data: @json($programCreations),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 13, weight: 600 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderRadius: 8,
                    titleFont: { size: 13, weight: 600 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f1f5f9' },
                    ticks: { padding: 10 }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Development Stages Chart
    const developmentCtx = document.getElementById('developmentChart').getContext('2d');
    new Chart(developmentCtx, {
        type: 'doughnut',
        data: {
            labels: @json($developmentStages->pluck('development_stage')->toArray()),
            datasets: [{
                data: @json($developmentStages->pluck('count')->toArray()),
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#a855f7'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12, weight: 600 }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Sparkline Charts
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        scales: { x: { display: false }, y: { display: false } },
        elements: { line: { borderWidth: 2 }, point: { radius: 0 } }
    };

    // Players Sparkline
    new Chart(document.getElementById('playersSparkline'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                data: @json($playerRegistrations),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: sparklineOptions
    });

    // Programs Sparkline
    new Chart(document.getElementById('programsSparkline'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                data: @json($programCreations),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: sparklineOptions
    });

    // Partners Sparkline
    new Chart(document.getElementById('partnersSparkline'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                data: [5, 8, 6, 9, 12, 15, 13, 18, 22, 25, 28, 30],
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: sparklineOptions
    });

    // News Sparkline
    new Chart(document.getElementById('newsSparkline'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                data: [2, 4, 3, 6, 5, 8, 7, 9, 11, 10, 13, 15],
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: sparklineOptions
    });

    // Global Search
    const searchInput = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    const searchResultsContent = document.getElementById('searchResultsContent');
    const searchLoading = document.getElementById('searchLoading');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length === 0) {
            searchResults.style.display = 'none';
            return;
        }

        if (query.length < 2) return;

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    function performSearch(query) {
        searchResults.style.display = 'block';
        searchLoading.style.display = 'block';
        searchResultsContent.innerHTML = '';

        // Simulate API call
        setTimeout(() => {
            searchLoading.style.display = 'none';
            const mockResults = {
                players: [
                    { id: 1, name: 'John Doe', position: 'Forward' },
                    { id: 2, name: 'Jane Smith', position: 'Midfielder' }
                ].filter(p => p.name.toLowerCase().includes(query.toLowerCase())),
                programs: [
                    { id: 1, name: 'Elite Youth Program' },
                    { id: 2, name: 'Senior Development' }
                ].filter(p => p.name.toLowerCase().includes(query.toLowerCase()))
            };

            displaySearchResults(mockResults);
        }, 500);
    }

    function displaySearchResults(data) {
        let html = '';

        if (data.players.length > 0) {
            html += '<div class="p-3 bg-light fw-semibold small">Players</div>';
            html += data.players.map(p => `
                <div class="p-3 border-bottom" style="cursor: pointer;" onclick="window.location.href='/admin/players/${p.id}'">
                    <div class="fw-semibold">${p.name}</div>
                    <small class="text-muted">${p.position}</small>
                </div>
            `).join('');
        }

        if (data.programs.length > 0) {
            html += '<div class="p-3 bg-light fw-semibold small">Programs</div>';
            html += data.programs.map(p => `
                <div class="p-3 border-bottom" style="cursor: pointer;" onclick="window.location.href='/admin/programs/${p.id}'">
                    <div class="fw-semibold">${p.name}</div>
                </div>
            `).join('');
        }

        if (!data.players.length && !data.programs.length) {
            html = '<div class="p-4 text-center text-muted">No results found</div>';
        }

        searchResultsContent.innerHTML = html;
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container-modern')) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
@endpush
