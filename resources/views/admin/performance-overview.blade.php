@extends('layouts.admin')

@section('title', __('Performance Overview - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Performance Overview') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <span>{{ __('Performance Overview') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-2"></i>{{ __('Print Report') }}
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Dashboard') }}
        </a>
    </div>
</div>

<!-- Creative Performance Dashboard -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-lg border-0 performance-card">
            <div class="card-header bg-gradient-primary text-white border-0 position-relative overflow-hidden">
                <div class="header-pattern"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 fw-bold">
                            <i class="fas fa-chart-line me-3"></i>{{ __('Academy Performance Dashboard') }}
                        </h3>
                        <p class="mb-0 opacity-75">{{ __('Real-time analytics & insights') }}</p>
                    </div>
                    <div class="text-end">
                        <div class="live-indicator">
                            <i class="fas fa-circle text-success me-2"></i>
                            <small class="fw-bold">{{ __('LIVE DATA') }}</small>
                        </div>
                        <small class="text-white-50">{{ now()->format('M d, Y H:i') }}</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Key Metrics Row -->
                <div class="key-metrics-row">
                    <div class="metric-item primary-metric">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $totalPlayers }}</div>
                            <div class="metric-label">{{ __('Total Players') }}</div>
                            <div class="metric-trend">
                                <span class="badge bg-success badge-sm">{{ $activePlayers }} active</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-item success-metric">
                        <div class="metric-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $highPerformers }}</div>
                            <div class="metric-label">{{ __('High Performers') }}</div>
                            <div class="metric-trend">
                                <span class="badge bg-warning badge-sm">≥8.0 rating</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-item warning-metric">
                        <div class="metric-icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $totalGoals }}</div>
                            <div class="metric-label">{{ __('Total Goals') }}</div>
                            <div class="metric-trend">
                                <span class="badge bg-info badge-sm">{{ number_format($totalGoals / max($totalMatches, 1), 1) }}/match</span>
                            </div>
                        </div>
                    </div>

                    <div class="metric-item info-metric">
                        <div class="metric-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $activePartners }}</div>
                            <div class="metric-label">{{ __('Active Partners') }}</div>
                            <div class="metric-trend">
                                <span class="badge bg-primary badge-sm">{{ $totalPartners }} total</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Creative Table Section -->
                <div class="creative-table-section">
                    <div class="table-responsive">
                        <table class="creative-performance-table">
                            <thead>
                                <tr class="table-main-header">
                                    <th colspan="4" class="text-center">
                                        <i class="fas fa-trophy me-2"></i>
                                        {{ __('Detailed Performance Analytics') }}
                                        <i class="fas fa-trophy ms-2"></i>
                                    </th>
                                </tr>
                                <tr class="table-sub-header">
                                    <th class="category-header">
                                        <i class="fas fa-tags me-2"></i>{{ __('Performance Category') }}
                                    </th>
                                    <th class="metric-header text-center">
                                        <i class="fas fa-chart-bar me-2"></i>{{ __('Current Value') }}
                                    </th>
                                    <th class="status-header text-center">
                                        <i class="fas fa-info-circle me-2"></i>{{ __('Status & Trends') }}
                                    </th>
                                    <th class="insights-header">
                                        <i class="fas fa-lightbulb me-2"></i>{{ __('Key Insights') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PLAYER DEVELOPMENT SECTION -->
                                <tr class="category-section player-section">
                                    <td colspan="4" class="section-title">
                                        <div class="section-icon-wrapper">
                                            <div class="section-icon player-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="section-content">
                                                <h5 class="section-title-text">{{ __('Player Development & Registration') }}</h5>
                                                <p class="section-subtitle">{{ __('Academy enrollment, demographics & player progression') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="metric-row player-metric">
                                    <td class="metric-name-cell">
                                        <div class="metric-name-wrapper">
                                            <div class="metric-icon-small">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <div>
                                                <div class="metric-title">{{ __('Total Academy Players') }}</div>
                                                <div class="metric-subtitle">{{ __('Registered & active members') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-value-cell text-center">
                                        <div class="value-display primary-value">
                                            <span class="value-number">{{ $totalPlayers }}</span>
                                            <span class="value-unit">{{ __('players') }}</span>
                                        </div>
                                    </td>
                                    <td class="metric-status-cell text-center">
                                        <div class="status-indicators">
                                            <div class="status-badge success-badge">
                                                <i class="fas fa-check-circle me-1"></i>{{ $activePlayers }} {{ __('Active') }}
                                            </div>
                                            <div class="status-badge warning-badge">
                                                <i class="fas fa-clock me-1"></i>{{ $pendingPlayers }} {{ __('Pending') }}
                                            </div>
                                            <div class="status-badge secondary-badge">
                                                <i class="fas fa-pause-circle me-1"></i>{{ $inactivePlayers }} {{ __('Inactive') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-insights-cell">
                                        <div class="insights-content">
                                            <div class="insight-item">
                                                <i class="fas fa-chart-line insight-icon text-success"></i>
                                                <span>{{ __('Youth players represent') }} {{ number_format(($youthPlayers / max($totalPlayers, 1)) * 100, 1) }}% {{ __('of total enrollment') }}</span>
                                            </div>
                                            <div class="insight-item">
                                                <i class="fas fa-balance-scale insight-icon text-info"></i>
                                                <span>{{ __('Gender distribution:') }} {{ $malePlayers }} {{ __('male,') }} {{ $femalePlayers }} {{ __('female players') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- ATHLETIC PERFORMANCE SECTION -->
                                <tr class="category-section performance-section">
                                    <td colspan="4" class="section-title">
                                        <div class="section-icon-wrapper">
                                            <div class="section-icon performance-icon">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                            <div class="section-content">
                                                <h5 class="section-title-text">{{ __('Athletic Performance & Statistics') }}</h5>
                                                <p class="section-subtitle">{{ __('On-field achievements, ratings & competitive metrics') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="metric-row performance-metric">
                                    <td class="metric-name-cell">
                                        <div class="metric-name-wrapper">
                                            <div class="metric-icon-small">
                                                <i class="fas fa-futbol"></i>
                                            </div>
                                            <div>
                                                <div class="metric-title">{{ __('Goal Scoring Performance') }}</div>
                                                <div class="metric-subtitle">{{ __('Total goals & scoring efficiency') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-value-cell text-center">
                                        <div class="value-display danger-value">
                                            <span class="value-number">{{ $totalGoals }}</span>
                                            <span class="value-unit">{{ __('goals') }}</span>
                                        </div>
                                    </td>
                                    <td class="metric-status-cell text-center">
                                        <div class="status-indicators">
                                            <div class="status-badge info-badge">
                                                <i class="fas fa-target me-1"></i>{{ number_format($totalGoals / max($totalMatches, 1), 2) }} {{ __('goals/match') }}
                                            </div>
                                            <div class="status-badge success-badge">
                                                <i class="fas fa-calendar-check me-1"></i>{{ $totalMatches }} {{ __('matches played') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-insights-cell">
                                        <div class="insights-content">
                                            <div class="insight-item">
                                                <i class="fas fa-star insight-icon text-warning"></i>
                                                <span>{{ $highPerformers }} {{ __('players rated 8.0+ (high performers)') }}</span>
                                            </div>
                                            <div class="insight-item">
                                                <i class="fas fa-chart-area insight-icon text-primary"></i>
                                                <span>{{ __('Average performance rating:') }} {{ number_format($averageRating ?? 0, 2) }}/10</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- ACADEMIC PERFORMANCE SECTION -->
                                <tr class="category-section academic-section">
                                    <td colspan="4" class="section-title">
                                        <div class="section-icon-wrapper">
                                            <div class="section-icon academic-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="section-content">
                                                <h5 class="section-title-text">{{ __('Academic Excellence & Education') }}</h5>
                                                <p class="section-subtitle">{{ __('Educational achievements & academic performance tracking') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="metric-row academic-metric">
                                    <td class="metric-name-cell">
                                        <div class="metric-name-wrapper">
                                            <div class="metric-icon-small">
                                                <i class="fas fa-award"></i>
                                            </div>
                                            <div>
                                                <div class="metric-title">{{ __('Academic Performance Distribution') }}</div>
                                                <div class="metric-subtitle">{{ __('GPA & academic achievement levels') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-value-cell text-center">
                                        <div class="value-display success-value">
                                            <span class="value-number">{{ number_format($averageAcademicGPA ?? 0, 2) }}</span>
                                            <span class="value-unit">{{ __('GPA') }}</span>
                                        </div>
                                    </td>
                                    <td class="metric-status-cell text-center">
                                        <div class="status-indicators">
                                            <div class="status-badge success-badge">
                                                <i class="fas fa-star me-1"></i>{{ $excellentAcademic }} {{ __('Excellent') }}
                                            </div>
                                            <div class="status-badge info-badge">
                                                <i class="fas fa-thumbs-up me-1"></i>{{ $goodAcademic }} {{ __('Good') }}
                                            </div>
                                            <div class="status-badge warning-badge">
                                                <i class="fas fa-minus-circle me-1"></i>{{ $averageAcademic }} {{ __('Average') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-insights-cell">
                                        <div class="insights-content">
                                            <div class="insight-item">
                                                <i class="fas fa-brain insight-icon text-purple"></i>
                                                <span>{{ __('Academic-athletic balance is crucial for player development') }}</span>
                                            </div>
                                            <div class="insight-item">
                                                <i class="fas fa-balance-scale insight-icon text-indigo"></i>
                                                <span>{{ __('Strong correlation between academic success and on-field performance') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- BUSINESS DEVELOPMENT SECTION -->
                                <tr class="category-section business-section">
                                    <td colspan="4" class="section-title">
                                        <div class="section-icon-wrapper">
                                            <div class="section-icon business-icon">
                                                <i class="fas fa-handshake"></i>
                                            </div>
                                            <div class="section-content">
                                                <h5 class="section-title-text">{{ __('Business Development & Partnerships') }}</h5>
                                                <p class="section-subtitle">{{ __('Sponsorship relationships & business growth metrics') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="metric-row business-metric">
                                    <td class="metric-name-cell">
                                        <div class="metric-name-wrapper">
                                            <div class="metric-icon-small">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div>
                                                <div class="metric-title">{{ __('Partnership Network') }}</div>
                                                <div class="metric-subtitle">{{ __('Active business relationships & collaborations') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-value-cell text-center">
                                        <div class="value-display info-value">
                                            <span class="value-number">{{ $totalPartners }}</span>
                                            <span class="value-unit">{{ __('partners') }}</span>
                                        </div>
                                    </td>
                                    <td class="metric-status-cell text-center">
                                        <div class="status-indicators">
                                            <div class="status-badge success-badge">
                                                <i class="fas fa-play-circle me-1"></i>{{ $activePartners }} {{ __('Active') }}
                                            </div>
                                            <div class="status-badge warning-badge">
                                                <i class="fas fa-pause-circle me-1"></i>{{ $pendingPartners }} {{ __('Pending') }}
                                            </div>
                                            <div class="status-badge danger-badge">
                                                <i class="fas fa-stop-circle me-1"></i>{{ $inactivePartners }} {{ __('Inactive') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-insights-cell">
                                        <div class="insights-content">
                                            <div class="insight-item">
                                                <i class="fas fa-chart-pie insight-icon text-success"></i>
                                                <span>{{ __('Partnership conversion rate:') }} {{ number_format(($activePartners / max($totalPartners, 1)) * 100, 1) }}%</span>
                                            </div>
                                            <div class="insight-item">
                                                <i class="fas fa-handshake insight-icon text-primary"></i>
                                                <span>{{ __('Strong business relationships drive academy sustainability') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- PROGRAM MANAGEMENT SECTION -->
                                <tr class="category-section program-section">
                                    <td colspan="4" class="section-title">
                                        <div class="section-icon-wrapper">
                                            <div class="section-icon program-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="section-content">
                                                <h5 class="section-title-text">{{ __('Program Management & Operations') }}</h5>
                                                <p class="section-subtitle">{{ __('Training programs, schedules & operational efficiency') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="metric-row program-metric">
                                    <td class="metric-name-cell">
                                        <div class="metric-name-wrapper">
                                            <div class="metric-icon-small">
                                                <i class="fas fa-football-ball"></i>
                                            </div>
                                            <div>
                                                <div class="metric-title">{{ __('Training Program Portfolio') }}</div>
                                                <div class="metric-subtitle">{{ __('Active programs & curriculum offerings') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-value-cell text-center">
                                        <div class="value-display warning-value">
                                            <span class="value-number">{{ $totalPrograms }}</span>
                                            <span class="value-unit">{{ __('programs') }}</span>
                                        </div>
                                    </td>
                                    <td class="metric-status-cell text-center">
                                        <div class="status-indicators">
                                            <div class="status-badge success-badge">
                                                <i class="fas fa-play-circle me-1"></i>{{ $activePrograms }} {{ __('Active') }}
                                            </div>
                                            <div class="status-badge info-badge">
                                                <i class="fas fa-clock me-1"></i>{{ $upcomingPrograms }} {{ __('Upcoming') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="metric-insights-cell">
                                        <div class="insights-content">
                                            <div class="insight-item">
                                                <i class="fas fa-calendar-check insight-icon text-success"></i>
                                                <span>{{ __('Program utilization rate:') }} {{ number_format(($activePrograms / max($totalPrograms, 1)) * 100, 1) }}%</span>
                                            </div>
                                            <div class="insight-item">
                                                <i class="fas fa-users insight-icon text-primary"></i>
                                                <span>{{ __('Comprehensive program offerings support player development at all levels') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Header Styles */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .header-pattern {
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.15)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .live-indicator {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Key Metrics Row */
    .key-metrics-row {
        display: flex;
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid #e9ecef;
    }

    .metric-item {
        flex: 1;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }

    .metric-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .metric-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .primary-metric::before { background: linear-gradient(90deg, #667eea, #764ba2); }
    .success-metric::before { background: linear-gradient(90deg, #059669, #10b981); }
    .warning-metric::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .info-metric::before { background: linear-gradient(90deg, #0891b2, #06b6d4); }

    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .primary-metric .metric-icon { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
    .success-metric .metric-icon { background: linear-gradient(135deg, #059669, #10b981); color: white; }
    .warning-metric .metric-icon { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    .info-metric .metric-icon { background: linear-gradient(135deg, #0891b2, #06b6d4); color: white; }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .metric-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .metric-trend {
        margin-top: 0.5rem;
    }

    .badge-sm {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    /* Creative Table Styles */
    .creative-table-section {
        padding: 0;
    }

    .creative-performance-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .creative-performance-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-main-header {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 100%);
        color: white;
        border: none;
    }

    .table-main-header th {
        padding: 2rem 1rem;
        font-size: 1.25rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }

    .table-sub-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
    }

    .table-sub-header th {
        padding: 1.5rem 1rem;
        font-weight: 700;
        font-size: 0.875rem;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        border-right: 1px solid #dee2e6;
    }

    .table-sub-header th:last-child {
        border-right: none;
    }

    /* Category Sections */
    .category-section {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .category-section:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
        transform: scale(1.01);
    }

    .section-title {
        padding: 2rem 1.5rem;
        border: none;
    }

    .section-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .section-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        position: relative;
        overflow: hidden;
    }

    .section-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .player-icon { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
    .performance-icon { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    .academic-icon { background: linear-gradient(135deg, #059669, #10b981); color: white; }
    .business-icon { background: linear-gradient(135deg, #0891b2, #06b6d4); color: white; }
    .program-icon { background: linear-gradient(135deg, #8b5cf6, #a855f7); color: white; }

    .section-title-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .section-subtitle {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }

    /* Metric Rows */
    .metric-row {
        background: white;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .metric-row:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .metric-name-cell {
        padding: 1.5rem 1rem;
        border-right: 1px solid #f1f5f9;
    }

    .metric-name-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .metric-icon-small {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .player-metric .metric-icon-small { background: #fff5f0; color: #ea1c4d; }
    .performance-metric .metric-icon-small { background: #fef3c7; color: #f59e0b; }
    .academic-metric .metric-icon-small { background: #f0fdf4; color: #059669; }
    .business-metric .metric-icon-small { background: #f0f9ff; color: #0891b2; }
    .program-metric .metric-icon-small { background: #faf5ff; color: #8b5cf6; }

    .metric-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .metric-subtitle {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }

    .metric-value-cell {
        padding: 1.5rem 1rem;
        border-right: 1px solid #f1f5f9;
        min-width: 150px;
    }

    .value-display {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .value-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    .primary-value .value-number { color: #667eea; }
    .danger-value .value-number { color: #ea1c4d; }
    .success-value .value-number { color: #059669; }
    .info-value .value-number { color: #0891b2; }
    .warning-value .value-number { color: #f59e0b; }

    .value-unit {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-status-cell {
        padding: 1.5rem 1rem;
        border-right: 1px solid #f1f5f9;
        min-width: 200px;
    }

    .status-indicators {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: center;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .success-badge { background: #f0fdf4; color: #059669; border: 1px solid #bbf7d0; }
    .warning-badge { background: #fffbeb; color: #f59e0b; border: 1px solid #fde68a; }
    .info-badge { background: #f0f9ff; color: #0891b2; border: 1px solid #bae6fd; }
    .danger-badge { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .secondary-badge { background: #f8f9fa; color: #6c757d; border: 1px solid #e9ecef; }

    .metric-insights-cell {
        padding: 1.5rem 1rem;
        min-width: 250px;
    }

    .insights-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .insight-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .insight-icon {
        font-size: 1rem;
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .insight-item span {
        font-size: 0.85rem;
        color: #4a5568;
        line-height: 1.4;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .key-metrics-row {
            flex-direction: column;
            gap: 1rem;
        }

        .metric-item {
            width: 100%;
        }

        .section-icon-wrapper {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .metric-name-wrapper {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .status-indicators {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }

        .insights-content {
            gap: 0.5rem;
        }
    }

    @media (max-width: 768px) {
        .key-metrics-row {
            padding: 1rem;
        }

        .metric-item {
            padding: 1rem;
        }

        .metric-value {
            font-size: 2rem;
        }

        .table-main-header th,
        .table-sub-header th {
            padding: 1rem 0.5rem;
            font-size: 0.8rem;
        }

        .section-title {
            padding: 1rem;
        }

        .metric-name-cell,
        .metric-value-cell,
        .metric-status-cell,
        .metric-insights-cell {
            padding: 1rem 0.5rem;
        }

        .value-number {
            font-size: 1.5rem;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
    }

    /* Performance Card Shadow */
    .performance-card {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: none;
    }

    /* Animation for loading */
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

    .metric-row {
        animation: fadeInUp 0.6s ease-out;
    }

    .metric-row:nth-child(1) { animation-delay: 0.1s; }
    .metric-row:nth-child(2) { animation-delay: 0.2s; }
    .metric-row:nth-child(3) { animation-delay: 0.3s; }
    .metric-row:nth-child(4) { animation-delay: 0.4s; }
    .metric-row:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush
