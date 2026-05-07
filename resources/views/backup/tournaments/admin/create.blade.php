@extends('layouts.admin')

@section('title', 'Create Tournament')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Create Tournament</h4>
                <small class="text-muted">Set up a new tournament</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Basic Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Tournament Name *</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required placeholder="e.g., Annual Youth Championship 2026">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Organization *</label>
                        <select name="organization_id" class="form-select form-select-sm" required>
                            <option value="">Select Organization</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name }} ({{ ucfirst($org->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Season</label>
                        <input type="text" name="season" class="form-control form-control-sm" value="{{ old('season') }}" placeholder="e.g., 2025-2026">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Settings</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Competition Format</label>
                        <select name="competition_format" id="competitionFormat" class="form-select form-select-sm" onchange="updateEstimatedMatches()">
                            <option value="">Select Format</option>
                            <option value="league" {{ old('competition_format') == 'league' ? 'selected' : '' }}>League - All teams play each other</option>
                            <option value="round_robin" {{ old('competition_format') == 'round_robin' ? 'selected' : '' }}>Round Robin - Home and away</option>
                            <option value="league_cup" {{ old('competition_format') == 'league_cup' ? 'selected' : '' }}>League + Cup</option>
                            <option value="knockout" {{ old('competition_format') == 'knockout' ? 'selected' : '' }}>Knockout - Single elimination</option>
                            <option value="knockout_plus" {{ old('competition_format') == 'knockout_plus' ? 'selected' : '' }}>Knockout with Third Place</option>
                            <option value="groups_knockout" {{ old('competition_format') == 'groups_knockout' ? 'selected' : '' }}>Groups + Knockout</option>
                            <option value="double_elimination" {{ old('competition_format') == 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                        </select>
                        <small class="text-muted">How teams compete against each other</small>
                    </div>
                    <div id="formatDescription" class="alert alert-info py-1 px-2 small mb-2" style="display: none;"></div>
                    <div class="row">
                        <div class="col-4 mb-2">
                            <label class="form-label">Max Teams</label>
                            <input type="number" name="max_teams" id="maxTeamsInput" class="form-control form-control-sm" value="{{ old('max_teams', 16) }}" min="2" oninput="updateEstimatedMatches()">
                        </div>
                        <div class="col-4 mb-2">
                            <label class="form-label">Squad Limit</label>
                            <input type="number" name="squad_limit" class="form-control form-control-sm" value="{{ old('squad_limit', 25) }}" min="5">
                        </div>
                        <div class="col-4 mb-2">
                            <label class="form-label">Min Players</label>
                            <input type="number" name="min_players" class="form-control form-control-sm" value="{{ old('min_players', 11) }}" min="5">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Est. Matches</label>
                        <input type="text" id="estimatedMatches" class="form-control form-control-sm" readonly placeholder="Auto-calculated">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Registration Deadline</label>
                        <input type="datetime-local" name="registration_deadline" class="form-control form-control-sm" value="{{ old('registration_deadline') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control form-control-sm" value="{{ old('venue') }}" placeholder="Primary venue">
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
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ old('start_date') }}">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ old('end_date') }}">
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
                        <textarea name="rules" class="form-control form-control-sm" rows="2">{{ old('rules') }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Initial Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open for Registration</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_public" class="form-check-input" value="1" {{ old('is_public', true) ? 'checked' : '' }}>
                        <label class="form-check-label">Make tournament public</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Create Tournament</button>
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
    const maxTeams = parseInt(document.getElementById('maxTeamsInput')?.value) || 0;
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
</script>
@endsection
