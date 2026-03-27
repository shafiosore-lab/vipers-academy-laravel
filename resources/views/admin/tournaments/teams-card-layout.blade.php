@extends('layouts.admin')

@section('title', 'Teams - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-users text-primary"></i>{{ $tournament->name }}
                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </h4>
                <small class="text-muted">{{ $tournament->organization->name ?? 'N/A' }} • {{ $tournament->season ?? 'No season' }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filters
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Status Filters</h6></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                        <i class="fas fa-circle text-secondary me-2"></i>All Teams
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}">
                        <i class="fas fa-circle text-success me-2"></i>Approved
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">
                        <i class="fas fa-circle text-warning me-2"></i>Pending
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}">
                        <i class="fas fa-circle text-danger me-2"></i>Rejected
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Actions</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.teams.create', $tournament->id) }}">
                        <i class="fas fa-plus me-2"></i>Add Team
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.teams.index', $tournament->id) }}">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a></li>
                </ul>
            </div>
            <a href="{{ route('admin.tournaments.standings.index', $tournament->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-list-ol me-2"></i>Standings
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Teams Summary Cards -->
<div class="tournament-card-row mb-4">
    <!-- Total Teams Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Total Teams'"
        :subtitle="'All registered teams'"
        :value="$teams->count()"
        :subvalue="'of ' . ($tournament->max_teams ?? '∞') . ' max'"
        :icon="'fa-users'"
        :color="'primary'"
        :trend="[
            'color' => $teams->count() >= 8 ? 'success' : 'warning',
            'icon' => $teams->count() >= 8 ? 'check-circle' : 'exclamation-triangle',
            'text' => $teams->count() >= 8 ? 'Target met' : 'Need more teams'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Format: {{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</small>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>All Teams
                    </a>
                    <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="btn btn-outline-success">
                        <i class="fas fa-plus me-1"></i>Add Team
                    </a>
                </div>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Approved Teams Card -->
    @php
        $approvedCount = $teams->where('pivot.status', 'approved')->count();
        $pendingCount = $teams->where('pivot.status', 'pending')->count();
        $rejectedCount = $teams->where('pivot.status', 'rejected')->count();
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Approved Teams'"
        :subtitle="'Teams ready to compete'"
        :value="$approvedCount"
        :subvalue="'teams approved'"
        :icon="'fa-check-circle'"
        :color="'success'"
        :trend="[
            'color' => $approvedCount >= 8 ? 'success' : 'warning',
            'icon' => $approvedCount >= 8 ? 'check-circle' : 'exclamation-triangle',
            'text' => $approvedCount >= 8 ? 'Full league' : 'Need approvals'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Pending: {{ $pendingCount }}</small>
                <small class="text-muted">Rejected: {{ $rejectedCount }}</small>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Pending Approvals Card -->
    <x-tournament-cards.tournament-summary-card
        :title="'Pending Approvals'"
        :subtitle="'Teams awaiting approval'"
        :value="$pendingCount"
        :subvalue="'teams to review'"
        :icon="'fa-clock'"
        :color="'warning'"
        :trend="[
            'color' => $pendingCount > 0 ? 'warning' : 'success',
            'icon' => $pendingCount > 0 ? 'clock' : 'check-circle',
            'text' => $pendingCount > 0 ? 'Action needed' : 'All approved'
        ]"
    >
        <x-slot:footer>
            <div class="text-center">
                @if($pendingCount > 0)
                    <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}?status=pending" class="btn btn-warning btn-sm">
                        <i class="fas fa-eye me-1"></i>Review Teams
                    </a>
                @else
                    <small class="text-muted">No pending approvals</small>
                @endif
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>

    <!-- Squad Registration Card -->
    @php
        $totalSquads = 0;
        $teamsWithSquads = 0;
        foreach($teams as $team) {
            $squadCount = $team->squads()->where('tournament_id', $tournament->id)->count();
            if($squadCount > 0) {
                $teamsWithSquads++;
            }
            $totalSquads += $squadCount;
        }
    @endphp
    <x-tournament-cards.tournament-summary-card
        :title="'Squad Registration'"
        :subtitle="'Player registrations'"
        :value="$totalSquads"
        :subvalue="'players registered'"
        :icon="'fa-user-plus'"
        :color="'info'"
        :trend="[
            'color' => $teamsWithSquads >= $approvedCount ? 'success' : 'warning',
            'icon' => $teamsWithSquads >= $approvedCount ? 'users' : 'exclamation-triangle',
            'text' => $teamsWithSquads >= $approvedCount ? 'All squads ready' : 'Squad registration needed'
        ]"
    >
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ $teamsWithSquads }}/{{ $approvedCount }} teams have squads</small>
                <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, 0]) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-users me-1"></i>Manage Squads
                </a>
            </div>
        </x-slot:footer>
    </x-tournament-cards.tournament-summary-card>
