@extends('layouts.academy')

@section('title', 'Our Players - Vipers Academy')

@section('content')
<div class="players-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Our Players</h1>

        @auth
        @if(auth()->user()->is_admin)
        <a href="{{ route('players.sync') }}" class="sync-button">
            <svg class="sync-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Sync Players
        </a>
        @endif
        @endauth
    </div>

    <!-- Compact Filters Bar -->
    <div class="compact-filters-bar">
        <div class="filters-row">
            <!-- Search Input -->
            <div class="search-compact">
                <svg class="search-icon-compact" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="search-input" class="search-input-compact" placeholder="Search players..." value="{{ $search ?? '' }}">
                <button type="button" id="clear-search" class="clear-search-compact" style="display: {{ !empty($search) ? 'block' : 'none' }};">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Filter Chips -->
            <div class="filter-chips">
                <button type="button" class="filter-chip {{ empty($gender) ? 'active' : '' }}" data-filter="gender" data-value="">
                    <span>All</span>
                </button>
                <button type="button" class="filter-chip {{ ($gender ?? '') === 'M' ? 'active' : '' }}" data-filter="gender" data-value="M">
                    <span>Men</span>
                </button>
                <button type="button" class="filter-chip {{ ($gender ?? '') === 'F' ? 'active' : '' }}" data-filter="gender" data-value="F">
                    <span>Women</span>
                </button>
            </div>

            <div class="filter-chips">
                <button type="button" class="filter-chip {{ empty($category) ? 'active' : '' }}" data-filter="category" data-value="">
                    <span>All Ages</span>
                </button>
                <button type="button" class="filter-chip {{ ($category ?? '') === 'junior' ? 'active' : '' }}" data-filter="category" data-value="junior">
                    <span>Junior</span>
                </button>
                <button type="button" class="filter-chip {{ ($category ?? '') === 'senior' ? 'active' : '' }}" data-filter="category" data-value="senior">
                    <span>Senior</span>
                </button>
            </div>

            <!-- Mobile Filter Toggle -->
            <button type="button" class="mobile-filter-toggle" id="mobile-filter-toggle">
                <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="filter-count" id="active-filter-count" style="display: none;">0</span>
            </button>
        </div>

        <!-- Mobile Filter Panel -->
        <div class="mobile-filter-panel" id="mobile-filter-panel" style="display: none;">
            <div class="mobile-filter-section">
                <h4>Gender</h4>
                <div class="mobile-filter-options">
                    <button type="button" class="mobile-filter-option {{ empty($gender) ? 'active' : '' }}" data-filter="gender" data-value="">
                        <span>All Genders</span>
                    </button>
                    <button type="button" class="mobile-filter-option {{ ($gender ?? '') === 'M' ? 'active' : '' }}" data-filter="gender" data-value="M">
                        <span>Men</span>
                    </button>
                    <button type="button" class="mobile-filter-option {{ ($gender ?? '') === 'F' ? 'active' : '' }}" data-filter="gender" data-value="F">
                        <span>Women</span>
                    </button>
                </div>
            </div>

            <div class="mobile-filter-section">
                <h4>Category</h4>
                <div class="mobile-filter-options">
                    <button type="button" class="mobile-filter-option {{ empty($category) ? 'active' : '' }}" data-filter="category" data-value="">
                        <span>All Ages</span>
                    </button>
                    <button type="button" class="mobile-filter-option {{ ($category ?? '') === 'junior' ? 'active' : '' }}" data-filter="category" data-value="junior">
                        <span>Junior</span>
                    </button>
                    <button type="button" class="mobile-filter-option {{ ($category ?? '') === 'senior' ? 'active' : '' }}" data-filter="category" data-value="senior">
                        <span>Senior</span>
                    </button>
                </div>
            </div>

            <div class="mobile-filter-actions">
                <button type="button" id="mobile-clear-all" class="mobile-clear-btn">Clear All</button>
                <button type="button" id="mobile-apply-filters" class="mobile-apply-btn">Apply</button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="success-message">
        <svg class="success-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Players Grid -->
    <div id="players-content">
        @if($players->count() > 0)
        <!-- Responsive Player Grid -->
        <div class="responsive-player-grid">
            @foreach($players as $player)
            <a href="{{ route('players.overview', $player->id) }}" class="player-card-link">
                <div class="player-card-grid" data-player-id="{{ $player->id }}">
                <!-- Player Avatar & Basic Info -->
                <div class="player-avatar-section">
                    <div class="player-avatar">
                        @if($player->image_url)
                        <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                        @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="player-basic-info">
                        <div class="player-name-row">
                            <h3 class="compact-player-name">{{ $player->name }}</h3>
                            <div class="player-badges-compact">
                                @if($player->youtube_url)
                                <span class="badge-video" title="Video Available">📹</span>
                                @endif
                                @if($player->goals > 0 || $player->assists > 0)
                                <span class="badge-stats" title="Stats Available">📊</span>
                                @endif
                            </div>
                        </div>
                        <div class="player-meta-row">
                            <span class="player-position">{{ ucfirst($player->position) }}</span>
                            @if($player->age)
                            <span class="player-age">{{ $player->age }}y</span>
                            @endif
                            <span class="player-category">{{ $player->standardized_category }}</span>
                        </div>
                    </div>
                </div>

                <!-- Key Stats (Desktop) -->
                <div class="player-stats-compact">
                    <div class="stat-item-compact">
                        <span class="stat-value">{{ $player->goals ?? 0 }}</span>
                        <span class="stat-label">G</span>
                    </div>
                    <div class="stat-item-compact">
                        <span class="stat-value">{{ $player->assists ?? 0 }}</span>
                        <span class="stat-label">A</span>
                    </div>
                    <div class="stat-item-compact">
                        <span class="stat-value">{{ $player->appearances ?? 0 }}</span>
                        <span class="stat-label">Apps</span>
                    </div>
                    @if($player->position === 'Goalkeeper')
                    <div class="stat-item-compact">
                        <span class="stat-value">{{ $player->clean_sheets ?? 0 }}</span>
                        <span class="stat-label">CS</span>
                    </div>
                    @endif
                </div>

                <!-- Mobile Action -->
                <div class="player-action-mobile">
                    <button type="button" class="expand-player-btn" data-player-id="{{ $player->id }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Expandable Details (Mobile) -->
                <div class="player-details-expanded" id="details-{{ $player->id }}" style="display: none;">
                    <div class="expanded-stats">
                        <div class="expanded-stat-row">
                            <span>Goals: {{ $player->goals ?? 0 }}</span>
                            <span>Assists: {{ $player->assists ?? 0 }}</span>
                            <span>Appearances: {{ $player->appearances ?? 0 }}</span>
                        </div>
                        @if($player->jersey_number)
                        <div class="expanded-info-row">
                            <span>Jersey: #{{ $player->jersey_number }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="expanded-actions">
                        <a href="{{ route('players.show', $player->id) }}" class="expanded-action-btn primary">View Profile</a>
                        <a href="{{ route('players.statistics', $player->id) }}" class="expanded-action-btn secondary">Statistics</a>
                    </div>
                </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <!-- Loading State -->
        <div id="loading-state" class="loading-state" style="display: none;">
            <div class="loading-spinner">
                <svg class="spinner-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <p class="loading-text">Loading players...</p>
        </div>

        <!-- Compact Empty State -->
        <div class="compact-empty-state">
            <div class="empty-icon-compact">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="empty-content">
                <h3 class="empty-title-compact">No Players Found</h3>
                <p class="empty-text-compact">Try adjusting your search or filters to find players.</p>
            </div>
        </div>

        @auth
        @if(auth()->user()->is_admin)
        <a href="{{ route('players.sync') }}" class="sync-button">
            <svg class="sync-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Sync Players Now
        </a>
        @endif
        @endauth
    </div>
    @endif

        <!-- Compact Pagination -->
        @if($players->hasPages())
        <div class="compact-pagination">
            <div class="pagination-stats">
                <span id="pagination-from">{{ $players->firstItem() }}</span>-<span id="pagination-to">{{ $players->lastItem() }}</span> of <span id="pagination-total">{{ $players->total() }}</span>
            </div>
            <div class="pagination-controls">
                <button type="button" id="prev-page" class="pagination-btn prev {{ $players->onFirstPage() ? 'disabled' : '' }}" {{ $players->onFirstPage() ? 'disabled' : '' }}>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button type="button" id="next-page" class="pagination-btn next {{ $players->hasMorePages() ? '' : 'disabled' }}" {{ $players->hasMorePages() ? '' : 'disabled' }}>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* ========================================
    HIGH-DENSITY COMPACT DESIGN SYSTEM
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
    CONTAINER & LAYOUT
    ======================================== */

