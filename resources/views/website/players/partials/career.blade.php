{{-- Player Career Partial --}}
{{-- Version: 1.0.0 --}}
{{-- Last Modified: 2025-12-06 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (Player model) --}}
{{-- Validation: Ensures $player is not null --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <h2 class="section-title">Career Timeline</h2>
    <div class="extra-stats">
        <ul>
            <li><strong>Current Position:</strong> {{ ucfirst($player->position ?? 'N/A') }}</li>
            <li><strong>Jersey Number:</strong> {{ $player->jersey_number ?? 'N/A' }}</li>
            <li><strong>Category:</strong> {{ $player->formatted_category ?? 'N/A' }}</li>
            <li><strong>Total Goals:</strong> {{ $player->goals ?? 0 }}</li>
            <li><strong>Total Assists:</strong> {{ $player->assists ?? 0 }}</li>
            <li><strong>Total Appearances:</strong> {{ $player->appearances ?? 0 }}</li>
        </ul>
    </div>
@endif
