@extends('layouts.admin')

@section('title', 'Create Match - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-futbol fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Create Football Match</h4>
                            <small class="opacity-75">Add a new match to the match center</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.matches.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Match Information -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Match Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="opponent" class="form-label">Opponent Team *</label>
                                                    <input type="text" class="form-control @error('opponent') is-invalid @enderror"
                                                           id="opponent" name="opponent" value="{{ old('opponent') }}" required
                                                           placeholder="e.g., Gor Mahia FC">
                                                    @error('opponent')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">Match Type *</label>
                                                    <select class="form-control @error('type') is-invalid @enderror"
                                                            id="type" name="type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="friendly" {{ old('type') == 'friendly' ? 'selected' : '' }}>Friendly</option>
                                                        <option value="tournament" {{ old('type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="match_date" class="form-label">Match Date & Time *</label>
                                                    <input type="datetime-local" class="form-control @error('match_date') is-invalid @enderror"
                                                           id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                                                    @error('match_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="venue" class="form-label">Venue *</label>
                                                    <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                                           id="venue" name="venue" value="{{ old('venue') }}" required
                                                           placeholder="e.g., Moi International Sports Centre">
                                                    @error('venue')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Match Status *</label>
                                                    <select class="form-control @error('status') is-invalid @enderror"
                                                            id="status" name="status" required>
                                                        <option value="">Select Status</option>
                                                        <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="tournament_name" class="form-label">Tournament Name</label>
                                                    <input type="text" class="form-control @error('tournament_name') is-invalid @enderror"
                                                           id="tournament_name" name="tournament_name" value="{{ old('tournament_name') }}"
                                                           placeholder="e.g., KPL Season 2024">
                                                    @error('tournament_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Match Results (for completed matches) -->
                                <div class="card mb-4" id="results-section" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Match Results</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vipers_score" class="form-label">Vipers Score</label>
                                                    <input type="number" class="form-control @error('vipers_score') is-invalid @enderror"
                                                           id="vipers_score" name="vipers_score" value="{{ old('vipers_score') }}" min="0">
                                                    @error('vipers_score')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="opponent_score" class="form-label">Opponent Score</label>
                                                    <input type="number" class="form-control @error('opponent_score') is-invalid @enderror"
                                                           id="opponent_score" name="opponent_score" value="{{ old('opponent_score') }}" min="0">
                                                    @error('opponent_score')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Match Details -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Match Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Match Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description" name="description" rows="4"
                                                      placeholder="Brief description of the match or event">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="match_summary" class="form-label">Match Summary</label>
                                            <textarea class="form-control @error('match_summary') is-invalid @enderror"
                                                      id="match_summary" name="match_summary" rows="4"
                                                      placeholder="Detailed summary of the match (for completed matches)">{{ old('match_summary') }}</textarea>
                                            @error('match_summary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Media & Links -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-photo-video me-2"></i>Media & Links</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Match Images</label>
                                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                                   id="images" name="images[]" multiple accept="image/*">
                                            <div class="form-text">Upload multiple images (JPEG, PNG, JPG, GIF - Max 2MB each)</div>
                                            @error('images.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="live_link" class="form-label">Live Stream Link</label>
                                                    <input type="url" class="form-control @error('live_link') is-invalid @enderror"
                                                           id="live_link" name="live_link" value="{{ old('live_link') }}"
                                                           placeholder="https://youtube.com/...">
                                                    @error('live_link')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="highlights_link" class="form-label">Highlights Link</label>
                                                    <input type="url" class="form-control @error('highlights_link') is-invalid @enderror"
                                                           id="highlights_link" name="highlights_link" value="{{ old('highlights_link') }}"
                                                           placeholder="https://youtube.com/...">
                                                    @error('highlights_link')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tournament Settings -->
                                <div class="card mb-4" id="tournament-section" style="display: none;">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-crown me-2"></i>Tournament Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="registration_open" class="form-label">Registration Open</label>
                                                    <select class="form-control @error('registration_open') is-invalid @enderror"
                                                            id="registration_open" name="registration_open">
                                                        <option value="0" {{ old('registration_open') == '0' ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ old('registration_open') == '1' ? 'selected' : '' }}>Yes</option>
                                                    </select>
                                                    @error('registration_open')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="registration_deadline" class="form-label">Registration Deadline</label>
                                                    <input type="date" class="form-control @error('registration_deadline') is-invalid @enderror"
                                                           id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}">
                                                    @error('registration_deadline')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Match Creation Tips</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-primary">
                                            <small>
                                                <i class="fas fa-lightbulb me-1"></i>
                                                <strong>Tips for match entries:</strong><br>
                                                • Use clear, descriptive opponent names<br>
                                                • Include venue details for fans<br>
                                                • Upload high-quality match images<br>
                                                • Add live stream links for upcoming matches<br>
                                                • Complete results for finished matches
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Stats Preview -->
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Current Stats</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="stat-number">{{ \App\Models\FootballMatch::completed()->count() }}</div>
                                                <div class="stat-label">Played</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="stat-number">{{ \App\Models\FootballMatch::upcoming()->count() }}</div>
                                                <div class="stat-label">Upcoming</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="stat-number">{{ \App\Models\FootballMatch::tournaments()->count() }}</div>
                                                <div class="stat-label">Tournaments</div>
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
                                        All required fields marked with *
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Create Match
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

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide results section based on status
    const statusSelect = document.getElementById('status');
    const resultsSection = document.getElementById('results-section');
    const typeSelect = document.getElementById('type');
    const tournamentSection = document.getElementById('tournament-section');

    function toggleSections() {
        // Show results for completed matches
        if (statusSelect.value === 'completed') {
            resultsSection.style.display = 'block';
        } else {
            resultsSection.style.display = 'none';
        }

        // Show tournament settings for tournament matches
        if (typeSelect.value === 'tournament') {
            tournamentSection.style.display = 'block';
        } else {
            tournamentSection.style.display = 'none';
        }
    }

    statusSelect.addEventListener('change', toggleSections);
    typeSelect.addEventListener('change', toggleSections);

    // Initialize on page load
    toggleSections();

    // Image preview
    const imageInput = document.getElementById('images');
    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 5) {
            alert('Maximum 5 images allowed');
            this.value = '';
        }
    });
});
</script>
@endsection
