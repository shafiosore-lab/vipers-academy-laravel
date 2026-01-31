@extends('layouts.admin')

@section('title', __('Dashboard - Vipers Academy Admin'))

@section('breadcrumb')
<nav aria-label="{{ __('Breadcrumb navigation') }}">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-th-large me-1" aria-hidden="true"></i>{{ __('Dashboard') }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="dashboard-container">
    {{-- Welcome Header --}}
    <div class="welcome-section">
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="welcome-text">
            <h1>{{ __('Welcome back') }}, {{ Auth::user()->name }}! 👋</h1>
            <p>{{ __("Here's what's happening with your academy today") }}</p>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="{{ __('Searching...') }}">
        </div>
        <div class="current-date">
            <span>{{ now()->format('M d, Y') }}</span>
            <span>{{ now()->format('l') }}</span>
        </div>
    </div>

    {{-- Statistics Table --}}
    <div class="stats-table-container">
        <h2>📊 {{ __('Academy Statistics') }}</h2>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>{{ __('Metric') }}</th>
                    <th>{{ __('Value') }}</th>
                    <th>{{ __('Change') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="metric-info">
                            <span class="metric-icon">👥</span>
                            <span class="metric-name">{{ __('Total Players') }}</span>
                        </div>
                    </td>
                    <td class="stat-value">{{ $totalPlayers }}</td>
                    <td><span class="stat-change positive">📈 {{ $playerGrowth }}% {{ __('vs last month') }}</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="metric-info">
                            <span class="metric-icon">🎯</span>
                            <span class="metric-name">{{ __('Active Programs') }}</span>
                        </div>
                    </td>
                    <td class="stat-value">{{ $totalPrograms }}</td>
                    <td><span class="stat-change positive">📈 {{ $programGrowth }}% {{ __('growth') }}</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="metric-info">
                            <span class="metric-icon">🤝</span>
                            <span class="metric-name">{{ __('Partnerships') }}</span>
                        </div>
                    </td>
                    <td class="stat-value">{{ $totalPartners }}</td>
                    <td><span class="stat-change">⏳ {{ $pendingPartners }} {{ __('pending') }}</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="metric-info">
                            <span class="metric-icon">📰</span>
                            <span class="metric-name">{{ __('News Articles') }}</span>
                        </div>
                    </td>
                    <td class="stat-value">{{ $totalNews }}</td>
                    <td><span class="stat-change positive">📈 {{ $newsGrowth }}% {{ __('engagement') }}</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="metric-info">
                            <span class="metric-icon">📝</span>
                            <span class="metric-name">{{ __('Program Enrollments') }}</span>
                        </div>
                    </td>
                    <td class="stat-value">{{ \App\Models\Enrollment::count() }}</td>
                    <td><span class="stat-change">📈 {{ \App\Models\Enrollment::whereDate('created_at', '>=', now()->startOfWeek())->count() }} {{ __('this week') }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Performance Analytics --}}
    <div class="analytics-section">
        <div class="section-header">
            <h2>📊 {{ __('Performance Analytics') }}</h2>
            <p>{{ __("Track your academy's growth over time") }}</p>
        </div>
        <div class="analytics-filters">
            <button class="filter-btn">{{ __('12M') }}</button>
            <button class="filter-btn">{{ __('6M') }}</button>
            <button class="filter-btn active">{{ __('30D') }}</button>
        </div>
        <div class="analytics-chart">
            <canvas id="analyticsChart"></canvas>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="recent-activity">
        <div class="section-header">
            <h2>🔔 {{ __('Recent Activity') }}</h2>
            <p>{{ __('Latest updates from your academy') }}</p>
        </div>

        <div class="activity-list">
            @forelse($recentPartners as $partner)
                <div class="activity-item">
                    <div class="activity-icon partnership">🤝</div>
                    <div class="activity-content">
                        <h4>{{ $partner->name }}</h4>
                        <p>{{ __('New partnership') }} • {{ $partner->created_at->diffForHumans() }}</p>
                    </div>
                    @if($partner->status === 'pending')
                        <span class="badge badge-pending">{{ __('Pending') }}</span>
                    @endif
                </div>
            @empty
                <p class="no-data">{{ __('No recent partnerships') }}</p>
            @endforelse

            @forelse($recentPlayers->take(3) as $player)
                <div class="activity-item">
                    <div class="activity-icon player">👤</div>
                    <div class="activity-content">
                        <h4>{{ $player->first_name }} {{ $player->last_name }}</h4>
                        <p>{{ __('New registration') }} • {{ $player->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="no-data">{{ __('No recent registrations') }}</p>
            @endforelse

            @php
                $recentNews = \App\Models\Blog::latest()->take(2)->get();
            @endphp

            @forelse($recentNews as $news)
                <div class="activity-item">
                    <div class="activity-icon news">📰</div>
                    <div class="activity-content">
                        <h4>{{ Str::limit($news->title, 30) }}</h4>
                        <p>{{ __('Published') }} • {{ $news->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="no-data">{{ __('No recent news') }}</p>
            @endforelse
        </div>
    </div>

    {{-- Document Management --}}
    <div class="document-management">
        <div class="section-header">
            <h2>📄 {{ __('Document Management') }}</h2>
            <p>{{ __('Academy policies, regulations, and compliance materials') }}</p>
        </div>

        <div class="document-categories">
            <div class="doc-category">
                <div class="doc-icon">🛡️</div>
                <div class="doc-info">
                    <span class="doc-count">
                        {{ \App\Models\Document::where('category', 'safety_protection')->active()->count() }}
                    </span>
                    <h4>{{ __('Safety Policies') }}</h4>
                    <p>{{ __('Child protection, incident reporting, and safety guidelines') }}</p>
                </div>
            </div>

            <div class="doc-category">
                <div class="doc-icon">📝</div>
                <div class="doc-info">
                    <span class="doc-count">
                        {{ \App\Models\Document::where('category', 'contracts_agreements')->active()->count() }}
                    </span>
                    <h4>{{ __('Contracts & Agreements') }}</h4>
                    <p>{{ __('Registration forms, terms, and legal agreements') }}</p>
                </div>
            </div>

            <div class="doc-category">
                <div class="doc-icon">⚠️</div>
                <div class="doc-info">
                    <span class="doc-count">
                        {{ \App\Models\Document::where('is_mandatory', true)->active()->count() }}
                    </span>
                    <h4>{{ __('Mandatory Documents') }}</h4>
                    <p>{{ __('Required documentation for compliance') }}</p>
                </div>
            </div>

            @php
                $expiringSoon = \App\Models\Document::whereNotNull('expiry_days')->active()->count();
            @endphp

            <div class="doc-category alert">
                <div class="doc-icon">🔔</div>
                <div class="doc-info">
                    <span class="doc-count">{{ $expiringSoon }}</span>
                    <h4>{{ __('Documents to Review') }}</h4>
                    <p>{{ __('Documents requiring periodic renewal') }}</p>
                </div>
            </div>
        </div>

        <div class="document-actions">
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">{{ __('Create Document') }}</a>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">{{ __('Manage Documents') }}</a>
            <a href="{{ route('admin.compliance.report') }}" class="btn btn-secondary">{{ __('Compliance Report') }}</a>
        </div>
    </div>

    {{-- Usage Statistics --}}
    <div class="usage-stats">
        <h3>📊 {{ __('Usage Statistics') }}</h3>
        <div class="stats-row">
            <div class="usage-stat">
                <span class="usage-value">
                    @try
                        {{ \App\Models\Document::sum(\DB::raw('COALESCE((SELECT SUM(download_count) FROM user_documents ud WHERE ud.document_id = documents.id), 0)')) }}
                    @catch
                        0
                    @endtry
                </span>
                <span class="usage-label">{{ __('Total Downloads') }}</span>
            </div>

            <div class="usage-stat">
                <span class="usage-value">
                    @try
                        {{ \App\Models\Document::sum(\DB::raw('COALESCE((SELECT COUNT(*) FROM user_documents ud WHERE ud.document_id = documents.id AND ud.status = "signed"), 0)')) }}
                    @catch
                        0
                    @endtry
                </span>
                <span class="usage-label">{{ __('Completed Signatures') }}</span>
            </div>

            <div class="usage-stat alert">
                <span class="usage-value">
                    @try
                        {{ \App\Models\UserDocument::expiringSoon(7)->count() }}
                    @catch
                        0
                    @endtry
                </span>
                <span class="usage-label">{{ __('Expiring This Week') }}</span>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="quick-actions">
        <h2>⚡ {{ __('Quick Actions') }}</h2>
        <p>{{ __('Frequently used features and shortcuts') }}</p>

        <div class="actions-grid">
            <a href="{{ route('admin.players.create') }}" class="action-card">
                <div class="action-icon">👤</div>
                <h4>{{ __('Add Player') }}</h4>
                <p>{{ __('Register new athlete') }}</p>
            </a>

            <a href="{{ route('admin.programs.create') }}" class="action-card">
                <div class="action-icon">🎯</div>
                <h4>{{ __('Add Program') }}</h4>
                <p>{{ __('Create training program') }}</p>
            </a>

            <a href="{{ route('admin.documents.index') }}" class="action-card">
                <div class="action-icon">📄</div>
                <h4>{{ __('Manage Documents') }}</h4>
                <p>{{ __('Policies & regulations') }}</p>
            </a>

            <a href="{{ route('admin.blog.create') }}" class="action-card">
                <div class="action-icon">📰</div>
                <h4>{{ __('Add Blog') }}</h4>
                <p>{{ __('Publish academy blog') }}</p>
            </a>

            <a href="{{ route('admin.website-players.create') }}" class="action-card">
                <div class="action-icon">🖼️</div>
                <h4>{{ __('Add Website Player') }}</h4>
                <p>{{ __('Upload player images') }}</p>
            </a>

            <a href="{{ route('admin.gallery.create') }}" class="action-card">
                <div class="action-icon">📸</div>
                <h4>{{ __('Add Gallery') }}</h4>
                <p>{{ __('Upload photos') }}</p>
            </a>
        </div>
    </div>

    {{-- Additional Sections Grid --}}
    <div class="dashboard-grid">
        {{-- Development Stages --}}
        <div class="dashboard-widget">
            <h3>📈 {{ __('Development Stages') }}</h3>
            <div class="widget-content">
                {{-- Development stages content --}}
            </div>
        </div>

        {{-- Top Performers --}}
        <div class="dashboard-widget">
            <h3>🏆 {{ __('Top Performers') }}</h3>
            <div class="performers-list">
                @forelse($topPerformers as $performer)
                    <div class="performer-item">
                        <div class="performer-avatar">
                            {{ strtoupper(substr($performer->first_name, 0, 1) . substr($performer->last_name, 0, 1)) }}
                        </div>
                        <div class="performer-info">
                            <h4>{{ $performer->first_name }} {{ $performer->last_name }}</h4>
                            <p>{{ $performer->position }}</p>
                        </div>
                        <div class="performer-rating">
                            <span class="rating-value">{{ $performer->performance_rating }}</span>
                            <span class="rating-max">/10</span>
                        </div>
                    </div>
                @empty
                    <p class="no-data">{{ __('No performance data available') }}</p>
                @endforelse
            </div>
        </div>

        {{-- School Distribution --}}
        <div class="dashboard-widget">
            <h3>🏫 {{ __('School Distribution') }}</h3>
            <div class="schools-list">
                @forelse($schools as $school)
                    <div class="school-item">
                        <div class="school-icon">🏫</div>
                        <div class="school-info">
                            <h4>{{ Str::limit($school->school_name, 20) }}</h4>
                            <p>{{ $school->student_count }} {{ __('students') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="no-data">{{ __('No school data available') }}</p>
                @endforelse
            </div>
        </div>

        {{-- FIFA Compliance Status --}}
        <div class="dashboard-widget compliance">
            <h3>⚽ {{ __('FIFA Compliance Status') }}</h3>
            <div class="compliance-stats">
                <div class="compliance-item">
                    <div class="compliance-icon">✅</div>
                    <div class="compliance-info">
                        <span class="compliance-count">
                            {{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}
                        </span>
                        <p>{{ __('FIFA Registered') }}</p>
                    </div>
                </div>

                <div class="compliance-item">
                    <div class="compliance-icon">🛡️</div>
                    <div class="compliance-info">
                        <span class="compliance-count">
                            {{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}
                        </span>
                        <p>{{ __('Safeguarding') }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.compliance.report') }}" class="btn btn-outline" download>
                📥 {{ __('Download Compliance Report') }}
            </a>
        </div>

        {{-- System Health --}}
        <div class="dashboard-widget system-health">
            <h3>💚 {{ __('System Health') }}</h3>
            <div class="health-items">
                <div class="health-item healthy">
                    <span class="health-label">{{ __('Database') }}</span>
                    <span class="health-status">{{ __('Healthy') }}</span>
                </div>
                <div class="health-item optimal">
                    <span class="health-label">{{ __('Storage') }}</span>
                    <span class="health-status">{{ __('Optimal') }}</span>
                </div>
                <div class="health-item good">
                    <span class="health-label">{{ __('Performance') }}</span>
                    <span class="health-status">{{ __('Good') }}</span>
                </div>
                <div class="health-item online">
                    <span class="health-label">{{ __('API Status') }}</span>
                    <span class="health-status">{{ __('Online') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('analyticsChart').getContext('2d');

    // Sample data - replace with real data from backend
    const data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Academy Growth',
            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
            borderColor: 'var(--primary)',
            backgroundColor: 'rgba(234, 28, 77, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    };

    new Chart(ctx, config);
});
</script>
@endpush

@push('styles')
<style>
/* ========================================
           DASHBOARD STYLES
           ======================================== */

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Welcome Section */
.welcome-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    box-shadow: var(--shadow-lg);
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 600;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.welcome-text h1 {
    font-size: 28px;
    margin-bottom: 0.5rem;
    color: var(--white);
}

.welcome-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.search-bar input {
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    color: var(--white);
    font-size: 14px;
    width: 250px;
    backdrop-filter: blur(10px);
}

.search-bar input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-bar input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
}

.current-date {
    text-align: right;
    font-size: 14px;
    opacity: 0.9;
}

.current-date span:first-child {
    display: block;
    font-weight: 600;
    font-size: 16px;
}

/* Statistics Table */
.stats-table-container {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.stats-table-container h2 {
    font-size: 24px;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.stats-table th {
    background: #f8f9fa;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-900);
    border-bottom: 2px solid var(--gray-300);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 12px;
}

.stats-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-300);
    vertical-align: middle;
}

.stats-table tbody tr:hover {
    background: #f8f9fa;
}

.metric-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.metric-icon {
    font-size: 24px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 8px;
    color: var(--white);
}

.metric-name {
    font-weight: 600;
    color: var(--gray-900);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
}

.stat-change {
    font-size: 12px;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.stat-change.positive {
    background: #dcfce7;
    color: var(--secondary);
}

/* Section Headers */
.section-header {
    margin-bottom: 1.5rem;
}

.section-header h2 {
    font-size: 24px;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header p {
    color: var(--gray-600);
    margin: 0;
    font-size: 14px;
}

/* Analytics Section */
.analytics-section {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.analytics-filters {
    display: flex;
    gap: 8px;
    margin-bottom: 1.5rem;
}

.filter-btn {
    padding: 8px 16px;
    border: 1px solid var(--gray-300);
    background: var(--white);
    color: var(--gray-600);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.filter-btn:hover {
    background: #f8f9fa;
    border-color: var(--primary);
    color: var(--primary);
}

.filter-btn.active {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

.analytics-chart {
    height: 300px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 16px;
    border: 2px dashed var(--gray-300);
}

/* Recent Activity */
.recent-activity {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: var(--transition);
}

.activity-item:hover {
    background: #e9ecef;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-right: 1rem;
}

.activity-icon.partnership {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
}

.activity-icon.player {
    background: linear-gradient(135deg, var(--secondary), #5cb85c);
    color: var(--white);
}

.activity-icon.news {
    background: linear-gradient(135deg, var(--accent), #f39c12);
    color: var(--white);
}

.activity-content h4 {
    font-size: 16px;
    margin-bottom: 4px;
    color: var(--gray-900);
}

.activity-content p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

.badge {
    margin-left: auto;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}

.badge-pending {
    background: #fef3c7;
    color: #d97706;
}

.no-data {
    text-align: center;
    color: var(--gray-600);
    font-style: italic;
    padding: 2rem;
}

/* Document Management */
.document-management {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.document-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.doc-category {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: var(--transition);
    border: 1px solid var(--gray-300);
}

.doc-category:hover {
    background: var(--white);
    box-shadow: var(--shadow);
}

.doc-category.alert {
    border-color: var(--danger);
    background: #fef2f2;
}

.doc-icon {
    font-size: 24px;
    margin-right: 1rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    border-radius: 8px;
    border: 1px solid var(--gray-300);
}

.doc-info {
    flex: 1;
}

.doc-count {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
    display: block;
    margin-bottom: 0.25rem;
}

.doc-info h4 {
    font-size: 16px;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.doc-info p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

.document-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Usage Statistics */
.usage-stats {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.usage-stats h3 {
    font-size: 20px;
    margin-bottom: 1.5rem;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 8px;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.usage-stat {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid var(--gray-300);
}

.usage-stat.alert {
    border-color: var(--danger);
    background: #fef2f2;
}

.usage-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    display: block;
    margin-bottom: 0.5rem;
}

.usage-label {
    font-size: 14px;
    color: var(--gray-600);
    font-weight: 500;
}

/* Quick Actions */
.quick-actions {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
    margin-bottom: 2rem;
}

.quick-actions h2 {
    font-size: 24px;
    margin-bottom: 0.5rem;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 8px;
}

.quick-actions > p {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
    font-size: 14px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: var(--gray-900);
    transition: var(--transition);
    border: 1px solid var(--gray-300);
}

.action-card:hover {
    background: var(--white);
    box-shadow: var(--shadow);
    transform: translateY(-2px);
    color: var(--primary);
}

.action-icon {
    font-size: 24px;
    margin-right: 1rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 8px;
    color: var(--white);
}

.action-card h4 {
    font-size: 16px;
    margin-bottom: 0.25rem;
}

.action-card p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.dashboard-widget {
    background: var(--white);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-300);
}

.dashboard-widget h3 {
    font-size: 18px;
    margin-bottom: 1rem;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 8px;
}

.widget-content {
    /* Placeholder for content */
}

.performers-list,
.schools-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.performer-item,
.school-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: var(--transition);
}

.performer-item:hover,
.school-item:hover {
    background: #e9ecef;
}

.performer-avatar,
.school-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-weight: 600;
    margin-right: 1rem;
}

.performer-info,
.school-info {
    flex: 1;
}

.performer-info h4,
.school-info h4 {
    font-size: 14px;
    margin-bottom: 4px;
    color: var(--gray-900);
}

.performer-info p,
.school-info p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
}

.performer-rating {
    text-align: right;
}

.rating-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    display: block;
}

.rating-max {
    font-size: 12px;
    color: var(--gray-600);
}

.compliance-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.compliance-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.compliance-icon {
    font-size: 20px;
    margin-right: 1rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--secondary), #5cb85c);
    border-radius: 8px;
    color: var(--white);
}

.compliance-info {
    flex: 1;
}

.compliance-count {
    font-size: 24px;
    font-weight: 700;
    color: var(--secondary);
    display: block;
    margin-bottom: 4px;
}

.compliance-info p {
    font-size: 14px;
    color: var(--gray-600);
    margin: 0;
}

.btn-outline {
    border: 1px solid var(--primary);
    background: transparent;
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition);
    margin-top: 1rem;
}

.btn-outline:hover {
    background: var(--primary);
    color: var(--white);
}

.system-health {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: var(--white);
}

.health-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.health-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.health-item.healthy {
    background: rgba(16, 185, 129, 0.2);
}

.health-item.optimal {
    background: rgba(59, 130, 246, 0.2);
}

.health-item.good {
    background: rgba(245, 158, 11, 0.2);
}

.health-item.online {
    background: rgba(16, 185, 129, 0.2);
}

.health-label {
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.health-status {
    font-size: 14px;
    font-weight: 600;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--primary);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
    color: var(--white);
}

.btn-secondary {
    background: #f8f9fa;
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.btn-secondary:hover {
    background: #e9ecef;
    color: var(--gray-900);
}

/* Responsive Design */
@media (max-width: 768px) {
    .welcome-section {
        flex-direction: column;
        text-align: center;
    }

    .search-bar input {
        width: 100%;
        max-width: 300px;
    }

    .current-date {
        text-align: center;
    }

    .stats-table-container {
        padding: 1.5rem;
    }

    .stats-table {
        font-size: 12px;
    }

    .stats-table th,
    .stats-table td {
        padding: 0.75rem 0.5rem;
    }

    .metric-info {
        gap: 0.5rem;
    }

    .metric-icon {
        width: 32px;
        height: 32px;
        font-size: 18px;
    }

    .stat-value {
        font-size: 20px;
    }

    .document-categories {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .stats-row {
        grid-template-columns: 1fr;
    }

    .analytics-filters {
        flex-wrap: wrap;
    }

    .document-actions {
        flex-direction: column;
    }

    .document-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .welcome-section {
        padding: 1.5rem;
    }

    .analytics-section,
    .recent-activity,
    .document-management,
    .usage-stats,
    .quick-actions,
    .dashboard-widget {
        padding: 1.5rem;
    }

    .stat-card {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
    }

    .action-card {
        flex-direction: column;
        text-align: center;
    }

    .activity-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endpush
