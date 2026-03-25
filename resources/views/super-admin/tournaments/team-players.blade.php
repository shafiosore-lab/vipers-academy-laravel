@extends('layouts.admin')

@section('title', ($team->team_name ?? $team->team->name ?? 'Team') . ' Players - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-1">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h6 class="mb-0">{{ $team->team_name ?? ($team->team->name ?? 'Team') }}</h6>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a> /
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a> /
                <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}">Teams</a> /
                {{ $team->team_name ?? $team->team->name ?? 'Team' }}
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Compact Stats Row -->
    <div class="row mb-2 g-1">
        <div class="col-3">
            <div class="bg-primary bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $team->squads()->count() }}</div>
                <small class="text-primary">Total</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-success bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $team->squads()->where('verification_status', 'verified')->count() }}</div>
                <small class="text-success">Verified</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-warning bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $team->squads()->where('verification_status', 'pending')->count() }}</div>
                <small class="text-warning">Pending</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-danger bg-opacity-10 p-2 rounded text-center">
                <div class="h5 mb-0">{{ $team->squads()->where('verification_status', 'rejected')->count() }}</div>
                <small class="text-danger">Rejected</small>
            </div>
        </div>
    </div>

    <!-- Players by Position - Symmetrical Grid -->
    <div class="row g-1 mb-1">
        @forelse($groupedPlayers as $position => $players)
        <div class="col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-1 px-2 d-flex align-items-center">
                    <span class="me-auto">
                        @if($position === 'Goalkeeper')
                            <i class="fas fa-hand-sparkles text-success me-1"></i>
                        @elseif($position === 'Defender')
                            <i class="fas fa-shield text-primary me-1"></i>
                        @elseif($position === 'Midfielder')
                            <i class="fas fa-bolt text-warning me-1"></i>
                        @else
                            <i class="fas fa-futbol text-danger me-1"></i>
                        @endif
                        <strong>{{ $position }}s</strong>
                    </span>
                    <span class="badge bg-secondary">{{ $players->count() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="py-1 px-1">#</th>
                                <th class="py-1 px-1">Player</th>
                                <th class="py-1 px-1">#</th>
                                <th class="py-1 px-1">Age</th>
                                <th class="py-1 px-1">Status</th>
                                <th class="py-1 px-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $index => $squad)
                            <tr>
                                <td class="py-1 px-1 small">{{ $index + 1 }}</td>
                                <td class="py-1 px-1">{{ $squad->player->full_name ?? 'N/A' }}</td>
                                <td class="py-1 px-1">#{{ $squad->jersey_number }}</td>
                                <td class="py-1 px-1 small">{{ $squad->player->age ?? '-' }}</td>
                                <td class="py-1 px-1">
                                    @if($squad->verification_status === 'verified')
                                        <span class="badge bg-success bg-opacity-25 text-success">Verified</span>
                                    @elseif($squad->verification_status === 'pending')
                                        <span class="badge bg-warning bg-opacity-25 text-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-25 text-danger">Rejected</span>
                                    @endif
                                </td>
                                <td class="py-1 px-1">
                                    <div class="btn-group btn-group-sm">
                                        @if($squad->verification_status !== 'verified')
                                        <form action="{{ route('super-admin.tournaments.teams.players.approve', [$tournament->id, $team->id, $squad->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm py-0 px-1" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($squad->verification_status !== 'rejected')
                                        <button type="button" class="btn btn-warning btn-sm py-0 px-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $squad->id }}" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <form action="{{ route('super-admin.tournaments.teams.players.destroy', [$tournament->id, $team->id, $squad->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm py-0 px-1" title="Remove">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reject Modals -->
        @foreach($players as $squad)
        @if($squad->verification_status !== 'rejected')
        <div class="modal fade" id="rejectModal{{ $squad->id }}" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <form action="{{ route('super-admin.tournaments.teams.players.reject', [$tournament->id, $team->id, $squad->id]) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header py-1">
                            <h6 class="modal-title">Reject</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-1">
                            <p class="small mb-1">Reject <strong>{{ $squad->player->full_name ?? 'N/A' }}</strong>?</p>
                            <textarea name="verification_notes" class="form-control form-control-sm" rows="2" required placeholder="Reason..."></textarea>
                        </div>
                        <div class="modal-footer py-1">
                            <button type="button" class="btn btn-secondary btn-sm py-0" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning btn-sm py-0">Reject</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endforeach
        @empty
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-2 text-muted">No players found</div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
