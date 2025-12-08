@extends('layouts.academy')

@section('content')
<style>
    :root {
        --primary-color: #ea1c4d;
        --primary-gradient: linear-gradient(135deg, #ea1c4d 0%, #f05a7a 100%);
        --secondary-color: #65c16e;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --text-medium: #374151;
        --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        --card-bg: rgba(255, 255, 255, 0.95);
        --border-radius: 20px;
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .vipers-player-container {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-gradient);
        color: var(--text-dark);
        padding: 40px 20px;
        min-height: 100vh;
    }

    .player-profile {
        max-width: 1150px;
        margin: auto;
    }

    /* Header Section */
    .player-header {
        display: flex;
        gap: 30px;
        align-items: center;
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    .player-photo {
        width: 280px;
        height: 320px;
        object-fit: cover;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .player-photo-placeholder {
        background: linear-gradient(135deg, #1a3a1a 0%, #0a1f0a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        font-weight: bold;
        color: var(--secondary-color);
    }

    .player-info {
        flex: 1;
    }

    .player-name {
        font-size: 42px;
        margin-bottom: 10px;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .player-position {
        font-size: 20px;
        color: var(--primary-color);
        margin-bottom: 15px;
        font-weight: 600;
    }

    .player-description {
        color: var(--text-gray);
        font-size: 16px;
        line-height: 1.6;
    }

    /* Navigation */
    .section-nav {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .nav-link {
        padding: 12px 30px;
        background: var(--card-bg);
        border-radius: 10px;
        text-decoration: none;
        border: 2px solid transparent;
        transition: var(--transition);
        font-weight: 600;
        color: var(--text-gray);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: rgba(234, 28, 77, 0.05);
    }

    .nav-link:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.2);
    }

    /* Content Section */
    .content-section {
        margin-top: 40px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .player-header {
            flex-direction: column;
            text-align: center;
        }

        .player-photo {
            width: 100%;
            max-width: 280px;
        }

        .player-name {
            font-size: 32px;
        }

        .section-nav {
            justify-content: center;
        }
    }
</style>

<div class="vipers-player-container">
    <div class="player-profile">
        <!-- Header -->
        <div class="player-header">
            @if($player->image_path)
                <img src="{{ asset('assets/img/players/' . $player->image_path) }}"
                     alt="{{ $player->name }}"
                     class="player-photo">
            @else
                <div class="player-photo player-photo-placeholder">
                    <span>{{ substr($player->first_name ?? 'P', 0, 1) }}{{ substr($player->last_name ?? 'L', 0, 1) }}</span>
                </div>
            @endif

            <div class="player-info">
                <div class="player-details">
                    <h1 class="player-name">{{ $player->name ?? 'Player Name' }}</h1>
                    <p class="player-position">
                        {{ ucfirst($player->position ?? 'Position') }}
                        @if($player->jersey_number)
                            • Jersey #{{ $player->jersey_number }}
                        @endif
                    </p>
                    <p class="player-description">
                        {{ $player->bio ?? 'A dynamic player known for exceptional skills and dedication on the field.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.overview', $player->id) }}" class="nav-link">Overview</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link">AI Insights</a>
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link">Biography</a>
            <a href="{{ route('players.career', $player->id) }}" class="nav-link active">Career</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @include('website.players.partials.career', ['player' => $player])
        </div>
    </div>
</div>
@endsection
