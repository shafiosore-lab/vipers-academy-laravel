@extends('layouts.admin')

@section('title', 'Teams - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">{{ $tournament->name }} - Teams</h4>
                <small class="text-muted">Manage registered teams</small>
            </div>
        </div>
        <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="btn btn-primary btn-sm">+ Add Team</a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-2">Team</th>
                    <th class="py-2">Contact</th>
                    <th class="py-2">Players</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Registered</th>
                    <th class="py-2 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                    <tr>
                        <td class="py-2">
                            <strong>{{ $team->team_name }}</strong>
                            @if($team->team)<div class="small text-muted">{{ $team->team->organization->name ?? '' }}</div>@endif
                        </td>
                        <td class="py-2">
                            <div class="small">{{ $team->team_contact_name }}</div>
                            <div class="small text-muted">{{ $team->team_contact_phone }}</div>
                        </td>
                        <td class="py-2">
                            <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}">{{ $team->squads()->count() }} players</a>
                        </td>
                        <td class="py-2">
                            @php $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'correction_requested' => 'info']; @endphp
                            <span class="badge bg-{{ $statusColors[$team->approval_status] ?? 'secondary' }}">
                                {{ ucfirst($team->approval_status) }}
                            </span>
                        </td>
                        <td class="py-2">{{ $team->registration_date ? \Carbon\Carbon::parse($team->registration_date)->format('M d, Y') : '-' }}</td>
                        <td class="py-2 text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.tournaments.teams.show', [$tournament->id, $team->id]) }}" class="btn btn-outline-primary">View</a>
                                <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-info">Squad</a>
                                <a href="{{ route('admin.tournaments.teams.edit', [$tournament->id, $team->id]) }}" class="btn btn-outline-secondary">Edit</a>
                            </div>
                            @if($team->approval_status === 'pending')
                                <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                    @csrf <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">No teams registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teams->hasPages())<div class="card-footer">{{ $teams->links() }}</div>@endif
</div>
@endsection
