@extends('layouts.admin')

@section('title', 'Add Team - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Add Team</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.teams.store', $tournament->id) }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Select Existing Team</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Available Teams</label>
                        <select name="team_id" class="form-select form-select-sm">
                            <option value="">-- Select a team --</option>
                            @foreach($availableTeams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Or Create New Team</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Team Name</label>
                        <input type="text" name="team_name" class="form-control form-control-sm" placeholder="Enter team name">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Contact Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="team_contact_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="team_contact_email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="team_contact_phone" class="form-control form-control-sm" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Add Team</button>
    </div>
</form>
@endsection
