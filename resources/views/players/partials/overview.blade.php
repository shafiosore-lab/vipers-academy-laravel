{{-- Player Statistics Overview Partial --}}
{{-- Version: 1.0.0 --}}
{{-- Last Modified: 2025-12-06 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (Player model), $allPlayers (collection) --}}
{{-- Validation: Ensures $player is not null and has required attributes --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    {{-- Overview section content removed as requested --}}
@endif
