{{-- Player Statistics Partial --}}
@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <div class="stats-compact">
        <div class="stat-item">
            <span class="stat-icon">⚽</span>
            <div class="stat-info">
                <span class="stat-value">{{ $player->goals ?? 0 }}</span>
                <span class="stat-label">Goals</span>
            </div>
        </div>
        <div class="stat-item">
            <span class="stat-icon">🎯</span>
            <div class="stat-info">
                <span class="stat-value">{{ $player->assists ?? 0 }}</span>
                <span class="stat-label">Assists</span>
            </div>
        </div>
        <div class="stat-item">
            <span class="stat-icon">👕</span>
            <div class="stat-info">
                <span class="stat-value">{{ $player->appearances ?? 0 }}</span>
                <span class="stat-label">Appearances</span>
            </div>
        </div>
        <div class="stat-item">
            <span class="stat-icon">⭐</span>
            <div class="stat-info">
                <span class="stat-value">{{ number_format(($player->goals + $player->assists) / max($player->appearances, 1), 2) }}</span>
                <span class="stat-label">Avg. Contribution</span>
            </div>
        </div>
    </div>

    <div class="performance-chart">
        <h4>Season Performance</h4>
        <canvas id="performanceChart"></canvas>
    </div>
@endif
