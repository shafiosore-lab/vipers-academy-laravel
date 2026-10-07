@extends('layouts.academy')

@section('title', 'Player Rankings - Mumias Vipers Academy')

<p class="rankings-subtitle">Top performers at Mumias Vipers Academy</p>
    </div>

    <div class="rankings-grid">
        <!-- Top Scorers -->
        <div class="ranking-section">
            <div class="ranking-header">
                <i class="fas fa-trophy"></i>
                <h2>Top Scorers</h2>
            </div>
            <ul class="ranking-list">
                @forelse($topScorers as $index => $player)
                <li class="ranking-item">
                    <div class="ranking-position {{ $index === 0 ? 'position-1' : ($index === 1 ? 'position-2' : ($index === 2 ? 'position-3' : 'position-default') }}">
                        {{ $index + 1 }}
                    </div>
                    @if($player->image_url)
                    <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}" class="player-avatar">
                    @else
                    <div class="player-placeholder">{{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}</div>
                    @endif
                    <div class="player-info">
                        <div class="player-name">{{ $player->full_name }}</div>
                        <div class="player-position">{{ ucfirst($player->position ?? 'Player') }}</div>
                    </div>
                    <div class="player-stats">
                        <div class="stat-value">{{ $player->goals }}</div>
                        <div class="stat-label">Goals</div>
                    </div>
                </li>
                @empty
                <li class="ranking-item">
                    <div class="player-info" style="text-align: center; width: 100%; color: var(--text-gray);">
                        No scoring data available yet
                    </div>
                </li>
                @endforelse
            </ul>
        </div>

        <!-- Top Assists -->
        <div class="ranking-section">
            <div class="ranking-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-hands-helping"></i>
                <h2>Top Assists</h2>
            </div>
            <ul class="ranking-list">
                @forelse($topAssists as $index => $player)
                <li class="ranking-item">
                    <div class="ranking-position {{ $index === 0 ? 'position-1' : ($index === 1 ? 'position-2' : ($index === 2 ? 'position-3' : 'position-default') }}">
                        {{ $index + 1 }}
                    </div>
                    @if($player->image_url)
                    <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}" class="player-avatar">
                    @else
                    <div class="player-placeholder">{{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}</div>
                    @endif
                    <div class="player-info">
                        <div class="player-name">{{ $player->full_name }}</div>
                        <div class="player-position">{{ ucfirst($player->position ?? 'Player') }}</div>
                    </div>
                    <div class="player-stats">
                        <div class="stat-value">{{ $player->assists }}</div>
                        <div class="stat-label">Assists</div>
                    </div>
                </li>
                @empty
                <li class="ranking-item">
                    <div class="player-info" style="text-align: center; width: 100%; color: var(--text-gray);">
                        No assist data available yet
                    </div>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

