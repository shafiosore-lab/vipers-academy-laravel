@extends('layouts.academy')

@section('title', 'Football Standings - Vipers Academy')

@section('content')
<div class="standings-page">

    <!-- Hero Section -->
    <section class="standings-hero position-relative">
        <div class="hero-background"
            style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1920&q=80');">
        </div>
        <div class="hero-overlay"></div>
        <div class="container position-relative">
            <div class="row align-items-center min-vh-50">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold text-white mb-4">Football Standings</h1>
                    <p class="lead text-white-50 mb-4">Comprehensive league statistics and player rankings</p>

                    <!-- Season & League Selector -->
                    <div class="filters-section bg-white rounded-3 p-1 shadow-lg d-block w-75 mx-auto">
                        <form method="GET" class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <select name="season" class="form-select form-select-lg" onchange="this.form.submit()">
                                    @forelse($availableSeasons as $seasonOption)
                                    <option value="{{ $seasonOption }}"
                                        {{ $seasonOption == $season ? 'selected' : '' }}>
                                        {{ $seasonOption }}
                                    </option>
                                    @empty
                                    <option value="2024/25" {{ '2024/25' == $season ? 'selected' : '' }}>2024/25</option>
                                    <option value="2023/24" {{ '2023/24' == $season ? 'selected' : '' }}>2023/24</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="league" class="form-select form-select-lg" onchange="this.form.submit()">
                                    @forelse($availableLeagues as $leagueOption)
                                    <option value="{{ $leagueOption }}"
                                        {{ $leagueOption == $league ? 'selected' : '' }}>
                                        {{ $leagueOption }}
                                    </option>
                                    @empty
                                    <option value="Premier League" {{ 'Premier League' == $league ? 'selected' : '' }}>Premier League</option>
                                    <option value="Championship" {{ 'Championship' == $league ? 'selected' : '' }}>Championship</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>View
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Compact Filter Bar -->
    <div class="filter-bar" id="filterBar">
        <div class="container-fluid px-4">
            <div class="row g-2 align-items-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <select class="form-select form-select-sm" id="seasonFilter">
                        <option value="">All Seasons</option>
                        @foreach($availableSeasons as $season)
                        <option value="{{ $season }}" {{ $season == $season ? 'selected' : '' }}>
                            {{ $season }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <select class="form-select form-select-sm" id="leagueFilter">
                        <option value="">All Leagues</option>
                        @foreach($availableLeagues as $league)
                        <option value="{{ $league }}" {{ $league == $league ? 'selected' : '' }}>
                            {{ $league }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <select class="form-select form-select-sm" id="sortFilter">
                        <option value="position">Position</option>
                        <option value="points-desc">Points ↓</option>
                        <option value="points-asc">Points ↑</option>
                        <option value="name-asc">Name A-Z</option>
                        <option value="name-desc">Name Z-A</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary active" id="gridView" title="Grid View">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="listView" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="resetFilters" title="Reset">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="standings-navigation-placeholder"></div>
    <section class="standings-navigation bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills nav-fill standings-tabs" id="standingsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="pill"
                                data-bs-target="#overview" type="button" role="tab">
                                Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="league-tab" data-bs-toggle="pill" data-bs-target="#league"
                                type="button" role="tab">
                                League Table
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="scorers-tab" data-bs-toggle="pill" data-bs-target="#scorers"
                                type="button" role="tab">
                                Top Scorers
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cleansheets-tab" data-bs-toggle="pill"
                                data-bs-target="#cleansheets" type="button" role="tab">
                                Clean Sheets
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="goalkeepers-tab" data-bs-toggle="pill"
                                data-bs-target="#goalkeepers" type="button" role="tab">
                                Goalkeepers
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="fixtures-tab" data-bs-toggle="pill"
                                data-bs-target="#fixtures" type="button" role="tab">
                                Fixtures
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="results-tab" data-bs-toggle="pill"
                                data-bs-target="#results" type="button" role="tab">
                                Results
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Tab Content -->
    <div class="tab-content" id="standingsTabContent">

        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="container py-5">
                <!-- League Standings Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold text-primary mb-1">
                                    <i class="fas fa-trophy me-2"></i>League Standings
                                </h2>
                                <p class="text-muted mb-0">Last updated: {{ now()->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="fas fa-futbol me-1"></i>{{ $stats['tournament_matches'] }} Tournament Matches
                                </span>
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-trophy me-1"></i>{{ $stats['victories'] }} Victories
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- League Table Preview -->
                    <div class="col-lg-8">
                        <div class="card standings-card h-100">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-table me-2"></i>{{ $league }} - {{ $season }}
                                    </h5>
                                    <a href="{{ route('standings.league-table', ['season' => $season, 'league' => $league]) }}"
                                        class="btn btn-light btn-sm">
                                        View Full Table <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center" width="60">Pos</th>
                                                <th>Team</th>
                                                <th class="text-center" width="60">P</th>
                                                <th class="text-center" width="60">W</th>
                                                <th class="text-center" width="60">D</th>
                                                <th class="text-center" width="60">L</th>
                                                <th class="text-center" width="80">GF</th>
                                                <th class="text-center" width="80">GA</th>
                                                <th class="text-center" width="80">GD</th>
                                                <th class="text-center" width="80">Pts</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($standings->take(10) as $team)
                                            <tr class="{{ $team->is_vipers_team ? 'table-primary' : '' }}">
                                                <td class="text-center fw-bold">{{ $team->position }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($team->team_logo)
                                                        <img src="{{ asset('storage/' . $team->team_logo) }}"
                                                            alt="{{ $team->team_name }}" class="team-logo-small me-2">
                                                        @else
                                                        <div class="team-logo-placeholder me-2">
                                                            {{ substr($team->team_name, 0, 2) }}</div>
                                                        @endif
                                                        <span class="fw-semibold">{{ $team->team_name }}</span>
                                                        @if($team->is_vipers_team)
                                                        <span class="badge bg-warning text-dark ms-2">VIPERS</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $team->played }}</td>
                                                <td class="text-center">{{ $team->won }}</td>
                                                <td class="text-center">{{ $team->drawn }}</td>
                                                <td class="text-center">{{ $team->lost }}</td>
                                                <td class="text-center">{{ $team->goals_for }}</td>
                                                <td class="text-center">{{ $team->goals_against }}</td>
                                                <td
                                                    class="text-center {{ $team->goal_difference > 0 ? 'text-success' : ($team->goal_difference < 0 ? 'text-danger' : '') }}">
                                                    {{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}
                                                </td>
                                                <td class="text-center fw-bold">{{ $team->points }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-5">
                                                    <div class="empty-standings">
                                                        <i class="fas fa-trophy fa-4x text-warning mb-4"></i>
                                                        <h4 class="text-primary fw-bold mb-3">Standings Coming Soon</h4>
                                                        <p class="text-muted mb-4">League standings will be displayed
                                                            here once tournament matches begin. Stay tuned for live
                                                            updates and rankings.</p>
                                                        <div class="d-flex justify-content-center gap-4">
                                                            <div class="stat-box">
                                                                <div class="stat-number">{{ $stats['tournament_matches'] }}</div>
                                                                <div class="stat-label">Tournament Matches</div>
                                                            </div>
                                                            <div class="stat-box">
                                                                <div class="stat-number">{{ $stats['victories'] }}</div>
                                                                <div class="stat-label">Victories</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Sidebar -->
                    <div class="col-lg-4">
                        <div class="row g-4">
                            <!-- Top Scorer -->
                            <div class="col-12">
                                <div class="card standings-card">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-star me-2"></i>Top Scorer
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @if($topScorers->first())
                                        @php $topScorer = $topScorers->first(); @endphp
                                        @if($topScorer->player_image)
                                        <img src="{{ asset('storage/' . $topScorer->player_image) }}"
                                            alt="{{ $topScorer->player_name }}" class="player-avatar-lg mb-3">
                                        @else
                                        <div class="player-avatar-placeholder mb-3">
                                            {{ substr($topScorer->player_name, 0, 2) }}</div>
                                        @endif
                                        <h5 class="mb-1">{{ $topScorer->player_name }}</h5>
                                        <p class="text-muted mb-2">{{ $topScorer->team_name }}</p>
                                        <div class="stat-highlight">
                                            <span class="stat-number">{{ $topScorer->goals }}</span>
                                            <span class="stat-label">Goals</span>
                                        </div>
                                        <a href="{{ route('standings.top-scorers', ['season' => $season, 'league' => $league]) }}"
                                            class="btn btn-outline-warning btn-sm mt-3">
                                            View All Scorers
                                        </a>
                                        @else
                                        <div class="text-muted py-3">
                                            <i class="fas fa-user fa-2x mb-2"></i>
                                            <p>No scorer data available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Clean Sheet Leader -->
                            <div class="col-12">
                                <div class="card standings-card">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-shield-alt me-2"></i>Clean Sheet Leader
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @if($cleanSheets->first())
                                        @php $topKeeper = $cleanSheets->first(); @endphp
                                        @if($topKeeper->goalkeeper_image)
                                        <img src="{{ asset('storage/' . $topKeeper->goalkeeper_image) }}"
                                            alt="{{ $topKeeper->goalkeeper_name }}" class="player-avatar-lg mb-3">
                                        @else
                                        <div class="player-avatar-placeholder mb-3">
                                            {{ substr($topKeeper->goalkeeper_name, 0, 2) }}</div>
                                        @endif
                                        <h5 class="mb-1">{{ $topKeeper->goalkeeper_name }}</h5>
                                        <p class="text-muted mb-2">{{ $topKeeper->team_name }}</p>
                                        <div class="stat-highlight">
                                            <span class="stat-number">{{ $topKeeper->clean_sheets }}</span>
                                            <span class="stat-label">Clean Sheets</span>
                                        </div>
                                        <a href="{{ route('standings.clean-sheets', ['season' => $season, 'league' => $league]) }}"
                                            class="btn btn-outline-success btn-sm mt-3">
                                            View All Keepers
                                        </a>
                                        @else
                                        <div class="text-muted py-3">
                                            <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                            <p>No clean sheet data available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- League Table Tab -->
        <div class="tab-pane fade" id="league" role="tabpanel">
            <div class="container py-5">
                @include('standings.partials.league-table')
            </div>
        </div>

        <!-- Top Scorers Tab -->
        <div class="tab-pane fade" id="scorers" role="tabpanel">
            <div class="container py-5">
                @include('standings.partials.top-scorers')
            </div>
        </div>

        <!-- Clean Sheets Tab -->
        <div class="tab-pane fade" id="cleansheets" role="tabpanel">
            <div class="container py-5">
                @include('standings.partials.clean-sheets')
            </div>
        </div>

        <!-- Goalkeepers Tab -->
        <div class="tab-pane fade" id="goalkeepers" role="tabpanel">
            <div class="container py-5">
                @include('standings.partials.goalkeepers')
            </div>
        </div>

        <!-- Fixtures Tab -->
        <div class="tab-pane fade" id="fixtures" role="tabpanel">
            <div class="container py-5">
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
        </div>

        <!-- Results Tab -->
        <div class="tab-pane fade" id="results" role="tabpanel">
            <div class="container py-5">
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
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* Hero Section */
.standings-hero {
    min-height: 60vh;
    position: relative;
    display: flex;
    align-items: center;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);
}

/* Filters Section */
.filters-section {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Navigation Tabs */
.standings-navigation {
    border-bottom: 2px solid #e9ecef;
    position: relative;
    transition: all 0.3s ease;
}

.standings-navigation.sticky {
    position: fixed;
    left: 0;
    right: 0;
    z-index: 100;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-top: 1px solid #e9ecef;
}

.standings-navigation-placeholder {
    display: none;
    height: calc(4rem + 2px); /* py-4 = 2rem top + 2rem bottom + border */
}

.standings-tabs .nav-link {
    border: none;
    border-radius: 0;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
    position: relative;
    font-size: 0.9rem;
}

.standings-tabs .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: #007bff;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.standings-tabs .nav-link:hover::after,
.standings-tabs .nav-link.active::after {
    width: 80%;
}

.standings-tabs .nav-link:hover,
.standings-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
}

/* Cards */
.standings-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.standings-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.standings-card .card-header {
    border: none;
    padding: 1.5rem;
    font-weight: 600;
}

/* Team Logos */
.team-logo-small {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.team-logo-placeholder {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Player Avatars */
.player-avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.player-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Statistics Highlight */
.stat-highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    border-radius: 10px;
    margin: 1rem 0;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Table Styles */
.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    font-size: 0.9rem;
}

/* Empty Standings State */
.empty-standings {
    max-width: 500px;
    margin: 0 auto;
}

.empty-standings .stat-box {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 12px;
    border: 2px solid #e9ecef;
    min-width: 120px;
}

.empty-standings .stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-standings .stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* League Standings Header */
.league-standings-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.league-standings-header .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
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

.btn-live-stream,
.btn-details,
.btn-highlights {
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

.btn-highlights {
    background: linear-gradient(135deg, #ea580c, #c2410c);
    color: white;
}

.btn-highlights:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .standings-tabs .nav-link {
        padding: 0.25rem 0.375rem;
        font-size: 0.85rem;
    }

    .filters-section {
        margin: 0 1rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .player-avatar-lg,
    .player-avatar-placeholder {
        width: 60px;
        height: 60px;
    }

    .empty-standings .stat-box {
        padding: 1rem;
        min-width: 100px;
    }

    .empty-standings .stat-number {
        font-size: 2rem;
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

    .result-teams {
        flex-direction: column;
        gap: 1rem;
    }

    .result-score {
        padding: 1rem 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab change handler to update URL
    const tabEls = document.querySelectorAll('#standingsTab .nav-link');
    tabEls.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const targetId = event.target.getAttribute('data-bs-target');
            const tabName = targetId.substring(1); // Remove #

            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        });
    });

    // Check for tab parameter in URL on page load
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab) {
        const tabElement = document.querySelector(`#${activeTab}-tab`);
        if (tabElement) {
            const tab = new bootstrap.Tab(tabElement);
            tab.show();
        }
    }

    // Add animation to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        setTimeout(() => {
            row.style.transition = 'all 0.4s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // Sticky navbar functionality
    const standingsNav = document.querySelector('.standings-navigation');
    const placeholder = document.querySelector('.standings-navigation-placeholder');
    const heroSection = document.querySelector('.standings-hero');
    const mainNavbar = document.querySelector('.main-navbar');
    const topBar = document.querySelector('.top-bar');
    let lastScrollY = 0;
    let navbarHeight = 0;

    function calculateNavbarHeight() {
        let totalHeight = 0;
        if (topBar) totalHeight += topBar.offsetHeight;
        if (mainNavbar) totalHeight += mainNavbar.offsetHeight;
        navbarHeight = totalHeight;
        return totalHeight;
    }

    function updateStickyPosition() {
        if (standingsNav && standingsNav.classList.contains('sticky')) {
            standingsNav.style.top = navbarHeight + 'px';
        }
    }

    function updateStickyNavbar() {
        if (!standingsNav || !heroSection) return;

        const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
        const currentScrollY = window.scrollY;

        if (currentScrollY > heroBottom) {
            standingsNav.classList.add('sticky');
            updateStickyPosition();
            if (placeholder) placeholder.style.display = 'block';
        } else {
            standingsNav.classList.remove('sticky');
            standingsNav.style.top = '';
            if (placeholder) placeholder.style.display = 'none';
        }

        lastScrollY = currentScrollY;
    }

    // Listen for navbar transitions
    if (mainNavbar) {
        mainNavbar.addEventListener('transitionend', function() {
            calculateNavbarHeight();
            updateStickyPosition();
        });
    }

    // Initial setup
    calculateNavbarHeight();
    updateStickyNavbar();

    // Update on scroll
    window.addEventListener('scroll', updateStickyNavbar, { passive: true });

    // Update on window resize
    window.addEventListener('resize', function() {
        calculateNavbarHeight();
        updateStickyPosition();
    });

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

    // Standings Filter Functionality
    const seasonFilter = document.getElementById('seasonFilter');
    const leagueFilter = document.getElementById('leagueFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetFilters = document.getElementById('resetFilters');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    function filterStandings() {
        const selectedSeason = seasonFilter.value;
        const selectedLeague = leagueFilter.value;

        // For now, just log - in a real implementation, this would filter the standings data
        console.log('Filtering standings:', { season: selectedSeason, league: selectedLeague });

        // If different from current, redirect with new parameters
        const currentUrl = new URL(window.location);
        const currentSeason = currentUrl.searchParams.get('season') || '2024/25';
        const currentLeague = currentUrl.searchParams.get('league') || 'Premier League';

        if (selectedSeason !== currentSeason || selectedLeague !== currentLeague) {
            currentUrl.searchParams.set('season', selectedSeason || currentSeason);
            currentUrl.searchParams.set('league', selectedLeague || currentLeague);
            window.location.href = currentUrl.toString();
        }
    }

    function sortStandings() {
        const sortValue = sortFilter.value;
        const tableBody = document.querySelector('.table tbody');
        if (!tableBody) return;

        const rows = Array.from(tableBody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            switch (sortValue) {
                case 'position':
                    const posA = parseInt(a.querySelector('td:first-child').textContent);
                    const posB = parseInt(b.querySelector('td:first-child').textContent);
                    return posA - posB;
                case 'points-desc':
                    const ptsA = parseInt(a.querySelector('td:last-child').textContent);
                    const ptsB = parseInt(b.querySelector('td:last-child').textContent);
                    return ptsB - ptsA;
                case 'points-asc':
                    const ptsAscA = parseInt(a.querySelector('td:last-child').textContent);
                    const ptsAscB = parseInt(b.querySelector('td:last-child').textContent);
                    return ptsAscA - ptsAscB;
                case 'name-asc':
                    const nameA = a.querySelector('td:nth-child(2) .fw-semibold').textContent.toLowerCase();
                    const nameB = b.querySelector('td:nth-child(2) .fw-semibold').textContent.toLowerCase();
                    return nameA.localeCompare(nameB);
                case 'name-desc':
                    const nameDescA = a.querySelector('td:nth-child(2) .fw-semibold').textContent.toLowerCase();
                    const nameDescB = b.querySelector('td:nth-child(2) .fw-semibold').textContent.toLowerCase();
                    return nameDescB.localeCompare(nameDescA);
                default:
                    return 0;
            }
        });

        // Re-append sorted rows
        rows.forEach(row => tableBody.appendChild(row));
    }

    function resetStandingsFilters() {
        seasonFilter.value = '';
        leagueFilter.value = '';
        sortFilter.value = 'position';
        sortStandings();
    }

    // Event listeners for standings filters
    if (seasonFilter) seasonFilter.addEventListener('change', filterStandings);
    if (leagueFilter) leagueFilter.addEventListener('change', filterStandings);
    if (sortFilter) sortFilter.addEventListener('change', sortStandings);
    if (resetFilters) resetFilters.addEventListener('click', resetStandingsFilters);

    // View toggle (for future implementation)
    if (gridView) gridView.addEventListener('click', function() {
        // Implement grid view logic
        console.log('Grid view clicked');
    });

    if (listView) listView.addEventListener('click', function() {
        // Implement list view logic
        console.log('List view clicked');
    });

    // Initialize
    sortStandings();

    // Filter Bar Positioning (similar to players page)
    const filterBar = document.getElementById('filterBar');
    let filterBarTicking = false;

    function updateFilterBarPosition() {
        const currentScrollY = window.scrollY;
        const navbarHeight = mainNavbar ? mainNavbar.offsetHeight : 0;
        const topBarHeight = topBar ? topBar.offsetHeight : 0;
        let topOffset = navbarHeight + topBarHeight;

        // If category bar exists and visible
        const categoryBar = document.querySelector('.category-bar');
        if (categoryBar && currentScrollY < categoryBar.offsetHeight) {
            topOffset += categoryBar.offsetHeight - currentScrollY;
        }

        // Apply position
        filterBar.style.top = `${topOffset}px`;

        filterBarTicking = false;
    }

    // Smooth scroll handling for filter bar
    window.addEventListener('scroll', () => {
        if (!filterBarTicking) {
            window.requestAnimationFrame(updateFilterBarPosition);
            filterBarTicking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', () => {
        updateFilterBarPosition();
    });

    // Initialize filter bar position
    updateFilterBarPosition();
});
</script>
@endpush