.players-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Page Header - Compact */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--neutral-200);
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0;
    letter-spacing: -0.025em;
}

.sync-button {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    background: var(--primary-red);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: var(--transition);
    box-shadow: var(--shadow);
}

.sync-button:hover {
    background: var(--primary-red-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.sync-icon {
    width: 1rem;
    height: 1rem;
}

/* ========================================
    COMPACT FILTERS BAR
    ======================================== */

.compact-filters-bar {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius-lg);
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}

.filters-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Search Input - Compact */
.search-compact {
    position: relative;
    flex: 1;
    min-width: 200px;
}

.search-input-compact {
    width: 100%;
    padding: 0.5rem 2.5rem 0.5rem 2rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    transition: var(--transition);
    background: var(--neutral-50);
}

.search-input-compact:focus {
    outline: none;
    border-color: var(--primary-red);
    box-shadow: 0 0 0 3px rgb(234 28 77 / 0.1);
}

.search-icon-compact {
    position: absolute;
    left: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1rem;
    height: 1rem;
    color: var(--neutral-400);
}

.clear-search-compact {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--neutral-400);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: var(--transition);
}

.clear-search-compact:hover {
    background: var(--neutral-100);
    color: var(--neutral-600);
}

/* Filter Chips */
.filter-chips {
    display: flex;
    gap: 0.25rem;
}

