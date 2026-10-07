@extends('layouts.admin')

@section('title', 'Players - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h6 class="mb-0">Players in {{ $tournament->name }}</h6>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a> /
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Stats -->
    <div class="row mb-2 g-1">
        <div class="col-4">
            <div class="bg-info bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $players->total() }}</div>
                <small class="text-info">Total</small>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-success bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $players->where('verification_status', 'verified')->count() }}</div>
                <small class="text-success">Verified</small>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-warning bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $players->where('verification_status', 'pending')->count() }}</div>
                <small class="text-warning">Pending</small>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-2">
        <div class="card-body py-2">
            <form method="GET" class="row g-1 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Team</label>
                    <select name="team_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Teams</option>
                        @foreach($teams as $teamOption)
                            <option value="{{ $teamOption->id }}" {{ request('team_id') == $teamOption->id ? 'selected' : '' }}>
                                {{ $teamOption->team_name ?? ($teamOption->team->name ?? 'N/A') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="py-1 px-2">#</th>
                        <th class="py-1 px-2">Player</th>
                        <th class="py-1 px-2">ID</th>
                        <th class="py-1 px-2">Team</th>
                        <th class="py-1 px-2">Age</th>
                        <th class="py-1 px-2">Status</th>
                        <th class="py-1 px-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $index => $squad)
                    <tr>
                        <td class="py-1 px-2 small">{{ $players->firstItem() + $index }}</td>
                        <td class="py-1 px-2">
                            <span class="fw-bold">{{ $squad->player->full_name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-1 px-2 small">{{ $squad->player->id_number ?? '-' }}</td>
                        <td class="py-1 px-2 small">{{ $squad->tournamentTeam->team_name ?? ($squad->tournamentTeam->team->name ?? '-') }}</td>
                        <td class="py-1 px-2 small">{{ $squad->player->age ?? '-' }}</td>
                        <td class="py-1 px-2">
                            <span class="badge bg-{{ $squad->verification_status === 'verified' ? 'success' : 'warning' }} bg-opacity-25">
                                {{ ucfirst($squad->verification_status) }}
                            </span>
                        </td>
                        <td class="py-1 px-2 small">{{ $squad->registration_date?->format('M d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-2 text-muted">No players found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($players->hasPages())
    <div class="mt-2 d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $players->firstItem() }} to {{ $players->lastItem() }} of {{ $players->total() }}</small>
        {{ $players->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
