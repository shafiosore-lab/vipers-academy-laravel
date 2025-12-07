{{-- Player Header Partial --}}
{{-- Dependencies: $player (Player model) --}}
{{-- Renders player photo, info, and skills radar chart --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <div class="player-header">
        {{-- Player Photo --}}
        @if($player->image_path)
            <img src="{{ asset('assets/img/players/' . $player->image_path) }}"
                 alt="{{ $player->name }}"
                 class="player-photo">
        @else
            <div class="player-photo player-photo-placeholder">
                <span>
                    {{ substr($player->first_name ?? 'P', 0, 1) }}{{ substr($player->last_name ?? 'L', 0, 1) }}
                </span>
            </div>
        @endif

        {{-- Player Info & Radar --}}
        <div class="player-info">
            {{-- Skills Radar Chart --}}
            <div class="player-radar-container">
                <canvas id="playerRadarChart"></canvas>
            </div>

            {{-- Player Details --}}
            <div class="player-details">
                <h1 class="player-name">{{ $player->full_name ?? 'Player Name' }}</h1>
                <p class="player-position">
                    {{ ucfirst($player->position ?? 'Position') }}
                    @if($player->jersey_number)
                        • Jersey #{{ $player->jersey_number }}
                    @endif
                </p>
                @if($player->bio)
                    <p class="player-description">{{ $player->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    @once
    <script>
    // Load Chart.js library
    (function() {
        if (window.Chart) {
            initPlayerRadarChart();
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = initPlayerRadarChart;
        script.onerror = () => console.warn('Chart.js failed to load');
        document.head.appendChild(script);
    })();

    // Skills Calculator Module
    const PlayerSkills = {
        calculate(stats) {
            const { goals, assists, appearances, age, yellowCards, position } = stats;

            // Base skill calculations
            const base = {
                shooting: goals * 3 + assists * 2 + 40,
                passing: assists * 4 + goals * 2 + 45,
                speed: (25 - age) * 2 + 60,
                defense: (100 - yellowCards * 8) + appearances * 0.5,
                stamina: appearances * 1.5 + 50
            };

            // Position-specific modifiers
            const positionMods = {
                striker: { shooting: 15, speed: 10, defense: -10 },
                forward: { shooting: 15, speed: 10, defense: -10 },
                midfielder: { passing: 10, stamina: 10 },
                defender: { defense: 15, passing: 5, speed: -5 },
                goalkeeper: { defense: 20, shooting: -20, speed: -10 }
            };

            const mods = positionMods[position] || {};

            // Apply modifiers and clamp to 0-100
            return Object.keys(base).reduce((skills, key) => {
                skills[key] = this.clamp(base[key] + (mods[key] || 0));
                return skills;
            }, {});
        },

        clamp(value, min = 0, max = 100) {
            return Math.max(min, Math.min(value, max));
        }
    };

    // Initialize Radar Chart with Position-Specific Data
    function initPlayerRadarChart() {
        const canvas = document.getElementById('playerRadarChart');
        if (!canvas || canvas.chart) return; // Prevent duplicate initialization

        const playerPosition = '{{ strtoupper($player->position ?? "ST") }}';
        let labels = [];
        let data = [];
        let tooltips = {};

        // Position-specific radar attributes using advanced stats
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
                tooltips = {
                    'Shot Stopping': `Save %: {{ number_format($player->save_percentage ?? 0, 1) }}%`,
                    'Distribution': `Distribution accuracy: {{ number_format($player->distribution_accuracy ?? 0, 1) }}%`,
                    'Aerial Ability': `Clean sheets: {{ $player->clean_sheets ?? 0 }}`,
                    'Command Area': `High claims: {{ $player->high_claims ?? 0 }}`,
                    'Handling': `Saves: {{ $player->saves ?? 0 }}`,
                    'Positioning': 'Defensive positioning skill'
                };
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
                tooltips = {
                    'Tackling': `Tackles won: {{ $player->tackles_won ?? 0 }}`,
                    'Interceptions': `Interceptions: {{ $player->interceptions ?? 0 }}`,
                    'Aerial Ability': `Aerial duels won: {{ $player->aerial_duels_won ?? 0 }}`,
                    'Positioning': `Clearances: {{ $player->clearances ?? 0 }}`,
                    'Strength': `Ball recoveries: {{ $player->ball_recoveries ?? 0 }}`,
                    'Passing': `Pass accuracy: {{ number_format($player->passing_accuracy ?? 0, 1) }}%`
                };
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
                tooltips = {
                    'Pace': `Progressive runs: {{ $player->progressive_runs ?? 0 }}`,
                    'Crossing': `Cross accuracy: {{ number_format($player->cross_accuracy ?? 0, 1) }}%`,
                    'Dribbling': `Dribbles completed: {{ $player->dribbles_completed ?? 0 }}`,
                    'Tackling': `Tackles won: {{ $player->tackles_won ?? 0 }}`,
                    'Positioning': `Interceptions: {{ $player->interceptions ?? 0 }}`,
                    'Stamina': `Defensive duels: {{ $player->defensive_duels_won ?? 0 }}`
                };
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
                tooltips = {
                    'Tackling': `Tackles won: {{ $player->tackles_won ?? 0 }}`,
                    'Interceptions': `Interceptions: {{ $player->interceptions ?? 0 }}`,
                    'Positioning': `Ball recoveries: {{ $player->ball_recoveries ?? 0 }}`,
                    'Passing': `Pass accuracy: {{ number_format($player->passing_accuracy ?? 0, 1) }}%`,
                    'Vision': `Progressive passes: {{ $player->progressive_passes ?? 0 }}`,
                    'Stamina': `Duels won: {{ $player->duels_won ?? 0 }}`
                };
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
                tooltips = {
                    'Passing': `Pass accuracy: {{ number_format($player->passing_accuracy ?? 0, 1) }}%`,
                    'Vision': `Key passes: {{ $player->key_passes ?? 0 }}`,
                    'Tackling': `Tackles won: {{ $player->tackles_won ?? 0 }}`,
                    'Positioning': `Interceptions: {{ $player->interceptions ?? 0 }}`,
                    'Stamina': `Ball progressions: {{ $player->ball_progressions ?? 0 }}`,
                    'Decisions': `xA: {{ number_format($player->expected_assists ?? 0, 1) }}`
                };
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
                tooltips = {
                    'Passing': `Key passes: {{ $player->key_passes ?? 0 }}`,
                    'Vision': `Chances created: {{ $player->chances_created ?? 0 }}`,
                    'Dribbling': `Dribbles completed: {{ $player->dribbles_completed ?? 0 }}`,
                    'Finishing': `xG: {{ number_format($player->expected_goals ?? 0, 1) }}`,
                    'Technique': `Through balls: {{ $player->through_balls ?? 0 }}`,
                    'Flair': `Goals: {{ $player->goals ?? 0 }}`
                };
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
                tooltips = {
                    'Pace': `Progressive runs: {{ $player->progressive_runs ?? 0 }}`,
                    'Crossing': `Cross accuracy: {{ number_format($player->cross_accuracy ?? 0, 1) }}%`,
                    'Dribbling': `Dribbles completed: {{ $player->dribbles_completed ?? 0 }}`,
                    'Finishing': `Shots on target: {{ $player->shots_on_target ?? 0 }}`,
                    'Technique': `Key passes: {{ $player->key_passes ?? 0 }}`,
                    'Balance': `xA: {{ number_format($player->expected_assists ?? 0, 1) }}`
                };
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
                tooltips = {
                    'Finishing': `Conversion rate: {{ number_format($player->shot_conversion_rate ?? 0, 2) }}`,
                    'Positioning': `Touches in box: {{ $player->touches_in_box ?? 0 }}`,
                    'Aerial Ability': `Aerial duels won: {{ $player->aerial_duels_won ?? 0 }}`,
                    'Pace': `Big chances scored: {{ $player->big_chances_scored ?? 0 }}`,
                    'Strength': `Hold-up play: {{ $player->hold_up_play_success ?? 0 }}`,
                    'Technique': `xG: {{ number_format($player->expected_goals ?? 0, 1) }}`
                };
                break;
        }

        // Chart configuration
        const config = {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ $player->full_name }} Skills',
                    data: data,
                    borderWidth: 2,
                    borderColor: '#ea1c4d',
                    backgroundColor: 'rgba(234, 28, 77, 0.1)',
                    pointBackgroundColor: '#ea1c4d',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#ea1c4d',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 12 },
                        displayColors: false,
                        callbacks: {
                            label: (context) => {
                                const value = Math.round(context.parsed.r);
                                const tooltipText = tooltips[context.label] || '';
                                return [`${context.label}: ${value}/100`, tooltipText];
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { display: false, stepSize: 20 },
                        grid: { color: 'rgba(0, 0, 0, 0.1)', circular: true },
                        angleLines: { display: false },
                        pointLabels: {
                            color: '#64748b',
                            font: { size: 11, weight: 'bold', family: 'Poppins' }
                        }
                    }
                }
            }
        };

        // Create and store chart instance
        canvas.chart = new Chart(canvas, config);
    }
    </script>
    @endonce
@endif
