@extends('layouts.admin')

@section('title', 'Standings - ' . $tournament->name . ' - Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Standings - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Standings Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">League Table</h6>
        </div>
        <div class="card-body p-0">
            @if($tournament->standings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-2 px-2 text-center">Pos</th>
                            <th class="py-2 px-2">Team</th>
                            <th class="py-2 px-2 text-center">P</th>
                            <th class="py-2 px-2 text-center">W</th>
                            <th class="py-2 px-2 text-center">D</th>
                            <th class="py-2 px-2 text-center">L</th>
                            <th class="py-2 px-2 text-center">GF</th>
                            <th class="py-2 px-2 text-center">GA</th>
                            <th class="py-2 px-2 text-center">GD</th>
                            <th class="py-2 px-2 text-center">Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tournament->standings->sortBy('position') as $standing)
                        <tr>
                            <td class="py-2 align-middle text-center">
                                <span class="badge bg-{{ $standing->position <= 3 ? 'success' : 'secondary' }}">
                                    {{ $standing->position }}
                                </span>
                            </td>
                            <td class="py-2 align-middle">
                                @php
                                    // Try to get team name from related Team model first, then fall back to team_name field
                                    $teamName = null;
                                    if ($standing->team) {
                                        if ($standing->team->team && isset($standing->team->team->name)) {
                                            $teamName = $standing->team->team->name;
                                        } elseif (isset($standing->team->team_name)) {
                                            $teamName = $standing->team->team_name;
                                        }
                                    }
                                @endphp
                                <span class="fw-bold">{{ $teamName ?? 'N/A' }}</span>
                            </td>
                            <td class="py-2 align-middle text-center">{{ $standing->played }}</td>
                            <td class="py-2 align-middle text-center">{{ $standing->won }}</td>
                            <td class="py-2 align-middle text-center">{{ $standing->drawn }}</td>
                            <td class="py-2 align-middle text-center">{{ $standing->lost }}</td>
                            <td class="py-2 align-middle text-center">{{ $standing->goals_for }}</td>
                            <td class="py-2 align-middle text-center">{{ $standing->goals_against }}</td>
                            <td class="py-2 align-middle text-center">
                                <span class="{{ $standing->goal_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $standing->goal_difference > 0 ? '+' : '' }}{{ $standing->goal_difference }}
                                </span>
                            </td>
                            <td class="py-2 align-middle text-center fw-bold">{{ $standing->points }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-list-ol fa-3x mb-3 d-block opacity-50"></i>
                <p>No standings available yet.</p>
                <small>Standings will be calculated once the tournament starts and matches are played.</small>
            </div>
            @endif
        </div>
    </div>

    <!-- Tournament Summary -->
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">Tournament Summary</h6>
        </div>
        <div class="card-body py-2">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="h4 mb-0">{{ $tournament->approvedTeams()->count() }}</div>
                    <small class="text-muted">Teams</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 mb-0">{{ $tournament->squads()->count() }}</div>
                    <small class="text-muted">Players</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 mb-0">{{ $tournament->matches()->count() }}</div>
                    <small class="text-muted">Matches</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 mb-0">{{ $tournament->matches()->where('status', 'completed')->count() }}</div>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
