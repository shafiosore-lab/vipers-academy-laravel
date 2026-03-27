@extends('layouts.admin')

@section('title', 'Teams - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header with Add Teams Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Teams in {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <!-- Add Teams Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-plus"></i> Add Teams
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                            <i class="fas fa-user-friends me-1"></i> Add Single Team
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                            <i class="fas fa-file-upload me-1"></i> Bulk Upload Teams
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('super-admin.tournaments.teams.template') }}">
                            <i class="fas fa-file-download me-1"></i> Download Template
                        </a>
                    </li>
                </ul>
            </div>
        </div>
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
                                @if($team->team && $team->team->organization)
                                    {{ $team->team->organization->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                            <p class="small">You are about to reject the registration for <strong>{{ $team->team_name ?? ($team->team->name ?? 'N/A') }}</strong>.</p>
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

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Teams</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super-admin.tournaments.teams.bulk-upload', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Upload an Excel file (.xlsx, .xls) or CSV file to add multiple teams at once.
                        <a href="{{ route('super-admin.tournaments.teams.template') }}" class="text-primary">Download template</a>
                    </p>

                    <div class="mb-3">
                        <label for="teams_file" class="form-label fw-bold">Select File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="teams_file" name="teams_file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Maximum file size: 5MB</div>
                    </div>

                    <div class="mb-3">
                        <label for="auto_approve" class="form-check">
                            <input type="checkbox" class="form-check-input" id="auto_approve" name="auto_approve" value="1">
                            <span class="form-check-label">Auto-approve teams after upload</span>
                        </label>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div id="uploadProgress" class="d-none">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">Uploading and processing...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload me-1"></i> Upload Teams
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeamModalLabel">Add Team to {{ $tournament->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super-admin.tournaments.teams.store', $tournament->id) }}" method="POST" id="addTeamForm">
                @csrf
                <div class="modal-body">
                    <!-- Team Selection -->
                    <div class="mb-3">
                        <label for="team_id" class="form-label fw-bold">Select Team <span class="text-danger">*</span></label>
                        <select class="form-select" id="team_id" name="team_id">
                            <option value="">-- Select a Team --</option>
                            @forelse($availableTeams as $team)
                                <option value="{{ $team->id }}">
                                    {{ $team->name }} ({{ $team->organization->name ?? 'No Organization' }})
                                </option>
                            @empty
                                <option value="">No teams available</option>
                            @endforelse
                        </select>
                        <div class="form-text">Select a team from the dropdown. Leave empty to create a new independent team.</div>
                    </div>

                    <!-- Alternative: New Team Name -->
                    <div class="mb-3">
                        <label for="new_team_name" class="form-label fw-bold">Or Enter New Team Name</label>
                        <input type="text" class="form-control" id="new_team_name" name="team_name" placeholder="Enter team name if not selecting above">
                    </div>

                    <hr>

                    <!-- Team Contact Information -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="team_contact_name" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="team_contact_name" name="team_contact_name" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="team_contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="team_contact_email" name="team_contact_email" value="{{ auth()->user()->email }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="team_contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="team_contact_phone" name="team_contact_phone" value="{{ auth()->user()->phone ?? '' }}">
                        </div>
                    </div>

                    <!-- Dynamic Location Fields -->
                    @if(!empty($locationFields))
                    <div class="card bg-light mb-3">
                        <div class="card-header py-2">
                            <h6 class="m-0 small font-weight-bold text-primary">Team Location</h6>
                            <small class="text-muted">Location level: {{ $locationLevel }}</small>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                @if(in_array('country', $locationFields))
                                <div class="col-md-6 mb-2">
                                    <label for="country" class="form-label small fw-bold">Country <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="country" name="country">
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                @if(in_array('county', $locationFields))
                                <div class="col-md-6 mb-2">
                                    <label for="county" class="form-label small fw-bold">County</label>
                                    <input type="text" class="form-control form-control-sm" id="county" name="county" placeholder="Enter county name">
                                </div>
                                @endif

                                @if(in_array('sub_county', $locationFields))
                                <div class="col-md-6 mb-2">
                                    <label for="sub_county" class="form-label small fw-bold">Sub-County</label>
                                    <input type="text" class="form-control form-control-sm" id="sub_county" name="sub_county" placeholder="Enter sub-county name">
                                </div>
                                @endif

                                @if(in_array('ward', $locationFields))
                                <div class="col-md-6 mb-2">
                                    <label for="ward" class="form-label small fw-bold">Ward</label>
                                    <input type="text" class="form-control form-control-sm" id="ward" name="ward" placeholder="Enter ward name">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Auto-approve Option -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="auto_approve" name="auto_approve" value="1">
                        <label class="form-check-label" for="auto_approve">
                            Auto-approve team registration (skip pending status)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('uploadBtn').addEventListener('click', function() {
    const fileInput = document.getElementById('teams_file');
    if (fileInput.files.length > 0) {
        document.getElementById('uploadProgress').classList.remove('d-none');
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Processing...';
    }
});
</script>
@endpush
@endsection
