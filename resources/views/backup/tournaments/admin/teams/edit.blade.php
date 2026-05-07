@extends('layouts.admin')

@section('title', 'Edit Team - ' . $team->team_name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.teams.show', [$tournament->id, $team->id]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Edit Team</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.teams.update', [$tournament->id, $team->id]) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Team Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Team Name</label>
                        <input type="text" name="team_name" class="form-control form-control-sm" value="{{ $team->team_name }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="approval_status" class="form-select form-select-sm">
                            <option value="pending" {{ $team->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $team->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $team->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Contact Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="team_contact_name" class="form-control form-control-sm" value="{{ $team->team_contact_name }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="team_contact_email" class="form-control form-control-sm" value="{{ $team->team_contact_email }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="team_contact_phone" class="form-control form-control-sm" value="{{ $team->team_contact_phone }}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.teams.show', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Update Team</button>
    </div>
</form>
@endsection
