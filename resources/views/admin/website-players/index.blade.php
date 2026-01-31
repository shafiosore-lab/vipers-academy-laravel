@extends('layouts.admin')

@section('title', 'Website Players - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Website Players Gallery</h1>
        <div>
            <a href="{{ route('admin.website-players.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Player
            </a>
            <a href="{{ route('admin.players.sync-gallery') }}" class="btn btn-success">
                <i class="fas fa-sync me-2"></i>Sync from Gallery
            </a>
        </div>
    </div>

    @if($players->count() > 0)
        <!-- Players Grid - Responsive: 4 cols phones, 2 cols tablets, 4 cols desktops -->
        <div class="row g-3">
            @foreach($players as $player)
                <div class="col-lg-3 col-md-6 col-3">
                    <div class="player-card">
                        <!-- Player Image -->
                        <div class="player-image-container">
                            @if($player->image_url)
                                <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}" class="player-image">
                            @else
                                <div class="player-placeholder">
                                    {{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Player Info -->
                        <div class="player-info">
                            <h5 class="player-name">{{ $player->full_name }}</h5>
                            <div class="player-details">
                                <span class="player-category">{{ $player->formatted_category }}</span>
                                <span class="player-position">{{ $player->formatted_position }}</span>
                                @if($player->jersey_number)
                                    <span class="jersey-number">#{{ $player->jersey_number }}</span>
                                @endif
                            </div>

                            <!-- Action Links -->
                            <div class="player-actions">
                                <a href="{{ route('admin.website-players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.website-players.edit', $player) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($player->youtube_url)
                                    <a href="{{ $player->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fab fa-youtube"></i> Highlights
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $players->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state-icon">
                <i class="fas fa-users"></i>
            </div>
            <h4 class="empty-state-title">No Players Added Yet</h4>
            <p class="empty-state-text">Start by adding players to display on the website or sync from the gallery.</p>
            <div class="empty-state-actions">
                <a href="{{ route('admin.website-players.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>Add First Player
                </a>
                <a href="{{ route('admin.players.sync-gallery') }}" class="btn btn-success">
                    <i class="fas fa-sync me-2"></i>Sync from Gallery
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Player Card Styles */
    .player-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .player-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-4px);
    }

    /* Player Image */
    .player-image-container {
        width: 100%;
        aspect-ratio: 1;
        overflow: hidden;
        position: relative;
    }

    .player-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .player-card:hover .player-image {
        transform: scale(1.05);
    }

    .player-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 2rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Player Info */
    .player-info {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .player-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .player-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .player-category,
    .player-position {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .jersey-number {
        font-size: 0.8rem;
        color: #ea1c4d;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    /* Player Actions */
    .player-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: auto;
    }

    .player-actions .btn {
        flex: 1;
        min-width: 0;
        font-size: 0.8rem;
        padding: 0.375rem 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .player-actions .btn:hover {
        transform: translateY(-1px);
    }

    /* Empty State */
    .empty-state-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #6b7280;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .empty-state-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Responsive Grid - 4 cols phones, 2 cols tablets, 4 cols desktops */
    @media (max-width: 576px) {
        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    /* Ensure no horizontal scrolling */
    .container-fluid {
        overflow-x: hidden;
    }

    /* Custom button styles for better spacing */
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush
