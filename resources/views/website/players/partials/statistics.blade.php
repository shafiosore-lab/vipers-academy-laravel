{{-- Player Statistics Partial --}}
{{-- Version: 2.0.0 --}}
{{-- Last Modified: 2025-12-07 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (WebsitePlayer model) --}}
{{-- Features: Position-specific metrics, dynamic radar chart, advanced analytics --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    {{-- Authentication Check for Portal Users --}}
    @php
        $isAuthenticatedPlayer = auth()->check() && auth()->user()->isPlayer();
        $isApprovedPlayer = $isAuthenticatedPlayer && auth()->user()->player && auth()->user()->player->isApproved();
        $canAccessPortal = $isApprovedPlayer;
    @endphp
    <div class="row">
        <!-- Main Statistics -->
        <div class="col-lg-8">
            <h2 class="section-title">Player Statistics</h2>

            <!-- Position-Specific Stats Grid -->
            <div class="stats-grid">
                @php
                    $position = strtoupper($player->position ?? 'ST');
                    $stats = [];

                    switch ($position) {
                        case 'GK':
                            $stats = [
                                ['value' => $player->saves ?? 0, 'label' => 'Saves'],
                                ['value' => number_format($player->save_percentage ?? 0, 1) . '%', 'label' => 'Save %'],
                                ['value' => $player->clean_sheets ?? 0, 'label' => 'Clean Sheets'],
                                ['value' => $player->goals_conceded ?? 0, 'label' => 'Goals Conceded'],
                                ['value' => $player->appearances ?? 0, 'label' => 'Appearances'],
                                ['value' => number_format($player->distribution_accuracy ?? 0, 1) . '%', 'label' => 'Distribution'],
                            ];
                            break;

                        case 'CB':
                            $stats = [
                                ['value' => $player->tackles_won ?? 0, 'label' => 'Tackles Won'],
                                ['value' => $player->interceptions ?? 0, 'label' => 'Interceptions'],
                                ['value' => $player->clearances ?? 0, 'label' => 'Clearances'],
                                ['value' => $player->aerial_duels_won ?? 0, 'label' => 'Aerial Duels'],
                                ['value' => number_format($player->passing_accuracy ?? 0, 1) . '%', 'label' => 'Pass Accuracy'],
                                ['value' => $player->ball_recoveries ?? 0, 'label' => 'Recoveries'],
                            ];
                            break;

                        case 'LB':
                        case 'RB':
                            $stats = [
                                ['value' => $player->tackles_won ?? 0, 'label' => 'Tackles Won'],
                                ['value' => $player->interceptions ?? 0, 'label' => 'Interceptions'],
                                ['value' => $player->crosses_attempted ?? 0, 'label' => 'Crosses'],
                                ['value' => number_format($player->cross_accuracy ?? 0, 1) . '%', 'label' => 'Cross Accuracy'],
                                ['value' => $player->dribbles_completed ?? 0, 'label' => 'Dribbles'],
                                ['value' => $player->progressive_runs ?? 0, 'label' => 'Progressive Runs'],
                            ];
                            break;

                        case 'CDM':
                            $stats = [
                                ['value' => $player->tackles_won ?? 0, 'label' => 'Tackles Won'],
                                ['value' => $player->interceptions ?? 0, 'label' => 'Interceptions'],
                                ['value' => $player->ball_recoveries ?? 0, 'label' => 'Recoveries'],
                                ['value' => number_format($player->passing_accuracy ?? 0, 1) . '%', 'label' => 'Pass Accuracy'],
                                ['value' => $player->progressive_passes ?? 0, 'label' => 'Progressive Passes'],
                                ['value' => $player->duels_won ?? 0, 'label' => 'Duels Won'],
                            ];
                            break;

                        case 'CM':
                            $stats = [
                                ['value' => number_format($player->passing_accuracy ?? 0, 1) . '%', 'label' => 'Pass Accuracy'],
                                ['value' => $player->key_passes ?? 0, 'label' => 'Key Passes'],
                                ['value' => number_format($player->expected_assists ?? 0, 1), 'label' => 'xA'],
                                ['value' => $player->tackles_won ?? 0, 'label' => 'Tackles'],
                                ['value' => $player->interceptions ?? 0, 'label' => 'Interceptions'],
                                ['value' => $player->ball_progressions ?? 0, 'label' => 'Progressions'],
                            ];
                            break;

                        case 'CAM':
                            $stats = [
                                ['value' => $player->goals ?? 0, 'label' => 'Goals'],
                                ['value' => $player->assists ?? 0, 'label' => 'Assists'],
                                ['value' => $player->key_passes ?? 0, 'label' => 'Key Passes'],
                                ['value' => number_format($player->expected_goals ?? 0, 1), 'label' => 'xG'],
                                ['value' => number_format($player->expected_assists ?? 0, 1), 'label' => 'xA'],
                                ['value' => $player->shots_on_target ?? 0, 'label' => 'Shots on Target'],
                            ];
                            break;

                        case 'LW':
                        case 'RW':
                            $stats = [
                                ['value' => $player->goals ?? 0, 'label' => 'Goals'],
                                ['value' => $player->assists ?? 0, 'label' => 'Assists'],
                                ['value' => $player->key_passes ?? 0, 'label' => 'Key Passes'],
                                ['value' => $player->dribbles_completed ?? 0, 'label' => 'Dribbles'],
                                ['value' => number_format($player->cross_accuracy ?? 0, 1) . '%', 'label' => 'Cross Accuracy'],
                                ['value' => $player->shots_on_target ?? 0, 'label' => 'Shots on Target'],
                            ];
                            break;

                        case 'ST':
                        case 'CF':
                        default:
                            $stats = [
                                ['value' => $player->goals ?? 0, 'label' => 'Goals'],
                                ['value' => $player->shots_on_target ?? 0, 'label' => 'Shots on Target'],
                                ['value' => number_format($player->shot_conversion_rate ?? 0, 2), 'label' => 'Conversion Rate'],
                                ['value' => number_format($player->expected_goals ?? 0, 1), 'label' => 'xG'],
                                ['value' => $player->assists ?? 0, 'label' => 'Assists'],
                                ['value' => $player->aerial_duels_won ?? 0, 'label' => 'Aerial Duels'],
                            ];
                            break;
                    }
                @endphp

                @foreach($stats as $stat)
                    <div class="stat-item">
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Additional Position-Specific Stats -->
            @if($position === 'GK')
                <div class="mt-4">
                    <h4 class="mb-3">Goalkeeper Specific</h4>
                    <div class="additional-info">
                        <div class="info-item"><strong>High Claims:</strong> {{ $player->high_claims ?? 0 }}</div>
                        <div class="info-item"><strong>Punches:</strong> {{ $player->punches ?? 0 }}</div>
                        <div class="info-item"><strong>Penalties Faced:</strong> {{ $player->penalties_faced ?? 0 }}</div>
                        <div class="info-item"><strong>Penalties Saved:</strong> {{ $player->penalties_saved ?? 0 }}</div>
                    </div>
                </div>
            @elseif(in_array($position, ['LW', 'RW', 'CAM']))
                <div class="mt-4">
                    <h4 class="mb-3">Attacking Metrics</h4>
                    <div class="additional-info">
                        <div class="info-item"><strong>Through Balls:</strong> {{ $player->through_balls ?? 0 }}</div>
                        <div class="info-item"><strong>Progressive Runs:</strong> {{ $player->progressive_runs ?? 0 }}</div>
                        <div class="info-item"><strong>Chance Creation:</strong> {{ $player->chance_creation ?? 0 }}</div>
                        <div class="info-item"><strong>Touches in Box:</strong> {{ $player->touches_in_box ?? 0 }}</div>
                    </div>
                </div>
            @elseif(in_array($position, ['CB', 'LB', 'RB']))
                <div class="mt-4">
                    <h4 class="mb-3">Defensive Metrics</h4>
                    <div class="additional-info">
                        <div class="info-item"><strong>Blocks:</strong> {{ $player->blocks ?? 0 }}</div>
                        <div class="info-item"><strong>Defensive Duels:</strong> {{ $player->defensive_duels_won ?? 0 }}</div>
                        <div class="info-item"><strong>Long Pass Accuracy:</strong> {{ number_format($player->long_pass_accuracy ?? 0, 1) }}%</div>
                        <div class="info-item"><strong>Short Pass Accuracy:</strong> {{ number_format($player->short_pass_accuracy ?? 0, 1) }}%</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Skills Radar Chart -->
        <div class="col-lg-4">
            <h2 class="section-title">Skills Radar</h2>
            <div class="radar-chart-container">
                <canvas id="skillsRadarChart" width="300" height="300"></canvas>
            </div>

            <!-- Radar Legend -->
            <div class="radar-legend mt-3">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: rgba(234, 28, 77, 0.8);"></span>
                    <span class="legend-text">{{ $player->full_name }} Skills</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Player Info -->
    <div class="additional-info mt-4">
        <div class="info-item">
            <strong>Position:</strong> {{ ucfirst($player->position ?? 'N/A') }}
        </div>
        <div class="info-item">
            <strong>Category:</strong> {{ $player->formatted_category ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Jersey Number:</strong> {{ $player->jersey_number ?? 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Appearances:</strong> {{ $player->appearances ?? 0 }}
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize radar chart if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        initSkillsRadar();
    }
});

function initSkillsRadar() {
    const ctx = document.getElementById('skillsRadarChart');
    if (!ctx) return;

    const playerPosition = '{{ strtoupper($player->position ?? "ST") }}';
    let labels = [];
    let data = [];

    // Position-specific radar attributes
    switch (playerPosition) {
        case 'GK':
            labels = ['Shot Stopping', 'Distribution', 'Aerial Ability', 'Command Area', 'Handling', 'Positioning'];
            data = [
                {{ $player->shot_stopping ?? 0 }},
                {{ $player->distribution ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->command_area ?? 0 }},
                {{ $player->handling ?? 0 }},
                {{ $player->positioning ?? 75 }}
            ];
            break;

        case 'CB':
            labels = ['Tackling', 'Interceptions', 'Aerial Ability', 'Positioning', 'Strength', 'Passing'];
            data = [
                {{ $player->tackling ?? 0 }},
                {{ $player->interceptions ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->strength ?? 0 }},
                {{ $player->passing ?? 0 }}
            ];
            break;

        case 'LB':
        case 'RB':
            labels = ['Pace', 'Crossing', 'Dribbling', 'Tackling', 'Positioning', 'Stamina'];
            data = [
                {{ $player->pace ?? 0 }},
                {{ $player->crossing ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->tackling ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->stamina ?? 0 }}
            ];
            break;

        case 'CDM':
            labels = ['Tackling', 'Interceptions', 'Positioning', 'Passing', 'Vision', 'Stamina'];
            data = [
                {{ $player->tackling ?? 0 }},
                {{ $player->interceptions ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->stamina ?? 0 }}
            ];
            break;

        case 'CM':
            labels = ['Passing', 'Vision', 'Tackling', 'Positioning', 'Stamina', 'Decisions'];
            data = [
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->tackling ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->stamina ?? 0 }},
                {{ $player->decisions ?? 0 }}
            ];
            break;

        case 'CAM':
            labels = ['Passing', 'Vision', 'Dribbling', 'Finishing', 'Technique', 'Flair'];
            data = [
                {{ $player->passing ?? 0 }},
                {{ $player->vision ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->finishing ?? 0 }},
                {{ $player->technique ?? 0 }},
                {{ $player->flair ?? 0 }}
            ];
            break;

        case 'LW':
        case 'RW':
            labels = ['Pace', 'Crossing', 'Dribbling', 'Finishing', 'Technique', 'Balance'];
            data = [
                {{ $player->pace ?? 0 }},
                {{ $player->crossing ?? 0 }},
                {{ $player->dribbling ?? 0 }},
                {{ $player->finishing ?? 0 }},
                {{ $player->technique ?? 0 }},
                {{ $player->balance ?? 0 }}
            ];
            break;

        case 'ST':
        case 'CF':
        default:
            labels = ['Finishing', 'Positioning', 'Aerial Ability', 'Pace', 'Strength', 'Technique'];
            data = [
                {{ $player->finishing ?? 0 }},
                {{ $player->positioning ?? 0 }},
                {{ $player->aerial_ability ?? 0 }},
                {{ $player->pace ?? 0 }},
                {{ $player->strength ?? 0 }},
                {{ $player->technique ?? 0 }}
            ];
            break;
    }

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ $player->full_name }} Skills',
                data: data,
                backgroundColor: 'rgba(234, 28, 77, 0.2)',
                borderColor: 'rgba(234, 28, 77, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(234, 28, 77, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(234, 28, 77, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    angleLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    pointLabels: {
                        font: {
                            size: window.innerWidth < 768 ? 9 : 11
                        }
                    }
                }
            }
        }
    });
}
</script>

<style>
.radar-chart-container {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(234, 28, 77, 0.2);
    margin-bottom: 20px;
}

.radar-legend {
    background: var(--card-bg);
    border-radius: 10px;
    padding: 15px;
    border: 1px solid rgba(234, 28, 77, 0.2);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-text {
    font-size: 14px;
    color: var(--text-medium);
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .radar-chart-container {
        padding: 15px;
        margin-bottom: 15px;
    }

    .radar-chart-container canvas {
        max-width: 250px;
        height: 250px;
        margin: 0 auto;
        display: block;
    }

    .radar-legend {
        padding: 12px;
    }

    .legend-text {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .radar-chart-container {
        padding: 10px;
    }

    .radar-chart-container canvas {
        max-width: 200px;
        height: 200px;
    }

    .radar-legend {
        padding: 10px;
    }

    .legend-text {
        font-size: 12px;
    }
}
</style>
