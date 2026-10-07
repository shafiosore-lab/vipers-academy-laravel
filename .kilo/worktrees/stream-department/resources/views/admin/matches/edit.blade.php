@extends('layouts.admin')

@section('title', 'Edit Match - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Football Match</h4>
                            <small class="opacity-75">Update match details and information</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.matches.update', $match) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                                           id="opponent" name="opponent" value="{{ old('opponent', $match->opponent) }}" required
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
                                                        <option value="friendly" {{ old('type', $match->type) == 'friendly' ? 'selected' : '' }}>Friendly</option>
                                                        <option value="tournament" {{ old('type', $match->type) == 'tournament' ? 'selected' : '' }}>Tournament</option>
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
                                                           id="match_date" name="match_date" value="{{ old('match_date', $match->match_date->format('Y-m-d\TH:i')) }}" required>
                                                    @error('match_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="venue" class="form-label">Venue *</label>
                                                    <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                                           id="venue" name="venue" value="{{ old('venue', $match->venue) }}" required
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
                                                        <option value="planned" {{ old('status', $match->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                                                        <option value="upcoming" {{ old('status', $match->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                        <option value="completed" {{ old('status', $match->status) == 'completed' ? 'selected' : '' }}>Completed</option>
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
                                                           id="tournament_name" name="tournament_name" value="{{ old('tournament_name', $match->tournament_name) }}"
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
                                <div class="card mb-4" id="results-section" style="display: {{ old('status', $match->status) === 'completed' ? 'block' : 'none' }};">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Match Results</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vipers_score" class="form-label">Vipers Score</label>
                                                    <input type="number" class="form-control @error('vipers_score') is-invalid @enderror"
                                                           id="vipers_score" name="vipers_score" value="{{ old('vipers_score', $match->vipers_score) }}" min="0">
                                                    @error('vipers_score')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="opponent_score" class="form-label">Opponent Score</label>
                                                    <input type="number" class="form-control @error('opponent_score') is-invalid @enderror"
                                                           id="opponent_score" name="opponent_score" value="{{ old('opponent_score', $match->opponent_score) }}" min="0">
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
                                                      placeholder="Brief description of the match or event">{{ old('description', $match->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="match_summary" class="form-label">Match Summary</label>
                                            <textarea class="form-control @error('match_summary') is-invalid @enderror"
                                                      id="match_summary" name="match_summary" rows="4"
                                                      placeholder="Detailed summary of the match (for completed matches)">{{ old('match_summary', $match->match_summary) }}</textarea>
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
                                        <!-- Existing Images -->
                                        @if($match->images && count($match->images) > 0)
                                        <div class="mb-3">
                                            <label class="form-label">Current Images</label>
                                            <div class="row g-2">
                                                @foreach($match->images as $index => $image)
                                                <div class="col-md-3">
                                                    <div class="image-preview">
                                                        <img src="{{ asset('storage/' . $image) }}" alt="Match image" class="img-fluid rounded">
                                                        <div class="image-overlay">
                                                            <label class="delete-checkbox">
                                                                <input type="checkbox" name="delete_images[]" value="{{ $image }}">
                                                                <span>Delete</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="form-text">Check images to delete them</div>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="images" class="form-label">Add More Images</label>
                                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                                   id="images" name="images[]" multiple accept="image/*">
                                            <div class="form-text">Upload additional images (JPEG, PNG, JPG, GIF - Max 2MB each)</div>
                                            @error('images.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="live_link" class="form-label">Live Stream Link</label>
                                                    <input type="url" class="form-control @error('live_link') is-invalid @enderror"
                                                           id="live_link" name="live_link" value="{{ old('live_link', $match->live_link) }}"
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
                                                           id="highlights_link" name="highlights_link" value="{{ old('highlights_link', $match->highlights_link) }}"
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
                                <div class="card mb-4" id="tournament-section" style="display: {{ old('type', $match->type) === 'tournament' ? 'block' : 'none' }};">
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
                                                        <option value="0" {{ old('registration_open', $match->registration_open) == '0' ? 'selected' : '' }}>No</option>
                                                        <option value="1" {{ old('registration_open', $match->registration_open) == '1' ? 'selected' : '' }}>Yes</option>
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
                                                           id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline', $match->registration_deadline ? $match->registration_deadline->format('Y-m-d') : '') }}">
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
                                <div class="card border-warning">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Match Tips</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning">
                                            <small>
                                                <i class="fas fa-lightbulb me-1"></i>
                                                <strong>Tips for updating matches:</strong><br>
                                                • Update scores only for completed matches<br>
                                                • Add highlights links after the match<br>
                                                • Upload match photos for better engagement<br>
                                                • Keep descriptions clear and informative
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Match Info Summary -->
                                <div class="card mt-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Match Info</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="match-info-item">
                                            <strong>ID:</strong> #{{ $match->id }}
                                        </div>
                                        <div class="match-info-item">
                                            <strong>Created:</strong> {{ $match->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="match-info-item">
                                            <strong>Updated:</strong> {{ $match->updated_at->format('M d, Y') }}
                                        </div>
                                        @if($match->images)
                                        <div class="match-info-item">
                                            <strong>Images:</strong> {{ count($match->images) }}
                                        </div>
                                        @endif
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
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Match
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
    background: linear-gradient(45deg, #ffc107, #e0a800);
    border: none;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #e0a800, #d39e00);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.image-preview {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.image-preview img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-preview:hover .image-overlay {
    opacity: 1;
}

.delete-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    cursor: pointer;
}

.delete-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.match-info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.9rem;
}

.match-info-item:last-child {
    border-bottom: none;
}

.match-info-item strong {
    color: #6b7280;
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
