@extends('layouts.admin')

@section('title', 'Edit Training Session - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Training Session</h4>
                            <small class="opacity-75">Update session details and schedule</small>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.training-sessions.update', $trainingSession) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_type" class="form-label">Session Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('session_type') is-invalid @enderror" id="session_type" name="session_type" required>
                                        <option value="">Select Session Type</option>
                                        <option value="training" {{ old('session_type', $trainingSession->session_type) == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="match" {{ old('session_type', $trainingSession->session_type) == 'match' ? 'selected' : '' }}>Match</option>
                                        <option value="office_time" {{ old('session_type', $trainingSession->session_type) == 'office_time' ? 'selected' : '' }}>Office Time</option>
                                        <option value="meeting" {{ old('session_type', $trainingSession->session_type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                    </select>
                                    @error('session_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="team_category" class="form-label">Team Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('team_category') is-invalid @enderror" id="team_category" name="team_category" required>
                                        <option value="">Select Team</option>
                                        <option value="U13" {{ old('team_category', $trainingSession->team_category) == 'U13' ? 'selected' : '' }}>U13</option>
                                        <option value="U15" {{ old('team_category', $trainingSession->team_category) == 'U15' ? 'selected' : '' }}>U15</option>
                                        <option value="U17" {{ old('team_category', $trainingSession->team_category) == 'U17' ? 'selected' : '' }}>U17</option>
                                        <option value="Senior" {{ old('team_category', $trainingSession->team_category) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                    </select>
                                    @error('team_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="scheduled_start_time" class="form-label">Scheduled Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('scheduled_start_time') is-invalid @enderror"
                                   id="scheduled_start_time" name="scheduled_start_time"
                                   value="{{ old('scheduled_start_time', $trainingSession->scheduled_start_time->format('Y-m-d\TH:i')) }}" required>
                            @error('scheduled_start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Players arriving after this time will be marked as late.
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Only scheduled sessions can be edited. Active or ended sessions cannot be modified to maintain data integrity.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.training-sessions.show', $trainingSession) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Session
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Set minimum datetime to current time + 1 hour
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setHours(now.getHours() + 1); // Minimum 1 hour from now
    const minDateTime = now.toISOString().slice(0, 16); // Format for datetime-local

    document.getElementById('scheduled_start_time').min = minDateTime;
});
</script>
