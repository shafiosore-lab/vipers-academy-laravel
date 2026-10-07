@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Bulk Upload Players')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bulk Upload Players</h5>
                </div>
                <div class="card-body">
                    <!-- Instructions -->
                    <div class="alert alert-info mb-4">
                        <h6><i class="fas fa-info-circle"></i> Instructions</h6>
                        <ul class="mb-0">
                            <li>Upload a CSV file with player information</li>
                            <li>The CSV must contain the following headers:
                                <code>first_name, middle_name, last_name, id_type, id_number, date_of_birth, gender, city, position, jersey_number</code></li>
                            <li>For <code>id_type</code>, use: <code>national_id</code>, <code>passport</code>, <code>birth_certificate</code>, or <code>other</code></li>
                            <li>For <code>gender</code>, use: <code>male</code> or <code>female</code></li>
                            <li>Date format: <code>YYYY-MM-DD</code> (e.g., 2010-01-15)</li>
                            <li>If a player with the same ID number already exists, they will be added to your squad (if not already registered)</li>
                        </ul>
                    </div>

                    <!-- Download Template -->
                    <div class="mb-4">
                        <a href="{{ route('team.players.template') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download"></i> Download CSV Template
                        </a>
                    </div>

                    <!-- Upload Form -->
                    <form action="{{ route('team.players.bulk-upload.post', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Select CSV File *</label>
                            <input type="file" class="form-control @error('csv_file') is-invalid @enderror"
                                   id="csv_file" name="csv_file"
                                   accept=".csv,.txt" required>
                            <small class="text-muted">Maximum file size: 5MB</small>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Squad Info -->
                        <div class="alert alert-warning mb-4">
                            <strong>Current Squad Size:</strong>
                            {{ $tournamentTeam->squads()->count() }} / {{ $tournament->squad_limit }} players
                            <br>
                            <strong>Minimum Required:</strong> {{ $tournament->min_players }} players
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('team.players', $tournament->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Players
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
