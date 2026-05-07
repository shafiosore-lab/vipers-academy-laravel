@extends('layouts.admin')

@section('title', 'Schedule Configuration - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-gear"></i> Schedule Configuration
            </h1>
            <p class="text-muted mb-0">{{ $tournament->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Schedule
            </a>
        </div>
    </div>

    <form action="{{ route('admin.tournaments.schedule.config.save', $tournament->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Match Duration Settings -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock"></i> Match Duration Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="match_duration" class="form-label">Match Duration (minutes)</label>
                                    <select class="form-select" id="match_duration" name="match_duration">
                                        <option value="45" {{ ($config['match_duration'] ?? 90) == 45 ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ ($config['match_duration'] ?? 90) == 60 ? 'selected' : '' }}>60 minutes</option>
                                        <option value="70" {{ ($config['match_duration'] ?? 90) == 70 ? 'selected' : '' }}>70 minutes</option>
                                        <option value="80" {{ ($config['match_duration'] ?? 90) == 80 ? 'selected' : '' }}>80 minutes</option>
                                        <option value="90" {{ ($config['match_duration'] ?? 90) == 90 ? 'selected' : '' }}>90 minutes</option>
                                    </select>
                                    <div class="form-text">Standard match duration for this tournament</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="halftime_duration" class="form-label">Halftime Duration (minutes)</label>
                                    <select class="form-select" id="halftime_duration" name="halftime_duration">
                                        <option value="5" {{ ($config['halftime_duration'] ?? 15) == 5 ? 'selected' : '' }}>5 minutes</option>
                                        <option value="10" {{ ($config['halftime_duration'] ?? 15) == 10 ? 'selected' : '' }}>10 minutes</option>
                                        <option value="15" {{ ($config['halftime_duration'] ?? 15) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="20" {{ ($config['halftime_duration'] ?? 15) == 20 ? 'selected' : '' }}>20 minutes</option>
                                    </select>
                                    <div class="form-text">Duration of halftime break</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rest_between_matches" class="form-label">Rest Between Matches (minutes)</label>
                                    <select class="form-select" id="rest_between_matches" name="rest_between_matches">
                                        <option value="15" {{ ($config['rest_between_matches'] ?? 30) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ ($config['rest_between_matches'] ?? 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ ($config['rest_between_matches'] ?? 30) == 45 ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ ($config['rest_between_matches'] ?? 30) == 60 ? 'selected' : '' }}>60 minutes</option>
                                    </select>
                                    <div class="form-text">Minimum time between matches at the same venue</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_rest_hours" class="form-label">Minimum Team Rest (hours)</label>
                                    <select class="form-select" id="min_rest_hours" name="min_rest_hours">
                                        <option value="12" {{ ($config['min_rest_hours'] ?? 24) == 12 ? 'selected' : '' }}>12 hours</option>
                                        <option value="24" {{ ($config['min_rest_hours'] ?? 24) == 24 ? 'selected' : '' }}>24 hours</option>
                                        <option value="36" {{ ($config['min_rest_hours'] ?? 24) == 36 ? 'selected' : '' }}>36 hours</option>
                                        <option value="48" {{ ($config['min_rest_hours'] ?? 24) == 48 ? 'selected' : '' }}>48 hours</option>
                                    </select>
                                    <div class="form-text">Minimum rest time for teams between matches</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Venue & Facility Settings -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Venue & Facility Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_games_per_venue_per_day" class="form-label">Max Games Per Venue/Day</label>
                                    <select class="form-select" id="max_games_per_venue_per_day" name="max_games_per_venue_per_day">
                                        <option value="2" {{ ($config['max_games_per_venue_per_day'] ?? 4) == 2 ? 'selected' : '' }}>2 games</option>
                                        <option value="3" {{ ($config['max_games_per_venue_per_day'] ?? 4) == 3 ? 'selected' : '' }}>3 games</option>
                                        <option value="4" {{ ($config['max_games_per_venue_per_day'] ?? 4) == 4 ? 'selected' : '' }}>4 games</option>
                                        <option value="5" {{ ($config['max_games_per_venue_per_day'] ?? 4) == 5 ? 'selected' : '' }}>5 games</option>
                                        <option value="6" {{ ($config['max_games_per_venue_per_day'] ?? 4) == 6 ? 'selected' : '' }}>6 games</option>
                                    </select>
                                    <div class="form-text">Maximum number of matches per venue per day</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="concurrent_matches" class="form-label">Allow Concurrent Matches</label>
                                    <select class="form-select" id="concurrent_matches" name="concurrent_matches">
                                        <option value="true" {{ ($config['concurrent_matches'] ?? true) === true || $config['concurrent_matches'] === 'true' ? 'selected' : '' }}>Yes</option>
                                        <option value="false" {{ ($config['concurrent_matches'] ?? true) === false || $config['concurrent_matches'] === 'false' ? 'selected' : '' }}>No</option>
                                    </select>
                                    <div class="form-text">Allow multiple matches to be played simultaneously</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduling Algorithm Settings -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Scheduling Algorithm</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="algorithm" class="form-label">Scheduling Algorithm</label>
                                    <select class="form-select" id="algorithm" name="algorithm">
                                        <option value="balanced" {{ ($config['algorithm'] ?? 'balanced') == 'balanced' ? 'selected' : '' }}>Balanced</option>
                                        <option value="home_away" {{ ($config['algorithm'] ?? 'balanced') == 'home_away' ? 'selected' : '' }}>Home/Away Alternation</option>
                                        <option value="min_travel" {{ ($config['algorithm'] ?? 'balanced') == 'min_travel' ? 'selected' : '' }}>Minimize Travel</option>
                                        <option value="day_spread" {{ ($config['algorithm'] ?? 'balanced') == 'day_spread' ? 'selected' : '' }}>Spread Across Days</option>
                                        <option value="venue_priority" {{ ($config['algorithm'] ?? 'balanced') == 'venue_priority' ? 'selected' : '' }}>Venue Priority</option>
                                    </select>
                                    <div class="form-text">Algorithm used for automatic match scheduling</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prefer_same_day" class="form-label">Prefer Same Day Matches</label>
                                    <select class="form-select" id="prefer_same_day" name="prefer_same_day">
                                        <option value="true" {{ ($config['prefer_same_day'] ?? false) === true || $config['prefer_same_day'] === 'true' ? 'selected' : '' }}>Yes</option>
                                        <option value="false" {{ ($config['prefer_same_day'] ?? false) === false || $config['prefer_same_day'] === 'false' ? 'selected' : '' }}>No</option>
                                    </select>
                                    <div class="form-text">Group team matches on the same day when possible</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="avoid_weekend_congestion" class="form-label">Avoid Weekend Congestion</label>
                                    <select class="form-select" id="avoid_weekend_congestion" name="avoid_weekend_congestion">
                                        <option value="true" {{ ($config['avoid_weekend_congestion'] ?? true) === true || $config['avoid_weekend_congestion'] === 'true' ? 'selected' : '' }}>Yes</option>
                                        <option value="false" {{ ($config['avoid_weekend_congestion'] ?? true) === false || $config['avoid_weekend_congestion'] === 'false' ? 'selected' : '' }}>No</option>
                                    </select>
                                    <div class="form-text">Distribute matches more evenly on weekends</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Preferences -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-clock-fill"></i> Time Preferences</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="match_start_time" class="form-label">Earliest Match Start Time</label>
                                    <input type="time" class="form-control" id="match_start_time" name="match_start_time"
                                           value="{{ $config['match_start_time'] ?? '09:00' }}">
                                    <div class="form-text">First match can start at this time</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="match_end_time" class="form-label">Latest Match Start Time</label>
                                    <input type="time" class="form-control" id="match_end_time" name="match_end_time"
                                           value="{{ $config['match_end_time'] ?? '18:00' }}">
                                    <div class="form-text">Last match should start by this time</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Preferred Match Days</label>
                                    <div class="d-flex gap-3">
                                        @php
                                            $preferredDays = $config['preferred_days'] ?? ['saturday', 'sunday'];
                                            if (is_string($preferredDays)) {
                                                $preferredDays = json_decode($preferredDays, true) ?? ['saturday', 'sunday'];
                                            }
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="monday"
                                                   id="day_monday" {{ in_array('monday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_monday">Monday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="tuesday"
                                                   id="day_tuesday" {{ in_array('tuesday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_tuesday">Tuesday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="wednesday"
                                                   id="day_wednesday" {{ in_array('wednesday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_wednesday">Wednesday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="thursday"
                                                   id="day_thursday" {{ in_array('thursday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_thursday">Thursday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="friday"
                                                   id="day_friday" {{ in_array('friday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_friday">Friday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="saturday"
                                                   id="day_saturday" {{ in_array('saturday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_saturday">Saturday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_days[]" value="sunday"
                                                   id="day_sunday" {{ in_array('sunday', $preferredDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_sunday">Sunday</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Configuration
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
