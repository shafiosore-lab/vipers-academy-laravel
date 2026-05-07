@extends('layouts.admin')

@section('title', 'Edit Tournament - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 text-gray-800">Edit Tournament</h1>
        <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('super-admin.tournaments.update', $tournament->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-2 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary small">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Tournament Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name', $tournament->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Organization <span class="text-danger">*</span></label>
                            <select name="organization_id" class="form-select form-select-sm @error('organization_id') is-invalid @enderror" required>
                                <option value="">Select Organization</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id', $tournament->organization_id) == $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organization_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Season</label>
                            <input type="text" name="season" class="form-control form-control-sm"
                                   value="{{ old('season', $tournament->season) }}" placeholder="e.g., 2024/2025">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Description</label>
                            <textarea name="description" rows="3" class="form-control form-control-sm">{{ old('description', $tournament->description) }}</textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $tournament->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="open" {{ old('status', $tournament->status) == 'open' ? 'selected' : '' }}>Open for Registration</option>
                                <option value="closed" {{ old('status', $tournament->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="ongoing" {{ old('status', $tournament->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $tournament->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $tournament->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1"
                                   id="isPublic" {{ old('is_public', $tournament->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="isPublic">
                                Public (visible to all teams)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Settings -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-2 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary small">Competition Format</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Competition Format</label>
                            <select name="competition_format" id="competitionFormat" class="form-select form-select-sm @error('competition_format') is-invalid @enderror" onchange="updateEstimatedMatches()">
                                <option value="">Select Format</option>
                                <option value="league" {{ old('competition_format', $tournament->competition_format) == 'league' ? 'selected' : '' }}>League</option>
                                <option value="round_robin" {{ old('competition_format', $tournament->competition_format) == 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                                <option value="league_cup" {{ old('competition_format', $tournament->competition_format) == 'league_cup' ? 'selected' : '' }}>League + Cup</option>
                                <option value="knockout" {{ old('competition_format', $tournament->competition_format) == 'knockout' ? 'selected' : '' }}>Knockout</option>
                                <option value="knockout_plus" {{ old('competition_format', $tournament->competition_format) == 'knockout_plus' ? 'selected' : '' }}>Knockout + 3rd Place</option>
                                <option value="groups_knockout" {{ old('competition_format', $tournament->competition_format) == 'groups_knockout' ? 'selected' : '' }}>Groups + Knockout</option>
                                <option value="double_elimination" {{ old('competition_format', $tournament->competition_format) == 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                            </select>
                            @error('competition_format')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="formatDescription" class="small text-muted mb-2" style="display: {{ $tournament->competition_format ? 'block' : 'none' }};">
                            @if($tournament->competition_format && isset(\App\Models\Tournament::COMPETITION_FORMATS[$tournament->competition_format]))
                                <strong>{{ \App\Models\Tournament::COMPETITION_FORMATS[$tournament->competition_format]['name'] }}:</strong>
                                {{ \App\Models\Tournament::COMPETITION_FORMATS[$tournament->competition_format]['description'] }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header py-2 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary small">Registration Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Registration Deadline</label>
                            <input type="datetime-local" name="registration_deadline" class="form-control form-control-sm"
                                   value="{{ old('registration_deadline', $tournament->registration_deadline?->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Max Teams</label>
                                <input type="number" name="max_teams" class="form-control form-control-sm"
                                       value="{{ old('max_teams', $tournament->max_teams) }}" min="2" placeholder="e.g., 16">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Squad Limit</label>
                                <input type="number" name="squad_limit" class="form-control form-control-sm"
                                       value="{{ old('squad_limit', $tournament->squad_limit) }}" min="5" max="50">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Minimum Players per Squad</label>
                            <input type="number" name="min_players" class="form-control form-control-sm"
                                   value="{{ old('min_players', $tournament->min_players) }}" min="5" max="20">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Estimated Matches</label>
                            <input type="number" name="estimated_matches" id="estimatedMatches" class="form-control form-control-sm"
                                   value="{{ old('estimated_matches', $tournament->estimated_matches ?? $tournament->calculateEstimatedMatches()) }}" min="0" placeholder="Auto-calculated based on format">
                        </div>
                    </div>
                </div>

                <!-- Tournament Dates -->
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header py-2 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary small">Tournament Dates & Venue</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Start Date</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ old('start_date', $tournament->start_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">End Date</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ old('end_date', $tournament->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="mb-2 mt-2">
                            <label class="form-label small fw-bold">Venue</label>
                            <input type="text" name="venue" class="form-control form-control-sm"
                                   value="{{ old('venue', $tournament->venue) }}" placeholder="Tournament venue or location">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold">Rules & Regulations</label>
                            <textarea name="rules" rows="3" class="form-control form-control-sm">{{ old('rules', $tournament->rules) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Tournament
            </button>
        </div>
    </form>
</div>
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
    const maxTeams = parseInt(document.getElementById('maxTeams')?.value) || {{ $tournament->max_teams ?? 0 }};
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateEstimatedMatches();
});
</script>
@endsection
