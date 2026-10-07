@extends('layouts.academy')

@section('title', 'Player Rankings - Vipers Academy')

@section('content')
<style>
    :root {
        --primary-color: #ea1c4d;
        --primary-gradient: linear-gradient(135deg, #ea1c4d 0%, #f05a7a 100%);
        --gold: #ffd700;
        --silver: #c0c0c0;
        --bronze: #cd7f32;
        --text-dark: #1e293b;
        --text-gray: #64748b;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .rankings-container {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        color: var(--text-dark);
        padding: 40px 20px;
        min-height: 100vh;
    }

    .rankings-header {
        max-width: 1200px;
        margin: 0 auto 40px;
        text-align: center;
    }

    .rankings-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }

    .rankings-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
    }

    .rankings-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 30px;
    }

    .ranking-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .ranking-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .ranking-header i {
        font-size: 1.5rem;
    }

    .ranking-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .ranking-list {
        padding: 0;
        list-style: none;
    }

    .ranking-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .ranking-item:last-child {
        border-bottom: none;
    }

    .ranking-item:hover {
        background: rgba(234, 28, 77, 0.03);
    }

    .ranking-position {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        margin-right: 15px;
    }

    .position-1 {
        background: linear-gradient(135deg, #ffd700, #ffed4a);
        color: #92400e;
    }

    .position-2 {
        background: linear-gradient(135deg, #c0c0c0, #e5e5e5);
        color: #4b5563;
    }

    .position-3 {
        background: linear-gradient(135deg, #cd7f32, #d4a574);
        color: white;
    }

    .position-default {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-gray);
    }

    .player-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .player-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 15px;
    }

    .player-info {
        flex: 1;
    }

    .player-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 2px;
    }

    .player-position {
        font-size: 0.8rem;
        color: var(--text-gray);
        text-transform: uppercase;
    }

    .player-stats {
        text-align: right;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-gray);
        text-transform: uppercase;
    }

    @media (max-width: 768px) {
        .rankings-grid {
            grid-template-columns: 1fr;
        }

        .rankings-title {
            font-size: 2rem;
        }

        .ranking-item {
            padding: 12px 15px;
        }

        .ranking-position {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="rankings-container">
    <div class="rankings-header">
        <h1 class="rankings-title">Player Rankings</h1>
        <p class="rankings-subtitle">Top performers at Vipers Academy</p>
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
