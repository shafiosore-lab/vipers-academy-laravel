@extends('layouts.admin')

@section('title', 'Tournaments')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Tournaments</h4>
            <small class="text-muted">Manage tournament competitions</small>
        </div>
        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Create Tournament
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-2">Tournament</th>
                    <th class="py-2">Organization</th>
                    <th class="py-2">Season</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Teams</th>
                    <th class="py-2">Dates</th>
                    <th class="py-2 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tournaments as $tournament)
                    <tr>
                        <td class="py-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-1 me-2">
                                    <i class="fas fa-trophy text-primary"></i>
                                </div>
                                <div>
                                    <strong>{{ $tournament->name }}</strong>
                                    <div class="small text-muted">{{ $tournament->venue ?? 'No venue' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2">{{ $tournament->organization->name ?? 'N/A' }}</td>
                        <td class="py-2">{{ $tournament->season ?? '-' }}</td>
                        <td class="py-2">
                            @php
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'open' => 'success',
                                    'closed' => 'warning',
                                    'ongoing' => 'primary',
                                    'completed' => 'info',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                        </td>
                        <td class="py-2">{{ $tournament->teams()->count() }} / {{ $tournament->max_teams ?? '∞' }}</td>
                        <td class="py-2">
                            @if($tournament->start_date)
                                {{ \Carbon\Carbon::parse($tournament->start_date)->format('M d') }}
                                @if($tournament->end_date)
                                    - {{ \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-2 text-end">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form action="{{ route('admin.tournaments.destroy', $tournament->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tournament?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No tournaments found. Create your first tournament to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tournaments->hasPages())
    <div class="card-footer">
        {{ $tournaments->links() }}
    </div>
    @endif
</div>
@endsection