</div>

<!-- Teams Cards Grid -->
@if($teams->count() > 0)
    <div class="tournament-card-grid">
        @foreach($teams as $team)
            <div class="card tournament-card shadow-sm border-0 h-100">
                <!-- Card Header with Team Info -->
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-start gap-3">
                            <div class="team-badge bg-{{ $team->pivot->status == 'approved' ? 'success' : ($team->pivot->status == 'pending' ? 'warning' : 'danger') }} rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-{{ $team->pivot->status == 'approved' ? 'check' : ($team->pivot->status == 'pending' ? 'clock' : 'times') }} text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $team->team_name }}</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">{{ $team->organization->name ?? 'N/A' }}</span>
                                    <span class="badge bg-{{ $team->pivot->status == 'approved' ? 'success' : ($team->pivot->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($team->pivot->status) }}
                                    </span>
                                    @if($team->pivot->status == 'approved')
                                        <span class="badge bg-info">
                                            <i class="fas fa-users me-1"></i>{{ $team->squads()->where('tournament_id', $tournament->id)->count() }} players
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.teams.show', $team->id) }}">
                                    <i class="fas fa-eye text-primary me-2"></i>Team Profile
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}">
                                    <i class="fas fa-users text-success me-2"></i>Squad Management
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Registration Status</h6></li>
                                @if($team->pivot->status == 'pending')
                                    <li>
                                        <form action="{{ route('admin.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="fas fa-check me-2"></i>Approve Team
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.tournaments.teams.reject', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-times me-2"></i>Reject Team
                                            </button>
                                        </form>
                                    </li>
                                @elseif($team->pivot->status == 'approved')
                                    <li>
                                        <form action="{{ route('admin.tournaments.teams.unapprove', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning">
                                                <i class="fas fa-times me-2"></i>Unapprove Team
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.tournaments.teams.destroy', [$tournament->id, $team->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Remove this team from tournament?')">
                                            <i class="fas fa-trash me-2"></i>Remove Team
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Card Body with Team Details -->
                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- Team Information -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-info-circle text-info"></i>
                                <span class="fw-semibold">Team Information</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small text-muted">Founded</div>
                                        <div class="h6 mb-0">{{ $team->founded_year ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Category</div>
                                        <div class="h6 mb-0">{{ ucfirst($team->category ?? 'N/A') }}</div>
                                    </div>
                                </div>
                                @if($team->contact_email)
                                    <div class="mt-2">
                                        <div class="small text-muted">Contact</div>
                                        <div class="h6 mb-0">{{ $team->contact_email }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Squad Information -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-users text-success"></i>
                                <span class="fw-semibold">Squad Status</span>
                            </div>
                            <div class="ms-4">
                                @php
                                    $squadCount = $team->squads()->where('tournament_id', $tournament->id)->count();
                                    $maxPlayers = $tournament->max_players_per_team ?? 25;
                                @endphp
                                <div class="row">
                                    <div class="col-6">
                                        <div class="small text-muted">Registered Players</div>
                                        <div class="h6 mb-0">{{ $squadCount }}</div>
                                        <small class="text-muted">of {{ $maxPlayers }} max</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Status</div>
                                        <span class="badge bg-{{ $squadCount > 0 ? 'success' : 'warning' }}">
                                            {{ $squadCount > 0 ? 'Complete' : 'Incomplete' }}
                                        </span>
                                    </div>
                                </div>
                                @if($squadCount > 0)
                                    <div class="mt-2">
                                        <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Squad
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Registration Details -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-primary"></i>
                                <span class="fw-semibold">Registration Details</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="small text-muted">Registered</div>
                                        <div class="h6 mb-0">{{ \Carbon\Carbon::parse($team->pivot->created_at)->format('M d, Y') }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-muted">Status</div>
                                        <span class="badge bg-{{ $team->pivot->status == 'approved' ? 'success' : ($team->pivot->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($team->pivot->status) }}
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-muted">Fee Paid</div>
                                        <span class="badge bg-{{ $team->pivot->registration_fee_paid ? 'success' : 'danger' }}">
                                            {{ $team->pivot->registration_fee_paid ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Documents -->
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-file-alt text-warning"></i>
                                <span class="fw-semibold">Required Documents</span>
                            </div>
                            <div class="ms-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Registration Form</div>
                                        <span class="badge bg-{{ $team->pivot->registration_form_submitted ? 'success' : 'danger' }}">
                                            {{ $team->pivot->registration_form_submitted ? 'Submitted' : 'Pending' }}
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Player List</div>
                                        <span class="badge bg-{{ $team->pivot->player_list_submitted ? 'success' : 'danger' }}">
                                            {{ $team->pivot->player_list_submitted ? 'Submitted' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer bg-light border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.teams.show', $team->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Team Profile
                            </a>
                            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}?team={{ $team->id }}" class="btn btn-outline-info">
                                <i class="fas fa-futbol me-1"></i>Matches
                            </a>
                            <a href="{{ route('admin.tournaments.squads.index', [$tournament->id, $team->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i>Squad
                            </a>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                Last updated: {{ \Carbon\Carbon::parse($team->pivot->updated_at)->format('M d, Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($teams->hasPages())
        <div class="card tournament-card mt-4 shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            Showing {{ $teams->firstItem() }} to {{ $teams->lastItem() }} of {{ $teams->total() }} teams
                        </span>
                    </div>
                    <div>
                        {{ $teams->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <!-- Empty State -->
    <div class="card tournament-card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No Teams Registered</h4>
            <p class="text-muted mb-4">No teams have registered for this tournament yet. You can:</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-circle text-primary me-2"></i>Invite teams to register</li>
                        <li><i class="fas fa-circle text-success me-2"></i>Add teams manually</li>
                        <li><i class="fas fa-circle text-info me-2"></i>Wait for team applications</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admin.tournaments.teams.create', $tournament->id) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Add First Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Quick Actions Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card tournament-card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Add Team'"
                            :subtitle="'Register new team'"
                            :icon="'fa-plus-circle'"
                            :color="'primary'"
                            :description="'Add teams manually to the tournament'"
                            :actions="[
                                ['url' => route('admin.tournaments.teams.create', $tournament->id), 'label' => 'Add Team', 'icon' => 'fa-plus', 'style' => 'primary']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Manual team registration</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Manage Squads'"
                            :subtitle="'Player registration'"
                            :icon="'fa-users'"
                            :color="'success'"
                            :description="'Manage player registrations for all teams'"
                            :actions="[
                                ['url' => route('admin.tournaments.squads.index', [$tournament->id, 0]), 'label' => 'Squad Management', 'icon' => 'fa-users', 'style' => 'success']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">{{ $totalSquads }} players registered</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Review Applications'"
                            :subtitle="'Pending approvals'"
                            :icon="'fa-clock'"
                            :color="'warning'"
                            :description="'Review and approve team applications'"
                            :actions="[
                                ['url' => route('admin.tournaments.teams.index', $tournament->id) . '?status=pending', 'label' => 'Review Teams', 'icon' => 'fa-eye', 'style' => 'warning']
                            ]"
                            :badge="['color' => $pendingCount > 0 ? 'warning' : 'success', 'text' => $pendingCount . ' pending']"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Action required</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                    <div class="col-md-3">
                        <x-tournament-cards.action-card
                            :title="'Export Teams'"
                            :subtitle="'Download team data'"
                            :icon="'fa-download'"
                            :color="'info'"
                            :description="'Export team information for external use'"
                            :actions="[
                                ['url' => route('admin.tournaments.teams.export', [$tournament->id, 'csv']), 'label' => 'Export CSV', 'icon' => 'fa-file-csv', 'style' => 'info'],
                                ['url' => route('admin.tournaments.teams.export', [$tournament->id, 'pdf']), 'label' => 'Export PDF', 'icon' => 'fa-file-pdf', 'style' => 'danger']
                            ]"
                        >
                            <x-slot:footer>
                                <div class="text-center">
                                    <small class="text-muted">Data export options</small>
                                </div>
                            </x-slot:footer>
                        </x-tournament-cards.action-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for teams cards */
.tournament-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.team-badge {
    border: 3px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tournament-card-grid {
        grid-template-columns: 1fr;
    }

    .card-body .row.g-3 {
        flex-direction: column;
    }

    .card-body .row.g-3 .col-md-6, .card-body .row.g-3 .col-md-12 {
        width: 100%;
    }

    .team-badge {
        width: 40px;
        height: 40px;
    }

    .team-badge i {
        font-size: 1.2rem;
    }
}

/* Hover effects for teams cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Status-specific styling */
.team-badge.bg-success {
    border-color: #28a745;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.team-badge.bg-warning {
    border-color: #ffc107;
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.team-badge.bg-danger {
    border-color: #dc3545;
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
}

/* Animation for team cards */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tournament-card {
    animation: fadeInSlide 0.4s ease-out;
}

/* Status indicator animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}
</style>

<script>
// Teams page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Teams card layout initialized');

    // Add click functionality to team cards
    const teamCards = document.querySelectorAll('.tournament-card');
    teamCards.forEach(card => {
        const teamLink = card.querySelector('.btn-outline-primary');
        if (teamLink) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons or dropdowns
                if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.dropdown')) {
                    return;
                }
                window.location.href = teamLink.href;
            });
        }
    });

    // Add confirmation for team removal actions
    const removeButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Remove this team from the tournament? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Add success feedback for approval actions
    const approvalForms = document.querySelectorAll('form[action*="approve"], form[action*="reject"], form[action*="unapprove"]');
    approvalForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add loading state
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        });
    });
});
</script>

@endsection