.filter-chip {
    padding: 0.375rem 0.75rem;
    border: 1px solid var(--neutral-300);
    border-radius: 20px;
    background: white;
    color: var(--neutral-700);
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.filter-chip:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.filter-chip.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

/* Mobile Filter Toggle */
.mobile-filter-toggle {
    display: none;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    background: white;
    color: var(--neutral-600);
    font-size: 0.75rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.mobile-filter-toggle:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.filter-icon {
    width: 1rem;
    height: 1rem;
}

.filter-count {
    background: var(--primary-red);
    color: white;
    border-radius: 10px;
    padding: 0.125rem 0.375rem;
    font-size: 0.625rem;
    font-weight: 600;
    min-width: 1rem;
    text-align: center;
}

/* Mobile Filter Panel */
.mobile-filter-panel {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--neutral-200);
}

.mobile-filter-section {
    margin-bottom: 1rem;
}

.mobile-filter-section h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 0.5rem 0;
}

.mobile-filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.mobile-filter-option {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    background: white;
    color: var(--neutral-700);
    font-size: 0.875rem;
    text-align: left;
    cursor: pointer;
    transition: var(--transition);
}

.mobile-filter-option:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.mobile-filter-option.active {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
}

.mobile-filter-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.mobile-clear-btn,
.mobile-apply-btn {
    flex: 1;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.mobile-clear-btn {
    background: var(--neutral-100);
    border: 1px solid var(--neutral-300);
    color: var(--neutral-700);
}

.mobile-clear-btn:hover {
    background: var(--neutral-200);
}

.mobile-apply-btn {
    background: var(--primary-red);
    border: 1px solid var(--primary-red);
    color: white;
}

.mobile-apply-btn:hover {
    background: var(--primary-red-dark);
}

/* ========================================
    COMPACT PLAYER LIST
    ======================================== */

/* ========================================
    RESPONSIVE PLAYER GRID
    ======================================== */

.responsive-player-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    width: 100%;
    justify-items: start;
}

