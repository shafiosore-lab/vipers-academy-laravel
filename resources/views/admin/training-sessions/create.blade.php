@extends('layouts.admin')

@section('title', 'Schedule Training Session - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-plus fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Schedule Training Session</h4>
                            <small class="opacity-75">Create a time-tracked training session</small>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.training-sessions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_type" class="form-label">Session Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('session_type') is-invalid @enderror" id="session_type" name="session_type" required>
                                        <option value="">Select Session Type</option>
                                        <option value="training" {{ old('session_type') == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="match" {{ old('session_type') == 'match' ? 'selected' : '' }}>Match</option>
                                        <option value="office_time" {{ old('session_type') == 'office_time' ? 'selected' : '' }}>Office Time</option>
                                        <option value="meeting" {{ old('session_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
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
                                        <option value="U13" {{ old('team_category') == 'U13' ? 'selected' : '' }}>U13</option>
                                        <option value="U15" {{ old('team_category') == 'U15' ? 'selected' : '' }}>U15</option>
                                        <option value="U17" {{ old('team_category') == 'U17' ? 'selected' : '' }}>U17</option>
                                        <option value="Senior" {{ old('team_category') == 'Senior' ? 'selected' : '' }}>Senior</option>
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
                                   value="{{ old('scheduled_start_time') }}" required>
                            @error('scheduled_start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Players arriving after this time will be marked as late. The session must start at this exact time for accurate tracking.
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Important:</strong> The scheduled start time is crucial for calculating player punctuality.
                            Make sure this matches the agreed training time with players and parents.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.training-sessions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Sessions
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-check me-2"></i>Schedule Session
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
