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
    @if($players->count() > 0)
    <div class="players-grid">
        @foreach($players as $player)
        <a href="{{ route('players.show', $player->id) }}" class="player-card">
            <div class="player-image">
                @if($player->image_url)
                <img src="{{ $player->image_url }}" alt="{{ $player->name }}" loading="lazy">
                @else
                <div class="player-placeholder">
                    {{ strtoupper(substr($player->name, 0, 1)) }}
                </div>
                @endif
            </div>

            <div class="player-info">
                <h3 class="player-name">{{ $player->name }}</h3>
                <p class="player-position">{{ ucfirst($player->position) }}</p>

                <div class="player-details">
                    <span class="player-age">Age: {{ $player->age }}</span>
                    <div class="player-badges">
                        @if($player->youtube_url)
                        <span class="youtube-indicator" title="Has Video">
                            <svg class="youtube-icon" fill="currentColor" viewBox="0 0 24 24" width="14" height="14">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </span>
                        @endif
                        @if($player->goals > 0 || $player->assists > 0)
                        <span class="stats-indicator" title="Performance Stats Available">
                            <i class="fas fa-chart-bar"></i>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>

        <h3 class="empty-title">No Players Found</h3>
        <p class="empty-text">Add player images to automatically populate this page.</p>

        <div class="empty-instructions">
            <p><strong>Directory:</strong> <code>public/assets/img/players/</code></p>
            <p><strong>Format:</strong> <code>firstname-surname-category-position-age.jpg</code></p>
            <p><strong>Example:</strong> <code>john-doe-u16-midfielder-15.jpg</code></p>
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

    <!-- Pagination -->
    @if($players->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $players->firstItem() }} to {{ $players->lastItem() }} of {{ $players->total() }} results
        </div>
        <div class="pagination-links">
            @if($players->onFirstPage())
                <span class="pagination-prev disabled">« Previous</span>
            @else
                <a href="{{ $players->previousPageUrl() }}" class="pagination-prev">« Previous</a>
            @endif

            @if($players->hasMorePages())
                <a href="{{ $players->nextPageUrl() }}" class="pagination-next">Next »</a>
            @else
                <span class="pagination-next disabled">Next »</span>
            @endif
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
/* ========================================
   CONTAINER
   ======================================== */
.players-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* ========================================
   PAGE HEADER
   ======================================== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

/* ========================================
   SYNC BUTTON
   ======================================== */
.sync-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #2563eb;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.sync-button:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.sync-icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* ========================================
   SUCCESS MESSAGE
   ======================================== */
.success-message {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 0.875rem 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.success-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

/* ========================================
   CATEGORY SECTION
   ======================================== */
.category-section {
    margin-bottom: 3rem;
}

.category-header {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #3b82f6;
}

.player-count {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 400;
}

/* ========================================
   PLAYERS GRID
   ======================================== */
.players-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}

/* ========================================
   PLAYER CARD
   ======================================== */
.player-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}

.player-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
}

/* Player Image */
.player-image {
    width: 100%;
    aspect-ratio: 1;
    overflow: hidden;
    background: #f3f4f6;
    position: relative;
}

.player-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.player-card:hover .player-image img {
    transform: scale(1.05);
}

.player-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    font-size: 2.5rem;
    color: white;
    font-weight: 700;
}

/* Player Info */
.player-info {
    padding: 1rem;
}

.player-name {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
}

.player-position {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 0.5rem 0;
}

.player-details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.7rem;
    color: #9ca3af;
}

.player-badges {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.youtube-indicator {
    color: #ff0000;
    display: flex;
    align-items: center;
    transition: opacity 0.3s ease;
}

.youtube-icon {
    width: 1rem;
    height: 1rem;
}

.youtube-indicator:hover {
    opacity: 0.8;
}

.player-age {
    font-weight: 500;
}

.stats-indicator {
    color: #2563eb;
    font-size: 0.75rem;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.stats-indicator:hover {
    opacity: 1;
}

/* ========================================
   EMPTY STATE
   ======================================== */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
}

.empty-icon {
    width: 6rem;
    height: 6rem;
    color: #d1d5db;
    margin: 0 auto 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 1rem 0;
}

.empty-text {
    color: #6b7280;
    margin: 0 0 1.5rem 0;
    font-size: 1rem;
}

.empty-instructions {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 0 auto 1.5rem;
    max-width: 600px;
    text-align: left;
}

.empty-instructions p {
    margin: 0 0 0.5rem 0;
    color: #374151;
}

.empty-instructions p:last-child {
    margin-bottom: 0;
}

.empty-instructions code {
    background: #fff;
    padding: 0.125rem 0.5rem;
    border-radius: 0.25rem;
    font-family: monospace;
    font-size: 0.875rem;
    color: #2563eb;
    border: 1px solid #e5e7eb;
}

/* ========================================
   PAGINATION
   ======================================== */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.pagination-container .pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.pagination-container .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0.5rem 0.75rem;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination-container .page-link:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

.pagination-container .page-item.active .page-link {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.pagination-container .page-item.disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */

/* Desktop - 4 columns */
@media (min-width: 1025px) {
    .players-container {
        padding: 2.5rem 2rem;
    }

    .players-grid {
        gap: 1.5rem;
    }

    .player-name {
        font-size: 1.125rem;
    }

    .player-position {
        font-size: 0.875rem;
    }

    .player-details {
        font-size: 0.75rem;
    }

    .player-placeholder {
        font-size: 3rem;
    }
}

/* Tablet - 4 columns */
@media (min-width: 769px) and (max-width: 1024px) {
    .players-grid {
        gap: 1rem;
    }

    .player-info {
        padding: 0.875rem;
    }

    .player-name {
        font-size: 0.95rem;
    }
}

/* Mobile - 4 columns with smaller cards */
@media (max-width: 768px) {
    .players-container {
        padding: 1.5rem 0.75rem;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .category-header {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .players-grid {
        gap: 0.5rem;
    }

    .player-info {
        padding: 0.5rem;
    }

    .player-name {
        font-size: 0.75rem;
        margin-bottom: 0.125rem;
    }

    .player-position {
        font-size: 0.625rem;
        margin-bottom: 0.25rem;
    }

    .player-details {
        font-size: 0.625rem;
    }

    .player-age {
        font-size: 0.6rem;
    }

    .stats-indicator {
        font-size: 0.625rem;
    }

    .player-placeholder {
        font-size: 2rem;
    }

    .sync-button {
        width: 100%;
        justify-content: center;
    }
}

/* Very Small Mobile - 4 columns extra compact */
@media (max-width: 480px) {
    .players-container {
        padding: 1rem 0.5rem;
    }

    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .category-header {
        font-size: 1.125rem;
    }

    .players-grid {
        gap: 0.375rem;
    }

    .player-info {
        padding: 0.375rem;
    }

    .player-name {
        font-size: 0.7rem;
    }

    .player-position {
        font-size: 0.575rem;
    }

    .player-details {
        font-size: 0.575rem;
    }

    .player-placeholder {
        font-size: 1.75rem;
    }
}
</style>
@endpush
@endsection
