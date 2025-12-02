@extends('layouts.academy')

@section('title', 'Match Center - Vipers Academy')

@section('content')


<!-- Tab Content Container -->
<main class="tab-content-container">
    <!-- Overview Tab -->
    <section class="tab-content active" id="overview">
        <div class="container">
            <!-- Live Matches Section -->
            @if($matches->where('status', 'live')->count() > 0)
            <div class="live-matches-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="live-indicator"></span>
                        Live Matches
                    </h2>
                </div>
                <div class="live-matches-grid">
                    @foreach($matches->where('status', 'live') as $match)
                    <div class="live-match-card">
                        <div class="live-match-header">
                            <div class="competition-badge">{{ $match->type }}</div>
                            <div class="live-status">
                                <span class="live-dot"></span>
                                LIVE
                            </div>
                        </div>
                        <div class="live-match-content">
                            <div class="live-teams">
                                <div class="team">
                                    <div class="team-logo">
                                        <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers">
                                    </div>
                                    <div class="team-name">Vipers Academy</div>
                                </div>
                                <div class="live-score">
                                    <div class="score-display">
                                        <span class="home-score">{{ $match->vipers_score ?? 0 }}</span>
                                        <span class="score-separator">-</span>
                                        <span class="away-score">{{ $match->opponent_score ?? 0 }}</span>
                                    </div>
                                    <div class="match-time">LIVE</div>
                                </div>
                                <div class="team">
                                    <div class="team-logo">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="team-name">{{ Str::limit($match->opponent, 12) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="live-match-footer">
                            <div class="match-details">
                                <span class="venue">{{ Str::limit($match->venue, 20) }}</span>
                                <span class="minute">{{ rand(45, 90) }}'</span>
                            </div>
                            <a href="{{ route('match-center.show', $match) }}" class="btn-live-details">
                                <i class="fas fa-info-circle"></i>
                                Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Next Matches -->
            <div class="next-matches-section">
                <div class="section-header">
                    <h2 class="section-title">Next Matches</h2>
                    <a href="#fixtures" class="view-all-link">View All</a>
                </div>
                <div class="matches-list">
                    @foreach($matches->where('status', 'upcoming')->take(5) as $match)
                    <div class="match-list-item">
                        <div class="match-date">
                            <div class="date">{{ $match->match_date->format('d') }}</div>
                            <div class="month">{{ $match->match_date->format('M') }}</div>
                        </div>
                        <div class="match-info">
                            <div class="competition">{{ $match->type }}</div>
                            <div class="teams">
                                <span class="home-team">Vipers Academy</span>
                                <span class="vs">vs</span>
                                <span class="away-team">{{ Str::limit($match->opponent, 15) }}</span>
                            </div>
                            <div class="match-meta">
                                <span class="time">{{ $match->match_date->format('H:i') }}</span>
                                <span class="venue">{{ Str::limit($match->venue, 15) }}</span>
                            </div>
                        </div>
                        <div class="match-actions">
                            @if($match->live_link)
                            <a href="{{ $match->live_link }}" class="btn-live-stream" target="_blank">
                                <i class="fas fa-play-circle"></i>
                            </a>
                            @endif
                            <a href="{{ route('match-center.show', $match) }}" class="btn-details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Results -->
            <div class="recent-results-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Results</h2>
                    <a href="#results" class="view-all-link">View All</a>
                </div>
                <div class="results-list">
                    @foreach($matches->where('status', 'completed')->take(5) as $match)
                    <div class="result-list-item">
                        <div class="match-date">
                            <div class="date">{{ $match->match_date->format('d') }}</div>
                            <div class="month">{{ $match->match_date->format('M') }}</div>
                        </div>
                        <div class="match-info">
                            <div class="competition">{{ $match->type }}</div>
                            <div class="teams">
                                <span class="home-team">Vipers Academy</span>
                                <span
                                    class="score">{{ $match->vipers_score ?? 0 }}-{{ $match->opponent_score ?? 0 }}</span>
                                <span class="away-team">{{ Str::limit($match->opponent, 15) }}</span>
                            </div>
                            <div class="match-meta">
                                <span class="venue">{{ Str::limit($match->venue, 15) }}</span>
                            </div>
                        </div>
                        <div class="match-result">
                            @if(($match->vipers_score ?? 0) > ($match->opponent_score ?? 0))
                            <span class="result-badge win">W</span>
                            @elseif(($match->vipers_score ?? 0) < ($match->opponent_score ?? 0))
                                <span class="result-badge loss">L</span>
                                @else
                                <span class="result-badge draw">D</span>
                                @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Fixtures Tab -->
    <section class="tab-content" id="fixtures">
        <div class="container">
            <div class="fixtures-header">
                <h2 class="section-title">All Fixtures</h2>
                <div class="fixtures-filters">
                    <select class="filter-select" id="competitionFilter">
                        <option value="all">All Competitions</option>
                        <option value="tournament">Tournaments</option>
                        <option value="friendly">Friendlies</option>
                    </select>
                    <select class="filter-select" id="monthFilter">
                        <option value="all">All Months</option>
                        <option value="current">This Month</option>
                        <option value="next">Next Month</option>
                    </select>
                </div>
            </div>

            <div class="fixtures-calendar">
                @php
                $groupedMatches = $matches->where('status', 'upcoming')->groupBy(function($match) {
                return $match->match_date->format('Y-m-d');
                });
                @endphp

                @foreach($groupedMatches as $date => $dayMatches)
                <div class="calendar-day">
                    <div class="day-header">
                        <div class="day-date">
                            <div class="day">{{ \Carbon\Carbon::parse($date)->format('d') }}</div>
                            <div class="month">{{ \Carbon\Carbon::parse($date)->format('M') }}</div>
                        </div>
                        <div class="day-name">{{ \Carbon\Carbon::parse($date)->format('l') }}</div>
                    </div>
                    <div class="day-matches">
                        @foreach($dayMatches as $match)
                        <div class="fixture-card">
                            <div class="fixture-time">{{ $match->match_date->format('H:i') }}</div>
                            <div class="fixture-teams">
                                <div class="fixture-team">
                                    <div class="team-logo">
                                        <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers">
                                    </div>
                                    <div class="team-name">Vipers Academy</div>
                                </div>
                                <div class="fixture-vs">vs</div>
                                <div class="fixture-team">
                                    <div class="team-logo">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="team-name">{{ $match->opponent }}</div>
                                </div>
                            </div>
                            <div class="fixture-meta">
                                <span class="competition">{{ $match->type }}</span>
                                <span class="venue">{{ $match->venue }}</span>
                            </div>
                            <div class="fixture-actions">
                                @if($match->live_link)
                                <a href="{{ $match->live_link }}" class="btn-live-stream" target="_blank">
                                    <i class="fas fa-play-circle"></i>
                                    Live Stream
                                </a>
                                @endif
                                <a href="{{ route('match-center.show', $match) }}" class="btn-details">
                                    <i class="fas fa-info-circle"></i>
                                    Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Results Tab -->
    <section class="tab-content" id="results">
        <div class="container">
            <div class="results-header">
                <h2 class="section-title">Match Results</h2>
                <div class="results-filters">
                    <select class="filter-select" id="resultsCompetitionFilter">
                        <option value="all">All Competitions</option>
                        <option value="tournament">Tournaments</option>
                        <option value="friendly">Friendlies</option>
                    </select>
                    <select class="filter-select" id="resultsMonthFilter">
                        <option value="all">All Months</option>
                        <option value="last">Last Month</option>
                        <option value="current">This Month</option>
                    </select>
                </div>
            </div>

            <div class="results-grid">
                @foreach($matches->where('status', 'completed') as $match)
                <div class="result-card">
                    <div class="result-header">
                        <div class="competition-badge">{{ $match->type }}</div>
                        <div class="match-date">{{ $match->match_date->format('M d, Y') }}</div>
                    </div>
                    <div class="result-content">
                        <div class="result-teams">
                            <div class="result-team">
                                <div class="team-logo">
                                    <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers">
                                </div>
                                <div class="team-name">Vipers Academy</div>
                            </div>
                            <div class="result-score">
                                <div class="score-display">
                                    <span
                                        class="home-score {{ ($match->vipers_score ?? 0) > ($match->opponent_score ?? 0) ? 'winner' : '' }}">
                                        {{ $match->vipers_score ?? 0 }}
                                    </span>
                                    <span class="score-separator">-</span>
                                    <span
                                        class="away-score {{ ($match->opponent_score ?? 0) > ($match->vipers_score ?? 0) ? 'winner' : '' }}">
                                        {{ $match->opponent_score ?? 0 }}
                                    </span>
                                </div>
                                <div class="result-status">
                                    @if(($match->vipers_score ?? 0) > ($match->opponent_score ?? 0))
                                    <span class="status win">WIN</span>
                                    @elseif(($match->vipers_score ?? 0) < ($match->opponent_score ?? 0))
                                        <span class="status loss">LOSS</span>
                                        @else
                                        <span class="status draw">DRAW</span>
                                        @endif
                                </div>
                            </div>
                            <div class="result-team">
                                <div class="team-logo">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="team-name">{{ $match->opponent }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="result-footer">
                        <div class="match-venue">{{ $match->venue }}</div>
                        <div class="result-actions">
                            @if($match->highlights_link)
                            <a href="{{ $match->highlights_link }}" class="btn-highlights" target="_blank">
                                <i class="fas fa-video"></i>
                                Highlights
                            </a>
                            @endif
                            <a href="{{ route('match-center.show', $match) }}" class="btn-details">
                                <i class="fas fa-info-circle"></i>
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Standings Tab -->
    <section class="tab-content" id="standings">
        <div class="container">
            <div class="standings-header">
                <h2 class="section-title">League Standings</h2>
                <div class="standings-info">
                    <span class="last-updated">Last updated: {{ now()->format('M d, Y H:i') }}</span>
                </div>
            </div>

            <div class="standings-table-container">
                <div class="standings-placeholder">
                    <div class="placeholder-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="placeholder-title">Standings Coming Soon</h3>
                    <p class="placeholder-text">
                        League standings will be displayed here once tournament matches begin.
                        Stay tuned for live updates and rankings.
                    </p>
                    <div class="placeholder-stats">
                        <div class="stat">
                            <div class="stat-number">{{ $stats['tournament_matches'] }}</div>
                            <div class="stat-label">Tournament Matches</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">{{ $stats['victories'] }}</div>
                            <div class="stat-label">Victories</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Tab -->
    <section class="tab-content" id="stats">
        <div class="container">
            <div class="stats-header">
                <h2 class="section-title">Season Statistics</h2>
                <div class="stats-period">
                    <span class="current-season">2025 Season</span>
                </div>
            </div>

            <div class="stats-overview">
                <div class="stats-cards">
                    <div class="stat-card-large">
                        <div class="stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['victories'] }}</div>
                            <div class="stat-label">Victories</div>
                        </div>
                    </div>
                    <div class="stat-card-large">
                        <div class="stat-icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['goals_scored'] }}</div>
                            <div class="stat-label">Goals Scored</div>
                        </div>
                    </div>
                    <div class="stat-card-large">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['completed_matches'] }}</div>
                            <div class="stat-label">Matches Played</div>
                        </div>
                    </div>
                    <div class="stat-card-large">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['upcoming_matches'] }}</div>
                            <div class="stat-label">Upcoming Fixtures</div>
                        </div>
                    </div>
                </div>

                <div class="stats-breakdown">
                    <div class="stats-section">
                        <h3 class="stats-section-title">Match Results</h3>
                        <div class="result-breakdown">
                            <div class="result-item">
                                <span class="result-label">Wins</span>
                                <div class="result-bar">
                                    <div class="result-fill win"
                                        style="width: {{ $stats['completed_matches'] > 0 ? ($stats['victories'] / $stats['completed_matches']) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span class="result-count">{{ $stats['victories'] }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Draws</span>
                                <div class="result-bar">
                                    <div class="result-fill draw"
                                        style="width: {{ $stats['completed_matches'] > 0 ? (($stats['completed_matches'] - $stats['victories'] - ($stats['completed_matches'] - $stats['victories'] - ($stats['completed_matches'] - $stats['victories']))) / $stats['completed_matches']) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span
                                    class="result-count">{{ $stats['completed_matches'] - $stats['victories'] - ($stats['completed_matches'] - $stats['victories'] - ($stats['completed_matches'] - $stats['victories'])) }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Losses</span>
                                <div class="result-bar">
                                    <div class="result-fill loss"
                                        style="width: {{ $stats['completed_matches'] > 0 ? (($stats['completed_matches'] - $stats['victories']) / $stats['completed_matches']) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span
                                    class="result-count">{{ $stats['completed_matches'] - $stats['victories'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</div>
@endsection

@push('styles')
<style>
/* Modern Match Center Styles */
.match-center-wrapper {
    background: #f8fafc;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Header Styles */
.match-center-header {
    position: relative;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: white;
    padding: 2rem 0;
    overflow: hidden;
}

.header-background {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.header-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(234, 28, 77, 0.1) 0%, rgba(101, 198, 110, 0.1) 100%);
}

.header-pattern {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
}

.header-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.header-brand {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.brand-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid rgba(234, 28, 77, 0.3);
    box-shadow: 0 8px 25px rgba(234, 28, 77, 0.2);
}

.brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.brand-info h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.brand-subtitle {
    font-size: 1.1rem;
    opacity: 0.8;
    margin: 0.25rem 0 0 0;
}

.header-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 900;
    color: #ea1c4d;
    display: block;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.25rem;
}

/* Navigation Styles */
.match-navigation {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: fixed;
    top: 154px;
    left: 0;
    right: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0;
}

.nav-tabs {
    display: flex;
    gap: 0;
}

.nav-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    border-bottom: 3px solid transparent;
}

.nav-tab:hover {
    color: #ea1c4d;
    background: rgba(234, 28, 77, 0.05);
}

.nav-tab.active {
    color: #ea1c4d;
    border-bottom-color: #ea1c4d;
    background: rgba(234, 28, 77, 0.05);
}

.nav-tab i {
    font-size: 1rem;
}

.nav-search {
    padding: 1rem;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box i {
    position: absolute;
    left: 1rem;
    color: #9ca3af;
    font-size: 0.9rem;
}

.search-box input {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    width: 250px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #ea1c4d;
    box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
}

/* Tab Content */
.tab-content-container {
    padding: 15rem 0 2rem 0;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.live-indicator {
    width: 12px;
    height: 12px;
    background: #ef4444;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.view-all-link {
    color: #ea1c4d;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: #c0173f;
}

/* Live Matches */
.live-matches-section {
    margin-bottom: 3rem;
}

.live-matches-grid {
    display: grid;
    gap: 1.5rem;
}

.live-match-card {
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.live-match-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ef4444, #dc2626, #ef4444);
    animation: live-pulse 2s infinite;
}

.live-match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.competition-badge {
    background: linear-gradient(135deg, #ea1c4d, #c0173f);
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.live-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #ef4444;
    font-weight: 700;
    font-size: 0.9rem;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    animation: live-pulse 1s infinite;
}

.live-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.live-teams .team {
    flex: 1;
    text-align: center;
}

.live-teams .team-logo {
    width: 60px;
    height: 60px;
    margin: 0 auto 0.75rem;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.live-teams .team-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: #374151;
}

.live-score {
    text-align: center;
    padding: 0 2rem;
}

.live-score .score-display {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.live-score .match-time {
    color: #ef4444;
    font-weight: 700;
    font-size: 0.9rem;
}

.live-match-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.match-details {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.match-details .venue,
.match-details .minute {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.btn-live-details {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #ea1c4d, #c0173f);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-live-details:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(234, 28, 77, 0.3);
}

/* Next Matches */
.next-matches-section {
    margin-bottom: 3rem;
}

.matches-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.match-list-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.match-list-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-color: #ea1c4d;
}

.match-date {
    text-align: center;
    min-width: 60px;
}

.match-date .date {
    font-size: 1.5rem;
    font-weight: 900;
    color: #ea1c4d;
    line-height: 1;
}

.match-date .month {
    font-size: 0.8rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-top: 0.25rem;
}

.match-info {
    flex: 1;
}

.match-info .competition {
    font-size: 0.8rem;
    color: #ea1c4d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.match-info .teams {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.teams .home-team,
.teams .away-team {
    font-weight: 600;
    color: #1f2937;
}

.teams .vs {
    color: #6b7280;
    font-weight: 500;
}

.match-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.match-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-live-stream,
.btn-details {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-live-stream {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.btn-live-stream:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
}

.btn-details {
    background: #f3f4f6;
    color: #6b7280;
}

.btn-details:hover {
    background: #ea1c4d;
    color: white;
}

/* Recent Results */
.recent-results-section {
    margin-bottom: 3rem;
}

.results-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.result-list-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.result-list-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-color: #ea1c4d;
}

.result-list-item .match-date {
    min-width: 60px;
}

.result-list-item .match-info {
    flex: 1;
}

.result-list-item .competition {
    font-size: 0.8rem;
    color: #ea1c4d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.result-list-item .teams {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.result-list-item .score {
    font-weight: 900;
    color: #ea1c4d;
    font-size: 1.1rem;
}

.result-list-item .match-meta {
    font-size: 0.85rem;
    color: #6b7280;
}

.result-result {
    min-width: 40px;
}

.result-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.8rem;
}

.result-badge.win {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.result-badge.loss {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.result-badge.draw {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

/* Fixtures Styles */
.fixtures-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.fixtures-filters {
    display: flex;
    gap: 1rem;
}

.filter-select {
    padding: 0.5rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #ea1c4d;
    box-shadow: 0 0 0 3px rgba(234, 28, 77, 0.1);
}

.fixtures-calendar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.calendar-day {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.day-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 1px solid #e5e7eb;
}

.day-header .day-date {
    text-align: center;
    min-width: 60px;
}

.day-header .day {
    font-size: 1.75rem;
    font-weight: 900;
    color: #ea1c4d;
    line-height: 1;
}

.day-header .month {
    font-size: 0.9rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-top: 0.25rem;
}

.day-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
}

.day-matches {
    padding: 1.5rem;
}

.fixture-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.fixture-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-color: #ea1c4d;
}

.fixture-card:last-child {
    margin-bottom: 0;
}

.fixture-time {
    font-size: 1.1rem;
    font-weight: 700;
    color: #ea1c4d;
    min-width: 60px;
}

.fixture-teams {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.fixture-team {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.fixture-team .team-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.fixture-team .team-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.9rem;
}

.fixture-vs {
    color: #6b7280;
    font-weight: 500;
    padding: 0 0.5rem;
}

.fixture-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    min-width: 120px;
}

.fixture-meta .competition {
    font-size: 0.8rem;
    color: #ea1c4d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fixture-meta .venue {
    font-size: 0.8rem;
    color: #6b7280;
}

.fixture-actions {
    display: flex;
    gap: 0.5rem;
}

/* Results Styles */
.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.results-filters {
    display: flex;
    gap: 1rem;
}

.results-grid {
    display: grid;
    gap: 1.5rem;
}

.result-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.result-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #ea1c4d;
}

.result-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 1px solid #e5e7eb;
}

.result-header .competition-badge {
    background: linear-gradient(135deg, #ea1c4d, #c0173f);
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.result-header .match-date {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

.result-content {
    padding: 2rem 1.5rem;
}

.result-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.result-teams .result-team {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.result-team .team-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.result-team .team-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.9rem;
}

.result-score {
    text-align: center;
    padding: 0 2rem;
}

.result-score .score-display {
    font-size: 2rem;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.result-score .score-display .winner {
    color: #10b981;
}

.result-status .status {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.win {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status.loss {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.status.draw {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.result-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
}

.result-footer .match-venue {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

.result-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-highlights,
.btn-details {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-highlights {
    background: linear-gradient(135deg, #ea580c, #c2410c);
    color: white;
}

.btn-highlights:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3);
}

/* Standings Styles */
.standings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.standings-info {
    text-align: right;
}

.last-updated {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.standings-table-container {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.standings-placeholder {
    max-width: 500px;
    margin: 0 auto;
}

.placeholder-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-icon i {
    font-size: 2.5rem;
    color: #9ca3af;
}

.placeholder-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.placeholder-text {
    font-size: 1rem;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.placeholder-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.placeholder-stats .stat {
    text-align: center;
}

.placeholder-stats .stat-number {
    font-size: 2rem;
    font-weight: 900;
    color: #ea1c4d;
    display: block;
}

.placeholder-stats .stat-label {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Statistics Styles */
.stats-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.stats-period {
    text-align: right;
}

.current-season {
    font-size: 1rem;
    font-weight: 600;
    color: #ea1c4d;
    background: rgba(234, 28, 77, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

.stats-overview {
    display: grid;
    gap: 3rem;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.stat-card-large {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2.5rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.stat-card-large:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.stat-card-large .stat-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.stat-card-large .stat-icon i {
    font-size: 2rem;
}

.stat-card-large .stat-content .stat-value {
    font-size: 3rem;
    font-weight: 900;
    color: #1f2937;
    display: block;
    margin-bottom: 0.5rem;
}

.stat-card-large .stat-content .stat-label {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-breakdown {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.stats-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 2rem;
}

.result-breakdown {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.result-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.result-label {
    min-width: 60px;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.result-bar {
    flex: 1;
    height: 12px;
    background: #f3f4f6;
    border-radius: 6px;
    overflow: hidden;
}

.result-fill {
    height: 100%;
    border-radius: 6px;
    transition: width 1s ease;
}

.result-fill.win {
    background: linear-gradient(90deg, #10b981, #059669);
}

.result-fill.draw {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.result-fill.loss {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.result-count {
    min-width: 30px;
    text-align: right;
    font-weight: 700;
    color: #374151;
    font-size: 0.9rem;
}

/* Animations */
@keyframes live-pulse {

    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: 0.7;
        transform: scale(1.05);
    }
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }
}

/* Responsive Design */
@media (max-width: 1199.98px) {
    .header-content {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }

    .header-stats {
        justify-content: center;
    }

    .nav-container {
        flex-direction: column;
        gap: 1rem;
    }

    .nav-search {
        width: 100%;
    }

    .search-box input {
        width: 100%;
    }
}

@media (max-width: 1200px) {
    .match-navigation {
        top: 154px;
    }
}

@media (max-width: 992px) {
    .match-navigation {
        top: 110px;
    }
}

@media (max-width: 768px) {
    .match-navigation {
        top: 70px;
    }

    .tab-content-container {
        padding: 10rem 0 2rem 0;
    }
}

@media (max-width: 991.98px) {
    .match-center-header {
        padding: 1.5rem 0;
    }

    .brand-title {
        font-size: 2rem;
    }

    .header-stats {
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .nav-tabs {
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-tab {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 1.75rem;
    }

    .live-match-card {
        padding: 1.5rem;
    }

    .fixture-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .fixture-teams {
        width: 100%;
        justify-content: space-between;
    }

    .fixture-meta {
        min-width: auto;
        flex-direction: row;
        gap: 1rem;
    }
}

@media (max-width: 767.98px) {
    .match-center-header {
        padding: 1rem 0;
    }

    .brand-title {
        font-size: 1.75rem;
    }

    .stat-value {
        font-size: 2rem;
    }

    .nav-tabs {
        gap: 0.25rem;
    }

    .nav-tab {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }

    .nav-tab span {
        display: none;
    }

    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .fixtures-header,
    .results-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .fixtures-filters,
    .results-filters {
        width: 100%;
        justify-content: space-between;
    }

    .filter-select {
        flex: 1;
    }

    .match-list-item,
    .result-list-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .match-date,
    .result-result {
        align-self: center;
    }

    .live-teams {
        flex-direction: column;
        gap: 1rem;
    }

    .live-score {
        padding: 1rem 0;
    }

    .result-teams {
        flex-direction: column;
        gap: 1rem;
    }

    .result-score {
        padding: 1rem 0;
    }

    .stats-cards {
        grid-template-columns: 1fr;
    }

    .stat-card-large {
        padding: 2rem;
    }

    .result-breakdown {
        gap: 1rem;
    }

    .result-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .result-bar {
        width: 100%;
        height: 16px;
    }
}

@media (max-width: 575.98px) {
    .brand-info h1 {
        font-size: 1.5rem;
    }

    .brand-subtitle {
        font-size: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
    }

    .nav-tab {
        padding: 0.5rem;
    }

    .section-title {
        font-size: 1.5rem;
    }

    .live-match-card {
        padding: 1rem;
    }

    .fixture-card {
        padding: 1rem;
    }

    .result-card {
        margin: 0 -0.5rem;
    }

    .stats-breakdown {
        padding: 2rem 1rem;
    }

    .placeholder-stats {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Accessibility */
.nav-tab:focus,
.filter-select:focus,
.search-box input:focus {
    outline: 3px solid rgba(234, 28, 77, 0.5);
    outline-offset: 2px;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .match-center-wrapper {
        background: #111827;
        color: #f9fafb;
    }

    .match-navigation,
    .live-match-card,
    .match-list-item,
    .result-list-item,
    .fixture-card,
    .result-card,
    .stat-card-large,
    .stats-breakdown {
        background: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }

    .day-header,
    .result-header {
        background: linear-gradient(135deg, #374151, #4b5563);
    }

    .live-match-footer,
    .result-footer,
    .day-matches .fixture-card {
        background: #374151;
        border-color: #4b5563;
    }

    .search-box input {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }

    .filter-select {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
}

/* Print Styles */
@media print {

    .match-navigation,
    .nav-search,
    .match-actions,
    .fixture-actions,
    .result-actions {
        display: none;
    }

    .tab-content-container {
        padding: 0;
    }

    .match-center-header {
        background: white;
        color: black;
    }

    .header-pattern,
    .header-shapes {
        display: none;
    }

    .brand-title {
        color: black;
    }

    .section-title {
        color: black;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const navTabs = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    navTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs
            navTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));
            // Show target tab content
            document.getElementById(targetTab).classList.add('active');

            // Ensure content is visible without scrolling - the padding handles positioning
            // Only scroll if user is at the very top of the page
            if (window.pageYOffset < 50) {
                setTimeout(() => {
                    const tabContent = document.getElementById(targetTab);
                    if (tabContent) {
                        tabContent.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 100);
            }
        });
    });

    // Search Functionality
    const searchInput = document.getElementById('matchSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const matchCards = document.querySelectorAll(
                '.match-card, .fixture-card, .result-card, .match-list-item, .result-list-item');

            matchCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                card.style.display = shouldShow ? '' : 'none';
            });
        });
    }

    // Filter Functionality
    const competitionFilter = document.getElementById('competitionFilter');
    const monthFilter = document.getElementById('monthFilter');
    const resultsCompetitionFilter = document.getElementById('resultsCompetitionFilter');
    const resultsMonthFilter = document.getElementById('resultsMonthFilter');

    function applyFilters() {
        const competitionValue = competitionFilter ? competitionFilter.value : 'all';
        const monthValue = monthFilter ? monthFilter.value : 'all';
        const fixtureCards = document.querySelectorAll('.fixture-card');

        fixtureCards.forEach(card => {
            const competition = card.querySelector('.competition').textContent.toLowerCase();
            const matchDate = new Date(card.closest('.calendar-day').querySelector('.day-name')
                .textContent);
            const currentMonth = new Date().getMonth();
            const matchMonth = matchDate.getMonth();

            let showCard = true;

            if (competitionValue !== 'all') {
                showCard = showCard && competition.includes(competitionValue);
            }

            if (monthValue === 'current') {
                showCard = showCard && (matchMonth === currentMonth);
            } else if (monthValue === 'next') {
                showCard = showCard && (matchMonth === (currentMonth + 1) % 12);
            }

            card.style.display = showCard ? '' : 'none';
        });
    }

    function applyResultsFilters() {
        const competitionValue = resultsCompetitionFilter ? resultsCompetitionFilter.value : 'all';
        const monthValue = resultsMonthFilter ? resultsMonthFilter.value : 'all';
        const resultCards = document.querySelectorAll('.result-card');

        resultCards.forEach(card => {
            const competition = card.querySelector('.competition-badge').textContent.toLowerCase();
            const matchDate = new Date(card.querySelector('.match-date').textContent);
            const currentMonth = new Date().getMonth();
            const lastMonth = currentMonth === 0 ? 11 : currentMonth - 1;
            const matchMonth = matchDate.getMonth();

            let showCard = true;

            if (competitionValue !== 'all') {
                showCard = showCard && competition.includes(competitionValue);
            }

            if (monthValue === 'current') {
                showCard = showCard && (matchMonth === currentMonth);
            } else if (monthValue === 'last') {
                showCard = showCard && (matchMonth === lastMonth);
            }

            card.style.display = showCard ? '' : 'none';
        });
    }

    if (competitionFilter) competitionFilter.addEventListener('change', applyFilters);
    if (monthFilter) monthFilter.addEventListener('change', applyFilters);
    if (resultsCompetitionFilter) resultsCompetitionFilter.addEventListener('change', applyResultsFilters);
    if (resultsMonthFilter) resultsMonthFilter.addEventListener('change', applyResultsFilters);

    // Live Match Updates (Simulated)
    function updateLiveMatches() {
        const liveMatches = document.querySelectorAll('.live-match-card');
        liveMatches.forEach(match => {
            const minuteElement = match.querySelector('.minute');
            if (minuteElement) {
                const currentMinute = parseInt(minuteElement.textContent);
                if (currentMinute < 90) {
                    minuteElement.textContent = (currentMinute + 1) + "'";
                }
            }
        });
    }

    // Update live matches every 30 seconds
    if (document.querySelectorAll('.live-match-card').length > 0) {
        setInterval(updateLiveMatches, 30000);
    }

    // Smooth Scroll for Internal Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Keyboard Navigation
    navTabs.forEach((tab, index) => {
        tab.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }

            if (e.key === 'ArrowRight') {
                e.preventDefault();
                const nextIndex = (index + 1) % navTabs.length;
                navTabs[nextIndex].focus();
            }

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                const prevIndex = (index - 1 + navTabs.length) % navTabs.length;
                navTabs[nextIndex].focus();
            }
        });
    });

    // Intersection Observer for Animations
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe cards for animation
        document.querySelectorAll('.match-card, .fixture-card, .result-card, .stat-card-large').forEach(
            card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
    }

    // Ripple Effect for Buttons
    function createRipple(event) {
        const button = event.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');

        button.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    // Add ripple effect to interactive buttons
    const interactiveButtons = document.querySelectorAll(
        '.nav-tab, .btn-live-stream, .btn-details, .btn-highlights, .btn-live-details');
    interactiveButtons.forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Add CSS for ripple effect dynamically
    const style = document.createElement('style');
    style.textContent = `
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .nav-tab,
        .btn-live-stream,
        .btn-details,
        .btn-highlights,
        .btn-live-details {
            position: relative;
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);

    // Performance Optimization: Lazy Load Images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Touch Gestures for Mobile
    if ('ontouchstart' in window) {
        let touchStartX = 0;
        let touchEndX = 0;

        const navContainer = document.querySelector('.nav-tabs');
        if (navContainer) {
            navContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            navContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    const activeTab = document.querySelector('.nav-tab.active');
                    const allTabs = Array.from(navTabs);
                    const currentIndex = allTabs.indexOf(activeTab);

                    if (diff > 0 && currentIndex < allTabs.length - 1) {
                        // Swipe left - next tab
                        allTabs[currentIndex + 1].click();
                    } else if (diff < 0 && currentIndex > 0) {
                        // Swipe right - previous tab
                        allTabs[currentIndex - 1].click();
                    }
                }
            }
        }
    }

    // Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        // Number keys 1-5 to switch tabs
        if (e.key >= '1' && e.key <= '5') {
            const index = parseInt(e.key) - 1;
            if (navTabs[index]) {
                navTabs[index].click();
                navTabs[index].focus();
            }
        }

        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchInput) {
                searchInput.focus();
            }
        }
    });

    // Visual Feedback for Button Interactions
    interactiveButtons.forEach(button => {
        button.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });

        button.addEventListener('mouseup', function() {
            this.style.transform = '';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Auto-refresh for live matches (simulated)
    const liveMatches = document.querySelectorAll('.live-match-card');
    if (liveMatches.length > 0) {
        setInterval(() => {
            // In a real application, this would fetch live data from an API
            console.log('Checking for live match updates...');
        }, 60000); // Check every minute
    }

    // Scroll to Top Button
    const scrollButton = document.createElement('button');
    scrollButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollButton.className = 'scroll-to-top';
    scrollButton.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #ea1c4d, #764ba2);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(234, 28, 77, 0.3);
        font-size: 1.1rem;
    `;

    document.body.appendChild(scrollButton);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 500) {
            scrollButton.style.opacity = '1';
            scrollButton.style.visibility = 'visible';
        } else {
            scrollButton.style.opacity = '0';
            scrollButton.style.visibility = 'hidden';
        }
    });

    scrollButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    scrollButton.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
    });

    scrollButton.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });

    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-out'
        });
    }

    // Loading States for Buttons
    function addLoadingState(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }

    // Add loading states to action buttons
    document.querySelectorAll('.btn-live-stream, .btn-highlights').forEach(button => {
        button.addEventListener('click', function() {
            addLoadingState(this);
        });
    });

    // Match Card Hover Effects
    document.querySelectorAll('.match-card, .fixture-card, .result-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const logos = this.querySelectorAll('.team-logo');
            logos.forEach((logo, index) => {
                setTimeout(() => {
                    logo.style.transform = 'scale(1.1) rotateY(360deg)';
                    logo.style.transition = 'transform 0.6s ease';
                }, index * 100);
            });
        });

        card.addEventListener('mouseleave', function() {
            const logos = this.querySelectorAll('.team-logo');
            logos.forEach(logo => {
                logo.style.transform = 'scale(1) rotateY(0deg)';
            });
        });
    });

    // Statistics Animation
    function animateStats() {
        const statValues = document.querySelectorAll('.stat-value');
        statValues.forEach(value => {
            const target = parseInt(value.textContent.replace(/,/g, ''));
            if (!isNaN(target)) {
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    value.textContent = Math.floor(current).toLocaleString();
                }, 30);
            }
        });
    }

    // Animate stats when statistics tab is viewed
    const statsTab = document.querySelector('[data-tab="stats"]');
    if (statsTab) {
        statsTab.addEventListener('click', () => {
            setTimeout(animateStats, 300);
        });
    }

    // Trigger initial animations
    setTimeout(() => {
        animateStats();
    }, 1000);
});
</script>
@endpush
