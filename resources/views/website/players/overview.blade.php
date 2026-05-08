@extends('layouts.academy')

@section('title', $player->name . ' - Overview - Vipers Academy')

@section('content')
@push('styles')
<style>
/* ========================================
    HIGH-DENSITY PLAYER PROFILE SYSTEM
    ======================================== */

:root {
    --primary-red: #ea1c4d;
    --primary-red-light: #f87171;
    --primary-red-dark: #dc2626;
    --secondary-green: #059669;
    --neutral-50: #fafafa;
    --neutral-100: #f5f5f5;
    --neutral-200: #e5e5e5;
    --neutral-300: #d4d4d4;
    --neutral-400: #a3a3a3;
    --neutral-500: #737373;
    --neutral-600: #525252;
    --neutral-700: #404040;
    --neutral-800: #262626;
    --neutral-900: #171717;
    --border-radius: 8px;
    --border-radius-lg: 12px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --transition: all 0.15s ease;
}

/* ========================================
    COMPACT PLAYER HEADER
    ======================================== */

.compact-player-header {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.player-header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.compact-player-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--primary-red);
}

.compact-player-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-compact {
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

.player-identity {
    flex: 1;
    min-width: 0;
}

.compact-player-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.player-position-badge {
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

.player-meta-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin-top: 0.5rem;
}

.player-category-badge {
    padding: 0.125rem 0.375rem;
    background: var(--neutral-100);
    color: var(--neutral-700);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Compact radar container */
.compact-radar-container {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
}

.compact-radar-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* ========================================
    COMPACT NAVIGATION
    ======================================== */

.compact-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.compact-nav-link {
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

.compact-nav-link:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.compact-nav-link.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* ========================================
    COMPACT CONTENT SECTIONS
    ======================================== */

.compact-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Stats Overview - High Density */
.stats-overview-compact {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.stats-overview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.stats-overview-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.stats-grid-compact {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 0.75rem;
}

.stat-item-compact {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem 0.5rem;
    background: var(--neutral-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--neutral-200);
}

.stat-value-compact {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-red);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label-compact {
    font-size: 0.75rem;
    color: var(--neutral-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

/* Collapsible Sections */
.collapsible-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.collapsible-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
    cursor: pointer;
    transition: var(--transition);
}

.collapsible-header:hover {
    background: var(--neutral-100);
}

.collapsible-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.collapsible-icon {
    width: 1rem;
    height: 1rem;
    color: var(--neutral-500);
    transition: var(--transition);
}

.collapsible-content {
    padding: 1rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.collapsible-section.expanded .collapsible-content {
    max-height: 1000px;
}

.collapsible-section.expanded .collapsible-icon {
    transform: rotate(180deg);
}

/* Profile Data */
.profile-data-compact {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.75rem;
}

.profile-data-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.profile-data-label {
    font-size: 0.75rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.profile-data-value {
    font-size: 0.875rem;
    color: var(--neutral-900);
    font-weight: 500;
}

/* Recent Form */
.recent-form-compact {
    display: flex;
    gap: 0.375rem;
    flex-wrap: wrap;
}

.form-indicator-compact {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.75rem;
    color: white;
}

.form-win-compact {
    background: #10b981;
}

.form-draw-compact {
    background: #f59e0b;
}

.form-loss-compact {
    background: #ef4444;
}

/* Quick Actions */
.quick-actions-compact {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.quick-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid var(--primary-red);
    background: var(--primary-red);
    color: white;
}

.quick-action-btn:hover {
    background: var(--primary-red-dark);
    transform: translateY(-1px);
}

.quick-action-btn.secondary {
    background: white;
    color: var(--primary-red);
}

.quick-action-btn.secondary:hover {
    background: var(--primary-red);
    color: white;
}

/* ========================================
    MOBILE OPTIMIZATIONS
    ======================================== */

/* Mobile First */
@media (max-width: 767px) {
    .compact-player-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .player-header-main {
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .compact-player-avatar {
        width: 3rem;
        height: 3rem;
    }

    .compact-player-name {
        font-size: 1.125rem;
    }

    .player-meta-compact {
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .compact-radar-container {
        width: 80px;
        height: 80px;
    }

    .compact-nav {
        gap: 0.125rem;
        padding-bottom: 0.5rem;
    }

    .compact-nav-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .stats-grid-compact {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .stat-item-compact {
        padding: 0.5rem 0.25rem;
    }

    .stat-value-compact {
        font-size: 1rem;
        margin-bottom: 0.125rem;
    }

    .stat-label-compact {
        font-size: 0.6875rem;
    }

    .profile-data-compact {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .profile-data-item {
        gap: 0.125rem;
    }

    .profile-data-label {
        font-size: 0.6875rem;
    }

    .profile-data-value {
        font-size: 0.8125rem;
    }

    .collapsible-header {
        padding: 0.75rem;
    }

    .collapsible-content {
        padding: 0.75rem;
    }

    .quick-actions-compact {
        flex-direction: column;
    }

    .quick-action-btn {
        justify-content: center;
        padding: 0.625rem 1rem;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .compact-player-header {
        padding: 0.75rem;
    }

    .player-header-main {
        gap: 0.5rem;
    }

    .compact-player-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .compact-player-name {
        font-size: 1rem;
    }

    .player-position-badge {
        font-size: 0.6875rem;
        padding: 0.1875rem 0.375rem;
    }

    .player-meta-compact {
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .stats-grid-compact {
        grid-template-columns: repeat(3, 1fr);
    }

    .profile-data-compact {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

<div class="compact-player-container">
    <!-- Compact Player Header -->
    <div class="compact-player-header">
        <div class="player-header-main">
            <div class="compact-player-avatar">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="avatar-placeholder-compact">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="player-identity">
                <h1 class="compact-player-name">{{ $player->name }}</h1>
                <div class="player-position-badge">{{ ucfirst($player->position) }}</div>
                <div class="player-meta-compact">
                    <span class="player-category-badge">{{ $player->standardized_category }}</span>
                    @if($player->age)
                    <span>{{ $player->age }}y</span>
                    @endif
                    @if($player->jersey_number)
                    <span>#{{ $player->jersey_number }}</span>
                    @endif
                </div>
            </div>

            <!-- Compact Radar Chart -->
            <div class="compact-radar-container">
                <canvas id="playerRadarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Compact Navigation -->
    <nav class="compact-nav">
        <a href="{{ route('players.overview', $player->id) }}" class="compact-nav-link active">Overview</a>
        <a href="{{ route('players.statistics', $player->id) }}" class="compact-nav-link">Statistics</a>
        <a href="{{ route('players.ai-insights', $player->id) }}" class="compact-nav-link">AI Insights</a>
        <a href="{{ route('players.biography', $player->id) }}" class="compact-nav-link">Biography</a>
        <a href="{{ route('players.career', $player->id) }}" class="compact-nav-link">Career</a>
    </nav>

    <!-- Compact Content -->
    <div class="compact-content">
        <!-- Stats Overview -->
        <div class="stats-overview-compact">
            <div class="stats-overview-header">
                <h2 class="stats-overview-title">Performance Overview</h2>
            </div>
            <div class="stats-grid-compact">
                <div class="stat-item-compact">
                    <span class="stat-value-compact">{{ $player->appearances ?? 0 }}</span>
                    <span class="stat-label-compact">Apps</span>
                </div>
                <div class="stat-item-compact">
                    <span class="stat-value-compact">{{ $player->goals ?? 0 }}</span>
                    <span class="stat-label-compact">Goals</span>
                </div>
                <div class="stat-item-compact">
                    <span class="stat-value-compact">{{ $player->assists ?? 0 }}</span>
                    <span class="stat-label-compact">Assists</span>
                </div>
                @if($player->position === 'Goalkeeper')
                <div class="stat-item-compact">
                    <span class="stat-value-compact">{{ $player->clean_sheets ?? 0 }}</span>
                    <span class="stat-label-compact">CS</span>
                </div>
                @else
                <div class="stat-item-compact">
                    <span class="stat-value-compact">{{ $player->yellow_cards ?? 0 }}</span>
                    <span class="stat-label-compact">YC</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Collapsible Profile Section -->
        <div class="collapsible-section" id="profile-section">
            <div class="collapsible-header" onclick="toggleSection('profile-section')">
                <h3 class="collapsible-title">
                    <span>Player Profile</span>
                    <svg class="collapsible-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </h3>
            </div>
            <div class="collapsible-content">
                <div class="profile-data-compact">
                    @if($player->age)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Age</span>
                        <span class="profile-data-value">{{ $player->age }} years</span>
                    </div>
                    @endif
                    @if($player->position)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Position</span>
                        <span class="profile-data-value">{{ ucfirst($player->position) }}</span>
                    </div>
                    @endif
                    @if($player->jersey_number)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Jersey</span>
                        <span class="profile-data-value">#{{ $player->jersey_number }}</span>
                    </div>
                    @endif
                    @if($player->height)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Height</span>
                        <span class="profile-data-value">{{ $player->height }}</span>
                    </div>
                    @endif
                    @if($player->weight)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Weight</span>
                        <span class="profile-data-value">{{ $player->weight }}</span>
                    </div>
                    @endif
                    @if($player->preferred_foot)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Foot</span>
                        <span class="profile-data-value">{{ ucfirst($player->preferred_foot) }}</span>
                    </div>
                    @endif
                    @if($player->created_at)
                    <div class="profile-data-item">
                        <span class="profile-data-label">Joined</span>
                        <span class="profile-data-value">{{ $player->created_at->format('M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Collapsible Recent Form Section -->
        @if(isset($player->recentForm) && $player->recentForm->isNotEmpty())
        <div class="collapsible-section" id="form-section">
            <div class="collapsible-header" onclick="toggleSection('form-section')">
                <h3 class="collapsible-title">
                    <span>Recent Form</span>
                    <svg class="collapsible-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </h3>
            </div>
            <div class="collapsible-content">
                <div class="recent-form-compact">
                    @foreach($player->recentForm as $match)
                    <div class="form-indicator-compact {{ $match->result === 'W' ? 'form-win-compact' : ($match->result === 'D' ? 'form-draw-compact' : 'form-loss-compact') }}"
                         title="{{ $match->competition ?? 'Match' }} - {{ $match->date->format('d M') }}">
                        {{ $match->result === 'W' ? 'W' : ($match->result === 'D' ? 'D' : 'L') }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="quick-actions-compact">
            <a href="{{ route('players.statistics', $player->id) }}" class="quick-action-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                View Statistics
            </a>
            <a href="{{ route('players.biography', $player->id) }}" class="quick-action-btn secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Biography
            </a>
            <a href="{{ route('players.career', $player->id) }}" class="quick-action-btn secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Career
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load Chart.js library
(function() {
    if (window.Chart) {
        initCompactRadarChart();
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = initCompactRadarChart;
    script.onerror = () => console.warn('Chart.js failed to load');
    document.head.appendChild(script);
})();

// Initialize Compact Radar Chart
function initCompactRadarChart() {
    const canvas = document.getElementById('playerRadarChart');
    if (!canvas || canvas.chart) return; // Prevent duplicate initialization

    const playerPosition = '{{ strtoupper($player->position ?? "ST") }}';
    let labels = [];
    let data = [];

    // Position-specific radar attributes using advanced stats
    switch (playerPosition) {
        case 'GK':
            labels = ['Shot Stopping', 'Distribution', 'Aerial Ability', 'Command Area', 'Handling', 'Positioning'];
            data = [
                {{ $player->shot_stopping ?? 0 }},
                {{ $player->distribution ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->command_area ?? 0 }},
                {{ $player->handling ?? 0 }},
                {{ $player->positioning ?? 75 }}
            ];
            break;

        case 'CB':
            labels = ['Tackling', 'Interceptions', 'Aerial Ability', 'Positioning', 'Strength', 'Passing'];
            data = [
                {{ $player->tackling ?? 0 }},
                {{ $player->interceptions ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->strength ?? 0 }},
                {{ $player->passing ?? 0 }}
            ];
            break;

        case 'LB':
        case 'RB':
            labels = ['Pace', 'Crossing', 'Dribbling', 'Tackling', 'Positioning', 'Stamina'];
            data = [
                {{ $player->pace ?? 0 }},
                {{ $player->crossing ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->tackling ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->stamina ?? 0 }}
            ];
            break;

        case 'CDM':
            labels = ['Tackling', 'Interceptions', 'Positioning', 'Passing', 'Vision', 'Stamina'];
            data = [
                {{ $player->tackling ?? 0 }},
                {{ $player->interceptions ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->stamina ?? 0 }}
            ];
            break;

        case 'CM':
            labels = ['Passing', 'Vision', 'Tackling', 'Positioning', 'Stamina', 'Decisions'];
            data = [
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->tackling ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->stamina ?? 0 }},
                {{ $player->decisions ?? 0 }}
            ];
            break;

        case 'CAM':
            labels = ['Passing', 'Vision', 'Dribbling', 'Finishing', 'Technique', 'Flair'];
            data = [
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->finishing ?? 0 }},
                {{ $player->technique ?? 0 }},
                {{ $player->flair ?? 0 }}
            ];
            break;

        case 'LW':
        case 'RW':
            labels = ['Pace', 'Crossing', 'Dribbling', 'Finishing', 'Technique', 'Balance'];
            data = [
                {{ $player->pace ?? 0 }},
                {{ $player->crossing ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->finishing ?? 0 }},
                {{ $player->technique ?? 0 }},
                {{ $player->balance ?? 0 }}
            ];
            break;

        case 'ST':
        case 'CF':
        default:
            labels = ['Finishing', 'Positioning', 'Aerial Ability', 'Pace', 'Strength', 'Technique'];
            data = [
                {{ $player->finishing ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->pace ?? 0 }},
                {{ $player->strength ?? 0 }},
                {{ $player->technique ?? 0 }}
            ];
            break;
    }

    // Chart configuration
    const config = {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ $player->name }} Skills',
                data: data,
                borderWidth: 2,
                borderColor: '#ea1c4d',
                backgroundColor: 'rgba(234, 28, 77, 0.1)',
                pointBackgroundColor: '#ea1c4d',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#ea1c4d',
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 8,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 },
                    displayColors: false,
                    callbacks: {
                        label: (context) => {
                            const value = Math.round(context.parsed.r);
                            return `${context.label}: ${value}/100`;
                        }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { display: false, stepSize: 20 },
                    grid: { color: 'rgba(0, 0, 0, 0.1)', circular: true },
                    angleLines: { display: false },
                    pointLabels: {
                        color: '#64748b',
                        font: { size: window.innerWidth < 768 ? 8 : 10, weight: 'bold' }
                    }
                }
            }
        }
    };

    // Create and store chart instance
    canvas.chart = new Chart(canvas, config);
}

// Collapsible sections functionality
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.toggle('expanded');
    }
}

// Initialize sections - expand profile by default on desktop
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth >= 768) {
        const profileSection = document.getElementById('profile-section');
        if (profileSection) {
            profileSection.classList.add('expanded');
        }
    }
});
</script>
@endpush

<style>
    /* Overview-specific styles */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-item {
        background: rgba(234, 28, 77, 0.05);
        padding: 20px 15px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid rgba(234, 28, 77, 0.2);
        transition: var(--transition);
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(234, 28, 77, 0.15);
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-gray);
        font-weight: 500;
    }

    .bio-section {
        margin-bottom: 30px;
    }

    .bio-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(234, 28, 77, 0.2);
        display: inline-block;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .profile-item {
        background: rgba(255, 255, 255, 0.8);
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
        font-size: 0.95rem;
    }

    .profile-item strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    .highlights-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .highlights-list li {
        position: relative;
        padding: 12px 0 12px 30px;
        color: var(--text-medium);
        line-height: 1.6;
        border-bottom: 1px solid rgba(234, 28, 77, 0.1);
    }

    .highlights-list li:last-child {
        border-bottom: none;
    }

    .highlights-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 12px;
        color: var(--secondary-color);
        font-weight: bold;
        font-size: 1.1rem;
    }

    .quick-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid rgba(234, 28, 77, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(234, 28, 77, 0.3);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-secondary:hover {
        background: var(--primary-color);
        color: white;
    }

    .recent-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .form-indicator {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .form-win {
        background: #10b981;
        color: white;
    }

    .form-draw {
        background: #f59e0b;
        color: white;
    }

    .form-loss {
        background: #ef4444;
        color: white;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-item {
            padding: 15px 10px;
        }

        .profile-grid {
            grid-template-columns: 1fr 1fr;
        }

        .quick-links {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

