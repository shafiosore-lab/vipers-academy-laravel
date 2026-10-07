@extends('layouts.admin')

@section('title', 'Website Players - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Website Players</h1>
        <div>
            <a href="{{ route('admin.website-players.create') }}" class="btn btn-sm btn-primary me-1">
                <i class="fas fa-plus me-1"></i>Add
            </a>
            <a href="{{ route('admin.players.sync-gallery') }}" class="btn btn-sm btn-success">
                <i class="fas fa-sync me-1"></i>Sync
            </a>
        </div>
    </div>

    @if($players->count() > 0)
        <!-- Players Grid -->
        <div class="row g-2">
            @foreach($players as $player)
                <div class="col-lg-3 col-md-6 col-6">
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
                                <a href="{{ route('admin.website-players.show', $player) }}" class="btn btn-sm btn-outline-primary py-0">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.website-players.edit', $player) }}" class="btn btn-sm btn-outline-warning py-0">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($player->youtube_url)
                                    <a href="{{ $player->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-danger py-0">
                                        <i class="fab fa-youtube"></i>
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
            <div class="d-flex justify-content-center mt-2">
                {{ $players->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-4">
            <i class="fas fa-users fa-2x text-muted mb-2"></i>
            <h6 class="text-muted">No Players Added</h6>
            <p class="text-muted small mb-2">Add players to display on the website</p>
            <a href="{{ route('admin.website-players.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Add First
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .player-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .player-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

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
    }

    .player-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .player-info {
        padding: 0.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .player-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .player-details {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        margin-bottom: 0.5rem;
    }

    .player-category,
    .player-position {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .jersey-number {
        font-size: 0.7rem;
        color: #ea1c4d;
        font-weight: 600;
    }

    .player-actions {
        display: flex;
        gap: 0.25rem;
        margin-top: auto;
    }

    .player-actions .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
        border-radius: 4px;
    }

    @media (max-width: 576px) {
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    .container-fluid {
        overflow-x: hidden;
    }
</style>
@endpush
