@extends('layouts.academy')

@section('title', $player->name . ' - Career - Vipers Academy')

@section('content')
@push('styles')
<style>
/* ========================================
    HIGH-DENSITY CAREER PROGRESSION
    ======================================== */


/* ========================================
    COMPACT CAREER HEADER
    ======================================== */

.compact-career-header {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.career-header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.compact-career-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--primary-red);
}

.compact-career-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.career-avatar-placeholder {
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

.career-identity {
    flex: 1;
    min-width: 0;
}

.compact-career-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.career-position-badge {
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

.career-meta-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin-top: 0.5rem;
}

.career-category-badge {
    padding: 0.125rem 0.375rem;
    background: var(--neutral-100);
    color: var(--neutral-700);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.career-focus-badge {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.career-focus-badge svg {
    width: 0.75rem;
    height: 0.75rem;
}

/* ========================================
    COMPACT NAVIGATION
    ======================================== */

.compact-career-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.compact-career-nav-link {
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

.compact-career-nav-link:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.compact-career-nav-link.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* ========================================
    CAREER DASHBOARD
    ======================================== */

.career-dashboard {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Career Stats Overview */
.career-stats-overview {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.career-stats-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 0.75rem 0;
}

.career-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 0.75rem;
}

.career-stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.career-stat-label {
    font-size: 0.75rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.career-stat-value {
    font-size: 1rem;
    color: var(--neutral-900);
    font-weight: 700;
}

/* Career Progression */
.career-progression-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.career-progression-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
    cursor: pointer;
    transition: var(--transition);
}

.career-progression-header:hover {
    background: var(--neutral-100);
}

.career-progression-title-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.career-progression-icon {
    width: 1rem;
    height: 1rem;
    color: var(--primary-red);
}

.career-progression-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.career-progression-toggle {
    width: 1rem;
    height: 1rem;
    color: var(--neutral-500);
    transition: var(--transition);
}

.career-progression-section.expanded .career-progression-toggle {
    transform: rotate(180deg);
}

.career-progression-content {
    padding: 1rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.career-progression-section.expanded .career-progression-content {
    max-height: 1000px;
}

/* Career Timeline */
.career-timeline {
    position: relative;
    padding-left: 2rem;
}

.career-timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--neutral-200);
}

.career-milestone {
    position: relative;
    padding: 1rem 0;
    border-bottom: 1px solid var(--neutral-100);
}

.career-milestone:last-child {
    border-bottom: none;
}

.career-milestone-dot {
    position: absolute;
    left: -2.25rem;
    top: 1rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: var(--primary-red);
    border: 3px solid white;
    box-shadow: var(--shadow-sm);
}

.career-milestone-date {
    font-size: 0.75rem;
    color: var(--neutral-500);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.career-milestone-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
}

.career-milestone-description {
    font-size: 0.8125rem;
    color: var(--neutral-700);
    margin: 0;
}

/* Future Goals */
.future-goals-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.future-goals-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 0.75rem 0;
}

.future-goals-content {
    font-size: 0.875rem;
    color: var(--neutral-700);
    line-height: 1.5;
}

/* ========================================
    MOBILE OPTIMIZATIONS
    ======================================== */

/* Mobile First */
@media (max-width: 767px) {
    .compact-career-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .career-header-main {
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .compact-career-avatar {
        width: 3rem;
        height: 3rem;
    }

    .compact-career-name {
        font-size: 1.125rem;
    }

    .career-meta-compact {
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .compact-career-nav {
        gap: 0.125rem;
        padding-bottom: 0.5rem;
    }

    .compact-career-nav-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .career-stats-overview {
        padding: 0.75rem;
    }

    .career-stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .career-stat-item {
        gap: 0.125rem;
    }

    .career-stat-label {
        font-size: 0.6875rem;
    }

    .career-stat-value {
        font-size: 0.9375rem;
    }

    .career-progression-header {
        padding: 0.75rem;
    }

    .career-progression-content {
        padding: 0.75rem;
    }

    .career-timeline {
        padding-left: 1.5rem;
    }

    .career-milestone {
        padding: 0.75rem 0;
    }

    .career-milestone-dot {
        left: -1.75rem;
        top: 0.75rem;
        width: 0.875rem;
        height: 0.875rem;
    }

    .future-goals-section {
        padding: 0.75rem;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .compact-career-header {
        padding: 0.75rem;
    }

    .career-header-main {
        gap: 0.5rem;
    }

    .compact-career-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .compact-career-name {
        font-size: 1rem;
    }

    .career-position-badge {
        font-size: 0.6875rem;
        padding: 0.1875rem 0.375rem;
    }

    .career-meta-compact {
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .career-stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush

<div class="compact-player-container">
    <!-- Compact Career Header -->
    <div class="compact-career-header">
        <div class="career-header-main">
            <div class="compact-career-avatar">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="career-avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="career-identity">
                <h1 class="compact-career-name">{{ $player->name }}</h1>
                <div class="career-position-badge">{{ ucfirst($player->position) }}</div>
                <div class="career-meta-compact">
                    <span class="career-category-badge">{{ $player->standardized_category }}</span>
                    <div class="career-focus-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Career Path</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Navigation -->
    <nav class="compact-career-nav">
        <a href="{{ route('players.overview', $player->id) }}" class="compact-career-nav-link">Overview</a>
        <a href="{{ route('players.statistics', $player->id) }}" class="compact-career-nav-link">Statistics</a>
        <a href="{{ route('players.ai-insights', $player->id) }}" class="compact-career-nav-link">AI Insights</a>
        <a href="{{ route('players.biography', $player->id) }}" class="compact-career-nav-link">Biography</a>
        <a href="{{ route('players.career', $player->id) }}" class="compact-career-nav-link active">Career</a>
    </nav>

    <!-- Career Dashboard -->
    <div class="career-dashboard">
        <!-- Career Stats Overview -->
        <div class="career-stats-overview">
            <h2 class="career-stats-title">Career Statistics</h2>
            <div class="career-stats-grid">
                <div class="career-stat-item">
                    <span class="career-stat-label">Position</span>
                    <span class="career-stat-value">{{ ucfirst($player->position ?? 'N/A') }}</span>
                </div>
                <div class="career-stat-item">
                    <span class="career-stat-label">Jersey</span>
                    <span class="career-stat-value">{{ $player->jersey_number ?? 'N/A' }}</span>
                </div>
                <div class="career-stat-item">
                    <span class="career-stat-label">Category</span>
                    <span class="career-stat-value">{{ $player->standardized_category }}</span>
                </div>
                <div class="career-stat-item">
                    <span class="career-stat-label">Goals</span>
                    <span class="career-stat-value">{{ $player->goals ?? 0 }}</span>
                </div>
                <div class="career-stat-item">
                    <span class="career-stat-label">Assists</span>
                    <span class="career-stat-value">{{ $player->assists ?? 0 }}</span>
                </div>
                <div class="career-stat-item">
                    <span class="career-stat-label">Apps</span>
                    <span class="career-stat-value">{{ $player->appearances ?? 0 }}</span>
                </div>
                @if($player->joined_date)
                <div class="career-stat-item">
                    <span class="career-stat-label">Joined</span>
                    <span class="career-stat-value">{{ $player->joined_date->format('M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Career Progression Timeline -->
        <div class="career-progression-section" id="progression-section">
            <div class="career-progression-header" onclick="toggleCareerSection('progression-section')">
                <div class="career-progression-title-section">
                    <svg class="career-progression-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="career-progression-title">Career Timeline</h3>
                </div>
                <svg class="career-progression-toggle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="career-progression-content">
                <div class="career-timeline">
                    @if($player->joined_date)
                    <div class="career-milestone">
                        <div class="career-milestone-dot"></div>
                        <div class="career-milestone-date">{{ $player->joined_date->format('M Y') }}</div>
                        <div class="career-milestone-title">Joined Vipers Academy</div>
                        <div class="career-milestone-description">
                            Started journey as a {{ $player->standardized_category }} player in the {{ $player->position }} position.
                        </div>
                    </div>
                    @endif

                    @if($player->appearances > 0)
                    <div class="career-milestone">
                        <div class="career-milestone-dot"></div>
                        <div class="career-milestone-date">Current</div>
                        <div class="career-milestone-title">Active Player</div>
                        <div class="career-milestone-description">
                            {{ $player->appearances }} appearances, {{ $player->goals }} goals, {{ $player->assists }} assists.
                            @if($player->jersey_number) Wearing jersey #{{ $player->jersey_number }}. @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Future Goals -->
        @if($player->career_aspiration)
        <div class="future-goals-section">
            <h2 class="future-goals-title">Career Aspirations</h2>
            <div class="future-goals-content">
                {{ $player->career_aspiration }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Toggle career sections
function toggleCareerSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.toggle('expanded');
    }
}

// Initialize sections - expand progression on desktop
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth >= 768) {
        const progressionSection = document.getElementById('progression-section');
        if (progressionSection) {
            progressionSection.classList.add('expanded');
        }
    }
});
</script>
@endpush

