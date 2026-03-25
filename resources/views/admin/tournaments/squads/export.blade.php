@extends('layouts.admin')

@section('title', __('Export Tournament Squad - Vipers Academy Admin'))

@php
// Determine route prefix based on the current URL path (not just user role)
$currentPath = request()->path();
if (str_starts_with($currentPath, 'super-admin')) {
    $routePrefix = 'super-admin';
} elseif (str_starts_with($currentPath, 'organization')) {
    $routePrefix = 'organization';
} elseif (str_starts_with($currentPath, 'coach')) {
    $routePrefix = 'coach';
} else {
    $routePrefix = 'admin';
}
@endphp

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Export Tournament Squad') }}</h1>
                    <p class="text-muted">{{ __('Download squad data in CSV format') }}</p>
                </div>
                <a href="{{ route($routePrefix . '.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Squad') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Export Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-export me-2 text-primary"></i>{{ __('Export Configuration') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route($routePrefix . '.tournaments.squads.export', [$tournament->id, $team->id]) }}" target="_blank">
                        <!-- Hidden tournament and team IDs -->
                        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                        <input type="hidden" name="team_id" value="{{ $team->id }}">

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-filter me-2"></i>{{ __('Filters') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('Verification Status') }}</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>{{ __('Verified') }}</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">{{ __('Position') }}</label>
                                <select class="form-select @error('position') is-invalid @enderror"
                                        id="position" name="position">
                                    <option value="">{{ __('All Positions') }}</option>
                                    <option value="Goalkeeper" {{ old('position') == 'Goalkeeper' ? 'selected' : '' }}>{{ __('Goalkeeper') }}</option>
                                    <option value="Defender" {{ old('position') == 'Defender' ? 'selected' : '' }}>{{ __('Defender') }}</option>
                                    <option value="Midfielder" {{ old('position') == 'Midfielder' ? 'selected' : '' }}>{{ __('Midfielder') }}</option>
                                    <option value="Forward" {{ old('position') == 'Forward' ? 'selected' : '' }}>{{ __('Forward') }}</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Export Options -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-cogs me-2"></i>{{ __('Export Options') }}</h6>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_player_details" name="include_player_details" value="1" checked>
                                    <label class="form-check-label" for="include_player_details">
                                        {{ __('Include detailed player information') }}
                                    </label>
                                </div>
                                <div class="form-text">{{ __('Adds player contact details and additional information to the export') }}</div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_verification_status" name="include_verification_status" value="1" checked>
                                    <label class="form-check-label" for="include_verification_status">
                                        {{ __('Include verification status') }}
                                    </label>
                                </div>
                                <div class="form-text">{{ __('Shows verification status and registration dates') }}</div>
                            </div>
                        </div>

                        <!-- Export Preview -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>{{ __('Export Preview') }}</h6>
                                    <p class="mb-2">{{ __('Your CSV file will include the following columns:') }}</p>
                                    <ul class="mb-0 small">
                                        <li>{{ __('Full Name, Position, Jersey Number') }}</li>
                                        <li>{{ __('Status, Registration Date') }}</li>
                                        <li>{{ __('Player Details (if selected)') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route($routePrefix . '.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>{{ __('Export CSV') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2 text-info"></i>{{ __('Export Help') }}</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">{{ __('How to Export Data') }}</h6>
                    <ol class="small mb-3">
                        <li>{{ __('Set verification status filter (optional)') }}</li>
                        <li>{{ __('Filter by player position (optional)') }}</li>
                        <li>{{ __('Choose export options') }}</li>
                        <li>{{ __('Click "Export CSV" to download') }}</li>
                    </ol>

                    <h6 class="mb-2">{{ __('Use Cases') }}</h6>
                    <ul class="small mb-3">
                        <li>{{ __('Tournament registration verification') }}</li>
                        <li>{{ __('Team roster management') }}</li>
                        <li>{{ __('Player eligibility tracking') }}</li>
                        <li>{{ __('Compliance reporting') }}</li>
                    </ul>

                    <div class="alert alert-light">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>{{ __('Tip') }}</h6>
                        <p class="small mb-0">{{ __('Filter by verification status to export only approved players for tournament submission.') }}</p>
                    </div>

                    <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded">
                        <h6 class="mb-2"><i class="fas fa-file-csv me-2 text-primary"></i>{{ __('CSV Export') }}</h6>
                        <p class="small mb-0">{{ __('Perfect for tournament submissions, compliance checks, and team management.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const includePlayerDetails = document.getElementById('include_player_details');
    const includeVerificationStatus = document.getElementById('include_verification_status');
    const extraColumns = document.getElementById('extra-columns');

    function toggleExtraColumns() {
        if (includePlayerDetails.checked || includeVerificationStatus.checked) {
            extraColumns.classList.remove('d-none');
        } else {
            extraColumns.classList.add('d-none');
        }
    }

    includePlayerDetails.addEventListener('change', toggleExtraColumns);
    includeVerificationStatus.addEventListener('change', toggleExtraColumns);
    toggleExtraColumns(); // Initial state
});
</script>
