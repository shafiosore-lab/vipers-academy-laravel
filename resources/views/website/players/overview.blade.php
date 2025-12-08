@extends('layouts.academy')

@section('content')
<style>
    :root {
        --primary: #ea1c4d;
        --primary-light: #f05a7a;
        --secondary: #65c16e;
        --dark: #1e293b;
        --gray: #64748b;
        --medium: #374151;
        --light: #f8fafc;
        --radius-lg: 20px;
        --radius-md: 15px;
        --radius-sm: 10px;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Container */
    .vipers-player-container {
        background: linear-gradient(135deg, var(--light) 0%, #f1f5f9 50%, #e2e8f0 100%);
        color: var(--dark);
        padding: 40px 20px;
        min-height: 100vh;
    }

    .player-profile {
        max-width: 1150px;
        margin: 0 auto;
    }

    /* Player Header Card */
    .player-header {
        display: flex;
        gap: 30px;
        align-items: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    /* Photo */
    .player-photo {
        width: 280px;
        height: 320px;
        object-fit: cover;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .player-photo-placeholder {
        background: linear-gradient(135deg, #1a3a1a 0%, #0a1f0a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        font-weight: bold;
        color: var(--secondary);
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
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .player-position {
        font-size: 20px;
        color: var(--primary);
        margin-bottom: 15px;
        font-weight: 600;
    }

    .player-description {
        color: var(--gray);
        font-size: 16px;
        line-height: 1.6;
        max-width: 600px;
    }

    /* Navigation Tabs */
    .section-nav {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .nav-link {
        padding: 12px 30px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: 2px solid transparent;
        transition: var(--transition);
        font-weight: 600;
        color: var(--gray);
        box-shadow: var(--shadow-sm);
    }

    .nav-link:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.2);
        color: var(--primary);
    }

    .nav-link.active {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(234, 28, 77, 0.05);
    }

    /* Content Section */
    .content-section {
        margin-top: 40px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .player-header {
            flex-direction: column;
            text-align: center;
        }

        .player-photo {
            width: 100%;
            max-width: 280px;
        }

        .player-info {
            padding-top: 160px;
            width: 100%;
        }

        .player-radar-container {
            width: 180px;
            height: 180px;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
        }

        .player-name {
            font-size: 32px;
        }

        .player-description {
            max-width: 100%;
        }

        .section-nav {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .vipers-player-container {
            padding: 20px 10px;
        }

        .player-header {
            padding: 20px;
        }

        .player-info {
            padding-top: 140px;
        }

        .player-radar-container {
            width: 150px;
            height: 150px;
        }

        .player-name {
            font-size: 28px;
        }

        .player-position {
            font-size: 18px;
        }

        .nav-link {
            padding: 10px 20px;
            font-size: 14px;
        }
    }
</style>

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <nav class="section-nav">
            <a href="{{ route('players.biography', $player->id) }}"
               class="nav-link {{ request()->routeIs('players.biography') ? 'active' : '' }}">
                Biography
            </a>
            <a href="{{ route('players.statistics', $player->id) }}"
               class="nav-link {{ request()->routeIs('players.statistics') ? 'active' : '' }}">
                Statistics
            </a>
            <a href="{{ route('players.ai-insights', $player->id) }}"
               class="nav-link {{ request()->routeIs('players.ai-insights') ? 'active' : '' }}">
                AI Insights
            </a>
        </nav>

        <!-- Content -->
        <div class="content-section">
            @include('website.players.partials.overview', ['player' => $player, 'allPlayers' => $allPlayers])
        </div>
    </div>
</div>

@endsection
