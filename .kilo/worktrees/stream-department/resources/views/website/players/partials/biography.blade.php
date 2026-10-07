{{-- Player Biography Partial --}}
@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <div class="player-biography">
        @if($player->bio)
            <p class="bio-text">{{ $player->bio }}</p>
        @else
            <p class="bio-text">
                {{ $player->name }} is a talented {{ strtolower($player->position) }} at Vipers Academy,
                demonstrating exceptional skill and dedication on the field. At {{ $player->age }} years old,
                {{ explode(' ', $player->name)[0] }} has shown remarkable growth and continues to be an
                integral part of the team's development. Known for strong work ethic and commitment to
                excellence, {{ explode(' ', $player->name)[0] }} embodies the values and spirit of
                Vipers Academy both on and off the pitch.
            </p>
        @endif
    </div>

    <style>
        .player-biography {
            background: #fafafa;
            border-radius: 0.75rem;
            padding: 2rem;
            border: 1px solid #e0e0e0;
        }

        .bio-text {
            color: #333;
            font-size: 1.05rem;
            line-height: 1.8;
            margin: 0;
            text-align: justify;
        }

        @media (max-width: 768px) {
            .player-biography {
                padding: 1.5rem;
            }

            .bio-text {
                font-size: 1rem;
                line-height: 1.7;
            }
        }

        @media (max-width: 480px) {
            .player-biography {
                padding: 1.25rem;
            }

            .bio-text {
                font-size: 0.95rem;
                text-align: left;
            }
        }
    </style>
@endif
