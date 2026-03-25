@extends('layouts.admin')

@section('title', 'Teams - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Teams in {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Teams Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-2 px-2">Team</th>
                            <th class="py-2 px-2">Organization</th>
                            <th class="py-2 px-2">Contact Person</th>
                            <th class="py-2 px-2">Players</th>
                            <th class="py-2 px-2">Status</th>
                            <th class="py-2 px-2">Registered</th>
                            <th class="py-2 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                        <tr>
                            <td class="py-2 align-middle">
                                @php
                                    $displayName = $team->team_name ?? ($team->team->name ?? 'Unknown Team');
                                @endphp
                                <a href="{{ route('super-admin.tournaments.teams.players.index', [$tournament->id, $team->id]) }}" class="fw-bold text-decoration-none">{{ $displayName }}</a>
                            </td>
                            <td class="py-2 align-middle small">
                                {{ $team->team->organization->name ?? '-' }}
                            </td>
                            <td class="py-2 align-middle small">
                                {{ $team->team_contact_name ?? '-' }}
                            </td>
                            <td class="py-2 align-middle">
                                <a href="{{ route('super-admin.tournaments.teams.players.index', [$tournament->id, $team->id]) }}" class="badge bg-info text-decoration-none">{{ $team->squads->count() }} / {{ $tournament->squad_limit }}</a>
                            </td>
                            <td class="py-2 align-middle">
                                @php
                                    $approvalColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $approvalColors[$team->approval_status] ?? 'secondary' }}-subtle text-{{ $approvalColors[$team->approval_status] ?? 'secondary' }}">
                                    {{ ucfirst($team->approval_status) }}
                                </span>
                            </td>
                            <td class="py-2 align-middle small">
                                {{ $team->registration_date->format('M d, Y') }}
                            </td>
                            <td class="py-2 align-middle">
                                @if($team->approval_status === 'pending')
                                    <form action="{{ route('super-admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success py-0 px-1" title="Approve">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger py-0 px-1" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $team->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @else
                                    <span class="text-muted small">No actions</span>
                                @endif
                            </td>
                        </tr>
                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $team->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('super-admin.tournaments.teams.reject', [$tournament->id, $team->id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title small">Reject Team Registration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="small">You are about to reject the registration for <strong>{{ $team->team->name ?? 'N/A' }}</strong>.</p>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                                <textarea name="rejection_reason" class="form-control form-control-sm" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">
                                <i class="fas fa-users fa-2x mb-2 d-block opacity-50"></i>
                                No teams found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($teams->hasPages())
    <div class="mt-3">
        {{ $teams->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
