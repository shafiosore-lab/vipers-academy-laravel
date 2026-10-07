@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', $tournament->name . ' - Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $tournament->name }}</h1>
            <p class="text-muted">{{ $tournament->organization->name ?? '' }}</p>
        </div>
        <div>
            <a href="{{ route('team.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="alert alert-{{ $tournamentTeam->isApproved() ? 'success' : ($tournamentTeam->isPending() ? 'warning' : 'danger') }} mb-4">
        <strong>Registration Status:</strong>
        {{ ucfirst($tournamentTeam->approval_status) }}
        @if($tournamentTeam->isPending())
            - Your registration is pending approval from the tournament organizer.
        @elseif($tournamentTeam->isRejected())
            - {{ $tournamentTeam->rejection_reason ?? 'Your registration was rejected.' }}
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mb-0">{{ $tournamentTeam->squads()->count() }}</h4>
                    <small>Registered Players</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="mb-0">{{ $tournamentTeam->squads()->verified()->count() }}</h4>
                    <small>Verified Players</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h4 class="mb-0">{{ $tournamentTeam->squads()->pending()->count() }}</h4>
                    <small>Pending Verification</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4 class="mb-0">{{ $tournamentTeam->tournament->squad_limit }}</h4>
                    <small>Squad Limit</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Manage Squad</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('team.players', $tournament->id) }}" class="btn btn-primary">
                            <i class="fas fa-users"></i> View Players
                        </a>

                        @if(!$tournamentTeam->isSquadLocked())
                            <a href="{{ route('team.players.create', $tournament->id) }}" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Add Player
                            </a>
                            <a href="{{ route('team.players.bulk-upload', $tournament->id) }}" class="btn btn-info">
                                <i class="fas fa-file-upload"></i> Bulk Upload
                            </a>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-lock"></i> Squad is locked. Cannot make changes.
                            </div>
                        @endif

                        <a href="{{ route('team.players.export', $tournament->id) }}" class="btn btn-secondary">
                            <i class="fas fa-download"></i> Export Players
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournament Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tournament Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Season:</th>
                            <td>{{ $tournament->season ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Venue:</th>
                            <td>{{ $tournament->venue ?? 'TBA' }}</td>
                        </tr>
                        <tr>
                            <th>Start Date:</th>
                            <td>{{ $tournament->start_date ? $tournament->start_date->format('M d, Y') : 'TBA' }}</td>
                        </tr>
                        <tr>
                            <th>End Date:</th>
                            <td>{{ $tournament->end_date ? $tournament->end_date->format('M d, Y') : 'TBA' }}</td>
                        </tr>
                        <tr>
                            <th>Min Players:</th>
                            <td>{{ $tournament->min_players }}</td>
                        </tr>
                        <tr>
                            <th>Squad Limit:</th>
                            <td>{{ $tournament->squad_limit }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Team Name:</th>
                            <td>{{ $tournamentTeam->team_name }}</td>
                        </tr>
                        <tr>
                            <th>Contact Name:</th>
                            <td>{{ $tournamentTeam->team_contact_name }}</td>
                        </tr>
                        <tr>
                            <th>Contact Email:</th>
                            <td>{{ $tournamentTeam->team_contact_email }}</td>
                        </tr>
                        <tr>
                            <th>Contact Phone:</th>
                            <td>{{ $tournamentTeam->team_contact_phone }}</td>
                        </tr>
                        <tr>
                            <th>Registered:</th>
                            <td>{{ $tournamentTeam->registration_date ? $tournamentTeam->registration_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