.player-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: var(--transition);
}

.player-card-link:hover {
    transform: translateY(-2px);
}

.player-card-grid {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 1rem;
    transition: var(--transition);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    min-height: 140px;
}

.player-card-grid:hover {
    border-color: var(--primary-red);
    box-shadow: var(--shadow);
}

/* Player Avatar Section */
.player-avatar-section {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.player-avatar {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.player-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
}

/* Player Basic Info */
.player-basic-info {
    flex: 1;
    min-width: 0;
}

.player-name-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.compact-player-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.player-badges-compact {
    display: flex;
    gap: 0.25rem;
    flex-shrink: 0;
}

.badge-video,
.badge-stats {
    font-size: 0.75rem;
    opacity: 0.7;
}

.player-meta-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.75rem;
    color: var(--neutral-600);
}

.player-position {
    font-weight: 500;
    color: var(--primary-red);
    text-transform: capitalize;
}

.player-age,
.player-category {
    color: var(--neutral-500);
}

/* Player Stats (Desktop) */
.player-stats-compact {
    display: flex;
    gap: 1rem;
    margin-left: auto;
    align-items: center;
}

.stat-item-compact {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 2.5rem;
}

.stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--neutral-900);
    line-height: 1;
}

.stat-label {
    font-size: 0.625rem;
    color: var(--neutral-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 0.125rem;
}

/* Mobile Action */
.player-action-mobile {
    display: none;
    margin-left: auto;
}

.expand-player-btn {
    padding: 0.375rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    background: white;
    color: var(--neutral-600);
    cursor: pointer;
    transition: var(--transition);
}

.expand-player-btn:hover {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.expand-player-btn svg {
    width: 1rem;
    height: 1rem;
}

/* Expandable Details (Mobile) */
.player-details-expanded {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--neutral-200);
}

.expanded-stats {
    margin-bottom: 0.75rem;
}

.expanded-stat-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--neutral-700);
}

.expanded-info-row {
    font-size: 0.75rem;
    color: var(--neutral-600);
    margin-top: 0.25rem;
}

.expanded-actions {
    display: flex;
    gap: 0.5rem;
}

.expanded-action-btn {
    flex: 1;
    padding: 0.5rem 0.75rem;
    border-radius: var(--border-radius);
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}

.expanded-action-btn.primary {
    background: var(--primary-red);
    color: white;
    border: 1px solid var(--primary-red);
}

.expanded-action-btn.primary:hover {
    background: var(--primary-red-dark);
}

.expanded-action-btn.secondary {
    background: white;
    color: var(--primary-red);
    border: 1px solid var(--primary-red);
}

.expanded-action-btn.secondary:hover {
    background: var(--primary-red);
    color: white;
}

/* ========================================
    COMPACT PAGINATION
    ======================================== */

.compact-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding: 1rem;
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
}

.pagination-stats {
    font-size: 0.875rem;
    color: var(--neutral-600);
    font-weight: 500;
}

.pagination-controls {
    display: flex;
    gap: 0.5rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    background: white;
    color: var(--neutral-600);
    cursor: pointer;
    transition: var(--transition);
}

.pagination-btn:hover:not(.disabled) {
    border-color: var(--primary-red);
    color: var(--primary-red);
}

.pagination-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.pagination-btn svg {
    width: 1rem;
    height: 1rem;
}

/* ========================================
    COMPACT EMPTY STATE
    ======================================== */

.compact-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    text-align: center;
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    margin-top: 1rem;
}

