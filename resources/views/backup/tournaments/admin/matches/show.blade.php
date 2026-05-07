@extends('layouts.admin')

@section('title', 'Match Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Match Details</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'live' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($match->status) }}
                </span>
                @if($match->isScheduled())
                    <form action="{{ route('admin.tournaments.matches.start', [$tournament->id, $match->id]) }}" method="POST" class="d-inline">
                        @csrf <button type="submit" class="btn btn-success btn-sm">Start</button>
                    </form>
                @endif
            </div>
            <div class="card-body text-center py-4">
                <div class="row align-items-center">
                    <div class="col-4">
                        <div class="h4 mb-1">{{ $match->homeTeam->team_name ?? 'TBD' }}</div>
                    </div>
                    <div class="col-4">
                        <div class="h2 mb-0">{{ $match->isCompleted() || $match->isLive() ? $match->home_score . ' - ' . $match->away_score : 'vs' }}</div>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-1">{{ $match->awayTeam->team_name ?? 'TBD' }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-3"><small class="text-muted">Date</small><div>{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('M d, H:i') : 'TBD' }}</div></div>
                    <div class="col-3"><small class="text-muted">Venue</small><div>{{ $match->venue ?? '-' }}</div></div>
                    <div class="col-3"><small class="text-muted">Day</small><div>{{ $match->match_day ?? '-' }}</div></div>
                    <div class="col-3"><small class="text-muted">Round</small><div>{{ $match->round ?? '-' }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($match->isLive() && !$match->isCompleted())
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Record Result</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tournaments.matches.record-result', [$tournament->id, $match->id]) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="number" name="home_score" class="form-control form-control-sm" min="0" value="0" required>
                            </div>
                            <div class="col-4 text-center py-1">-</div>
                            <div class="col-4">
                                <input type="number" name="away_score" class="form-control form-control-sm" min="0" value="0" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Record</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
