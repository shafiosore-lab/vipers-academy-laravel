{{-- Player Biography Partial --}}
{{-- Version: 1.0.0 --}}
{{-- Last Modified: 2025-12-06 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (Player model) --}}
{{-- Validation: Ensures $player is not null --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <h2 class="section-title">Player Biography</h2>
    <div class="content-box">
        @if(!empty($player->bio))
            <p>{{ $player->bio }}</p>
        @else
            <p style="color: #64748b; font-style: italic;">No biography available for this player yet.</p>
        @endif
    </div>
@endif
