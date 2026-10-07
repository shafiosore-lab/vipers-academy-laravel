@extends('layouts.admin')

@section('title', 'Edit Match - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Edit Match</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.matches.update', [$tournament->id, $match->id]) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Teams</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Home Team</label>
                        <select name="home_team_id" class="form-select form-select-sm" required>
                            @foreach($approvedTeams as $team)
                                <option value="{{ $team->id }}" {{ $match->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Away Team</label>
                        <select name="away_team_id" class="form-select form-select-sm" required>
                            @foreach($approvedTeams as $team)
                                <option value="{{ $team->id }}" {{ $match->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Details</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Match Day</label>
                            <input type="number" name="match_day" class="form-control form-control-sm" value="{{ $match->match_day }}" min="1">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Round</label>
                            <input type="number" name="round" class="form-control form-control-sm" value="{{ $match->round }}" min="1">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Kickoff Time</label>
                        <input type="datetime-local" name="kickoff_time" class="form-control form-control-sm" value="{{ $match->kickoff_time ? \Carbon\Carbon::parse($match->kickoff_time)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control form-control-sm" value="{{ $match->venue }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.matches.show', [$tournament->id, $match->id]) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Update Match</button>
    </div>
</form>
@endsection
