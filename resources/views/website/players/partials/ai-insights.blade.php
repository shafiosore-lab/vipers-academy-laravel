{{-- Player AI Insights Partial --}}
{{-- Version: 2.0.0 --}}
{{-- Last Modified: 2025-12-07 --}}
{{-- Author: Kilo Code --}}
{{-- Dependencies: $player (WebsitePlayer model) --}}
{{-- Features: Position-aware AI analysis using advanced metrics and auto-sync data --}}

@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    {{-- Authentication Check for Portal Users --}}
    @php
        $isAuthenticatedPlayer = auth()->check() && auth()->user()->isPlayer();
        $isApprovedPlayer = $isAuthenticatedPlayer && auth()->user()->player && auth()->user()->player->isApproved();
        $canAccessPortal = $isApprovedPlayer;
    @endphp

    @php
        $position = strtoupper($player->position ?? 'ST');
        $appearances = $player->appearances ?? 0;

        // Position-specific performance analysis
        $insights = [];
        $strengths = [];
        $improvements = [];
        $recommendations = [];
        $overallRating = 0;

        switch ($position) {
            case 'GK':
                // Goalkeeper analysis
                $savePercentage = $player->save_percentage ?? 0;
                $cleanSheets = $player->clean_sheets ?? 0;
                $goalsConceded = $player->goals_conceded ?? 0;
                $distributionAccuracy = $player->distribution_accuracy ?? 0;

                if ($savePercentage > 75) {
                    $insights[] = "Elite shot-stopping ability with " . number_format($savePercentage, 1) . "% save rate.";
                    $strengths[] = "exceptional shot-stopping";
                } elseif ($savePercentage > 70) {
                    $insights[] = "Solid goalkeeping fundamentals with " . number_format($savePercentage, 1) . "% save percentage.";
                    $strengths[] = "reliable shot-stopping";
                }

                if ($cleanSheets > 0) {
                    $insights[] = "Clean sheet specialist with " . $cleanSheets . " shutouts this season.";
                    $strengths[] = "defensive reliability";
                }

                if ($distributionAccuracy > 80) {
                    $strengths[] = "excellent distribution accuracy";
                }

                if ($savePercentage < 65) {
                    $improvements[] = "shot-stopping technique needs refinement";
                }

                if ($distributionAccuracy < 70) {
                    $improvements[] = "distribution accuracy requires improvement";
                }

                // Rating calculation for GK
                $overallRating = min(100, ($savePercentage * 0.4) + ($cleanSheets * 2) + ($distributionAccuracy * 0.3) + 20);
                break;

            case 'CB':
                // Centre back analysis
                $tacklesWon = $player->tackles_won ?? 0;
                $interceptions = $player->interceptions ?? 0;
                $clearances = $player->clearances ?? 0;
                $aerialDuelsWon = $player->aerial_duels_won ?? 0;
                $passingAccuracy = $player->passing_accuracy ?? 0;

                if ($tacklesWon > 0 && $appearances > 0) {
                    $tacklesPerGame = round($tacklesWon / $appearances, 1);
                    $insights[] = "Proven defensive presence with " . $tacklesPerGame . " tackles per game.";
                    $strengths[] = "strong tackling ability";
                }

                if ($aerialDuelsWon > 0 && $appearances > 0) {
                    $aerialPerGame = round($aerialDuelsWon / $appearances, 1);
                    $insights[] = "Dominant aerial presence winning " . $aerialPerGame . " duels per match.";
                    $strengths[] = "excellent aerial ability";
                }

                if ($passingAccuracy > 85) {
                    $strengths[] = "comprehensive passing range";
                }

                if ($tacklesWon < $appearances * 1.5) {
                    $improvements[] = "tackling consistency could be improved";
                }

                if ($passingAccuracy < 80) {
                    $improvements[] = "passing accuracy needs development";
                }

                // Rating calculation for CB
                $overallRating = min(100, ($tacklesWon * 0.3) + ($interceptions * 0.4) + ($aerialDuelsWon * 0.3) + ($passingAccuracy * 0.2) + 15);
                break;

            case 'LB':
            case 'RB':
                // Full back analysis
                $tacklesWon = $player->tackles_won ?? 0;
                $interceptions = $player->interceptions ?? 0;
                $crossesAttempted = $player->crosses_attempted ?? 0;
                $crossAccuracy = $player->cross_accuracy ?? 0;
                $dribblesCompleted = $player->dribbles_completed ?? 0;
                $progressiveRuns = $player->progressive_runs ?? 0;

                if ($crossAccuracy > 75) {
                    $insights[] = "Precise crossing ability with " . number_format($crossAccuracy, 1) . "% accuracy.";
                    $strengths[] = "accurate crossing";
                }

                if ($progressiveRuns > 0 && $appearances > 0) {
                    $progressivePerGame = round($progressiveRuns / $appearances, 1);
                    $insights[] = "Dynamic attacking threat with " . $progressivePerGame . " progressive runs per game.";
                    $strengths[] = "strong attacking contribution";
                }

                if ($dribblesCompleted > 0 && $appearances > 0) {
                    $dribblesPerGame = round($dribblesCompleted / $appearances, 1);
                    $insights[] = "Skilled dribbler completing " . $dribblesPerGame . " dribbles per match.";
                    $strengths[] = "excellent dribbling technique";
                }

                if ($crossAccuracy < 60) {
                    $improvements[] = "crossing accuracy needs improvement";
                }

                if ($tacklesWon < $appearances * 1.2) {
                    $improvements[] = "defensive work rate could be increased";
                }

                // Rating calculation for fullbacks
                $overallRating = min(100, ($tacklesWon * 0.25) + ($interceptions * 0.3) + ($crossAccuracy * 0.2) + ($dribblesCompleted * 0.15) + ($progressiveRuns * 0.1) + 20);
                break;

            case 'CDM':
                // Defensive midfielder analysis
                $tacklesWon = $player->tackles_won ?? 0;
                $interceptions = $player->interceptions ?? 0;
                $ballRecoveries = $player->ball_recoveries ?? 0;
                $passingAccuracy = $player->passing_accuracy ?? 0;
                $progressivePasses = $player->progressive_passes ?? 0;

                if ($ballRecoveries > 0 && $appearances > 0) {
                    $recoveriesPerGame = round($ballRecoveries / $appearances, 1);
                    $insights[] = "Excellent ball-winning ability with " . $recoveriesPerGame . " recoveries per game.";
                    $strengths[] = "outstanding ball recovery";
                }

                if ($progressivePasses > 0 && $appearances > 0) {
                    $progressivePerGame = round($progressivePasses / $appearances, 1);
                    $insights[] = "Progressive distributor with " . $progressivePerGame . " forward passes per match.";
                    $strengths[] = "effective ball progression";
                }

                if ($passingAccuracy > 88) {
                    $strengths[] = "exceptional passing accuracy";
                }

                if ($tacklesWon < $appearances * 2) {
                    $improvements[] = "tackling frequency could be increased";
                }

                if ($progressivePasses < $appearances * 3) {
                    $improvements[] = "progressive passing could be enhanced";
                }

                // Rating calculation for CDM
                $overallRating = min(100, ($tacklesWon * 0.2) + ($interceptions * 0.25) + ($ballRecoveries * 0.2) + ($passingAccuracy * 0.2) + ($progressivePasses * 0.15) + 15);
                break;

            case 'CM':
                // Central midfielder analysis
                $passingAccuracy = $player->passing_accuracy ?? 0;
                $keyPasses = $player->key_passes ?? 0;
                $expectedAssists = $player->expected_assists ?? 0;
                $tacklesWon = $player->tackles_won ?? 0;
                $interceptions = $player->interceptions ?? 0;
                $ballProgressions = $player->ball_progressions ?? 0;

                if ($passingAccuracy > 90) {
                    $insights[] = "Elite passing accuracy of " . number_format($passingAccuracy, 1) . "%.";
                    $strengths[] = "exceptional passing accuracy";
                }

                if ($keyPasses > 0 && $appearances > 0) {
                    $keyPassesPerGame = round($keyPasses / $appearances, 1);
                    $insights[] = "Creative playmaker with " . $keyPassesPerGame . " key passes per game.";
                    $strengths[] = "excellent creativity";
                }

                if ($expectedAssists > 0 && $appearances > 0) {
                    $xAPerGame = round($expectedAssists / $appearances, 1);
                    $insights[] = "High-quality chance creation with " . $xAPerGame . " expected assists per match.";
                    $strengths[] = "outstanding chance creation";
                }

                if ($ballProgressions > 0 && $appearances > 0) {
                    $progressionsPerGame = round($ballProgressions / $appearances, 1);
                    $insights[] = "Effective ball carrier with " . $progressionsPerGame . " progressions per game.";
                    $strengths[] = "strong ball progression";
                }

                if ($passingAccuracy < 85) {
                    $improvements[] = "passing accuracy needs improvement";
                }

                if ($keyPasses < $appearances * 1.5) {
                    $improvements[] = "creative output could be enhanced";
                }

                // Rating calculation for CM
                $overallRating = min(100, ($passingAccuracy * 0.25) + ($keyPasses * 0.2) + ($expectedAssists * 0.2) + ($tacklesWon * 0.15) + ($ballProgressions * 0.2) + 10);
                break;

            case 'CAM':
                // Attacking midfielder analysis
                $goals = $player->goals ?? 0;
                $assists = $player->assists ?? 0;
                $keyPasses = $player->key_passes ?? 0;
                $expectedGoals = $player->expected_goals ?? 0;
                $expectedAssists = $player->expected_assists ?? 0;
                $shotsOnTarget = $player->shots_on_target ?? 0;

                if ($goals > 0 && $appearances > 0) {
                    $goalsPerGame = round($goals / $appearances, 1);
                    $insights[] = "Goal-scoring midfielder with " . $goalsPerGame . " goals per game.";
                    $strengths[] = "clinical finishing";
                }

                if ($expectedGoals > 0 && $appearances > 0) {
                    $xGPerGame = round($expectedGoals / $appearances, 1);
                    $insights[] = "High-quality scoring opportunities with " . $xGPerGame . " expected goals per match.";
                    $strengths[] = "excellent positioning";
                }

                if ($expectedAssists > 0 && $appearances > 0) {
                    $xAPerGame = round($expectedAssists / $appearances, 1);
                    $insights[] = "Elite playmaker with " . $xAPerGame . " expected assists per game.";
                    $strengths[] = "outstanding creativity";
                }

                if ($shotsOnTarget > 0 && $appearances > 0) {
                    $shotsPerGame = round($shotsOnTarget / $appearances, 1);
                    $insights[] = "Regular threat from distance with " . $shotsPerGame . " shots on target per match.";
                    $strengths[] = "strong shooting ability";
                }

                if ($goals < $appearances * 0.3) {
                    $improvements[] = "goal-scoring frequency could be improved";
                }

                if ($expectedAssists < $appearances * 1.5) {
                    $improvements[] = "creative output could be enhanced";
                }

                // Rating calculation for CAM
                $overallRating = min(100, ($goals * 2) + ($assists * 1.5) + ($keyPasses * 0.8) + ($expectedGoals * 1.2) + ($expectedAssists * 1.2) + ($shotsOnTarget * 0.3) + 15);
                break;

            case 'LW':
            case 'RW':
                // Winger analysis
                $goals = $player->goals ?? 0;
                $assists = $player->assists ?? 0;
                $keyPasses = $player->key_passes ?? 0;
                $dribblesCompleted = $player->dribbles_completed ?? 0;
                $crossAccuracy = $player->cross_accuracy ?? 0;
                $progressiveRuns = $player->progressive_runs ?? 0;
                $shotsOnTarget = $player->shots_on_target ?? 0;

                if ($dribblesCompleted > 0 && $appearances > 0) {
                    $dribblesPerGame = round($dribblesCompleted / $appearances, 1);
                    $insights[] = "Skilled dribbler completing " . $dribblesPerGame . " dribbles per game.";
                    $strengths[] = "excellent dribbling technique";
                }

                if ($crossAccuracy > 70) {
                    $insights[] = "Accurate crosser with " . number_format($crossAccuracy, 1) . "% crossing accuracy.";
                    $strengths[] = "precise crossing";
                }

                if ($progressiveRuns > 0 && $appearances > 0) {
                    $progressivePerGame = round($progressiveRuns / $appearances, 1);
                    $insights[] = "Dynamic attacker with " . $progressivePerGame . " progressive runs per match.";
                    $strengths[] = "strong attacking contribution";
                }

                if ($goals > 0 && $appearances > 0) {
                    $goalsPerGame = round($goals / $appearances, 1);
                    $insights[] = "Goal-scoring winger with " . $goalsPerGame . " goals per game.";
                    $strengths[] = "clinical finishing";
                }

                if ($dribblesCompleted < $appearances * 2) {
                    $improvements[] = "dribbling frequency could be increased";
                }

                if ($crossAccuracy < 65) {
                    $improvements[] = "crossing accuracy needs improvement";
                }

                // Rating calculation for wingers
                $overallRating = min(100, ($goals * 2.5) + ($assists * 2) + ($keyPasses * 1) + ($dribblesCompleted * 0.3) + ($crossAccuracy * 0.2) + ($progressiveRuns * 0.4) + 15);
                break;

            case 'ST':
            case 'CF':
            default:
                // Striker analysis
                $goals = $player->goals ?? 0;
                $shotsOnTarget = $player->shots_on_target ?? 0;
                $shotConversionRate = $player->shot_conversion_rate ?? 0;
                $expectedGoals = $player->expected_goals ?? 0;
                $assists = $player->assists ?? 0;
                $keyPasses = $player->key_passes ?? 0;
                $aerialDuelsWon = $player->aerial_duels_won ?? 0;
                $touchesInBox = $player->touches_in_box ?? 0;

                if ($goals > 0 && $appearances > 0) {
                    $goalsPerGame = round($goals / $appearances, 1);
                    $insights[] = "Prolific goal-scorer with " . $goalsPerGame . " goals per game.";
                    $strengths[] = "exceptional finishing ability";
                }

                if ($shotConversionRate > 0) {
                    $conversionPercent = round($shotConversionRate * 100, 1);
                    $insights[] = "Elite conversion rate of " . $conversionPercent . "% from shots on target.";
                    $strengths[] = "clinical finishing";
                }

                if ($expectedGoals > 0 && $appearances > 0) {
                    $xGPerGame = round($expectedGoals / $appearances, 1);
                    $insights[] = "Creates high-quality chances with " . $xGPerGame . " expected goals per match.";
                    $strengths[] = "excellent positioning";
                }

                if ($aerialDuelsWon > 0 && $appearances > 0) {
                    $aerialPerGame = round($aerialDuelsWon / $appearances, 1);
                    $insights[] = "Strong aerial presence winning " . $aerialPerGame . " duels per game.";
                    $strengths[] = "dominant aerial ability";
                }

                if ($touchesInBox > 0 && $appearances > 0) {
                    $touchesPerGame = round($touchesInBox / $appearances, 1);
                    $insights[] = "Regular presence in dangerous areas with " . $touchesPerGame . " touches in the box per match.";
                    $strengths[] = "excellent positioning";
                }

                if ($goals < $appearances * 0.4) {
                    $improvements[] = "goal-scoring frequency could be improved";
                }

                if ($shotConversionRate < 0.15) {
                    $improvements[] = "shot conversion rate needs improvement";
                }

                // Rating calculation for strikers
                $overallRating = min(100, ($goals * 3) + ($assists * 1.5) + ($shotConversionRate * 200) + ($expectedGoals * 1.5) + ($aerialDuelsWon * 0.3) + ($touchesInBox * 0.1) + 10);
                break;
        }

        // Common analysis elements
        $age = $player->age ?? 0;
        $yellowCards = $player->yellow_cards ?? 0;
        $redCards = $player->red_cards ?? 0;

        // Discipline analysis
        $totalCards = $yellowCards + ($redCards * 3);
        if ($totalCards === 0 && $appearances > 5) {
            $insights[] = "Exemplary disciplinary record with no cards in " . $appearances . " appearances.";
            $strengths[] = "exemplary discipline";
        } elseif ($totalCards < $appearances * 0.3) {
            $strengths[] = "good disciplinary record";
        } elseif ($totalCards > $appearances * 0.5) {
            $improvements[] = "disciplinary consistency requires attention";
        }

        // Age and experience analysis
        if ($age < 20 && count($strengths) > 2) {
            $insights[] = "Promising young talent showing advanced abilities beyond age.";
        } elseif ($age > 30 && $overallRating > 70) {
            $insights[] = "Experienced professional maintaining high performance levels.";
        }

        // Generate recommendations based on position and analysis
        if ($age < 23) {
            $recommendations[] = "focus on technical skill development and tactical understanding";
        }

        if (count($improvements) > 0) {
            $recommendations[] = "targeted training to address " . implode(" and ", array_slice($improvements, 0, 2));
        }

        if ($appearances < 15) {
            $recommendations[] = "increased match exposure and competitive experience";
        }

        if (count($strengths) > 3) {
            $recommendations[] = "continue developing strengths while maintaining consistency";
        }

        // Round overall rating
        $overallRating = round($overallRating, 1);
    @endphp

    <h2 class="section-title">AI Insights</h2>
    <div class="content-box">
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
                $analysis = [];

                // Profile snapshot
                $profileDesc = "";
                if ($age < 18) {
                    $profileDesc = "Emerging young talent";
                } elseif ($age < 23) {
                    $profileDesc = "Developing professional";
                } elseif ($age < 30) {
                    $profileDesc = "Established player";
                } else {
                    $profileDesc = "Experienced veteran";
                }

                if ($appearances < 10) {
                    $profileDesc .= " with limited senior experience";
                } elseif ($appearances < 50) {
                    $profileDesc .= " building competitive experience";
                } elseif ($appearances < 100) {
                    $profileDesc .= " with solid match experience";
                } else {
                    $profileDesc .= " with extensive professional experience";
                }

                $analysis[] = "<strong>Profile:</strong> " . ucfirst($profileDesc) . " currently playing as a " . strtolower($player->position ?? 'player') . ".";

                // Current form analysis
                $performanceDesc = "";
                $totalContributions = ($player->goals ?? 0) + ($player->assists ?? 0);
                $contributionRate = $appearances > 0 ? $totalContributions / $appearances : 0;

                if ($contributionRate > 1.5) {
                    $performanceDesc = "high-impact performer";
                } elseif ($contributionRate > 1.0) {
                    $performanceDesc = "consistent contributor";
                } elseif ($contributionRate > 0.5) {
                    $performanceDesc = "developing player";
                } else {
                    $performanceDesc = "player requiring development focus";
                }

                $analysis[] = "<strong>Current Form:</strong> Demonstrates as a " . $performanceDesc . " with " . ($player->goals ?? 0) . " goals and " . ($player->assists ?? 0) . " assists across " . $appearances . " appearances.";

                // Strengths
                if (!empty($strengths)) {
                    $analysis[] = "<strong>Strengths:</strong> Shows " . implode(", ", $strengths) . ".";
                }

                // Areas for development
                if (!empty($improvements)) {
                    $analysis[] = "<strong>Areas for Development:</strong> " . implode(", ", $improvements) . ".";
                }

                // Potential outlook
                $potential = "";
                if ($age < 20 && $overallRating > 60) {
                    $potential = "Shows promising early potential with room for significant development.";
                } elseif ($age < 25 && $overallRating > 70) {
                    $potential = "Demonstrates solid foundation for long-term professional success.";
                } elseif ($age < 30 && $appearances > 50) {
                    $potential = "At peak performance level with established professional capabilities.";
                } elseif ($age >= 30 && $appearances > 100) {
                    $potential = "Brings valuable experience and leadership to the team.";
                } else {
                    $potential = "Development trajectory suggests continued growth with focused training.";
                }

                $analysis[] = "<strong>Potential Outlook:</strong> " . $potential;

                // Recommendations
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
                    <p style="color: #64748b; margin-bottom: 12px; margin: 0 0 12px 0; word-wrap: break-word; overflow-wrap: break-word;">
                        {!! $paragraph !!}
                    </p>
                @endforeach
            </div>

            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.1);">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                    <i class="fas fa-clock" style="margin-right: 5px;"></i>
                    Analysis updated: {{ now()->format('M j, Y \a\t g:i A') }} |
                    Based on {{ $appearances }} appearances, {{ $player->goals ?? 0 }} goals, {{ $player->assists ?? 0 }} assists
                </p>
            </div>
        </div>
    </div>
@endif
