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
                <h1 class="player-name">{{ $player->name ?? 'Player Name' }}</h1>
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

    // Initialize Radar Chart
    function initPlayerRadarChart() {
        const canvas = document.getElementById('playerRadarChart');
        if (!canvas || canvas.chart) return; // Prevent duplicate initialization

        // Player stats from backend
        const stats = {
            position: '{{ strtolower($player->position ?? "unknown") }}',
            goals: {{ $player->goals ?? 0 }},
            assists: {{ $player->assists ?? 0 }},
            appearances: {{ $player->appearances ?? 0 }},
            age: {{ $player->age ?? 20 }},
            yellowCards: {{ $player->yellow_cards ?? 0 }}
        };

        // Calculate skills
        const skills = PlayerSkills.calculate(stats);

        // Chart configuration
        const config = {
            type: 'radar',
            data: {
                labels: ['Shooting', 'Passing', 'Speed', 'Defense', 'Stamina'],
                datasets: [{
                    label: 'Player Skills',
                    data: [skills.shooting, skills.passing, skills.speed, skills.defense, skills.stamina],
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
                                const tooltips = {
                                    'Shooting': `Based on ${stats.goals} goals, ${stats.assists} assists`,
                                    'Passing': `Based on ${stats.assists} assists and playmaking`,
                                    'Speed': `Age-adjusted: ${stats.age} years old`,
                                    'Defense': `Based on ${stats.yellowCards} yellow cards, ${stats.appearances} matches`,
                                    'Stamina': `Based on ${stats.appearances} total appearances`
                                };
                                const value = Math.round(context.parsed.r);
                                return [`${context.label}: ${value}/100`, tooltips[context.label]];
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        min: 0,
                        max: 100,
                        ticks: { display: false, stepSize: 20 },
                        grid: { color: 'rgba(0, 0, 0, 0.1)', circular: true },
                        angleLines: { display: false },
                        pointLabels: {
                            color: '#64748b',
                            font: { size: 12, weight: 'bold', family: 'Poppins' }
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