.empty-icon-compact {
    width: 3rem;
    height: 3rem;
    color: var(--neutral-400);
    margin-bottom: 1rem;
}

.empty-icon-compact svg {
    width: 100%;
    height: 100%;
}

.empty-content {
    max-width: 300px;
}

.empty-title-compact {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 0.5rem 0;
}

.empty-text-compact {
    font-size: 0.875rem;
    color: var(--neutral-600);
    margin: 0;
}

/* ========================================
    RESPONSIVE DESIGN - MOBILE FIRST
    ======================================== */

/* Tablet and Desktop */
@media (min-width: 768px) {
    .responsive-player-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        justify-items: start;
    }

    .player-card-grid {
        padding: 1rem;
    }

    .player-avatar {
        width: 3.5rem;
        height: 3.5rem;
    }

    .compact-player-name {
        font-size: 1rem;
    }

    .player-meta-row {
        font-size: 0.8125rem;
    }

    .player-stats-compact {
        display: flex;
    }

    .mobile-filter-toggle {
        display: none;
    }

    .mobile-filter-panel {
        display: none;
    }

    .player-action-mobile {
        display: none;
    }

    .player-details-expanded {
        display: none !important;
    }
}

/* Mobile */
@media (max-width: 767px) {
    .players-container {
        padding: 0.75rem;
    }

    .responsive-player-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        justify-items: start;
    }

    .page-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .sync-button {
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
    }

    .compact-filters-bar {
        padding: 0.75rem;
    }

    .filters-row {
        gap: 0.75rem;
    }

    .search-compact {
        min-width: 150px;
        flex: 1;
    }

    .filter-chips {
        display: none;
    }

    .mobile-filter-toggle {
        display: flex;
    }

    .responsive-player-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        justify-items: start;
    }

    .player-card-grid {
        padding: 0.75rem;
        min-height: 120px;
    }

    .player-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }

    .player-name-row {
        margin-bottom: 0.375rem;
    }

    .compact-player-name {
        font-size: 0.8125rem;
    }

    .player-meta-row {
        gap: 0.5rem;
        font-size: 0.6875rem;
    }

    .player-stats-compact {
        display: none;
    }

    .player-action-mobile {
        display: block;
    }

    .compact-pagination {
        padding: 0.75rem;
        margin-top: 1rem;
    }

    .pagination-stats {
        font-size: 0.8125rem;
    }

    .compact-empty-state {
        padding: 2rem 1rem;
    }

    .empty-icon-compact {
        width: 2.5rem;
        height: 2.5rem;
    }

    .empty-title-compact {
        font-size: 1rem;
    }

    .empty-text-compact {
        font-size: 0.8125rem;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .players-container {
        padding: 0.5rem;
    }

    .filters-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }

    .search-compact {
        min-width: auto;
    }

    .player-card-grid {
        padding: 0.625rem;
    }

    .player-avatar-section {
        gap: 0.5rem;
    }

    .player-avatar {
        width: 2.25rem;
        height: 2.25rem;
    }

    .compact-player-name {
        font-size: 0.75rem;
    }

    .player-meta-row {
        font-size: 0.625rem;
        gap: 0.375rem;
    }

    .compact-pagination {
        padding: 0.625rem;
    }

    .pagination-controls {
        gap: 0.25rem;
    }

    .pagination-btn {
        width: 1.75rem;
        height: 1.75rem;
    }

    .pagination-btn svg {
        width: 0.875rem;
        height: 0.875rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter state management
    let currentFilters = {
        search: '{{ $search ?? '' }}',
        gender: '{{ $gender ?? '' }}',
        category: '{{ $category ?? '' }}',
        page: 1
    };

    // DOM elements
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const playersContent = document.getElementById('players-content');
    const loadingState = document.getElementById('loading-state');
    const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
    const mobileFilterPanel = document.getElementById('mobile-filter-panel');
    const mobileClearBtn = document.getElementById('mobile-clear-all');
    const mobileApplyBtn = document.getElementById('mobile-apply-filters');
    const activeFilterCount = document.getElementById('active-filter-count');

    let searchTimeout;
    let isMobile = window.innerWidth < 768;

    // Debounced search function
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = searchInput.value.trim();
            currentFilters.page = 1;
            fetchPlayers();
        }, 300);
    }

    // Update clear search button visibility
    function updateClearSearchVisibility() {
        if (clearSearchBtn) {
            clearSearchBtn.style.display = searchInput.value.trim() ? 'block' : 'none';
        }
    }

    // Update active filter count for mobile
    function updateActiveFilterCount() {
        let count = 0;
        if (currentFilters.search) count++;
        if (currentFilters.gender) count++;
        if (currentFilters.category) count++;

        if (activeFilterCount) {
            activeFilterCount.textContent = count;
            activeFilterCount.style.display = count > 0 ? 'block' : 'none';
        }
    }

    // Fetch players via AJAX
    function fetchPlayers() {
        // Show loading state
        if (loadingState) {
            loadingState.style.display = 'block';
            playersContent.style.opacity = '0.5';
        }

        // Build query string
        const params = new URLSearchParams();
        if (currentFilters.search) params.append('search', currentFilters.search);
        if (currentFilters.gender) params.append('gender', currentFilters.gender);
        if (currentFilters.category) params.append('category', currentFilters.category);
        params.append('page', currentFilters.page);
        params.append('per_page', isMobile ? 20 : 50);

        // Update URL without page reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);

        // Fetch data
        fetch(`/api/players?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPlayers(data.data);
                    updatePagination(data.pagination);
                    updateActiveFilterCount();
                } else {
                    console.error('Failed to fetch players:', data.message);
                    renderEmptyState();
                }
            })
            .catch(error => {
                console.error('Error fetching players:', error);
                renderEmptyState();
            })
            .finally(() => {
                if (loadingState) {
                    loadingState.style.display = 'none';
                    playersContent.style.opacity = '1';
                }
            });
    }

    // Render compact player list
    function renderPlayers(players) {
        if (players.length === 0) {
            renderEmptyState();
            return;
        }

        const listHtml = players.map(player => `
            <a href="/players/${player.id}" class="player-card-link">
                <div class="player-card-grid" data-player-id="${player.id}">
                <div class="player-avatar-section">
                    <div class="player-avatar">
                        ${player.image_url ?
                            `<img src="${player.image_url}" alt="${player.name}" loading="lazy">` :
                            `<div class="avatar-placeholder">${player.name.charAt(0).toUpperCase()}</div>`
                        }
                    </div>
                    <div class="player-basic-info">
                        <div class="player-name-row">
                            <h3 class="compact-player-name">${player.name}</h3>
                            <div class="player-badges-compact">
                                ${player.has_youtube ? '<span class="badge-video" title="Video Available">📹</span>' : ''}
                                ${player.has_stats ? '<span class="badge-stats" title="Stats Available">📊</span>' : ''}
                            </div>
                        </div>
                        <div class="player-meta-row">
                            <span class="player-position">${player.formatted_position}</span>
                            ${player.age ? `<span class="player-age">${player.age}y</span>` : ''}
                            <span class="player-category">${player.standardized_category}</span>
                        </div>
                    </div>
                </div>

                ${!isMobile ? `
                <div class="player-stats-compact">
                    <div class="stat-item-compact">
                        <span class="stat-value">${player.goals || 0}</span>
                        <span class="stat-label">G</span>
                    </div>
                    <div class="stat-item-compact">
                        <span class="stat-value">${player.assists || 0}</span>
                        <span class="stat-label">A</span>
                    </div>
                    <div class="stat-item-compact">
                        <span class="stat-value">${player.appearances || 0}</span>
                        <span class="stat-label">Apps</span>
                    </div>
                    ${player.position === 'Goalkeeper' ? `
                    <div class="stat-item-compact">
                        <span class="stat-value">${player.clean_sheets || 0}</span>
                        <span class="stat-label">CS</span>
                    </div>
                    ` : ''}
                </div>
                ` : `
                <div class="player-action-mobile">
                    <button type="button" class="expand-player-btn" data-player-id="${player.id}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div class="player-details-expanded" id="details-${player.id}" style="display: none;">
                    <div class="expanded-stats">
                        <div class="expanded-stat-row">
                            <span>Goals: ${player.goals || 0}</span>
                            <span>Assists: ${player.assists || 0}</span>
                            <span>Appearances: ${player.appearances || 0}</span>
                        </div>
                        ${player.jersey_number ? `<div class="expanded-info-row"><span>Jersey: #${player.jersey_number}</span></div>` : ''}
                    </div>
                    <div class="expanded-actions">
                        <a href="/players/${player.id}" class="expanded-action-btn primary">View Profile</a>
                        <a href="/players/statistics/${player.id}" class="expanded-action-btn secondary">Statistics</a>
                    </div>
                </div>
                </div>
            </a>
        `).join('');

        playersContent.innerHTML = `<div class="responsive-player-grid">${listHtml}</div>`;
    }

    // Render compact empty state
    function renderEmptyState() {
        playersContent.innerHTML = `
            <div class="compact-empty-state">
                <div class="empty-icon-compact">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="empty-content">
                    <h3 class="empty-title-compact">No Players Found</h3>
                    <p class="empty-text-compact">Try adjusting your search or filters to find players.</p>
                </div>
            </div>
        `;
    }

    // Update compact pagination
    function updatePagination(pagination) {
        const paginationContainer = document.querySelector('.compact-pagination');
        if (!paginationContainer) return;

        const paginationFrom = document.getElementById('pagination-from');
        const paginationTo = document.getElementById('pagination-to');
        const paginationTotal = document.getElementById('pagination-total');

        if (paginationFrom) paginationFrom.textContent = pagination.from || 0;
        if (paginationTo) paginationTo.textContent = pagination.to || 0;
        if (paginationTotal) paginationTotal.textContent = pagination.total || 0;

        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');

        if (prevBtn) {
            prevBtn.classList.toggle('disabled', pagination.current_page === 1);
            prevBtn.disabled = pagination.current_page === 1;
        }

        if (nextBtn) {
            nextBtn.classList.toggle('disabled', pagination.current_page === pagination.last_page);
            nextBtn.disabled = pagination.current_page === pagination.last_page;
        }

        // Show/hide pagination based on total pages
        if (paginationContainer) {
            paginationContainer.style.display = pagination.last_page > 1 ? 'flex' : 'none';
        }
    }

    // Handle filter chip clicks
    function handleFilterChipClick(e) {
        const chip = e.target.closest('.filter-chip');
        if (!chip) return;

        const filterType = chip.dataset.filter;
        const filterValue = chip.dataset.value;

        // Toggle active state
        const siblings = chip.parentElement.querySelectorAll('.filter-chip');
        siblings.forEach(s => s.classList.remove('active'));
        chip.classList.add('active');

        // Update filter state
        currentFilters[filterType] = filterValue;
        currentFilters.page = 1;
        fetchPlayers();
    }

    // Handle mobile filter option clicks
    function handleMobileFilterOptionClick(e) {
        const option = e.target.closest('.mobile-filter-option');
        if (!option) return;

        const filterType = option.dataset.filter;
        const filterValue = option.dataset.value;

        // Toggle active state within the same filter group
        const siblings = option.parentElement.querySelectorAll('.mobile-filter-option');
        siblings.forEach(s => s.classList.remove('active'));
        option.classList.add('active');

        // Update filter state
        currentFilters[filterType] = filterValue;
    }

    // Handle mobile filter panel toggle
    function toggleMobileFilterPanel() {
        const isVisible = mobileFilterPanel.style.display !== 'none';
        mobileFilterPanel.style.display = isVisible ? 'none' : 'block';
        mobileFilterToggle.classList.toggle('active', !isVisible);
    }

    // Handle mobile filter apply
    function applyMobileFilters() {
        currentFilters.page = 1;
        fetchPlayers();
        mobileFilterPanel.style.display = 'none';
        mobileFilterToggle.classList.remove('active');
        updateActiveFilterCount();
    }

    // Handle mobile clear all filters
    function clearMobileFilters() {
        // Reset all mobile filter options
        document.querySelectorAll('.mobile-filter-option').forEach(option => {
            option.classList.remove('active');
            // Set first option (All) as active
            if (option.dataset.value === '') {
                option.classList.add('active');
            }
        });

        // Reset filter state
        currentFilters = { search: '', gender: '', category: '', page: 1 };
        if (searchInput) searchInput.value = '';
        updateClearSearchVisibility();
        updateActiveFilterCount();
    }

    // Handle player item expansion (mobile)
    function handlePlayerExpansion(e) {
        if (!isMobile) return;

        const btn = e.target.closest('.expand-player-btn');
        if (!btn) return;

        const playerId = btn.dataset.playerId;
        const detailsEl = document.getElementById(`details-${playerId}`);

        if (detailsEl) {
            const isExpanded = detailsEl.style.display !== 'none';
            detailsEl.style.display = isExpanded ? 'none' : 'block';
            btn.classList.toggle('expanded', !isExpanded);
        }
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            updateClearSearchVisibility();
            debounceSearch();
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            updateClearSearchVisibility();
            currentFilters.search = '';
            currentFilters.page = 1;
            fetchPlayers();
        });
    }

    // Filter chip clicks (desktop)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.filter-chip')) {
            handleFilterChipClick(e);
        }
    });

    // Mobile filter interactions
    if (mobileFilterToggle) {
        mobileFilterToggle.addEventListener('click', toggleMobileFilterPanel);
    }

    if (mobileApplyBtn) {
        mobileApplyBtn.addEventListener('click', applyMobileFilters);
    }

    if (mobileClearBtn) {
        mobileClearBtn.addEventListener('click', clearMobileFilters);
    }

    // Mobile filter option clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.mobile-filter-option')) {
            handleMobileFilterOptionClick(e);
        }
    });

    // Player expansion (mobile)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.expand-player-btn')) {
            handlePlayerExpansion(e);
        }
    });

    // Pagination buttons
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentFilters.page > 1) {
                currentFilters.page--;
                fetchPlayers();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentFilters.page++;
            fetchPlayers();
        });
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        currentFilters = {
            search: urlParams.get('search') || '',
            gender: urlParams.get('gender') || '',
            category: urlParams.get('category') || '',
            page: parseInt(urlParams.get('page')) || 1
        };

        if (searchInput) searchInput.value = currentFilters.search;
        updateClearSearchVisibility();
        updateActiveFilterCount();
        fetchPlayers();
    });

    // Handle window resize for responsive behavior
    window.addEventListener('resize', function() {
        const wasMobile = isMobile;
        isMobile = window.innerWidth < 768;

        if (wasMobile !== isMobile) {
            // Refetch with appropriate page size
            fetchPlayers();
        }
    });

    // Initialize
    updateClearSearchVisibility();
    updateActiveFilterCount();

    // Auto-apply URL filters on page load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('gender') || urlParams.has('category')) {
        currentFilters = {
            search: urlParams.get('search') || '',
            gender: urlParams.get('gender') || '',
            category: urlParams.get('category') || '',
            page: parseInt(urlParams.get('page')) || 1
        };

        if (searchInput) searchInput.value = currentFilters.search;
        updateClearSearchVisibility();
        updateActiveFilterCount();
        fetchPlayers();
    }
});
</script>
@endpush

@endsection

