@extends('layouts.academy')

@section('title', $player->name . ' - Statistics - Vipers Academy')

@section('content')
@push('styles')
<style>
/* ========================================
    HIGH-DENSITY STATISTICS DASHBOARD
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
    COMPACT STATISTICS HEADER
    ======================================== */

.compact-stats-header {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.stats-header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.compact-stats-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--primary-red);
}

.compact-stats-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stats-avatar-placeholder {
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

.stats-identity {
    flex: 1;
    min-width: 0;
}

.compact-stats-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stats-position-badge {
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

.stats-meta-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin-top: 0.5rem;
}

.stats-category-badge {
    padding: 0.125rem 0.375rem;
    background: var(--neutral-100);
    color: var(--neutral-700);
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* ========================================
    COMPACT NAVIGATION
    ======================================== */

.compact-stats-nav {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.compact-stats-nav-link {
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

.compact-stats-nav-link:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.compact-stats-nav-link.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* ========================================
    HIGH-DENSITY STATS GRID
    ======================================== */

.stats-dashboard {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stats-overview-section {
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

.stats-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 0.75rem;
}

.stats-metric-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem 0.5rem;
    background: var(--neutral-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--neutral-200);
}

.stats-metric-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-red);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stats-metric-label {
    font-size: 0.75rem;
    color: var(--neutral-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    text-align: center;
}

/* ========================================
    COLLAPSIBLE DATA SECTIONS
    ======================================== */

.collapsible-stats-section {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.collapsible-stats-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
    cursor: pointer;
    transition: var(--transition);
}

.collapsible-stats-header:hover {
    background: var(--neutral-100);
}

.collapsible-stats-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.collapsible-stats-icon {
    width: 1rem;
    height: 1rem;
    color: var(--neutral-500);
    transition: var(--transition);
}

.collapsible-stats-section.expanded .collapsible-stats-icon {
    transform: rotate(180deg);
}

.collapsible-stats-content {
    padding: 1rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.collapsible-stats-section.expanded .collapsible-stats-content {
    max-height: 1000px;
}

/* Position-specific stats */
.position-specific-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
}

.position-stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.position-stat-label {
    font-size: 0.75rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.position-stat-value {
    font-size: 0.875rem;
    color: var(--neutral-900);
    font-weight: 500;
}

/* Performance chart container */
.performance-chart-container {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
}

.performance-chart-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 1rem 0;
}

.performance-chart-canvas {
    width: 100% !important;
    height: 200px !important;
}

/* ========================================
    MOBILE OPTIMIZATIONS
    ======================================== */

/* Mobile First */
@media (max-width: 767px) {
    .compact-stats-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .stats-header-main {
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .compact-stats-avatar {
        width: 3rem;
        height: 3rem;
    }

    .compact-stats-name {
        font-size: 1.125rem;
    }

    .stats-meta-compact {
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .compact-stats-nav {
        gap: 0.125rem;
        padding-bottom: 0.5rem;
    }

    .compact-stats-nav-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .stats-metrics-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .stats-metric-item {
        padding: 0.5rem 0.25rem;
    }

    .stats-metric-value {
        font-size: 1rem;
        margin-bottom: 0.125rem;
    }

    .stats-metric-label {
        font-size: 0.6875rem;
    }

    .position-specific-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .position-stat-item {
        gap: 0.125rem;
    }

    .position-stat-label {
        font-size: 0.6875rem;
    }

    .position-stat-value {
        font-size: 0.8125rem;
    }

    .collapsible-stats-header {
        padding: 0.75rem;
    }

    .collapsible-stats-content {
        padding: 0.75rem;
    }

    .performance-chart-container {
        padding: 0.75rem;
    }

    .performance-chart-canvas {
        height: 150px !important;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .compact-stats-header {
        padding: 0.75rem;
    }

    .stats-header-main {
        gap: 0.5rem;
    }

    .compact-stats-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .compact-stats-name {
        font-size: 1rem;
    }

    .stats-position-badge {
        font-size: 0.6875rem;
        padding: 0.1875rem 0.375rem;
    }

    .stats-meta-compact {
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .stats-metrics-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .position-specific-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

<div class="compact-player-container">
    <!-- Compact Statistics Header -->
    <div class="compact-stats-header">
        <div class="stats-header-main">
            <div class="compact-stats-avatar">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="stats-avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="stats-identity">
                <h1 class="compact-stats-name">{{ $player->name }}</h1>
                <div class="stats-position-badge">{{ ucfirst($player->position) }}</div>
                <div class="stats-meta-compact">
                    <span class="stats-category-badge">{{ $player->standardized_category }}</span>
                    @if($player->age)
                    <span>{{ $player->age }}y</span>
                    @endif
                    @if($player->jersey_number)
                    <span>#{{ $player->jersey_number }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Navigation -->
    <nav class="compact-stats-nav">
        <a href="{{ route('players.overview', $player->id) }}" class="compact-stats-nav-link">Overview</a>
        <a href="{{ route('players.statistics', $player->id) }}" class="compact-stats-nav-link active">Statistics</a>
        <a href="{{ route('players.ai-insights', $player->id) }}" class="compact-stats-nav-link">AI Insights</a>
        <a href="{{ route('players.biography', $player->id) }}" class="compact-stats-nav-link">Biography</a>
        <a href="{{ route('players.career', $player->id) }}" class="compact-stats-nav-link">Career</a>
    </nav>

    <!-- Statistics Dashboard -->
    <div class="stats-dashboard">
        <!-- Overview Metrics -->
        <div class="stats-overview-section">
            <div class="stats-overview-header">
                <h2 class="stats-overview-title">Performance Overview</h2>
            </div>
            <div class="stats-metrics-grid">
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ $player->appearances ?? 0 }}</span>
                    <span class="stats-metric-label">Apps</span>
                </div>
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ $player->goals ?? 0 }}</span>
                    <span class="stats-metric-label">Goals</span>
                </div>
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ $player->assists ?? 0 }}</span>
                    <span class="stats-metric-label">Assists</span>
                </div>
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ number_format(($player->goals + $player->assists) / max($player->appearances, 1), 1) }}</span>
                    <span class="stats-metric-label">Avg/G</span>
                </div>
                @if($player->position === 'Goalkeeper')
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ $player->clean_sheets ?? 0 }}</span>
                    <span class="stats-metric-label">CS</span>
                </div>
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ number_format($player->save_percentage ?? 0, 1) }}%</span>
                    <span class="stats-metric-label">Save %</span>
                </div>
                @else
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ $player->yellow_cards ?? 0 }}</span>
                    <span class="stats-metric-label">YC</span>
                </div>
                <div class="stats-metric-item">
                    <span class="stats-metric-value">{{ number_format($player->passing_accuracy ?? 0, 1) }}%</span>
                    <span class="stats-metric-label">Pass %</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Position-Specific Stats -->
        <div class="collapsible-stats-section" id="position-stats-section">
            <div class="collapsible-stats-header" onclick="toggleStatsSection('position-stats-section')">
                <h3 class="collapsible-stats-title">
                    <span>{{ ucfirst($player->position) }} Statistics</span>
                    <svg class="collapsible-stats-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </h3>
            </div>
            <div class="collapsible-stats-content">
                <div class="position-specific-grid">
                    @if($player->position === 'Goalkeeper')
                    <div class="position-stat-item">
                        <span class="position-stat-label">Saves</span>
                        <span class="position-stat-value">{{ $player->saves ?? 0 }}</span>
                    </div>
                    <div class="position-stat-item">
                        <span class="position-stat-label">Clean Sheets</span>
                        <span class="position-stat-value">{{ $player->clean_sheets ?? 0 }}</span>
                    </div>
                    <div class="position-stat-item">
                        <span class="position-stat-label">Distribution</span>
                        <span class="position-stat-value">{{ number_format($player->distribution_accuracy ?? 0, 1) }}%</span>
                    </div>
                    @else
                    <div class="position-stat-item">
                        <span class="position-stat-label">Tackles</span>
                        <span class="position-stat-value">{{ $player->tackles_won ?? 0 }}</span>
                    </div>
                    <div class="position-stat-item">
                        <span class="position-stat-label">Interceptions</span>
                        <span class="position-stat-value">{{ $player->interceptions ?? 0 }}</span>
                    </div>
                    <div class="position-stat-item">
                        <span class="position-stat-label">Key Passes</span>
                        <span class="position-stat-value">{{ $player->key_passes ?? 0 }}</span>
                    </div>
                    <div class="position-stat-item">
                        <span class="position-stat-label">xG</span>
                        <span class="position-stat-value">{{ number_format($player->expected_goals ?? 0, 1) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="performance-chart-container">
            <h2 class="performance-chart-title">Season Performance</h2>
            <canvas class="performance-chart-canvas" id="performanceChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load Chart.js
(function() {
    if (window.Chart) {
        initPerformanceChart();
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = initPerformanceChart;
    script.onerror = () => console.warn('Chart.js failed to load');
    document.head.appendChild(script);
})();

// Initialize Performance Chart
function initPerformanceChart() {
    const canvas = document.querySelector('.performance-chart-canvas');
    if (!canvas) return;

    // Sample data - in real implementation, this would come from player game stats
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const goalsData = [2, 1, 3, 0, 2, 1];
    const assistsData = [1, 2, 1, 3, 1, 2];

    const config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Goals',
                data: goalsData,
                borderColor: '#ea1c4d',
                backgroundColor: 'rgba(234, 28, 77, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Assists',
                data: assistsData,
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    };

    new Chart(canvas, config);
}

// Toggle collapsible sections
function toggleStatsSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.toggle('expanded');
    }
}

