@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Team Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Team Dashboard</h1>
            <p class="text-muted">Manage your team and tournament registrations</p>
        </div>
        <div>
            <a href="{{ route('team.tournaments.index') }}" class="btn btn-primary">
                <i class="fas fa-trophy"></i> Browse Tournaments
            </a>
        </div>
    </div>

    <!-- Team Info Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Your Team</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ $team->name }}</h4>
                    <p class="text-muted">
                        <strong>Category:</strong> {{ $team->category ?? 'N/A' }}<br>
                        <strong>Age Group:</strong> {{ $team->age_group ?? 'N/A' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted">
                        <strong>Your Role:</strong> {{ ucfirst($teamAdmin->role) }}<br>
                        <strong>Primary Contact:</strong> {{ $teamAdmin->is_primary ? 'Yes' : 'No' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournament Registrations -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tournament Registrations</h5>
        </div>
        <div class="card-body">
            @if($tournamentTeams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tournament</th>
                                <th>Status</th>
                                <th>Registration Date</th>
                                <th>Players</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournamentTeams as $tournamentTeam)
                                <tr>
                                    <td>
                                        <strong>{{ $tournamentTeam->tournament->name }}</strong><br>
                                        <small class="text-muted">{{ $tournamentTeam->tournament->organization->name ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $tournamentTeam->isApproved() ? 'success' : ($tournamentTeam->isPending() ? 'warning' : 'danger') }}">
                                            {{ ucfirst($tournamentTeam->approval_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $tournamentTeam->registration_date ? $tournamentTeam->registration_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        {{ $tournamentTeam->squads()->count() }} / {{ $tournamentTeam->tournament->squad_limit }}
                                    </td>
                                    <td>
                                        <a href="{{ route('team.tournament.show', $tournamentTeam->tournament->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-3">You haven't registered for any tournaments yet.</p>
                    <a href="{{ route('team.tournaments.index') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Find Tournaments
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
