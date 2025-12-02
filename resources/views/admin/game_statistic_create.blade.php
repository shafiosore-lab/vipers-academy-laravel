@extends('layouts.admin')

@section('title', 'Add Game Statistics - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Add Game Statistics</h4>
                            <small class="opacity-75">Record player performance from a game</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.game-statistics.store') }}" method="POST" id="statisticsForm">
                        @csrf

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
                                                    {{ old('player_id') == $player->id ? 'selected' : '' }}>
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
                                    <div class="form-text">Select the player whose statistics you want to record</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="game_date" class="form-label">Game Date *</label>
                                            <input type="date" class="form-control @error('game_date') is-invalid @enderror"
                                                   id="game_date" name="game_date" value="{{ old('game_date', date('Y-m-d')) }}" required>
                                            @error('game_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="minutes_played" class="form-label">Minutes Played</label>
                                            <input type="number" class="form-control @error('minutes_played') is-invalid @enderror"
                                                   id="minutes_played" name="minutes_played" value="{{ old('minutes_played') }}"
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
                                           id="opponent" name="opponent" value="{{ old('opponent') }}"
                                           placeholder="e.g., Manchester United, Arsenal FC" required>
                                    @error('opponent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tournament" class="form-label">Tournament/Competition</label>
                                    <input type="text" class="form-control @error('tournament') is-invalid @enderror"
                                           id="tournament" name="tournament" value="{{ old('tournament') }}"
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

                                <!-- AI Processing Section -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-light">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-robot me-2 text-primary"></i>
                                            <h6 class="mb-0">AI-Powered Statistics Extraction</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="use_ai" name="use_ai" value="1"
                                                       {{ old('use_ai') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="use_ai">
                                                    <strong>Use AI to extract statistics from game summary</strong>
                                                </label>
                                            </div>
                                            <div class="form-text">Enable this to automatically generate statistics from a text description of the game</div>
                                        </div>

                                        <div id="aiSection" style="display: none;">
                                            <label for="game_summary" class="form-label">Game Summary *</label>
                                            <textarea class="form-control @error('game_summary') is-invalid @enderror"
                                                      id="game_summary" name="game_summary" rows="6"
                                                      placeholder="Describe the player's performance in the game. For example: 'John played 85 minutes, scored 2 goals including a stunning free kick, provided 1 assist with a perfect cross, made 15 successful tackles, and received an 8.5 rating from the coach.'">{{ old('game_summary') }}</textarea>
                                            @error('game_summary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Provide a detailed description of the player's performance for AI analysis</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manual Statistics Section -->
                                <div id="manualSection">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="goals_scored" class="form-label">Goals Scored</label>
                                                <input type="number" class="form-control @error('goals_scored') is-invalid @enderror"
                                                       id="goals_scored" name="goals_scored" value="{{ old('goals_scored', 0) }}" min="0">
                                                @error('goals_scored')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="assists" class="form-label">Assists</label>
                                                <input type="number" class="form-control @error('assists') is-invalid @enderror"
                                                       id="assists" name="assists" value="{{ old('assists', 0) }}" min="0">
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
                                                       id="shots_on_target" name="shots_on_target" value="{{ old('shots_on_target', 0) }}" min="0">
                                                @error('shots_on_target')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="passes_completed" class="form-label">Passes Completed</label>
                                                <input type="number" class="form-control @error('passes_completed') is-invalid @enderror"
                                                       id="passes_completed" name="passes_completed" value="{{ old('passes_completed', 0) }}" min="0">
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
                                                       id="tackles" name="tackles" value="{{ old('tackles', 0) }}" min="0">
                                                @error('tackles')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="interceptions" class="form-label">Interceptions</label>
                                                <input type="number" class="form-control @error('interceptions') is-invalid @enderror"
                                                       id="interceptions" name="interceptions" value="{{ old('interceptions', 0) }}" min="0">
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
                                                       id="saves" name="saves" value="{{ old('saves', 0) }}" min="0">
                                                @error('saves')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="rating" class="form-label">Performance Rating</label>
                                                <input type="number" class="form-control @error('rating') is-invalid @enderror"
                                                       id="rating" name="rating" value="{{ old('rating') }}" min="0" max="10" step="0.1"
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
                                                       id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', 0) }}" min="0" max="2">
                                                @error('yellow_cards')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="red_cards" class="form-label">Red Cards</label>
                                                <input type="number" class="form-control @error('red_cards') is-invalid @enderror"
                                                       id="red_cards" name="red_cards" value="{{ old('red_cards', 0) }}" min="0" max="1">
                                                @error('red_cards')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-info py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Fields marked with * are required
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.game-statistics.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Save Statistics
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

.btn-success {
    background: linear-gradient(45deg, #65c16e, #4a8c52);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(45deg, #4a8c52, #3a6b3f);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(101, 193, 110, 0.3);
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.alert-info {
    background: linear-gradient(45deg, #d1ecf1, #cce5ff);
    border: 1px solid #a3cfbb;
    color: #0c5460;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiCheckbox = document.getElementById('use_ai');
    const aiSection = document.getElementById('aiSection');
    const manualSection = document.getElementById('manualSection');
    const gameSummaryField = document.getElementById('game_summary');
    const form = document.getElementById('statisticsForm');

    // Toggle AI vs Manual input
    function toggleInputMode() {
        if (aiCheckbox.checked) {
            aiSection.style.display = 'block';
            manualSection.style.display = 'none';
            // Make game summary required when AI is enabled
            gameSummaryField.setAttribute('required', 'required');
            // Clear manual fields
            clearManualFields();
        } else {
            aiSection.style.display = 'none';
            manualSection.style.display = 'block';
            // Remove required from game summary
            gameSummaryField.removeAttribute('required');
        }
    }

    function clearManualFields() {
        const manualFields = [
            'goals_scored', 'assists', 'shots_on_target', 'passes_completed',
            'tackles', 'interceptions', 'saves', 'yellow_cards', 'red_cards', 'rating'
        ];

        manualFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) field.value = '';
        });
    }

    aiCheckbox.addEventListener('change', toggleInputMode);

    // Initialize on page load
    toggleInputMode();

    // Form validation
    form.addEventListener('submit', function(e) {
        if (aiCheckbox.checked && !gameSummaryField.value.trim()) {
            e.preventDefault();
            gameSummaryField.focus();
            alert('Please provide a game summary when using AI processing.');
            return;
        }

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

    // Trigger player change on load if pre-selected
    const playerSelect = document.getElementById('player_id');
    if (playerSelect.value) {
        playerSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
