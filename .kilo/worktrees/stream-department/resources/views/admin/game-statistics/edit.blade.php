@extends('layouts.admin')

@section('title', 'Edit Game Statistics - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Game Statistics</h4>
                            <small class="opacity-75">Modify player performance data</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.game-statistics.update', $statistic) }}" method="POST" id="editStatisticsForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column - Basic Game Info -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Game Information</h5>

                                <div class="mb-3">
                                    <label for="player_id" class="form-label">Player *</label>
                                    <select class="form-control @error('player_id') is-invalid @enderror"
                                            id="player_id" name="player_id" required>
                                        <option value="">Select Player</option>
                                        @foreach($players as $player)
                                            <option value="{{ $player->id }}"
                                                    data-position="{{ $player->position }}"
                                                    {{ old('player_id', $statistic->player_id) == $player->id ? 'selected' : '' }}>
                                                {{ $player->name }} - {{ $player->position }}
                                                @if($player->first_name && $player->last_name)
                                                    ({{ $player->first_name }} {{ $player->last_name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('player_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the player whose statistics you want to modify</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="game_date" class="form-label">Game Date *</label>
                                            <input type="date" class="form-control @error('game_date') is-invalid @enderror"
                                                   id="game_date" name="game_date" value="{{ old('game_date', $statistic->game_date->format('Y-m-d')) }}" required>
                                            @error('game_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="minutes_played" class="form-label">Minutes Played</label>
                                            <input type="number" class="form-control @error('minutes_played') is-invalid @enderror"
                                                   id="minutes_played" name="minutes_played" value="{{ old('minutes_played', $statistic->minutes_played) }}"
                                                   min="0" max="120" placeholder="90">
                                            @error('minutes_played')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Minutes the player was on the field</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="opponent" class="form-label">Opponent Team *</label>
                                    <input type="text" class="form-control @error('opponent') is-invalid @enderror"
                                           id="opponent" name="opponent" value="{{ old('opponent', $statistic->opponent) }}"
                                           placeholder="e.g., Manchester United, Arsenal FC" required>
                                    @error('opponent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tournament" class="form-label">Tournament/Competition</label>
                                    <input type="text" class="form-control @error('tournament') is-invalid @enderror"
                                           id="tournament" name="tournament" value="{{ old('tournament', $statistic->tournament) }}"
                                           placeholder="e.g., Premier League, FA Cup, Friendly">
                                    @error('tournament')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Specify the competition type</div>
                                </div>
                            </div>

                            <!-- Right Column - Statistics Input -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Performance Statistics</h5>

                                <!-- Current Statistics Info -->
                                @if($statistic->ai_generated)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-robot me-2"></i>
                                        <strong>AI Generated Statistics:</strong> These statistics were originally generated using AI from the game summary.
                                        You can modify them manually now.
                                    </div>
                                @else
                                    <div class="alert alert-secondary mb-3">
                                        <i class="fas fa-user me-2"></i>
                                        <strong>Manual Entry:</strong> These statistics were entered manually.
                                    </div>
                                @endif

                                <!-- Manual Statistics Section -->
                                <div id="manualSection">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="goals_scored" class="form-label">Goals Scored</label>
                                                <input type="number" class="form-control @error('goals_scored') is-invalid @enderror"
                                                       id="goals_scored" name="goals_scored" value="{{ old('goals_scored', $statistic->goals_scored) }}" min="0">
                                                @error('goals_scored')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="assists" class="form-label">Assists</label>
                                                <input type="number" class="form-control @error('assists') is-invalid @enderror"
                                                       id="assists" name="assists" value="{{ old('assists', $statistic->assists) }}" min="0">
                                                @error('assists')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="shots_on_target" class="form-label">Shots on Target</label>
                                                <input type="number" class="form-control @error('shots_on_target') is-invalid @enderror"
                                                       id="shots_on_target" name="shots_on_target" value="{{ old('shots_on_target', $statistic->shots_on_target) }}" min="0">
                                                @error('shots_on_target')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="passes_completed" class="form-label">Passes Completed</label>
                                                <input type="number" class="form-control @error('passes_completed') is-invalid @enderror"
                                                       id="passes_completed" name="passes_completed" value="{{ old('passes_completed', $statistic->passes_completed) }}" min="0">
                                                @error('passes_completed')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tackles" class="form-label">Tackles</label>
                                                <input type="number" class="form-control @error('tackles') is-invalid @enderror"
                                                       id="tackles" name="tackles" value="{{ old('tackles', $statistic->tackles) }}" min="0">
                                                @error('tackles')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="interceptions" class="form-label">Interceptions</label>
                                                <input type="number" class="form-control @error('interceptions') is-invalid @enderror"
                                                       id="interceptions" name="interceptions" value="{{ old('interceptions', $statistic->interceptions) }}" min="0">
                                                @error('interceptions')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="saves" class="form-label">Saves (Goalkeepers)</label>
                                                <input type="number" class="form-control @error('saves') is-invalid @enderror"
                                                       id="saves" name="saves" value="{{ old('saves', $statistic->saves) }}" min="0">
                                                @error('saves')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="rating" class="form-label">Performance Rating</label>
                                                <input type="number" class="form-control @error('rating') is-invalid @enderror"
                                                       id="rating" name="rating" value="{{ old('rating', $statistic->rating) }}" min="0" max="10" step="0.1"
                                                       placeholder="8.5">
                                                @error('rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Rate from 0-10 (optional)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="yellow_cards" class="form-label">Yellow Cards</label>
                                                <input type="number" class="form-control @error('yellow_cards') is-invalid @enderror"
                                                       id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', $statistic->yellow_cards) }}" min="0" max="2">
                                                @error('yellow_cards')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="red_cards" class="form-label">Red Cards</label>
                                                <input type="number" class="form-control @error('red_cards') is-invalid @enderror"
                                                       id="red_cards" name="red_cards" value="{{ old('red_cards', $statistic->red_cards) }}" min="0" max="1">
                                                @error('red_cards')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Game Summary -->
                                <div class="mb-3">
                                    <label for="game_summary" class="form-label">Game Summary</label>
                                    <textarea class="form-control @error('game_summary') is-invalid @enderror"
                                              id="game_summary" name="game_summary" rows="4"
                                              placeholder="Optional: Add or update a summary of the player's performance">{{ old('game_summary', $statistic->game_summary) }}</textarea>
                                    @error('game_summary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Provide a description of the player's performance</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-warning py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Modifying statistics will update the player's cumulative performance data
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.game-statistics.show', $statistic) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Statistics
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    border: none;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #e0a800, #e8590c);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.alert-warning {
    background: linear-gradient(45deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffecb5;
    color: #856404;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editStatisticsForm');

    // Form validation
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Real-time validation feedback
    document.addEventListener('input', function(e) {
        if (e.target.hasAttribute('required') && e.target.value.trim()) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        }
    });

    // Player selection enhancement
    document.getElementById('player_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const position = selectedOption.getAttribute('data-position');

        // Show goalkeeper-specific fields if needed
        const savesField = document.getElementById('saves');
        if (position && position.toLowerCase().includes('goalkeeper')) {
            savesField.closest('.mb-3').style.display = 'block';
        } else {
            savesField.closest('.mb-3').style.display = 'none';
        }
    });

    // Trigger player change on load
    const playerSelect = document.getElementById('player_id');
    if (playerSelect.value) {
        playerSelect.dispatchEvent(new Event('change'));
    }

    // Warn about changes
    let originalValues = {};
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        originalValues[input.name] = input.value;
        input.addEventListener('change', function() {
            if (this.value !== originalValues[this.name]) {
                this.classList.add('bg-light');
            } else {
                this.classList.remove('bg-light');
            }
        });
    });
});
</script>
@endsection
