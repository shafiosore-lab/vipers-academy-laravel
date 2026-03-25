@extends('layouts.admin')

@section('title', $team->team_name)

@section('header')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0">{{ $team->team_name }}</h4>
            <small class="text-muted">{{ $tournament->name }}</small>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Team Info -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    Team Details
                    <a href="{{ route('admin.tournaments.teams.edit', [$tournament->id, $team->id]) }}" class="btn btn-xs btn-outline-secondary ms-2">Edit</a>
                </h6>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'correction_requested' => 'info',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$team->approval_status] ?? 'secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $team->approval_status)) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Contact Name</div>
                    <div class="col-8">{{ $team->team_contact_name ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Contact Email</div>
                    <div class="col-8">{{ $team->team_contact_email ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Contact Phone</div>
                    <div class="col-8">{{ $team->team_contact_phone ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted small">Registration Date</div>
                    <div class="col-8">{{ $team->registration_date ? \Carbon\Carbon::parse($team->registration_date)->format('M d, Y') : '-' }}</div>
                </div>

                @if($team->approval_status === 'rejected' && $team->rejection_reason)
                    <div class="mt-2 pt-2 border-top">
                        <div class="text-danger small">Rejection Reason</div>
                        <div class="small">{{ $team->rejection_reason }}</div>
                    </div>
                @endif

                @if($team->approval_status === 'correction_requested' && $team->correction_notes)
                    <div class="mt-2 pt-2 border-top">
                        <div class="text-warning small">Correction Notes</div>
                        <div class="small">{{ $team->correction_notes }}</div>
                    </div>
                @endif

                @if($team->approval_status === 'pending')
                    <div class="mt-3 pt-2 border-top d-flex gap-2">
                        <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button type="button" onclick="showRejectModal()" class="btn btn-sm btn-danger">Reject</button>
                        <button type="button" onclick="showCorrectionModal()" class="btn btn-sm btn-warning">Request Correction</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Squad Stats -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Squad Summary</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-users"></i> Manage
                    </a>
                    <a href="{{ route('admin.tournaments.squads.create', [$tournament->id, $team->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h4 mb-0">{{ $team->squads()->count() }}</div>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0 text-success">{{ $team->squads()->where('verification_status', 'verified')->count() }}</div>
                        <small class="text-muted">Verified</small>
                    </div>
                    <div class="col-4">
                        <div class="h4 mb-0 text-warning">{{ $team->squads()->where('verification_status', 'pending')->count() }}</div>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Squad Table -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Squad Players</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Player Name</th>
                    <th>ID Number</th>
                    <th>Position</th>
                    <th>Jersey #</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $squadMembers = $team->squads()->with('player')->get();
                @endphp
                @forelse($squadMembers as $squad)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $squad->player->full_name }}</strong>
                        @if($squad->player->date_of_birth)
                        <div class="small text-muted">DOB: {{ \Carbon\Carbon::parse($squad->player->date_of_birth)->format('M d, Y') }}</div>
                        @endif
                    </td>
                    <td>{{ $squad->player->id_number ?? 'N/A' }}</td>
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
                            @csrf
                            <button type="submit" class="btn btn-xs btn-success">Verify</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.tournaments.squads.destroy', [$tournament->id, $team->id, $squad->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove player from squad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">
                        No players in squad yet.
                        <a href="{{ route('admin.tournaments.squads.create', [$tournament->id, $team->id]) }}">Add First Player</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Team</h5>
                <button type="button" class="btn-close" onclick="hideRejectModal()"></button>
            </div>
            <form action="{{ route('admin.tournaments.teams.reject', [$tournament->id, $team->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Correction Modal -->
<div id="correctionModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Correction</h5>
                <button type="button" class="btn-close" onclick="hideCorrectionModal()"></button>
            </div>
            <form action="{{ route('admin.tournaments.teams.request-correction', [$tournament->id, $team->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="correction_notes" class="form-label">Correction Notes</label>
                        <textarea name="correction_notes" id="correction_notes" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCorrectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
function hideRejectModal() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
    if (modal) modal.hide();
}
function showCorrectionModal() {
    var modal = new bootstrap.Modal(document.getElementById('correctionModal'));
    modal.show();
}
function hideCorrectionModal() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('correctionModal'));
    if (modal) modal.hide();
}
</script>
@endsection
