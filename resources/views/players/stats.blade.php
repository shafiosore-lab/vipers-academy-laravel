@extends('layouts.academy')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .vipers-player-container {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        color: #1e293b;
        padding: 40px 20px;
        min-height: 100vh;
    }

    .player-profile {
        max-width: 1150px;
        margin: auto;
    }

    /* HEADER */
    .player-header {
        display: flex;
        gap: 30px;
        align-items: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(234, 28, 77, 0.2);
    }

    .player-photo {
        width: 280px;
        height: 320px;
        object-fit: cover;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .player-info {
        flex: 1;
    }

    .player-name {
        font-size: 42px;
        margin-bottom: 10px;
        font-weight: 700;
        background: linear-gradient(135deg, #ea1c4d 0%, #f05a7a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .player-position {
        font-size: 20px;
        color: #ea1c4d;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .player-description {
        color: #64748b;
        font-size: 16px;
        line-height: 1.6;
    }

    /* TABS */
    .tabs {
        margin-top: 40px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 12px 30px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #64748b;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .tab.active {
        border-color: #ea1c4d;
        color: #ea1c4d;
        background: rgba(234, 28, 77, 0.05);
    }

    .tab:hover {
        border-color: #ea1c4d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.2);
    }

    .tab-content {
        margin-top: 35px;
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .tab-content.active {
        display: block;
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

    .section-title {
        color: #ea1c4d;
        font-size: 28px;
        margin-bottom: 25px;
        font-weight: 700;
    }

    /* METRIC CARDS */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.9);
        padding: 25px;
        border-radius: 18px;
        text-align: center;
        border-bottom: 4px solid #ea1c4d;
        transition: all 0.3s ease;
        border: 1px solid rgba(234, 28, 77, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .metric-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(234, 28, 77, 0.2);
        border-bottom-color: #ea1c4d;
    }

    .metric-value {
        font-size: 40px;
        font-weight: bold;
        background: linear-gradient(135deg, #ea1c4d 0%, #f05a7a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fadeUp 1s ease forwards;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .metric-label {
        font-size: 15px;
        color: #64748b;
        margin-top: 8px;
        font-weight: 500;
    }

    /* PROGRESS BARS */
    .skills-section {
        margin-top: 30px;
    }

    .progress-wrap {
        margin: 20px 0;
    }

    .progress-label {
        font-size: 14px;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
    }

    .progress-bar {
        height: 12px;
        background: rgba(31, 41, 55, 0.8);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #65c16e 0%, #ea1c4d 100%);
        width: 0%;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 10px rgba(101, 193, 110, 0.5);
    }

    /* CHART */
    .chart-container {
        margin-top: 30px;
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 15px;
        border: 1px solid rgba(234, 28, 77, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .chart-container canvas {
        max-width: 100% !important;
        height: 250px !important;
        width: 100% !important;
    }

    /* EXTRA STATS */
    .extra-stats {
        margin-top: 30px;
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 20px;
        border-left: 5px solid #ea1c4d;
        border: 1px solid rgba(234, 28, 77, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .extra-stats ul {
        list-style: none;
    }

    .extra-stats ul li {
        padding: 15px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        font-size: 17px;
        color: #374151;
    }

    .extra-stats ul li:last-child {
        border-bottom: none;
    }

    .extra-stats ul li strong {
        color: #ea1c4d;
    }

    /* BIO & CAREER */
    .content-box {
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid rgba(234, 28, 77, 0.2);
        color: #374151;
        line-height: 1.8;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* YouTube Button */
    .youtube-button {
        margin-top: 30px;
        width: 100%;
        background: linear-gradient(90deg, #ea1c4d 0%, #c50622 100%);
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 5px 20px rgba(234, 28, 77, 0.4);
    }

    .youtube-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(234, 28, 77, 0.6);
    }

    .youtube-disabled {
        background: rgba(31, 41, 55, 0.8);
        color: #64748b;
        cursor: not-allowed;
        box-shadow: none;
    }

    .youtube-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    /* RESPONSIVE */
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

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }

        .tabs {
            justify-content: center;
        }
    }
</style>

<div class="vipers-player-container">
    <div class="player-profile">

        <!-- HEADER -->
        <div class="player-header">
            @if($player->image_path)
                <img src="{{ asset('assets/img/players/' . $player->image_path) }}"
                     alt="{{ $player->name }}"
                     class="player-photo">
            @else
                <div class="player-photo" style="background: linear-gradient(135deg, #1a3a1a 0%, #0a1f0a 100%); display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 60px; font-weight: bold; color: #65c16e;">
                        {{ substr($player->first_name ?? 'P', 0, 1) }}{{ substr($player->last_name ?? 'L', 0, 1) }}
                    </span>
                </div>
            @endif

            <div class="player-info">
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

        <!-- TABS -->
        <div class="tabs">
            <div class="tab active" data-tab="overview">Overview</div>
            <div class="tab" data-tab="stats">Statistics</div>
            <div class="tab" data-tab="bio">Biography</div>
            <div class="tab" data-tab="career">Career</div>
        </div>

        <!-- TAB 1: OVERVIEW -->
        <div id="overview" class="tab-content active">
            <h2 class="section-title">Performance Overview</h2>

            <div class="stats-container">
                <div class="metric-card">
                    <div class="metric-value">{{ $player->appearances ?? 0 }}</div>
                    <div class="metric-label">Matches</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $player->goals ?? 0 }}</div>
                    <div class="metric-label">Goals</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $player->assists ?? 0 }}</div>
                    <div class="metric-label">Assists</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $player->age ?? 0 }}</div>
                    <div class="metric-label">Age</div>
                </div>
            </div>

            <!-- Progress Bars -->
            <div class="skills-section">
                <h3 class="section-title">Skill Breakdown</h3>

                @php
                    $skills = [
                        ['label' => 'Goals', 'value' => $player->goals ?? 0, 'max' => 50],
                        ['label' => 'Assists', 'value' => $player->assists ?? 0, 'max' => 30],
                        ['label' => 'Appearances', 'value' => $player->appearances ?? 0, 'max' => 50],
                        ['label' => 'Experience', 'value' => $player->age ?? 0, 'max' => 25],
                        ['label' => 'Discipline', 'value' => max(0, 100 - (($player->yellow_cards ?? 0) * 10)), 'max' => 100],
                    ];
                @endphp

                @foreach($skills as $skill)
                    @php
                        $percentage = $skill['max'] > 0 ? round(($skill['value'] / $skill['max']) * 100) : 0;
                        $percentage = min($percentage, 100);
                    @endphp
                    <div class="progress-wrap">
                        <div class="progress-label">
                            <span>{{ $skill['label'] }}</span>
                            <span>{{ $percentage }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" data-percent="{{ $percentage }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Radar Chart -->
            <div class="chart-container">
                <h3 style="font-size: 20px; margin-bottom: 15px;">Skills Radar</h3>
                <canvas id="radarChart"></canvas>
            </div>

            <!-- YouTube Highlights Button -->
            @if(!empty($player->youtube_url))
                <a href="{{ $player->youtube_url }}" target="_blank" rel="noopener noreferrer">
                    <button class="youtube-button">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        Watch Highlights on YouTube
                    </button>
                </a>
            @else
                <button class="youtube-button youtube-disabled" disabled>
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    No Highlights Available
                </button>
            @endif
        </div>

        <!-- TAB 2: STATS -->
        <div id="stats" class="tab-content">
            <h2 class="section-title">Full Statistics</h2>
            <div class="extra-stats">
                <ul>
                    <li><strong>Goals:</strong> {{ $player->goals ?? 0 }}</li>
                    <li><strong>Assists:</strong> {{ $player->assists ?? 0 }}</li>
                    <li><strong>Appearances:</strong> {{ $player->appearances ?? 0 }}</li>
                    <li><strong>Yellow Cards:</strong> {{ $player->yellow_cards ?? 0 }}</li>
                    <li><strong>Red Cards:</strong> {{ $player->red_cards ?? 0 }}</li>
                    <li><strong>Age:</strong> {{ $player->age ?? 'N/A' }}</li>
                    <li><strong>Position:</strong> {{ ucfirst($player->position ?? 'N/A') }}</li>
                    <li><strong>Category:</strong> {{ $player->formatted_category ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>

        <!-- TAB 3: BIO -->
        <div id="bio" class="tab-content">
            <h2 class="section-title">Player Biography</h2>
            <div class="content-box">
                @if(!empty($player->bio))
                    <p>{{ $player->bio }}</p>
                @else
                    <p style="color: #64748b; font-style: italic;">No biography available for this player yet.</p>
                @endif
            </div>
        </div>

        <!-- TAB 4: CAREER -->
        <div id="career" class="tab-content">
            <h2 class="section-title">Career Timeline</h2>
            <div class="extra-stats">
                <ul>
                    <li><strong>Current Position:</strong> {{ ucfirst($player->position ?? 'N/A') }}</li>
                    <li><strong>Jersey Number:</strong> {{ $player->jersey_number ?? 'N/A' }}</li>
                    <li><strong>Category:</strong> {{ $player->formatted_category ?? 'N/A' }}</li>
                    <li><strong>Total Goals:</strong> {{ $player->goals ?? 0 }}</li>
                    <li><strong>Total Assists:</strong> {{ $player->assists ?? 0 }}</li>
                    <li><strong>Total Appearances:</strong> {{ $player->appearances ?? 0 }}</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // TAB SWITCHING
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });

    // Animate Progress Bars on load
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.querySelectorAll('.progress-fill').forEach(fill => {
                fill.style.width = fill.dataset.percent + '%';
            });
        }, 300);
    });

    // Radar Chart
    const ctx = document.getElementById('radarChart');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Goals', 'Assists', 'Matches', 'Experience', 'Discipline'],
            datasets: [{
                label: 'Player Performance',
                data: [
                    {{ min(($player->goals ?? 0) * 2, 100) }},
                    {{ min(($player->assists ?? 0) * 3.33, 100) }},
                    {{ min(($player->appearances ?? 0) * 2, 100) }},
                    {{ min(($player->age ?? 0) * 4, 100) }},
                    {{ max(0, 100 - (($player->yellow_cards ?? 0) * 10)) }}
                ],
                borderWidth: 3,
                borderColor: '#65c16e',
                pointBackgroundColor: '#ea1c4d',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#65c16e',
                backgroundColor: 'rgba(101, 193, 110, 0.2)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                r: {
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    pointLabels: {
                        color: '#374151',
                        font: {
                            size: 11,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        color: '#6b7280',
                        backdropColor: 'transparent',
                        font: {
                            size: 10
                        }
                    },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
