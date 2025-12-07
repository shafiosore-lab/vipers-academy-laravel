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

    /* Player Header Card */
    .player-header {
        display: flex;
        gap: 30px;
        align-items: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    /* Photo */
    .player-photo {
        width: 280px;
        height: 320px;
        object-fit: cover;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
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

    /* Info Section */
    .player-info {
        flex: 1;
        position: relative;
        padding-top: 140px;
        min-height: 320px;
    }

    /* Radar Chart */
    .player-radar-container {
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
    }

    .player-radar-container canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* Player Details */
    .player-details {
        position: relative;
        z-index: 1;
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
        max-width: 600px;
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
        @include('players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link">Biography</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link active">AI Insights</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @include('players.partials.ai-insights', ['player' => $player])
        </div>
    </div>
</div>
@endsection
