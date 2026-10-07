@extends('layouts.admin')

@section('title', 'Add Player - ' . $team->team_name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Add Player</h4>
                <small class="text-muted">{{ $team->team_name }} - {{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.squads.store', [$tournament->id, $team->id]) }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Select Player</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Available Players</label>
                        <select name="player_id" class="form-select form-select-sm">
                            <option value="">-- Select a player --</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->full_name }} ({{ $player->id_number ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Player Details</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Position</label>
                            <select name="position" class="form-select form-select-sm">
                                <option value="">Select</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Forward">Forward</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Jersey Number</label>
                            <input type="number" name="jersey_number" class="form-control form-control-sm" min="1" max="99">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Add Player</button>
    </div>
</form>
@endsection
