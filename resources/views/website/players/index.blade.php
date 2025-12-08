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

    <!-- Players by Category -->
    @forelse($categories as $categoryKey => $players)
        <section class="category-section">
            <h2 class="category-header">
                {{ ucwords(str_replace('-', ' ', $categoryKey)) }}
                <span class="player-count">({{ $players->count() }})</span>
            </h2>

            <div class="players-grid">
                @foreach($players as $player)
                    <a href="{{ route('players.stats', $player->id) }}" class="player-card">
                        <div class="player-image">
                            @if($player->image_url)
                                <img src="{{ $player->image_url }}"
                                     alt="{{ $player->full_name }}"
                                     loading="lazy">
                            @else
                                <div class="player-placeholder">
                                    {{ strtoupper(substr($player->first_name, 0, 1)) }}{{ strtoupper(substr($player->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="player-info">
                            <h3 class="player-name">{{ $player->full_name }}</h3>
                            <p class="player-position">{{ $player->formatted_position }}</p>

                            <div class="player-details">
                                <span class="player-age">Age: {{ $player->age }}</span>
                                @if($player->jersey_number)
                                    <span class="player-number">#{{ $player->jersey_number }}</span>
                                @endif
                                @if(($player->goals ?? 0) + ($player->assists ?? 0) + ($player->appearances ?? 0) > 0)
                                    <span class="ai-indicator" title="AI Performance Analysis Available">🤖</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @empty
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
    @endforelse
</div>

@push('styles')
<style>
    /* Container */
    .players-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* Sync Button */
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

    /* Success Message */
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

    /* Category Section */
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

    /* Players Grid */
    .players-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    /* Player Card */
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
        font-size: 3rem;
        color: white;
        font-weight: 700;
    }

    /* Player Info */
    .player-info {
        padding: 1rem;
    }

    .player-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.25rem 0;
    }

    .player-position {
        font-size: 0.875rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 0.5rem 0;
    }

    .player-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .player-number {
        font-weight: 600;
    }

    .ai-indicator {
        font-size: 14px;
        margin-left: 8px;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .ai-indicator:hover {
        opacity: 1;
    }


    /* Empty State */
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

    /* Responsive Design */
    @media (max-width: 1024px) {
        .players-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .players-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .category-header {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .players-container {
            padding: 1rem 0.75rem;
        }

        .players-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
@endsection
