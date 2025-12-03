@extends('layouts.academy')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .players-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        color: #111827;
    }

    .sync-button {
        background: #2563eb;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .sync-button:hover {
        background: #1d4ed8;
    }

    .success-message {
        background: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

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

    .players-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
    }

    .player-card {
        width: calc(25% - 18px);
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .player-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-4px);
    }

    .player-image {
        width: 100%;
        aspect-ratio: 1;
        overflow: hidden;
        background: #e5e7eb;
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
        font-size: 4rem;
        color: white;
        font-weight: bold;
    }

    .player-info {
        padding: 16px;
    }

    .player-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .player-position {
        font-size: 0.875rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .player-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .player-age {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .player-number {
        font-size: 0.75rem;
        color: #9ca3af;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }

    .empty-icon {
        margin: 0 auto 1rem;
        width: 96px;
        height: 96px;
        color: #d1d5db;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .empty-code {
        background: #f3f4f6;
        padding: 2px 8px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .player-card {
            width: calc(33.333% - 16px);
        }
    }

    @media (max-width: 768px) {
        .player-card {
            width: calc(50% - 12px);
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .player-card {
            width: 100%;
        }
    }
</style>

<div class="players-container">
    <div class="page-header">
        <h1 class="page-title">Our Players</h1>

        @if(auth()->check() && auth()->user()->is_admin)
        <a href="{{ route('players.sync') }}" class="sync-button">
            Sync Players from Gallery
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif

    @forelse($categories as $categoryKey => $players)
    <div class="category-section">
        <h2 class="category-header">
            {{ ucwords(str_replace('-', ' ', $categoryKey)) }}
        </h2>

        <div class="players-grid">
            @foreach($players as $player)
            <a href="{{ route('players.stats', $player->id) }}" class="player-card">
                <div class="player-image">
                    @if($player->image_url)
                    <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}">
                    @else
                    <div class="player-placeholder">
                        {{ strtoupper(substr($player->first_name, 0, 1)) }}
                    </div>
                    @endif
                </div>

                <div class="player-info">
                    <div class="player-name">
                        {{ $player->full_name }}
                    </div>
                    <div class="player-position">
                        {{ $player->formatted_position }}
                    </div>
                    <div class="player-details">
                        <span class="player-age">
                            Age: {{ $player->age }}
                        </span>
                        @if($player->jersey_number)
                        <span class="player-number">
                            #{{ $player->jersey_number }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 class="empty-title">No Players Found</h3>
        <p class="empty-text">
            Add player images to <span class="empty-code">public/assets/img/players/</span>
        </p>
        <p class="empty-text">
            Format: <span class="empty-code">firstname-surname-category-position-age.jpg</span>
        </p>
        @if(auth()->check() && auth()->user()->is_admin)
        <a href="{{ route('players.sync') }}" class="sync-button" style="display: inline-block; margin-top: 1rem;">
            Sync Players from Gallery
        </a>
        @endif
    </div>
    @endforelse
</div>

@endsection
