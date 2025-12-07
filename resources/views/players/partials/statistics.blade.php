{{-- Player Statistics Partial --}}
{{-- Version: 1.0.0 --}}
{{-- Last Modified: 2025-12-06 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (Player model) --}}
{{-- Validation: Ensures $player is not null --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <h2 class="section-title">Player Statistics</h2>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-value">{{ $player->goals ?? 0 }}</div>
            <div class="stat-label">Goals</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $player->assists ?? 0 }}</div>
            <div class="stat-label">Assists</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $player->appearances ?? 0 }}</div>
            <div class="stat-label">Appearances</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $player->yellow_cards ?? 0 }}</div>
            <div class="stat-label">Yellow Cards</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $player->red_cards ?? 0 }}</div>
            <div class="stat-label">Red Cards</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $player->age ?? 'N/A' }}</div>
            <div class="stat-label">Age</div>
        </div>
    </div>

    <div class="additional-info">
        <div class="info-item">
            <strong>Position:</strong> {{ ucfirst($player->position ?? 'N/A') }}
        </div>
        <div class="info-item">
            <strong>Category:</strong> {{ $player->formatted_category ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Jersey Number:</strong> {{ $player->jersey_number ?? 'N/A' }}
        </div>
    </div>
@endif
