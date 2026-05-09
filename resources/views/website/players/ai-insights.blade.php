@extends('layouts.academy')

@section('title', $player->name . ' - AI Insights - Mumias Vipers Academy')

@section('content')
@push('styles')
<style>
/* ========================================
    HIGH-DENSITY AI INSIGHTS DASHBOARD
    ======================================== */


/* ========================================
    COMPACT AI HEADER
    ======================================== */

.compact-ai-header {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.ai-header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.compact-ai-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--primary-red);
}

.compact-ai-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ai-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
}

.ai-identity {
    flex: 1;
    min-width: 0;
}

.compact-ai-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ai-position-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    background: var(--primary-red);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.ai-meta-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin-top: 0.5rem;
}

.ai-category-badge {
    padding: 0.125rem 0.375rem;
    background: var(--neutral-100);
    color: var(--neutral-700);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.ai-powered-badge {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: linear-gradient(135deg, var(--secondary-green) 0%, #10b981 100%);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.ai-powered-badge svg {
    width: 0.75rem;
    height: 0.75rem;
}

/* ========================================
    COMPACT NAVIGATION
    ======================================== */

.compact-ai-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.compact-ai-nav-link {
    padding: 0.5rem 1rem;
    border: 1px solid var(--neutral-300);
    border-radius: 20px;
    background: white;
    color: var(--neutral-600);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    white-space: nowrap;
    transition: var(--transition);
    flex-shrink: 0;
}

.compact-ai-nav-link:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.compact-ai-nav-link.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* ========================================
    AI INSIGHTS DASHBOARD
    ======================================== */

.ai-insights-dashboard {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Data Freshness Indicator */
.data-freshness-indicator {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.freshness-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.freshness-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.freshness-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.freshness-indicator {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.freshness-indicator.fresh {
    background: var(--secondary-green);
    color: white;
}

.freshness-indicator.stale {
    background: #f59e0b;
    color: white;
}

.freshness-indicator.old {
    background: #ef4444;
    color: white;
}

.freshness-score {
    font-size: 0.875rem;
    color: var(--neutral-600);
}

/* AI Insights Grid */
.ai-insights-compact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

/* Insight Cards */
.ai-insight-compact-card {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.ai-insight-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
}

.ai-insight-title-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ai-insight-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--primary-red);
}

.ai-insight-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.ai-insight-confidence {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.ai-insight-confidence.high {
    background: var(--secondary-green);
    color: white;
}

.ai-insight-confidence.medium {
    background: #f59e0b;
    color: white;
}

.ai-insight-confidence.low {
    background: var(--neutral-400);
    color: white;
}

.ai-insight-content {
    padding: 1rem;
}

.ai-insight-text {
    font-size: 0.875rem;
    color: var(--neutral-700);
    line-height: 1.5;
    margin-bottom: 0.75rem;
}

.ai-insight-data {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.5rem;
}

.ai-data-item {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.ai-data-label {
    font-size: 0.75rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.ai-data-value {
    font-size: 0.875rem;
    color: var(--neutral-900);
    font-weight: 500;
}

/* ========================================
    MOBILE OPTIMIZATIONS
    ======================================== */

/* Mobile First */
@media (max-width: 767px) {
    .compact-ai-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .ai-header-main {
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .compact-ai-avatar {
        width: 3rem;
        height: 3rem;
    }

    .compact-ai-name {
        font-size: 1.125rem;
    }

    .ai-meta-compact {
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .compact-ai-nav {
        gap: 0.125rem;
        padding-bottom: 0.5rem;
    }

    .compact-ai-nav-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .ai-insights-compact-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .ai-insight-compact-card {
        border-radius: var(--border-radius);
    }

    .ai-insight-header {
        padding: 0.75rem;
    }

    .ai-insight-title {
        font-size: 0.9375rem;
    }

    .ai-insight-content {
        padding: 0.75rem;
    }

    .ai-insight-text {
        font-size: 0.8125rem;
    }

    .ai-insight-data {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.375rem;
    }

    .ai-data-item {
        gap: 0.0625rem;
    }

    .ai-data-label {
        font-size: 0.6875rem;
    }

    .ai-data-value {
        font-size: 0.8125rem;
    }

    .data-freshness-indicator {
        padding: 0.75rem;
    }

    .freshness-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .freshness-status {
        width: 100%;
        justify-content: space-between;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .compact-ai-header {
        padding: 0.75rem;
    }

    .ai-header-main {
        gap: 0.5rem;
    }

    .compact-ai-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .compact-ai-name {
        font-size: 1rem;
    }

    .ai-position-badge {
        font-size: 0.6875rem;
        padding: 0.1875rem 0.375rem;
    }

    .ai-meta-compact {
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .ai-insight-data {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

<div class="compact-player-container">
    <!-- Compact AI Header -->
    <div class="compact-ai-header">
        <div class="ai-header-main">
            <div class="compact-ai-avatar">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="ai-avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="ai-identity">
                <h1 class="compact-ai-name">{{ $player->name }}</h1>
                <div class="ai-position-badge">{{ ucfirst($player->position) }}</div>
                <div class="ai-meta-compact">
                    <span class="ai-category-badge">{{ $player->standardized_category }}</span>
                    <div class="ai-powered-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <span>AI-Powered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Navigation -->
    <nav class="compact-ai-nav">
        <a href="{{ route('players.overview', $player->id) }}" class="compact-ai-nav-link">Overview</a>
        <a href="{{ route('players.statistics', $player->id) }}" class="compact-ai-nav-link">Statistics</a>
        <a href="{{ route('players.ai-insights', $player->id) }}" class="compact-ai-nav-link active">AI Insights</a>
        <a href="{{ route('players.biography', $player->id) }}" class="compact-ai-nav-link">Biography</a>
        <a href="{{ route('players.career', $player->id) }}" class="compact-ai-nav-link">Career</a>
    </nav>

    <!-- AI Insights Dashboard -->
    <div class="ai-insights-dashboard">
        <!-- Data Freshness Indicator -->
        @if(!empty($dataFreshness))
        <div class="data-freshness-indicator">
            <div class="freshness-header">
                <h2 class="freshness-title">Data Freshness</h2>
                <div class="freshness-status">
                    @php
                        $freshnessScore = $dataFreshness['freshness_score'] ?? 0;
                        $statusClass = $freshnessScore >= 80 ? 'fresh' : ($freshnessScore >= 50 ? 'stale' : 'old');
                        $statusLabel = $dataFreshness['needs_refresh'] ? 'Needs Update' : 'Up to Date';
                    @endphp
                    <span class="freshness-indicator {{ $statusClass }}">{{ $statusLabel }}</span>
                    <span class="freshness-score">{{ round($freshnessScore) }}%</span>
                </div>
            </div>
        </div>
        @endif

        <!-- AI Insights Grid -->
        <div class="ai-insights-compact-grid">
            @forelse($groupedInsights ?? [] as $type => $insight)
            <div class="ai-insight-compact-card">
                <div class="ai-insight-header">
                    <div class="ai-insight-title-section">
                        @switch($type)
                            @case('strength')
                                <svg class="ai-insight-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <h3 class="ai-insight-title">Strengths</h3>
                                @break
                            @case('development')
                                <svg class="ai-insight-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <h3 class="ai-insight-title">Development Areas</h3>
                                @break
                            @case('trend')
                                <svg class="ai-insight-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="ai-insight-title">Performance Trend</h3>
                                @break
                            @case('style')
                                <svg class="ai-insight-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="ai-insight-title">Playing Style</h3>
                                @break
                            @default
                                <svg class="ai-insight-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                <h3 class="ai-insight-title">{{ ucfirst(str_replace('_', ' ', $type)) }}</h3>
                        @endswitch
                    </div>

                    @if(isset($insight['confidence_level']))
                        @php
                            $confidenceClass = match($insight['confidence_level']) {
                                'very_high', 'high' => 'high',
                                'medium' => 'medium',
                                default => 'low'
                            };
                        @endphp
                        <span class="ai-insight-confidence {{ $confidenceClass }}">
                            {{ round($insight['confidence_score'] ?? 0) }}%
                        </span>
                    @endif
                </div>

                <div class="ai-insight-content">
                    <p class="ai-insight-text">{{ $insight['insight_content'] ?? 'No insight available' }}</p>

                    @if(isset($insight['insight_data']) && is_array($insight['insight_data']))
                    <div class="ai-insight-data">
                        @foreach(array_slice($insight['insight_data'], 0, 4) as $key => $value)
                        <div class="ai-data-item">
                            <span class="ai-data-label">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="ai-data-value">
                                @if(is_array($value))
                                    {{ implode(', ', array_slice($value, 0, 2)) }}
                                @elseif(is_numeric($value))
                                    {{ is_float($value) ? number_format($value, 1) : $value }}
                                @else
                                    {{ $value }}
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="ai-insight-compact-card">
                <div class="ai-insight-content">
                    <p class="ai-insight-text">No AI insights available. More game data is needed to generate comprehensive analysis.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

