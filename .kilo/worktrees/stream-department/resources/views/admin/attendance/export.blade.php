@extends('layouts.admin')

@section('title', __('Export Attendance - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Export Attendance Data') }}</h1>
                    <p class="text-muted">{{ __('Download attendance records in CSV format') }}</p>
                </div>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Attendance') }}
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
                    <form method="GET" action="{{ route('admin.attendance.export') }}" target="_blank">
                        <!-- Date Range -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>{{ __('Date Range') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Leave empty for all dates') }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Leave empty for all dates') }}</div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-filter me-2"></i>{{ __('Filters') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="session_type" class="form-label">{{ __('Session Type') }}</label>
                                <select class="form-select @error('session_type') is-invalid @enderror"
                                        id="session_type" name="session_type">
                                    <option value="">{{ __('All Types') }}</option>
                                    <option value="training" {{ old('session_type') == 'training' ? 'selected' : '' }}>{{ __('Training Sessions') }}</option>
                                    <option value="match" {{ old('session_type') == 'match' ? 'selected' : '' }}>{{ __('Matches') }}</option>
                                </select>
                                @error('session_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('Attendance Status') }}</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                                    <option value="checked_in" {{ old('status') == 'checked_in' ? 'selected' : '' }}>{{ __('Checked In') }}</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                </select>
                                @error('status')
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
                                    <input class="form-check-input" type="checkbox" id="include_summary" name="include_summary" value="1">
                                    <label class="form-check-label" for="include_summary">
                                        {{ __('Include summary statistics') }}
                                    </label>
                                </div>
                                <div class="form-text">{{ __('Adds summary row with total counts at the end of the file') }}</div>
                            </div>
                        </div>

                        <!-- Export Preview -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>{{ __('Export Preview') }}</h6>
                                    <p class="mb-2">{{ __('Your CSV file will include the following columns:') }}</p>
                                    <ul class="mb-0 small">
                                        <li>{{ __('Player Name, Session Type, Session Date') }}</li>
                                        <li>{{ __('Check In Time, Check Out Time, Duration') }}</li>
                                        <li>{{ __('Recorded By, Recorded At') }}</li>
                                        <li id="extra-columns" class="d-none">{{ __('Player Details (if selected)') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
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
                        <li>{{ __('Set your date range (optional)') }}</li>
                        <li>{{ __('Apply filters for session type and status') }}</li>
                        <li>{{ __('Choose export options') }}</li>
                        <li>{{ __('Click "Export CSV" to download') }}</li>
                    </ol>

                    <h6 class="mb-2">{{ __('File Format') }}</h6>
                    <p class="small mb-3">{{ __('The exported file will be in CSV format, compatible with Excel, Google Sheets, and other spreadsheet applications.') }}</p>

                    <div class="alert alert-light">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>{{ __('Tip') }}</h6>
                        <p class="small mb-0">{{ __('For large datasets, consider applying filters to reduce file size and processing time.') }}</p>
                    </div>

                    <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded">
                        <h6 class="mb-2"><i class="fas fa-file-csv me-2 text-primary"></i>{{ __('CSV Export') }}</h6>
                        <p class="small mb-0">{{ __('Perfect for reporting, analysis, and record keeping. All attendance data is exported with timestamps for audit trails.') }}</p>
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
    const extraColumns = document.getElementById('extra-columns');

    function toggleExtraColumns() {
        if (includePlayerDetails.checked) {
            extraColumns.classList.remove('d-none');
        } else {
            extraColumns.classList.add('d-none');
        }
    }

    includePlayerDetails.addEventListener('change', toggleExtraColumns);
    toggleExtraColumns(); // Initial state
});
</script>
