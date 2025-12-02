@extends('layouts.academy')

@section('title', 'Match Center - Vipers Football Academy')
@section('meta_description', 'Stay updated with all Vipers Academy matches, results, and tournament information.')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold mb-3">Match Center</h1>
            <p class="lead text-muted">Live scores, match results, and tournament updates</p>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Matches</h5>
                        <h3 class="text-primary">{{ $stats['total_matches'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Wins</h5>
                        <h3 class="text-success">{{ $stats['wins'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Goals Scored</h5>
                        <h3 class="text-warning">{{ $stats['goals_scored'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($nextMatch) && $nextMatch)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Next Match</h5>
            </div>
            <div class="card-body text-center">
                <h4>{{ $nextMatch->opponent_name }}</h4>
                <p class="mb-2">{{ \Carbon\Carbon::parse($nextMatch->match_date)->format('D, M j, Y g:i A') }}</p>
                @if($nextMatch->venue)
                <small class="text-muted">{{ $nextMatch->venue }}</small>
                @endif
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Results</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($pastMatches) && count($pastMatches) > 0)
                            @foreach($pastMatches as $match)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span>{{ $match->opponent_name }}</span>
                                <strong>{{ $match->vipers_score }} - {{ $match->opponent_score }}</strong>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No recent results available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Upcoming Matches</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($matches) && count($matches) > 0)
                            @foreach($matches as $match)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span>{{ $match->opponent_name }}</span>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($match->match_date)->format('M j, g:i A') }}</small>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No upcoming matches scheduled.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
