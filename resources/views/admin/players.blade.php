@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Players</h1>
        <div>
            <a href="{{ route('admin.players.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Player
            </a>
            <form action="{{ route('admin.players.check.expired') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-clock me-2"></i>Check Expired Approvals
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Players</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $players->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $players->filter(function($player) { return $player->isApproved(); })->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $players->where('registration_status', 'Pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Temporary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $players->where('approval_type', 'temporary')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Player Registrations</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Approval Type</th>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $player)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($player->photo)
                                        <img class="rounded-circle mr-2" width="40" height="40" src="{{ asset('storage/' . $player->photo) }}" alt="Photo">
                                    @else
                                        <div class="bg-secondary rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $player->name }}</div>
                                        <small class="text-muted">{{ $player->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $player->position }}</td>
                            <td>{{ $player->age }} years</td>
                            <td>
                                @if($player->registration_status === 'Active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($player->registration_status === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($player->registration_status === 'Rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ $player->registration_status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($player->hasTemporaryApproval())
                                    <span class="badge badge-info">Temporary</span>
                                    @if($player->isTemporaryApprovalExpired())
                                        <br><small class="text-danger">Expired</small>
                                    @else
                                        <br><small class="text-muted">{{ $player->getTemporaryApprovalDaysRemaining() }} days left</small>
                                    @endif
                                @elseif($player->hasFullApproval())
                                    <span class="badge badge-success">Full</span>
                                @else
                                    <span class="badge badge-secondary">None</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $requiredDocs = ['guardian_consent_form', 'participation_agreement', 'data_consent_form', 'birth_certificate', 'medical_certificate', 'guardian_id_document', 'player_id_document'];
                                    $completedDocs = 0;
                                    foreach($requiredDocs as $doc) {
                                        if($player->$doc) $completedDocs++;
                                    }
                                @endphp
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ ($completedDocs / count($requiredDocs)) * 100 }}%" aria-valuenow="{{ $completedDocs }}" aria-valuemin="0" aria-valuemax="{{ count($requiredDocs) }}">
                                        {{ $completedDocs }}/{{ count($requiredDocs) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.players.show', $player) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($player->registration_status === 'Pending')
                                        <!-- Full Approval -->
                                        <form action="{{ route('admin.players.approve', $player) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success" title="Full Approval" onclick="return confirm('Grant full approval to {{ $player->name }}?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <!-- Temporary Approval -->
                                        <button type="button" class="btn btn-sm btn-primary" title="Temporary Approval" onclick="confirmTempApproval('{{ $player->name }}', {{ $player->id }})">
                                            <i class="fas fa-clock"></i>
                                        </button>

                                        <!-- Reject -->
                                        <form action="{{ route('admin.players.reject', $player) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm('Reject {{ $player->name }}?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @elseif($player->hasTemporaryApproval() && !$player->isTemporaryApprovalExpired())
                                        <span class="badge badge-info">Active (Temp)</span>
                                    @elseif($player->isTemporaryApprovalExpired())
                                        <form action="{{ route('admin.players.reject', $player) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Revoke Expired Approval" onclick="return confirm('Revoke expired temporary approval for {{ $player->name }}?')">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete {{ $player->name }}?')">
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
                                @method('PUT')
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
<script>
function confirmTempApproval(playerName, playerId) {
    const confirmed = confirm(`Grant temporary approval to ${playerName}?\n\nThis will give them 5 working days of full access to training and facilities. Their account will automatically return to pending status if all required documents are not submitted by the expiry date.`);

    if (confirmed) {
        document.getElementById(`tempForm${playerId}`).submit();
    }
}
</script>
@endsection
