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
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
        margin-bottom: 30px;
    }

    /* Photo */
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

    /* Info Section */
    .player-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 320px;
    }

    .player-details {
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

    /* Radar Chart */
    .player-radar-container {
        width: 100%;
        max-width: 200px;
        height: 200px;
        margin-top: 20px;
        align-self: flex-end;
    }

    .player-radar-container canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* Navigation */
    .section-nav {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 30px;
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
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .vipers-player-container {
            padding: 20px 15px;
        }

        .player-header {
            grid-template-columns: 1fr;
            padding: 25px;
            gap: 25px;
        }

        .player-photo {
            width: 100%;
            max-width: 280px;
            height: 280px;
            margin: 0 auto;
        }

        .player-info {
            min-height: auto;
        }

        .player-radar-container {
            max-width: 180px;
            height: 180px;
            margin: 20px auto 0;
            align-self: center;
        }

        .player-name {
            font-size: 32px;
            text-align: center;
        }

        .player-position {
            font-size: 18px;
            text-align: center;
        }

        .player-description {
            text-align: center;
        }

        .section-nav {
            justify-content: center;
            gap: 10px;
        }

        .nav-link {
            padding: 10px 20px;
            font-size: 14px;
        }

        .content-section {
            padding: 25px 20px;
        }
    }

    @media (max-width: 480px) {
        .vipers-player-container {
            padding: 15px 10px;
        }

        .player-header {
            padding: 20px;
            gap: 20px;
        }

        .player-photo {
            max-width: 240px;
            height: 240px;
        }

        .player-radar-container {
            max-width: 150px;
            height: 150px;
            margin-top: 15px;
        }

        .player-name {
            font-size: 26px;
        }

        .player-position {
            font-size: 16px;
        }

        .player-description {
            font-size: 14px;
        }

        .section-nav {
            gap: 8px;
        }

        .nav-link {
            padding: 8px 16px;
            font-size: 13px;
            flex: 1;
            min-width: 100px;
            text-align: center;
        }

        .content-section {
            padding: 20px 15px;
        }
    }
</style>

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link active">Biography</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link">AI Insights</a>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            @include('website.players.partials.biography', ['player' => $player])
        </div>
    </div>
</div>
@endsection
