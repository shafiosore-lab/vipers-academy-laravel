@extends('layouts.admin')

@section('title', 'Squad - ' . $team->team_name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">{{ $team->team_name }} - Squad</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.squads.export', [$tournament->id, $team->id]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Export</a>
            <a href="{{ route('admin.tournaments.squads.create', [$tournament->id, $team->id]) }}" class="btn btn-primary btn-sm">+ Add Player</a>
        </div>
    </div>
@endsection

@section('content')
<!-- Stats -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="h4 mb-0">{{ $squads->total() }}</div>
                <small class="text-muted">Total Players</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="h4 mb-0 text-success">{{ $squads->where('verification_status', 'verified')->count() }}</div>
                <small class="text-muted">Verified</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="h4 mb-0 text-warning">{{ $squads->where('verification_status', 'pending')->count() }}</div>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="h4 mb-0">{{ $tournament->min_players }}</div>
                <small class="text-muted">Min Required</small>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload -->
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Bulk Upload</h6></div>
    <div class="card-body py-2">
        <form method="POST" action="{{ route('admin.tournaments.squads.bulk-upload', [$tournament->id, $team->id]) }}" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="csv_file" class="form-control form-control-sm" accept=".csv,.txt" required>
            <button type="submit" class="btn btn-success btn-sm">Upload</button>
            <a href="{{ route('admin.tournaments.squads.template') }}" class="btn btn-outline-secondary btn-sm">Template</a>
        </form>
    </div>
</div>

<!-- Squad Table -->
<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Player</th><th>Position</th><th>Jersey</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($squads as $squad)
                    <tr>
                        <td>{{ $loop->iteration + ($squads->currentPage() - 1) * $squads->perPage() }}</td>
                        <td>
                            <strong>{{ $squad->player->full_name }}</strong>
                            <div class="small text-muted">{{ $squad->player->id_number ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $squad->position ?? '-' }}</td>
                        <td>{{ $squad->jersey_number ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $squad->verification_status == 'verified' ? 'success' : ($squad->verification_status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($squad->verification_status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($squad->verification_status === 'pending')
                                <form action="{{ route('admin.tournaments.squads.verify', [$tournament->id, $team->id, $squad->id]) }}" method="POST" class="d-inline">
                                    @csrf <button type="submit" class="btn btn-xs btn-success">Verify</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.tournaments.squads.destroy', [$tournament->id, $team->id, $squad->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove player?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">No players in squad yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($squads->hasPages())<div class="card-footer">{{ $squads->links() }}</div>@endif
</div>
@endsection
