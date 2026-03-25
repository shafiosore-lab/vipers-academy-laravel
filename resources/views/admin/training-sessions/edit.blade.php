@extends('layouts.admin')

@section('title', 'Edit Training Session - Admin Dashboard')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 fw-bold">Edit Training Session</h1>
        <a href="{{ route('admin.training-sessions.show', $trainingSession) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark py-2">
                    <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Session</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('admin.training-sessions.update', $trainingSession) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="session_type" class="form-label small mb-1">Session Type <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('session_type') is-invalid @enderror" id="session_type" name="session_type" required>
                                    <option value="">Select Type</option>
                                    <option value="training" {{ old('session_type', $trainingSession->session_type) == 'training' ? 'selected' : '' }}>Training</option>
                                    <option value="match" {{ old('session_type', $trainingSession->session_type) == 'match' ? 'selected' : '' }}>Match</option>
                                    <option value="office_time" {{ old('session_type', $trainingSession->session_type) == 'office_time' ? 'selected' : '' }}>Office Time</option>
                                    <option value="meeting" {{ old('session_type', $trainingSession->session_type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                </select>
                                @error('session_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="team_category" class="form-label small mb-1">Team Category <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('team_category') is-invalid @enderror" id="team_category" name="team_category" required>
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

                        <div class="mt-2">
                            <label for="scheduled_start_time" class="form-label small mb-1">Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control form-control-sm @error('scheduled_start_time') is-invalid @enderror"
                                   id="scheduled_start_time" name="scheduled_start_time"
                                   value="{{ old('scheduled_start_time', $trainingSession->scheduled_start_time->format('Y-m-d\TH:i')) }}" required>
                            @error('scheduled_start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Players late if after this time</small>
                        </div>

                        <div class="alert alert-warning py-2 mt-2 mb-2">
                            <small><i class="fas fa-exclamation-triangle me-1"></i> Only scheduled sessions can be edited.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ route('admin.training-sessions.show', $trainingSession) }}" class="btn btn-sm btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fas fa-save me-1"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Session</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row small">
                        <div class="col-6 mb-2">
                            <strong>Type:</strong><br>{{ ucfirst($trainingSession->session_type) }}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Team:</strong><br>{{ $trainingSession->team_category }}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Scheduled:</strong><br>{{ $trainingSession->scheduled_start_time->format('M j, Y g:i A') }}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Status:</strong><br>
                            @switch($trainingSession->status)
                                @case('scheduled')
                                    <span class="badge bg-secondary">Scheduled</span>
                                    @break
                                @case('active')
                                    <span class="badge bg-success">Active</span>
                                    @break
                                @case('ended')
                                    <span class="badge bg-primary">Ended</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setHours(now.getHours() + 1);
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('scheduled_start_time').min = minDateTime;
});
</script>

