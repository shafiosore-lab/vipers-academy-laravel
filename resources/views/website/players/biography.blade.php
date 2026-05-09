@extends('layouts.academy')

@section('title', $player->name . ' - Biography - Vipers Academy')

@section('content')
@push('styles')
<style>
/* ========================================
    HIGH-DENSITY BIOGRAPHY & BACKGROUND
    ======================================== */


/* ========================================
    COMPACT BIO HEADER
    ======================================== */

.compact-bio-header {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.bio-header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.compact-bio-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--primary-red);
}

.compact-bio-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.bio-avatar-placeholder {
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

.bio-identity {
    flex: 1;
    min-width: 0;
}

.compact-bio-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bio-position-badge {
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

.bio-meta-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin-top: 0.5rem;
}

.bio-category-badge {
    padding: 0.125rem 0.375rem;
    background: var(--neutral-100);
    color: var(--neutral-700);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.bio-focus-badge {
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

.bio-focus-badge svg {
    width: 0.75rem;
    height: 0.75rem;
}

/* ========================================
    COMPACT NAVIGATION
    ======================================== */

.compact-bio-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.compact-bio-nav-link {
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

.compact-bio-nav-link:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.compact-bio-nav-link.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* ========================================
    BIOGRAPHY DASHBOARD
    ======================================== */

.biography-dashboard {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Main Biography Section */
.main-biography-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.main-biography-content {
    font-size: 0.875rem;
    color: var(--neutral-700);
    line-height: 1.6;
}

/* Collapsible Background Sections */
.background-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.background-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
    cursor: pointer;
    transition: var(--transition);
}

.background-header:hover {
    background: var(--neutral-100);
}

.background-title-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.background-icon {
    width: 1rem;
    height: 1rem;
    color: var(--primary-red);
}

.background-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.background-toggle-icon {
    width: 1rem;
    height: 1rem;
    color: var(--neutral-500);
    transition: var(--transition);
}

.background-section.expanded .background-toggle-icon {
    transform: rotate(180deg);
}

.background-content {
    padding: 1rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.background-section.expanded .background-content {
    max-height: 1000px;
}

/* Background Data Grid */
.background-data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.75rem;
}

.background-data-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.background-data-label {
    font-size: 0.75rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.background-data-value {
    font-size: 0.875rem;
    color: var(--neutral-900);
    font-weight: 500;
}

/* Career Timeline */
.career-timeline {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.career-timeline-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 1rem 0;
}

.career-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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

/* ========================================
    MOBILE OPTIMIZATIONS
    ======================================== */

/* Mobile First */
@media (max-width: 767px) {
    .compact-bio-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .bio-header-main {
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .compact-bio-avatar {
        width: 3rem;
        height: 3rem;
    }

    .compact-bio-name {
        font-size: 1.125rem;
    }

    .bio-meta-compact {
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .compact-bio-nav {
        gap: 0.125rem;
        padding-bottom: 0.5rem;
    }

    .compact-bio-nav-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .main-biography-section {
        padding: 0.75rem;
    }

    .main-biography-content {
        font-size: 0.8125rem;
    }

    .background-header {
        padding: 0.75rem;
    }

    .background-content {
        padding: 0.75rem;
    }

    .background-data-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .background-data-item {
        gap: 0.125rem;
    }

    .background-data-label {
        font-size: 0.6875rem;
    }

    .background-data-value {
        font-size: 0.8125rem;
    }

    .career-timeline {
        padding: 0.75rem;
    }

    .career-stats-grid {
        grid-template-columns: repeat(2, 1fr);
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
}

/* Small Mobile */
@media (max-width: 480px) {
    .compact-bio-header {
        padding: 0.75rem;
    }

    .bio-header-main {
        gap: 0.5rem;
    }

    .compact-bio-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .compact-bio-name {
        font-size: 1rem;
    }

    .bio-position-badge {
        font-size: 0.6875rem;
        padding: 0.1875rem 0.375rem;
    }

    .bio-meta-compact {
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .background-data-grid {
        grid-template-columns: 1fr;
    }

    .career-stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

<div class="compact-player-container">
    <!-- Compact Bio Header -->
    <div class="compact-bio-header">
        <div class="bio-header-main">
            <div class="compact-bio-avatar">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="bio-avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="bio-identity">
                <h1 class="compact-bio-name">{{ $player->name }}</h1>
                <div class="bio-position-badge">{{ ucfirst($player->position) }}</div>
                <div class="bio-meta-compact">
                    <span class="bio-category-badge">{{ $player->standardized_category }}</span>
                    <div class="bio-focus-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Player Story</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Navigation -->
    <nav class="compact-bio-nav">
        <a href="{{ route('players.overview', $player->id) }}" class="compact-bio-nav-link">Overview</a>
        <a href="{{ route('players.statistics', $player->id) }}" class="compact-bio-nav-link">Statistics</a>
        <a href="{{ route('players.ai-insights', $player->id) }}" class="compact-bio-nav-link">AI Insights</a>
        <a href="{{ route('players.biography', $player->id) }}" class="compact-bio-nav-link active">Biography</a>
        <a href="{{ route('players.career', $player->id) }}" class="compact-bio-nav-link">Career</a>
    </nav>

    <!-- Biography Dashboard -->
    <div class="biography-dashboard">
        <!-- Main Biography -->
        <div class="main-biography-section">
            <div class="main-biography-content">
                @if($player->bio)
                    {{ $player->bio }}
                @else
                    {{ $player->name }} is a talented {{ strtolower($player->position) }} at Vipers Academy,
                    demonstrating exceptional skill and dedication on the field.
                    {{ explode(' ', $player->name)[0] }} has shown remarkable growth and continues to be an
                    integral part of the team's development. Known for strong work ethic and commitment to
                    excellence, {{ explode(' ', $player->name)[0] }} embodies the values and spirit of
                    Vipers Academy both on and off the pitch.
                @endif
            </div>
        </div>

        <!-- Personal Background -->
        <div class="background-section" id="personal-bg-section">
            <div class="background-header" onclick="toggleBackgroundSection('personal-bg-section')">
                <div class="background-title-section">
                    <svg class="background-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="background-title">Personal Background</h3>
                </div>
                <svg class="background-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="background-content">
                <div class="background-data-grid">
                    @if($player->date_of_birth)
                    <div class="background-data-item">
                        <span class="background-data-label">Date of Birth</span>
                        <span class="background-data-value">{{ $player->date_of_birth->format('M j, Y') }}</span>
                    </div>
                    @endif
                    @if($player->place_of_birth)
                    <div class="background-data-item">
                        <span class="background-data-label">Place of Birth</span>
                        <span class="background-data-value">{{ $player->place_of_birth }}</span>
                    </div>
                    @endif
                    @if($player->raised_where)
                    <div class="background-data-item">
                        <span class="background-data-label">Raised In</span>
                        <span class="background-data-value">{{ $player->raised_where }}</span>
                    </div>
                    @endif
                    @if($player->preferred_foot)
                    <div class="background-data-item">
                        <span class="background-data-label">Preferred Foot</span>
                        <span class="background-data-value">{{ ucfirst($player->preferred_foot) }}</span>
                    </div>
                    @endif
                    @if($player->height)
                    <div class="background-data-item">
                        <span class="background-data-label">Height</span>
                        <span class="background-data-value">{{ $player->height }}</span>
                    </div>
                    @endif
                    @if($player->weight)
                    <div class="background-data-item">
                        <span class="background-data-label">Weight</span>
                        <span class="background-data-value">{{ $player->weight }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Interests & Personality -->
        <div class="background-section" id="interests-section">
            <div class="background-header" onclick="toggleBackgroundSection('interests-section')">
                <div class="background-title-section">
                    <svg class="background-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="background-title">Interests & Personality</h3>
                </div>
                <svg class="background-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="background-content">
                <div class="background-data-grid">
                    @if($player->passion)
                    <div class="background-data-item">
                        <span class="background-data-label">Passion</span>
                        <span class="background-data-value">{{ $player->passion }}</span>
                    </div>
                    @endif
                    @if($player->favorite_thing_about_academy)
                    <div class="background-data-item">
                        <span class="background-data-label">Favorite About Academy</span>
                        <span class="background-data-value">{{ $player->favorite_thing_about_academy }}</span>
                    </div>
                    @endif
                    @if($player->favorite_player)
                    <div class="background-data-item">
                        <span class="background-data-label">Favorite Player</span>
                        <span class="background-data-value">{{ $player->favorite_player }}</span>
                    </div>
                    @endif
                    @if($player->favorite_team)
                    <div class="background-data-item">
                        <span class="background-data-label">Favorite Team</span>
                        <span class="background-data-value">{{ $player->favorite_team }}</span>
                    </div>
                    @endif
                    @if($player->hobbies)
                    <div class="background-data-item">
                        <span class="background-data-label">Hobbies</span>
                        <span class="background-data-value">{{ $player->hobbies }}</span>
                    </div>
                    @endif
                    @if($player->role_model)
                    <div class="background-data-item">
                        <span class="background-data-label">Role Model</span>
                        <span class="background-data-value">{{ $player->role_model }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Career Timeline -->
        <div class="career-timeline">
            <h2 class="career-timeline-title">Career Overview</h2>
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
                    <span class="career-stat-label">Appearances</span>
                    <span class="career-stat-value">{{ $player->appearances ?? 0 }}</span>
                </div>
                @if($player->joined_date)
                <div class="career-stat-item">
                    <span class="career-stat-label">Joined</span>
                    <span class="career-stat-value">{{ $player->joined_date->format('M Y') }}</span>
                </div>
                @endif
                @if($player->career_aspiration)
                <div class="career-stat-item">
                    <span class="career-stat-label">Aspiration</span>
                    <span class="career-stat-value">{{ $player->career_aspiration }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle background sections
function toggleBackgroundSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.toggle('expanded');
    }
}

// Initialize sections - expand personal background on desktop
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth >= 768) {
        const personalSection = document.getElementById('personal-bg-section');
        if (personalSection) {
            personalSection.classList.add('expanded');
        }
    }
});
</script>
@endpush

