{{-- Player AI Insights Partial --}}
{{-- Version: 1.0.0 --}}
{{-- Last Modified: 2025-12-06 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (Player model) --}}
{{-- Validation: Ensures $player is not null and has statistical attributes --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <h2 class="section-title">AI Insights</h2>
    <div class="content-box">
        @php
            $goals = $player->goals ?? 0;
            $assists = $player->assists ?? 0;
            $appearances = $player->appearances ?? 0;
            $yellowCards = $player->yellow_cards ?? 0;
            $redCards = $player->red_cards ?? 0;
            $age = $player->age ?? 0;
            $position = $player->position ?? 'unknown';

            // Calculate performance metrics
            $goalsPerGame = $appearances > 0 ? round($goals / $appearances, 2) : 0;
            $assistsPerGame = $appearances > 0 ? round($assists / $appearances, 2) : 0;
            $totalContributions = $goals + $assists;
            $contributionsPerGame = $appearances > 0 ? round($totalContributions / $appearances, 2) : 0;

            // Discipline rating (lower is better)
            $disciplineScore = max(0, 100 - ($yellowCards * 5) - ($redCards * 25));

            // Generate insights based on data
            $insights = [];

            if ($goalsPerGame > 0.5) {
                $insights[] = "Excellent goal-scoring efficiency with " . $goalsPerGame . " goals per game.";
            } elseif ($goalsPerGame > 0.2) {
                $insights[] = "Solid goal-scoring record with " . $goalsPerGame . " goals per match.";
            }

            if ($assistsPerGame > 0.3) {
                $insights[] = "Outstanding playmaking ability, averaging " . $assistsPerGame . " assists per game.";
            }

            if ($contributionsPerGame > 1) {
                $insights[] = "High-impact player with " . $contributionsPerGame . " goal contributions per match.";
            }

            if ($disciplineScore > 80) {
                $insights[] = "Exemplary disciplinary record with minimal cards received.";
            } elseif ($disciplineScore < 50) {
                $insights[] = "Disciplinary concerns may affect playing time and team performance.";
            }

            if ($age < 18 && $totalContributions > 10) {
                $insights[] = "Promising young talent showing early signs of professional potential.";
            }

            // Position-specific insights
            if ($position === 'striker' && $goalsPerGame < 0.3) {
                $insights[] = "As a striker, goal-scoring rate could be improved with more clinical finishing.";
            } elseif ($position === 'midfielder' && $assistsPerGame > 0.4) {
                $insights[] = "Creative midfielder with excellent vision and passing accuracy.";
            } elseif ($position === 'defender' && $yellowCards < 3) {
                $insights[] = "Reliable defender with good tactical discipline.";
            }

            // Overall rating
            $overallRating = 0;
            if ($appearances > 0) {
                $ratingComponents = [
                    min($goalsPerGame * 20, 20), // Goals contribution
                    min($assistsPerGame * 15, 15), // Assists contribution
                    $disciplineScore * 0.3, // Discipline
                    min($appearances * 2, 20), // Experience
                    min($age * 0.5, 10) // Age/maturity
                ];
                $overallRating = round(array_sum($ratingComponents), 1);
            }
        @endphp

        @if(count($insights) > 0)
            <div class="mb-4">
                <h4 style="color: #ea1c4d; margin-bottom: 15px;">Performance Analysis</h4>
                <ul style="list-style: none; padding: 0;">
                    @foreach($insights as $insight)
                        <li style="padding: 8px 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
                            <i class="fas fa-lightbulb" style="color: #65c16e; margin-right: 10px;"></i>
                            {{ $insight }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($overallRating > 0)
            <div class="mb-4">
                <h4 style="color: #ea1c4d; margin-bottom: 15px;">Overall Rating</h4>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="font-size: 48px; font-weight: bold; color: #ea1c4d;">{{ $overallRating }}</div>
                    <div>
                        <div style="font-size: 18px; font-weight: 600; color: #374151;">Performance Score</div>
                        <div style="color: #64748b; font-size: 14px;">Out of 100 possible points</div>
                    </div>
                </div>
            </div>
        @endif


        <div style="background: rgba(0,0,0,0.05); padding: 20px; border-radius: 10px; margin-top: 20px;">
            <h5 style="color: #374151; margin-bottom: 15px;"><i class="fas fa-robot" style="margin-right: 8px;"></i>AI Analysis Summary</h5>

            @php
                // Generate comprehensive player analysis
                $analysis = [];

                // Player profile snapshot
                $position = strtolower($player->position ?? 'unknown');
                $age = $player->age ?? 0;
                $experience = $player->appearances ?? 0;

                $profileSnapshot = "";
                if ($age < 18) {
                    $profileSnapshot = "Emerging young talent";
                } elseif ($age < 23) {
                    $profileSnapshot = "Developing professional";
                } elseif ($age < 30) {
                    $profileSnapshot = "Established player";
                } else {
                    $profileSnapshot = "Experienced veteran";
                }

                if ($experience < 10) {
                    $profileSnapshot .= " with limited senior experience";
                } elseif ($experience < 50) {
                    $profileSnapshot .= " building competitive experience";
                } elseif ($experience < 100) {
                    $profileSnapshot .= " with solid match experience";
                } else {
                    $profileSnapshot .= " with extensive professional experience";
                }

                $analysis[] = "<strong>Profile:</strong> " . ucfirst($profileSnapshot) . " currently playing as a " . ($player->position ?? 'player') . ".";

                // Performance analysis
                $performanceLevel = "";
                $totalContributions = ($goals ?? 0) + ($assists ?? 0);
                $contributionRate = $appearances > 0 ? $totalContributions / $appearances : 0;

                if ($appearances > 0) {
                    $contributionRate = $totalContributions / $appearances;

                    if ($contributionRate > 1.5) {
                        $performanceLevel = "high-impact performer";
                    } elseif ($contributionRate > 1.0) {
                        $performanceLevel = "consistent contributor";
                    } elseif ($contributionRate > 0.5) {
                        $performanceLevel = "developing player";
                    } else {
                        $performanceLevel = "player requiring development focus";
                    }
                }

                if ($performanceLevel) {
                    $analysis[] = "<strong>Current Form:</strong> Demonstrates as a " . $performanceLevel . " with " . ($goals ?? 0) . " goals and " . ($assists ?? 0) . " assists across " . ($appearances ?? 0) . " appearances.";
                }

                // Strengths analysis
                $strengths = [];
                if ($goalsPerGame > 0.4) {
                    $strengths[] = "exceptional finishing ability";
                } elseif ($goalsPerGame > 0.2) {
                    $strengths[] = "reliable goal-scoring threat";
                }

                if ($assistsPerGame > 0.3) {
                    $strengths[] = "strong playmaking vision";
                } elseif ($assistsPerGame > 0.15) {
                    $strengths[] = "solid creative passing";
                }

                if ($disciplineScore > 85) {
                    $strengths[] = "exemplary disciplinary record";
                }

                if ($position === 'defender' && $disciplineScore > 75) {
                    $strengths[] = "reliable defensive positioning";
                } elseif ($position === 'midfielder' && $assistsPerGame > 0.2) {
                    $strengths[] = "effective ball distribution";
                } elseif ($position === 'striker' && $goalsPerGame > 0.15) {
                    $strengths[] = "clinical finishing";
                }

                if (!empty($strengths)) {
                    $analysis[] = "<strong>Strengths:</strong> Shows " . implode(", ", $strengths) . ".";
                }

                // Areas for improvement
                $improvements = [];
                if ($goalsPerGame < 0.1 && $position === 'striker') {
                    $improvements[] = "goal conversion rate needs improvement";
                }

                if ($assistsPerGame < 0.1 && in_array($position, ['midfielder', 'winger'])) {
                    $improvements[] = "creative output could be enhanced";
                }

                if ($disciplineScore < 60) {
                    $improvements[] = "disciplinary consistency requires attention";
                }

                if ($experience < 20) {
                    $improvements[] = "match experience and tactical understanding developing";
                }

                if (!empty($improvements)) {
                    $analysis[] = "<strong>Areas for Development:</strong> " . implode(", ", $improvements) . ".";
                }

                // Potential and outlook
                $potential = "";
                if ($age < 20 && $totalContributions > 5) {
                    $potential = "Shows promising early potential with room for significant development.";
                } elseif ($age < 25 && $contributionRate > 0.8) {
                    $potential = "Demonstrates solid foundation for long-term professional success.";
                } elseif ($age < 30 && $experience > 50) {
                    $potential = "At peak performance level with established professional capabilities.";
                } elseif ($age >= 30 && $experience > 100) {
                    $potential = "Brings valuable experience and leadership to the team.";
                } else {
                    $potential = "Development trajectory suggests continued growth with focused training.";
                }

                $analysis[] = "<strong>Potential Outlook:</strong> " . $potential;

                // Recommendations
                $recommendations = [];
                if ($age < 22) {
                    $recommendations[] = "focus on technical skill development and tactical education";
                }

                if ($disciplineScore < 70) {
                    $recommendations[] = "emphasize tactical discipline and decision-making";
                }

                if ($contributionRate < 0.5) {
                    $recommendations[] = "targeted training to improve performance metrics";
                }

                if ($experience < 30) {
                    $recommendations[] = "increased match exposure and competitive experience";
                }

                if (!empty($recommendations)) {
                    $analysis[] = "<strong>Recommendations:</strong> " . implode(", ", $recommendations) . ".";
                }

                // Final assessment
                $assessment = "This analysis evolves with additional performance data and training metrics.";
                if ($appearances < 5) {
                    $assessment .= " Limited sample size - continued monitoring recommended.";
                }

                $analysis[] = $assessment;
            @endphp

            <div style="line-height: 1.7;">
                @foreach($analysis as $paragraph)
                    <p style="color: #64748b; margin-bottom: 12px; margin: 0 0 12px 0;">
                        {!! $paragraph !!}
                    </p>
                @endforeach
            </div>

            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.1);">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                    <i class="fas fa-clock" style="margin-right: 5px;"></i>
                    Analysis updated: {{ now()->format('M j, Y \a\t g:i A') }} |
                    Based on {{ $appearances ?? 0 }} appearances, {{ $goals ?? 0 }} goals, {{ $assists ?? 0 }} assists
                </p>
            </div>
        </div>
    </div>
@endif
