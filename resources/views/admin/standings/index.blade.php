@extends('layouts.admin')

@section('title', 'Standings Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Standings Management
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage league standings, top scorers, clean sheets, and goalkeeper rankings. Add, edit, or remove standings data.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-list-ol fa-2x"></i>
                    </div>
                    <h3 class="text-primary mb-0">{{ $stats['league_teams'] ?? 0 }}</h3>
                    <small class="text-muted">League Teams</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-futbol fa-2x"></i>
                    </div>
                    <h3 class="text-success mb-0">{{ $stats['top_scorers'] ?? 0 }}</h3>
                    <small class="text-muted">Top Scorers</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h3 class="text-info mb-0">{{ $stats['clean_sheets'] ?? 0 }}</h3>
                    <small class="text-muted">Clean Sheets</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-hand-paper fa-2x"></i>
                    </div>
                    <h3 class="text-warning mb-0">{{ $stats['goalkeepers'] ?? 0 }}</h3>
                    <small class="text-muted">Goalkeeper Rankings</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Type Tabs -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="standingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $type === 'league' ? 'active' : '' }}"
                               href="{{ route('admin.standings.index', ['type' => 'league']) }}"
                               role="tab">
                                <i class="fas fa-list-ol me-2"></i>League Standings
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $type === 'scorers' ? 'active' : '' }}"
                               href="{{ route('admin.standings.index', ['type' => 'scorers']) }}"
                               role="tab">
                                <i class="fas fa-futbol me-2"></i>Top Scorers
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $type === 'cleansheets' ? 'active' : '' }}"
                               href="{{ route('admin.standings.index', ['type' => 'cleansheets']) }}"
                               role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Clean Sheets
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $type === 'goalkeepers' ? 'active' : '' }}"
                               href="{{ route('admin.standings.index', ['type' => 'goalkeepers']) }}"
                               role="tab">
                                <i class="fas fa-hand-paper me-2"></i>Goalkeeper Rankings
                            </a>
                        </li>
                    </ul>
                    <div class="card-tools mt-2">
                        <a href="{{ route('admin.standings.create', ['type' => $type]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Add New {{ ucfirst($type) }}
                        </a>
                        <a href="{{ route('admin.standings.export', ['type' => $type]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-download me-2"></i>Export
                        </a>
                        <button class="btn btn-outline-success btn-sm" onclick="toggleBulkImport()">
                            <i class="fas fa-upload me-2"></i>Bulk Import
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if($data->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    @if($type === 'league')
                                        <tr>
                                            <th>Position</th>
                                            <th>Team</th>
                                            <th>P</th>
                                            <th>W</th>
                                            <th>D</th>
                                            <th>L</th>
                                            <th>GF</th>
                                            <th>GA</th>
                                            <th>GD</th>
                                            <th>Pts</th>
                                            <th>Season</th>
                                            <th>Actions</th>
                                        </tr>
                                    @elseif($type === 'scorers')
                                        <tr>
                                            <th>Rank</th>
                                            <th>Player</th>
                                            <th>Team</th>
                                            <th>Goals</th>
                                            <th>Assists</th>
                                            <th>Apps</th>
                                            <th>Mins</th>
                                            <th>Season</th>
                                            <th>Actions</th>
                                        </tr>
                                    @elseif($type === 'cleansheets')
                                        <tr>
                                            <th>Position</th>
                                            <th>Goalkeeper</th>
                                            <th>Team</th>
                                            <th>Clean Sheets</th>
                                            <th>Saves</th>
                                            <th>Apps</th>
                                            <th>Mins</th>
                                            <th>Season</th>
                                            <th>Actions</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Position</th>
                                            <th>Goalkeeper</th>
                                            <th>Team</th>
                                            <th>Saves</th>
                                            <th>Clean Sheets</th>
                                            <th>Apps</th>
                                            <th>Save %</th>
                                            <th>Season</th>
                                            <th>Actions</th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        @if($type === 'league')
                                            <tr>
                                                <td>{{ $item->position }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->team_logo)
                                                            <img src="{{ asset('storage/' . $item->team_logo) }}"
                                                                 alt="{{ $item->team_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                                        @endif
                                                        <strong>{{ $item->team_name }}</strong>
                                                        @if($item->is_vipers_team)
                                                            <span class="badge bg-primary ms-2">Vipers</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $item->played }}</td>
                                                <td>{{ $item->won }}</td>
                                                <td>{{ $item->drawn }}</td>
                                                <td>{{ $item->lost }}</td>
                                                <td>{{ $item->goals_for }}</td>
                                                <td>{{ $item->goals_against }}</td>
                                                <td>{{ $item->goal_difference }}</td>
                                                <td><strong>{{ $item->points }}</strong></td>
                                                <td>{{ $item->season }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.standings.show', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.standings.edit', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST"
                                                              action="{{ route('admin.standings.destroy', [$item, 'type' => $type]) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this standing?')"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif($type === 'scorers')
                                            <tr>
                                                <td>{{ $item->ranking_position }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->player_image)
                                                            <img src="{{ asset('storage/' . $item->player_image) }}"
                                                                 alt="{{ $item->player_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                                        @endif
                                                        <strong>{{ $item->player_name }}</strong>
                                                        @if($item->is_vipers_player)
                                                            <span class="badge bg-primary ms-2">Vipers</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->team_logo)
                                                        <img src="{{ asset('storage/' . $item->team_logo) }}"
                                                             alt="{{ $item->team_name }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 25px; height: 25px; object-fit: cover;">
                                                    @endif
                                                    {{ $item->team_name }}
                                                </td>
                                                <td><strong>{{ $item->goals }}</strong></td>
                                                <td>{{ $item->assists }}</td>
                                                <td>{{ $item->appearances }}</td>
                                                <td>{{ $item->minutes_played }}</td>
                                                <td>{{ $item->season }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.standings.show', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.standings.edit', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST"
                                                              action="{{ route('admin.standings.destroy', [$item, 'type' => $type]) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this scorer?')"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif($type === 'cleansheets')
                                            <tr>
                                                <td>{{ $item->position }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->goalkeeper_image)
                                                            <img src="{{ asset('storage/' . $item->goalkeeper_image) }}"
                                                                 alt="{{ $item->goalkeeper_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                                        @endif
                                                        <strong>{{ $item->goalkeeper_name }}</strong>
                                                        @if($item->is_vipers_player)
                                                            <span class="badge bg-primary ms-2">Vipers</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->team_logo)
                                                        <img src="{{ asset('storage/' . $item->team_logo) }}"
                                                             alt="{{ $item->team_name }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 25px; height: 25px; object-fit: cover;">
                                                    @endif
                                                    {{ $item->team_name }}
                                                </td>
                                                <td><strong>{{ $item->clean_sheets }}</strong></td>
                                                <td>{{ $item->saves }}</td>
                                                <td>{{ $item->appearances }}</td>
                                                <td>{{ $item->minutes_played }}</td>
                                                <td>{{ $item->season }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.standings.show', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.standings.edit', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST"
                                                              action="{{ route('admin.standings.destroy', [$item, 'type' => $type]) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this clean sheet record?')"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{{ $item->position }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->goalkeeper_image)
                                                            <img src="{{ asset('storage/' . $item->goalkeeper_image) }}"
                                                                 alt="{{ $item->goalkeeper_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                                        @endif
                                                        <strong>{{ $item->goalkeeper_name }}</strong>
                                                        @if($item->is_vipers_player)
                                                            <span class="badge bg-primary ms-2">Vipers</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->team_logo)
                                                        <img src="{{ asset('storage/' . $item->team_logo) }}"
                                                             alt="{{ $item->team_name }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 25px; height: 25px; object-fit: cover;">
                                                    @endif
                                                    {{ $item->team_name }}
                                                </td>
                                                <td>{{ $item->saves }}</td>
                                                <td>{{ $item->clean_sheets }}</td>
                                                <td>{{ $item->appearances }}</td>
                                                <td>{{ number_format($item->save_percentage ?? 0, 1) }}%</td>
                                                <td>{{ $item->season }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.standings.show', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-info"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.standings.edit', [$item, 'type' => $type]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST"
                                                              action="{{ route('admin.standings.destroy', [$item, 'type' => $type]) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this goalkeeper ranking?')"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $data->appends(request()->query())->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-trophy fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No {{ $type }} data yet</h5>
                            <p class="text-muted mb-4">Start building your {{ $type }} standings by adding your first entry.</p>
                            <a href="{{ route('admin.standings.create', ['type' => $type]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First {{ ucfirst($type) }} Entry
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Import Section (Hidden by default) -->
    <div class="row mt-4" id="bulkImportSection" style="display: none;">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fas fa-upload me-2"></i>Bulk Import {{ ucfirst($type) }} Data
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.standings.bulk-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                            <div class="form-text">
                                Upload a CSV file with {{ $type }} data. The system will process and import the data.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-upload me-2"></i>Import Data
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleBulkImport()">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-tools {
    float: right;
}

@media (max-width: 768px) {
    .card-tools {
        float: none;
        margin-top: 10px;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .btn-group .btn {
        margin-right: 0;
        border-radius: 4px !important;
    }
}
</style>

@push('scripts')
<script>
function toggleBulkImport() {
    const section = document.getElementById('bulkImportSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Make table responsive on mobile
document.addEventListener('DOMContentLoaded', function() {
    // Ensure active tab is highlighted
    const urlParams = new URLSearchParams(window.location.search);
    const currentType = urlParams.get('type') || 'league';
    const activeTab = document.querySelector(`[href*="${currentType}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }
});
</script>
@endpush
@endsection
