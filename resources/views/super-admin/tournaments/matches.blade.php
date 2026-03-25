@extends('layouts.admin')

@section('title', 'Matches - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Matches - {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Stats -->
    <div class="compact-stats-row mb-2">
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $tournament->matches()->count() }}</div>
                <div class="compact-stat-label">Total Matches</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <i class="fas fa-check-circle text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $tournament->matches()->where('status', 'completed')->count() }}</div>
                <div class="compact-stat-label">Completed</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <i class="fas fa-clock text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $tournament->matches()->where('status', 'scheduled')->count() }}</div>
                <div class="compact-stat-label">Scheduled</div>
            </div>
        </div>
        <div class="compact-stat-card">
            <div class="compact-stat-icon" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <i class="fas fa-spinner text-white"></i>
            </div>
            <div class="compact-stat-content">
                <div class="compact-stat-value">{{ $tournament->matches()->where('status', 'in_progress')->count() }}</div>
                <div class="compact-stat-label">In Progress</div>
            </div>
        </div>
    </div>

    <!-- Matches Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-1">Match Day</th>
                            <th class="py-1 px-1">Home Team</th>
                            <th class="py-1 px-1 text-center">Score</th>
                            <th class="py-1 px-1">Away Team</th>
                            <th class="py-1 px-1">Date/Time</th>
                            <th class="py-1 px-1">Venue</th>
                            <th class="py-1 px-1">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matches as $match)
                        <tr class="small">
                            <td class="py-1 align-middle small">Match {{ $match->match_day ?? '-' }}</td>
                            <td class="py-1 align-middle small">
                                @if($match->home_team_id)
                                    <span class="fw-bold">{{ $match->homeTeam->team->name ?? 'Unknown' }}</span>
                                @else
                                    <span class="text-muted">TBD</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle text-center">
                                @if($match->status === 'completed')
                                    <span class="badge bg-success">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                @elseif($match->status === 'in_progress')
                                    <span class="badge bg-primary">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                @else
                                    <span class="text-muted">vs</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                @if($match->away_team_id)
                                    <span class="fw-bold">{{ $match->awayTeam->team->name ?? 'Unknown' }}</span>
                                @else
                                    <span class="text-muted">TBD</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                @if($match->match_date)
                                    {{ $match->match_date->format('M d, Y') }}
                                    @if($match->kickoff_time)
                                        <br><span class="text-muted">{{ $match->kickoff_time }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                {{ $match->venue ?? '-' }}
                            </td>
                            <td class="py-1 align-middle">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'secondary',
                                        'in_progress' => 'primary',
                                        'completed' => 'success',
                                        'postponed' => 'warning',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$match->status] ?? 'secondary' }}-subtle text-{{ $statusColors[$match->status] ?? 'secondary' }}">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-2 text-muted small">
                                No matches scheduled yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($matches->hasPages())
    <div class="mt-2 mb-1">
        <nav>
            <ul class="pagination pagination-sm mb-0">
                {{ $matches->links('pagination::bootstrap-4') }}
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('styles')
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
    }
    .compact-stat-content {
        display: flex;
        flex-direction: column;
    }
    .compact-stat-value {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.2;
    }
    .compact-stat-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }
</style>
@endpush
