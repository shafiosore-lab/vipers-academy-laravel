{{-- Player Card Partial --}}
@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <!-- Main Player Card -->
    <div class="player-card">
        <div class="player-card-content">
            <!-- Left: Image Section -->
            <div class="player-image-wrapper">
                @if($player->image_url)
                    <img src="{{ $player->image_url }}" alt="{{ $player->name }}" class="player-image">
                @else
                    <div class="player-placeholder">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                @endif
                <div class="player-number">#{{ $player->jersey_number ?? '00' }}</div>
            </div>

            <!-- Middle: Info Section -->
            <div class="player-info">
                <h1 class="player-name">{{ $player->name }}</h1>
                <p class="player-position">{{ ucfirst($player->position) }}</p>

                <div class="player-quick-stats">
                    <div class="quick-stat"><i class="fas fa-calendar"></i> {{ $player->age }} years</div>
                    @if($player->nationality)
                    <div class="quick-stat"><i class="fas fa-flag"></i> {{ $player->nationality }}</div>
                    @endif
                    @if($player->appearances > 0)
                    <div class="quick-stat"><i class="fas fa-futbol"></i> {{ $player->appearances }} apps</div>
                    @endif
                </div>
            </div>

            <!-- Right: Radar Chart -->
            <div class="player-radar">
                <div class="radar-title">Skills Overview</div>
                <canvas id="skillsRadarChart"></canvas>
            </div>
        </div>
    </div>
@endif
