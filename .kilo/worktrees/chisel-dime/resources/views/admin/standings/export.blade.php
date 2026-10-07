@extends('layouts.admin')

@section('title', __('Export Standings - Vipers Academy Admin'))

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
                    <h1 class="h3 mb-0">{{ __('Export Standings Data') }}</h1>
                    <p class="text-muted">{{ __('Download standings records in CSV format') }}</p>
                </div>
                <a href="{{ route($routePrefix . '.standings.index', ['type' => request()->get('type', 'league')]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Standings') }}
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
                    <form method="GET" action="{{ route($routePrefix . '.standings.export') }}" target="_blank">
                        <!-- Hidden type field -->
                        <input type="hidden" name="type" value="{{ request()->get('type', 'league') }}">

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-filter me-2"></i>{{ __('Filters') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="season" class="form-label">{{ __('Season') }}</label>
                                <select class="form-select @error('season') is-invalid @enderror"
                                        id="season" name="season">
                                    <option value="">{{ __('All Seasons') }}</option>
                                    @foreach($seasons as $season)
                                        <option value="{{ $season }}" {{ old('season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                                    @endforeach
                                </select>
                                @error('season')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(request()->get('type') == 'league')
                            <div class="col-md-6 mb-3">
                                <label for="league" class="form-label">{{ __('League') }}</label>
                                <select class="form-select @error('league') is-invalid @enderror"
                                        id="league" name="league">
                                    <option value="">{{ __('All Leagues') }}</option>
                                    @foreach($leagues as $league)
                                        <option value="{{ $league }}" {{ old('league') == $league ? 'selected' : '' }}>{{ $league }}</option>
                                    @endforeach
                                </select>
                                @error('league')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <!-- Export Options -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-cogs me-2"></i>{{ __('Export Options') }}</h6>
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
                                    <p class="mb-2">{{ __('Your CSV file will include the following columns based on the selected type:') }}</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('League Standings:') }}</strong>
                                            <ul class="small mb-0">
                                                <li>Position, Team, Played, Won, Drawn, Lost</li>
                                                <li>GF, GA, GD, Points, Season, League</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('Top Scorers:') }}</strong>
                                            <ul class="small mb-0">
                                                <li>Rank, Player, Team, Goals, Assists</li>
                                                <li>Matches, Season, League</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>{{ __('Clean Sheets:') }}</strong>
                                            <ul class="small mb-0">
                                                <li>Rank, Goalkeeper, Team, Clean Sheets</li>
                                                <li>Matches, Season, League</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('Goalkeeper Stats:') }}</strong>
                                            <ul class="small mb-0">
                                                <li>Rank, Goalkeeper, Team, Goals Conceded</li>
                                                <li>Matches, Save %, Season, League</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route($routePrefix . '.standings.index', ['type' => request()->get('type', 'league')]) }}" class="btn btn-outline-secondary">
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
                        <li>{{ __('Select the season filter (optional)') }}</li>
                        <li>{{ __('For league standings, select specific league (optional)') }}</li>
                        <li>{{ __('Choose export options') }}</li>
                        <li>{{ __('Click "Export CSV" to download') }}</li>
                    </ol>

                    <h6 class="mb-2">{{ __('Data Types Available') }}</h6>
                    <ul class="small mb-3">
                        <li>{{ __('League Standings - Team performance data') }}</li>
                        <li>{{ __('Top Scorers - Player goal statistics') }}</li>
                        <li>{{ __('Clean Sheets - Goalkeeper performance') }}</li>
                        <li>{{ __('Goalkeeper Stats - Detailed keeper metrics') }}</li>
                    </ul>

                    <div class="alert alert-light">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>{{ __('Tip') }}</h6>
                        <p class="small mb-0">{{ __('Use season filters to export specific tournament data for reporting purposes.') }}</p>
                    </div>

                    <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded">
                        <h6 class="mb-2"><i class="fas fa-file-csv me-2 text-primary"></i>{{ __('CSV Export') }}</h6>
                        <p class="small mb-0">{{ __('Perfect for sports analytics, performance tracking, and historical data analysis.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