// Initialize - expand position stats on desktop
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth >= 768) {
        const positionSection = document.getElementById('position-stats-section');
        if (positionSection) {
            positionSection.classList.add('expanded');
        }
    }
});
</script>
@endpush

<script>
(function() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = function() {
        initCharts();
    };
    script.onerror = function() {
        console.warn('Chart.js failed to load. Charts will not be displayed.');
    };
    document.head.appendChild(script);
})();

function initCharts() {
    // AI Extraction Toggle
    document.getElementById('use_ai_extraction')?.addEventListener('change', function() {
        const container = document.getElementById('game_summary_container');
        if (container) {
            container.style.display = this.checked ? 'block' : 'none';
        }
    });

    // Player stats selector (for switching between players)
    const playerStatsSelect = document.getElementById('player-stats-select');
    if (playerStatsSelect) {
        playerStatsSelect.addEventListener('change', function() {
            const selectedUrl = this.value;
            if (selectedUrl) {
                window.location.href = selectedUrl;
            }
        });
    }

    // Form Submission Handler
    document.getElementById('statsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(this);
        const selectedPlayerId = document.getElementById('player_select').value;

        if (!selectedPlayerId) {
            showErrorMessage('Please select a player.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        // Update form action to use selected player ID
        const baseUrl = window.location.origin;
        const submitUrl = `${baseUrl}/players/${selectedPlayerId}/record-stats`;

        // Submit via AJAX
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update statistics on the page for the selected player
                updatePlayerStatistics(data.player);
                showSuccessMessage(`Game statistics recorded successfully for ${data.player.first_name} ${data.player.last_name}!`);

                // Reset form fields
                this.reset();
                // Reset date to today
                document.getElementById('game_date').valueAsDate = new Date();
                // Hide AI summary container
                document.getElementById('game_summary_container').style.display = 'none';
            } else {
                showErrorMessage(data.message || 'An error occurred while saving statistics.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while saving statistics.');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Utility functions for messages and updates
function showSuccessMessage(message) {
    // Create and show success message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function showErrorMessage(message) {
    // Create and show error message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function updatePlayerStatistics(playerData) {
    // Update metric cards
    const goalsCard = document.querySelector('.metric-card:nth-child(1) .metric-value');
    const assistsCard = document.querySelector('.metric-card:nth-child(2) .metric-value');
    const appearancesCard = document.querySelector('.metric-card:nth-child(3) .metric-value');

    if (goalsCard) goalsCard.textContent = playerData.goals || 0;
    if (assistsCard) assistsCard.textContent = playerData.assists || 0;
    if (appearancesCard) appearancesCard.textContent = playerData.appearances || 0;

    // Update statistics list
    const statsList = document.querySelector('.extra-stats ul');
    if (statsList) {
        const goalsLi = statsList.querySelector('li:nth-child(1) strong');
        const assistsLi = statsList.querySelector('li:nth-child(2) strong');
        const appearancesLi = statsList.querySelector('li:nth-child(3) strong');
        const yellowCardsLi = statsList.querySelector('li:nth-child(4) strong');
        const redCardsLi = statsList.querySelector('li:nth-child(5) strong');

        if (goalsLi) goalsLi.nextSibling.textContent = playerData.goals || 0;
        if (assistsLi) assistsLi.nextSibling.textContent = playerData.assists || 0;
        if (appearancesLi) appearancesLi.nextSibling.textContent = playerData.appearances || 0;
        if (yellowCardsLi) yellowCardsLi.nextSibling.textContent = playerData.yellow_cards || 0;
        if (redCardsLi) redCardsLi.nextSibling.textContent = playerData.red_cards || 0;
    }
}
</script>
@endsection

