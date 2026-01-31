@extends('layouts.academy')

@section('title', $player->name . ' - Overview - Vipers Academy')

@section('content')
@include('website.players.partials.shared-styles')

<div class="vipers-player-container">
    <div class="player-profile">
        @include('website.players.partials.shared-header', ['player' => $player])

        <!-- Navigation -->
        <div class="section-nav">
            <a href="{{ route('players.overview', $player->id) }}" class="nav-link active">Overview</a>
            <a href="{{ route('players.statistics', $player->id) }}" class="nav-link">Statistics</a>
            <a href="{{ route('players.ai-insights', $player->id) }}" class="nav-link">AI Insights</a>
            <a href="{{ route('players.biography', $player->id) }}" class="nav-link">Biography</a>
            <a href="{{ route('players.career', $player->id) }}" class="nav-link">Career</a>
        </div>

        <!-- Content Section - Player Overview -->
        <div class="content-section">
            <!-- Quick Stats Grid -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $player->appearances ?? 0 }}</div>
                    <div class="stat-label">Appearances</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $player->goals ?? 0 }}</div>
                    <div class="stat-label">Goals</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $player->assists ?? 0 }}</div>
                    <div class="stat-label">Assists</div>
                </div>
                @if($player->position === 'Goalkeeper')
                <div class="stat-item">
                    <div class="stat-value">{{ $player->clean_sheets ?? 0 }}</div>
                    <div class="stat-label">Clean Sheets</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $player->saves ?? 0 }}</div>
                    <div class="stat-label">Saves</div>
                </div>
                @else
                <div class="stat-item">
                    <div class="stat-value">{{ $player->yellow_cards ?? 0 }}</div>
                    <div class="stat-label">Yellow Cards</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $player->red_cards ?? 0 }}</div>
                    <div class="stat-label">Red Cards</div>
                </div>
                @endif
            </div>

            <!-- Player Bio Summary -->
            <div class="bio-section">
                <h3 class="section-title">Player Profile</h3>
                <div class="profile-grid">
                    @if($player->age)
                    <div class="profile-item">
                        <strong>Age:</strong> {{ $player->age }} years
                    </div>
                    @endif
                    @if($player->nationality)
                    <div class="profile-item">
                        <strong>Nationality:</strong> {{ $player->nationality }}
                    </div>
                    @endif
                    @if($player->position)
                    <div class="profile-item">
                        <strong>Position:</strong> {{ ucfirst($player->position) }}
                    </div>
                    @endif
                    @if($player->jersey_number)
                    <div class="profile-item">
                        <strong>Jersey Number:</strong> #{{ $player->jersey_number }}
                    </div>
                    @endif
                    @if($player->height)
                    <div class="profile-item">
                        <strong>Height:</strong> {{ $player->height }}
                    </div>
                    @endif
                    @if($player->weight)
                    <div class="profile-item">
                        <strong>Weight:</strong> {{ $player->weight }}
                    </div>
                    @endif
                    @if($player->preferred_foot)
                    <div class="profile-item">
                        <strong>Preferred Foot:</strong> {{ ucfirst($player->preferred_foot) }}
                    </div>
                    @endif
                    @if($player->created_at)
                    <div class="profile-item">
                        <strong>Joined Academy:</strong> {{ $player->created_at->format('M Y') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Performance -->
            @if(isset($player->recentForm) && $player->recentForm->isNotEmpty())
            <div class="bio-section">
                <h3 class="section-title">Recent Form</h3>
                <div class="recent-form">
                    @foreach($player->recentForm as $match)
                    <div class="form-indicator" title="{{ $match->competition ?? 'Match' }} - {{ $match->date->format('d M') }}">
                        @if($match->result === 'W')
                        <span class="form-win">W</span>
                        @elseif($match->result === 'D')
                        <span class="form-draw">D</span>
                        @else
                        <span class="form-loss">L</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Key Highlights -->
            @if($player->bio || $player->highlights)
            <div class="bio-section">
                <h3 class="section-title">Highlights</h3>
                <ul class="highlights-list">
                    @if($player->bio)
                    <li>{{ Str::limit($player->bio, 200) }}</li>
                    @endif
                    @if(isset($player->highlights) && is_array($player->highlights))
                    @foreach($player->highlights as $highlight)
                    <li>{{ $highlight }}</li>
                    @endforeach
                    @endif
                </ul>
            </div>
            @endif

            <!-- Quick Links -->
            <div class="quick-links">
                <a href="{{ route('players.statistics', $player->id) }}" class="btn btn-primary">
                    <i class="fas fa-chart-bar"></i> View Full Statistics
                </a>
                <a href="{{ route('players.biography', $player->id) }}" class="btn btn-secondary">
                    <i class="fas fa-user"></i> Read Biography
                </a>
                <a href="{{ route('players.career', $player->id) }}" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Career History
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Overview-specific styles */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-item {
        background: rgba(234, 28, 77, 0.05);
        padding: 20px 15px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid rgba(234, 28, 77, 0.2);
        transition: var(--transition);
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(234, 28, 77, 0.15);
    }

    .stat-value {
        font-size: 32px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-gray);
        font-weight: 500;
    }

    .bio-section {
        margin-bottom: 30px;
    }

    .bio-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(234, 28, 77, 0.2);
        display: inline-block;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .profile-item {
        background: rgba(255, 255, 255, 0.8);
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
        font-size: 0.95rem;
    }

    .profile-item strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    .highlights-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .highlights-list li {
        position: relative;
        padding: 12px 0 12px 30px;
        color: var(--text-medium);
        line-height: 1.6;
        border-bottom: 1px solid rgba(234, 28, 77, 0.1);
    }

    .highlights-list li:last-child {
        border-bottom: none;
    }

    .highlights-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 12px;
        color: var(--secondary-color);
        font-weight: bold;
        font-size: 1.1rem;
    }

    .quick-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid rgba(234, 28, 77, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(234, 28, 77, 0.3);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-secondary:hover {
        background: var(--primary-color);
        color: white;
    }

    .recent-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .form-indicator {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .form-win {
        background: #10b981;
        color: white;
    }

    .form-draw {
        background: #f59e0b;
        color: white;
    }

    .form-loss {
        background: #ef4444;
        color: white;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-item {
            padding: 15px 10px;
        }

        .profile-grid {
            grid-template-columns: 1fr 1fr;
        }

        .quick-links {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
