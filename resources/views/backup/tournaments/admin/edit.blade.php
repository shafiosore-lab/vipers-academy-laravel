@extends('layouts.admin')

@section('title', 'Edit Tournament')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Edit Tournament</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.update', $tournament->id) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Basic Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Tournament Name *</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $tournament->name) }}" required placeholder="e.g., Annual Youth Championship 2026">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Organization *</label>
                        <select name="organization_id" class="form-select form-select-sm" required>
                            <option value="">Select Organization</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id', $tournament->organization_id) == $org->id ? 'selected' : '' }}>
                                    {{ $org->name }} ({{ ucfirst($org->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Season</label>
                        <input type="text" name="season" class="form-control form-control-sm" value="{{ old('season', $tournament->season) }}" placeholder="e.g., 2025-2026">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $tournament->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Settings</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 mb-2">
                            <label class="form-label">Max Teams</label>
                            <input type="number" name="max_teams" class="form-control form-control-sm" value="{{ old('max_teams', $tournament->max_teams) }}" min="2">
                        </div>
                        <div class="col-4 mb-2">
                            <label class="form-label">Squad Limit</label>
                            <input type="number" name="squad_limit" class="form-control form-control-sm" value="{{ old('squad_limit', $tournament->squad_limit) }}" min="5">
                        </div>
                        <div class="col-4 mb-2">
                            <label class="form-label">Min Players</label>
                            <input type="number" name="min_players" class="form-control form-control-sm" value="{{ old('min_players', $tournament->min_players) }}" min="5">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Registration Deadline</label>
                        <input type="datetime-local" name="registration_deadline" class="form-control form-control-sm" value="{{ old('registration_deadline', $tournament->registration_deadline ? \Carbon\Carbon::parse($tournament->registration_deadline)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control form-control-sm" value="{{ old('venue', $tournament->venue) }}" placeholder="Primary venue">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Schedule</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ old('start_date', $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ old('end_date', $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Rules & Status</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Tournament Rules</label>
                        <textarea name="rules" class="form-control form-control-sm" rows="2">{{ old('rules', $tournament->rules) }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" {{ old('status', $tournament->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="open" {{ old('status', $tournament->status) == 'open' ? 'selected' : '' }}>Open for Registration</option>
                            <option value="ongoing" {{ old('status', $tournament->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status', $tournament->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $tournament->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_public" class="form-check-input" value="1" {{ old('is_public', $tournament->is_public) ? 'checked' : '' }}>
                        <label class="form-check-label">Make tournament public</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Update Tournament</button>
    </div>
</form>
<script>
const competitionFormats = {
    'league': { name: 'League', description: 'All teams play each other once. Points for wins/draws.', calculate: (n) => n < 2 ? 0 : n * (n - 1) / 2 },
    'round_robin': { name: 'Round Robin', description: 'Each team plays twice (home and away).', calculate: (n) => n < 2 ? 0 : n * (n - 1) },
    'league_cup': { name: 'League + Cup', description: 'League stage + knockout final.', calculate: (n) => n < 2 ? 0 : (n * (n - 1) / 2) + (n - 1) },
    'knockout': { name: 'Knockout', description: 'Single elimination.', calculate: (n) => n < 2 ? 0 : n - 1 },
    'knockout_plus': { name: 'Knockout + 3rd', description: 'With third place playoff.', calculate: (n) => n < 2 ? 0 : n },
    'groups_knockout': { name: 'Groups + Knockout', description: 'Group stage then knockout.', calculate: (n) => Math.floor((n/4 * 3) + (Math.pow(2, Math.ceil(Math.log2(n))) - 1)) },
    'double_elimination': { name: 'Double Elimination', description: 'Two loss bracket.', calculate: (n) => n < 2 ? 0 : (2*n - 2) + (Math.pow(2, Math.ceil(Math.log2(n))) - 1) }
};

function updateEstimatedMatches() {
    const format = document.getElementById('competitionFormat')?.value;
    const maxTeams = parseInt(document.querySelector('input[name="max_teams"]')?.value) || {{ $tournament->max_teams ?? 0 }};
    const descDiv = document.getElementById('formatDescription');
    const matchInput = document.getElementById('estimatedMatches');

    if (format && competitionFormats[format]) {
        const info = competitionFormats[format];
        descDiv.style.display = 'block';
        descDiv.innerHTML = '<strong>' + info.name + ':</strong> ' + info.description;
        matchInput.value = maxTeams > 1 ? info.calculate(maxTeams) : '';
    } else {
        descDiv.style.display = 'none';
        matchInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateEstimatedMatches();
});
</script>
@endsection
