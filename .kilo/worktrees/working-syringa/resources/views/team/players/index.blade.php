@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Player List - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Registered Players</h1>
            <p class="text-muted">{{ $tournament->name }} - {{ $tournamentTeam->team_name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('team.tournament.show', $tournament->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(!$tournamentTeam->isSquadLocked())
                <a href="{{ route('team.players.create', $tournament->id) }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Add Player
                </a>
                <a href="{{ route('team.players.bulk-upload', $tournament->id) }}" class="btn btn-info">
                    <i class="fas fa-file-upload"></i> Bulk Upload
                </a>
            @endif
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Squad Status -->
    <div class="alert alert-{{ $tournamentTeam->isSquadLocked() ? 'warning' : 'info' }} mb-4">
        @if($tournamentTeam->isSquadLocked())
            <i class="fas fa-lock"></i> <strong>Squad Locked:</strong> You cannot make changes while the tournament is ongoing.
        @else
            <i class="fas fa-unlock"></i> <strong>Squad Unlocked:</strong> You can add, edit, or remove players.
        @endif
        <span class="ms-3">
            <strong>Registered:</strong> {{ $tournamentTeam->squads()->count() }} / {{ $tournament->squad_limit }} players
            @if($tournamentTeam->squads()->count() < $tournament->min_players)
                <span class="text-danger">(Minimum: {{ $tournament->min_players }})</span>
            @else
                <span class="text-success">(Minimum requirement met)</span>
            @endif
        </span>
    </div>

    <!-- Players Table -->
    <div class="card">
        <div class="card-body">
            @if($tournamentTeam->squads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Player Name</th>
                                <th>ID Type</th>
                                <th>ID Number</th>
                                <th>Date of Birth</th>
                                <th>Age</th>
                                <th>City</th>
                                <th>Position</th>
                                <th>Jersey #</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournamentTeam->squads as $squad)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $squad->player->full_name }}</strong>
                                        @if($squad->player->passport_photo_path)
                                            <i class="fas fa-id-card text-success" title="Has passport photo"></i>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($squad->player->id_type ?? 'N/A') }}</td>
                                    <td>{{ $squad->player->id_number ?? 'N/A' }}</td>
                                    <td>{{ $squad->player->date_of_birth ? $squad->player->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $squad->player->age ?? 'N/A' }}</td>
                                    <td>{{ $squad->player->city ?? 'N/A' }}</td>
                                    <td>{{ $squad->position ?? 'N/A' }}</td>
                                    <td>{{ $squad->jersey_number ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $squad->isVerified() ? 'success' : ($squad->isPending() ? 'warning' : 'danger') }}">
                                            {{ ucfirst($squad->verification_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('team.players.view', [$tournament->id, $squad->id]) }}">
                                                        <i class="fas fa-eye"></i> View Details
                                                    </a>
                                                </li>
                                                @if(!$tournamentTeam->isSquadLocked())
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('team.players.edit', [$tournament->id, $squad->id]) }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('team.players.destroy', [$tournament->id, $squad->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to remove this player?')">
                                                                <i class="fas fa-trash"></i> Remove
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4>No Players Registered</h4>
                    <p class="text-muted">Start by adding players to your squad.</p>
                    @if(!$tournamentTeam->isSquadLocked())
                        <a href="{{ route('team.players.create', $tournament->id) }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add First Player
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
