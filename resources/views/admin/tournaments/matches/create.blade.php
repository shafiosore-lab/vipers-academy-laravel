@extends('layouts.admin')

@section('title', 'Create Match - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Create {{ ucfirst(str_replace('_', ' ', $matchType)) }} Match</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form action="{{ route('admin.tournaments.matches.store', $tournament->id) }}" method="POST" id="matchForm">
    @csrf

    <input type="hidden" name="match_type" value="{{ $matchType }}">

    <div class="row">
        <!-- Main Configuration -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Team Selection</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Home Team *</label>
                            <select name="home_team_id" class="form-select @error('home_team_id') is-invalid @enderror" required>
                                <option value="">Select Home Team</option>
                                @foreach($approvedTeams as $team)
                                    <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->team_name }} @if($team->pool)(Pool: {{ $team->pool->name }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('home_team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Away Team *</label>
                            <select name="away_team_id" class="form-select @error('away_team_id') is-invalid @enderror" required>
                                <option value="">Select Away Team</option>
                                @foreach($approvedTeams as $team)
                                    <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->team_name }} @if($team->pool)(Pool: {{ $team->pool->name }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('away_team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($pools->count() > 0)
                    <div class="mt-3">
                        <label class="form-label">Pool / Group (Optional)</label>
                        <select name="pool_id" class="form-select">
                            <option value="">No Pool</option>
                            @foreach($pools as $pool)
                                <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Scheduling -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>Schedule</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="kickoff_time" class="form-control" value="{{ old('kickoff_time') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Timezone</label>
                            <select name="timezone" class="form-select">
                                @foreach(\App\Models\TournamentMatch::getTimezones() as $tz => $label)
                                    <option value="{{ $tz }}" {{ (old('timezone') ?? config('app.timezone')) == $tz ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Match Day</label>
                            <input type="number" name="match_day" class="form-control" value="{{ old('match_day', 1) }}" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Round</label>
                            <input type="number" name="round" class="form-control" value="{{ old('round', 1) }}" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Scheduled Day</label>
                            <input type="number" name="scheduled_day" class="form-control" value="{{ old('scheduled_day') }}" min="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Venue -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Venue</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Select Venue</label>
                            <select name="venue_id" class="form-select">
                                <option value="">Tournament Default</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }} @if($venue->city)({{ $venue->city }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Or Enter Custom Venue</label>
                            <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="Enter venue name">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Format -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Match Format</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="match_format[duration]" class="form-control"
                                   value="{{ old('match_format.duration', \App\Models\TournamentMatch::getDefaultFormat($matchType)['duration']) }}"
                                   min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Number of Halves</label>
                            <input type="number" name="match_format[halves]" class="form-control"
                                   value="{{ old('match_format.halves', \App\Models\TournamentMatch::getDefaultFormat($matchType)['halves']) }}"
                                   min="1" max="4">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Half Duration (minutes)</label>
                            <input type="number" name="match_format[half_duration]" class="form-control"
                                   value="{{ old('match_format.half_duration', \App\Models\TournamentMatch::getDefaultFormat($matchType)['half_duration']) }}"
                                   min="1">
                        </div>
                    </div>

                    @if(in_array($matchType, ['knockout']))
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="match_format[has_overtime]" class="form-check-input"
                                       id="hasOvertime" {{ old('match_format.has_overtime', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hasOvertime">Allow Overtime</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="match_format[has_extra_time]" class="form-check-input"
                                       id="hasExtraTime" {{ old('match_format.has_extra_time', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hasExtraTime">Extra Time</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="match_format[has_penalties]" class="form-check-input"
                                       id="hasPenalties" {{ old('match_format.has_penalties', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hasPenalties">Penalties</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Overtime Duration (minutes)</label>
                            <input type="number" name="match_format[overtime_duration]" class="form-control"
                                   value="{{ old('match_format.overtime_duration', 30) }}"
                                   min="0">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Scoring Rules -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i>Scoring Rules</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Win Points</label>
                            <input type="number" name="scoring_rules[win_points]" class="form-control"
                                   value="{{ old('scoring_rules.win_points', 3) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Draw Points</label>
                            <input type="number" name="scoring_rules[draw_points]" class="form-control"
                                   value="{{ old('scoring_rules.draw_points', 1) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Loss Points</label>
                            <input type="number" name="scoring_rules[loss_points]" class="form-control"
                                   value="{{ old('scoring_rules.loss_points', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Tiebreaker</label>
                            <select name="scoring_rules[tiebreaker]" class="form-select">
                                <option value="goal_difference" {{ old('scoring_rules.tiebreaker') == 'goal_difference' ? 'selected' : '' }}>
                                    Goal Difference
                                </option>
                                <option value="head_to_head" {{ old('scoring_rules.tiebreaker') == 'head_to_head' ? 'selected' : '' }}>
                                    Head to Head
                                </option>
                                <option value="goals_scored" {{ old('scoring_rules.tiebreaker') == 'goals_scored' ? 'selected' : '' }}>
                                    Goals Scored
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                </div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional match notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Match Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Match Type:</strong><br>
                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $matchType)) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Format:</strong><br>
                        <span id="formatDisplay">
                            {{ \App\Models\TournamentMatch::getDefaultFormat($matchType)['duration'] }} min
                            ({{ \App\Models\TournamentMatch::getDefaultFormat($matchType)['halves'] }} x {{ \App\Models\TournamentMatch::getDefaultFormat($matchType)['half_duration'] }} min)
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Points:</strong><br>
                        Win: {{ \App\Models\TournamentMatch::getDefaultScoringRules()['win_points'] }} |
                        Draw: {{ \App\Models\TournamentMatch::getDefaultScoringRules()['draw_points'] }} |
                        Loss: {{ \App\Models\TournamentMatch::getDefaultScoringRules()['loss_points'] }}
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-1"></i> Create Match
                    </button>
                    <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-outline-secondary w-100 mt-2">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('matchForm');
    const homeSelect = form.querySelector('select[name="home_team_id"]');
    const awaySelect = form.querySelector('select[name="away_team_id"]');

    // Prevent selecting same team
    homeSelect.addEventListener('change', function() {
        const homeId = this.value;
        Array.from(awaySelect.options).forEach(option => {
            if (option.value === homeId && homeId !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });

    awaySelect.addEventListener('change', function() {
        const awayId = this.value;
        Array.from(homeSelect.options).forEach(option => {
            if (option.value === awayId && awayId !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
});
</script>
@endsection
