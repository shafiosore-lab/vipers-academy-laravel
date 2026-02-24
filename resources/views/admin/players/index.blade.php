@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 fw-bold">Manage Players</h1>
        <div>
            <a href="{{ route('admin.players.create') }}" class="btn btn-sm btn-primary me-1">
                <i class="fas fa-plus me-1"></i>Add Player
            </a>
            <form action="{{ route('admin.players.check.expired') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning" title="Check Expired Approvals">
                    <i class="fas fa-clock"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #ea1c4d 0%, #c31432 100%);">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $players->count() }}</div>
                            <div class="text-muted small">Total Players</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $players->filter(function($player) { return $player->isApproved(); })->count() }}</div>
                            <div class="text-muted small">Approved</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $players->where('registration_status', 'Pending')->count() }}</div>
                            <div class="text-muted small">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                <i class="fas fa-hourglass-half text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fs-4 fw-bold text-dark">{{ $players->where('approval_type', 'temporary')->count() }}</div>
                            <div class="text-muted small">Temporary</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">Player Registrations</h6>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">Name</th>
                            <th class="py-1 px-2">Position</th>
                            <th class="py-1 px-2">Age</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2">Approval</th>
                            <th class="py-1 px-2">Docs</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $player)
                        <tr class="py-1">
                            <td class="py-1 align-middle">
                                <div class="d-flex align-items-center">
                                    @if($player->image_path)
                                        <img class="rounded-circle me-2" width="28" height="28" src="{{ asset('assets/img/players/' . $player->image_path) }}" alt="Photo">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-user text-white small"></i>
                                        </div>
                                    @endif
                                    <div class="small">
                                        <div class="font-weight-bold">{{ $player->first_name }} {{ $player->last_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 align-middle small">{{ $player->position }}</td>
                            <td class="py-1 align-middle small">{{ $player->age }}</td>
                            <td class="py-1 align-middle">
                                @if($player->registration_status === 'Active')
                                    <span class="badge bg-success-subtle text-success" style="font-size: 10px;">Active</span>
                                @elseif($player->registration_status === 'Pending')
                                    <span class="badge bg-warning-subtle text-warning" style="font-size: 10px;">Pending</span>
                                @elseif($player->registration_status === 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger" style="font-size: 10px;">Rejected</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">{{ $player->registration_status }}</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @if($player->isTemporarilyApproved())
                                    <span class="badge bg-info-subtle text-info" style="font-size: 10px;">Temp</span>
                                    @if($player->isTemporaryApprovalExpired())
                                        <small class="text-danger d-block">{{ $player->getTemporaryApprovalDaysRemaining() }}d left</small>
                                    @else
                                        <small class="text-muted d-block">{{ $player->getTemporaryApprovalDaysRemaining() }}d left</small>
                                    @endif
                                @elseif($player->isFullyApproved())
                                    <span class="badge bg-success-subtle text-success" style="font-size: 10px;">Full</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">None</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @php
                                    $completedDocs = $player->documents_completed ?? 0;
                                    $totalDocs = 7;
                                @endphp
                                <div class="progress" style="height: 12px; width: 60px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($completedDocs / $totalDocs) * 100 }}%" aria-valuenow="{{ $completedDocs }}" aria-valuemin="0" aria-valuemax="{{ $totalDocs }}">
                                        {{ $completedDocs }}/{{ $totalDocs }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.players.show', $player) }}" class="btn btn-sm btn-info py-0 px-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-sm btn-warning py-0 px-1">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($player->registration_status === 'Pending')
                                            <!-- Full Approval -->
                                            <form action="{{ route('admin.players.approve', $player) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success py-0 px-1" title="Full Approval" onclick="return confirm('Grant full approval to {{ $player->full_name }}?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <!-- Temporary Approval -->
                                            <button type="button" class="btn btn-sm btn-primary py-0 px-1" title="Temporary Approval" onclick="confirmTempApproval('{{ $player->full_name }}', {{ $player->id }})">
                                                <i class="fas fa-clock"></i>
                                            </button>

                                            <!-- Reject -->
                                            <form action="{{ route('admin.players.reject', $player) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger py-0 px-1" title="Reject" onclick="return confirm('Reject {{ $player->full_name }}?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @elseif($player->isTemporarilyApproved() && !$player->isTemporaryApprovalExpired())
                                            <span class="badge bg-info-subtle text-info" style="font-size: 10px;">Temp</span>
                                        @elseif($player->isTemporaryApprovalExpired())
                                            <form action="{{ route('admin.players.reject', $player) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning py-0 px-1" title="Revoke Expired Approval" onclick="return confirm('Revoke expired temporary approval for {{ $player->full_name }}?')">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger py-0 px-1" title="Delete" onclick="return confirm('Delete {{ $player->full_name }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                            </td>
                        </tr>

                        @if($player->registration_status === 'Pending')
                            <!-- Temporary Approval Form (Inline) -->
                            <form action="{{ route('admin.players.approve.temporary', $player) }}" method="POST" class="d-inline" id="tempForm{{ $player->id }}">
                                @csrf
                                <input type="hidden" name="notes" value="Temporary approval granted for 5 working days to allow document submission.">
                            </form>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.compact-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.compact-stat-card {
    flex: 1;
    min-width: 120px;
    background: white;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.compact-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.compact-stat-content {
    display: flex;
    flex-direction: column;
}

.compact-stat-value {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
    color: #2c3e50;
}

.compact-stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
<script>
function confirmTempApproval(playerName, playerId) {
    const confirmed = confirm(`Grant temporary approval to ${playerName}?\n\nThis will give them 5 working days of full access to training and facilities. Their account will automatically return to pending status if all required documents are not submitted by the expiry date.`);

    if (confirmed) {
        document.getElementById(`tempForm${playerId}`).submit();
    }
}
</script>
@endsection
