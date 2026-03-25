@extends('layouts.admin')

@section('title', 'Schedule Training Session - Admin Dashboard')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 fw-bold">Schedule Training Session</h1>
        <a href="{{ route('admin.training-sessions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>New Session</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('admin.training-sessions.store') }}" method="POST">
                        @csrf

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="session_type" class="form-label small mb-1">Session Type <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('session_type') is-invalid @enderror" id="session_type" name="session_type" required>
                                    <option value="">Select Type</option>
                                    <option value="training" {{ old('session_type') == 'training' ? 'selected' : '' }}>Training</option>
                                    <option value="match" {{ old('session_type') == 'match' ? 'selected' : '' }}>Match</option>
                                    <option value="office_time" {{ old('session_type') == 'office_time' ? 'selected' : '' }}>Office Time</option>
                                    <option value="meeting" {{ old('session_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                </select>
                                @error('session_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="team_category" class="form-label small mb-1">Team Category <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('team_category') is-invalid @enderror" id="team_category" name="team_category" required>
                                    <option value="">Select Team</option>
                                    <option value="U10" {{ old('team_category') == 'U10' ? 'selected' : '' }}>Under 10</option>
                                    <option value="U12" {{ old('team_category') == 'U12' ? 'selected' : '' }}>Under 12</option>
                                    <option value="U13" {{ old('team_category') == 'U13' ? 'selected' : '' }}>Under 13</option>
                                    <option value="U14" {{ old('team_category') == 'U14' ? 'selected' : '' }}>Under 14</option>
                                    <option value="U15" {{ old('team_category') == 'U15' ? 'selected' : '' }}>Under 15</option>
                                    <option value="U16" {{ old('team_category') == 'U16' ? 'selected' : '' }}>Under 16</option>
                                    <option value="U17" {{ old('team_category') == 'U17' ? 'selected' : '' }}>Under 17</option>
                                    <option value="U18" {{ old('team_category') == 'U18' ? 'selected' : '' }}>Under 18</option>
                                    <option value="U20" {{ old('team_category') == 'U20' ? 'selected' : '' }}>Under 20</option>
                                    <option value="Senior" {{ old('team_category') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="Veteran" {{ old('team_category') == 'Veteran' ? 'selected' : '' }}>Veteran</option>
                                </select>
                                @error('team_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label for="gender" class="form-label small mb-1">Gender</label>
                                <select class="form-select form-select-sm @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">All (Mixed)</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Boys</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Girls</option>
                                    <option value="mixed" {{ old('gender') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="organization_id" class="form-label small mb-1">Organization</label>
                                <select class="form-select form-select-sm @error('organization_id') is-invalid @enderror" id="organization_id" name="organization_id">
                                    <option value="">All Organizations</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('organization_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="scheduled_start_time" class="form-label small mb-1">Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control form-control-sm @error('scheduled_start_time') is-invalid @enderror"
                                   id="scheduled_start_time" name="scheduled_start_time"
                                   value="{{ old('scheduled_start_time') }}" required>
                            @error('scheduled_start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Players late if after this time</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.training-sessions.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check me-1"></i>Schedule
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
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Time-tracked sessions</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Auto punctuality calculation</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Player attendance tracking</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Late arrival marking</li>
                    </ul>
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

