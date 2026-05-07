@extends('layouts.admin')

@section('title', 'Pool Management - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Pool Management</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.pools.create', $tournament->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Create Pool
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Auto-create Pools -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-magic me-2"></i>Quick Pool Setup</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tournaments.pools.auto-create', $tournament->id) }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Number of Pools</label>
                <input type="number" name="num_pools" class="form-control" value="2" min="2" max="10">
            </div>
            <div class="col-md-4">
                <label class="form-label">Distribution Method</label>
                <select name="method" class="form-select">
                    <option value="random">Random</option>
                    <option value="seeding">By Seed Number</option>
                    <option value="performance">By Performance</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-random me-1"></i> Auto-Create & Distribute
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Pools Grid -->
<div class="row">
    @forelse($pools as $pool)
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ $pool->name }}</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.tournaments.pools.edit', [$tournament->id, $pool->id]) }}">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a></li>
                        <li>
                            <form action="{{ route('admin.tournaments.pools.destroy', [$tournament->id, $pool->id]) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this pool?')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0 pool-teams" data-pool-id="{{ $pool->id }}">
                @php
                    $poolTeams = $teams->filter(fn($t) => $t->pool_id == $pool->id)->sortBy('pool_position');
                @endphp
                @forelse($poolTeams as $team)
                    <div class="team-item p-2 border-bottom d-flex align-items-center justify-content-between"
                         data-team-id="{{ $team->id }}"
                         draggable="true">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-grip-vertical text-muted handle"></i>
                            <span class="badge bg-secondary me-2">{{ $team->pool_position ?? '-' }}</span>
                            <span>{{ $team->team_name }}</span>
                        </div>
                        <div class="dropup">
                            <button class="btn btn-xs btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('admin.tournaments.pools.remove-team', [$tournament->id, $pool->id, $team->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item btn-sm">Remove from Pool</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-users mb-2 d-block"></i>
                        No teams assigned
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                <small class="text-muted">{{ $poolTeams->count() }} teams</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No pools created yet. Create pools to organize teams into groups.
        </div>
    </div>
    @endforelse
</div>

<!-- Unassigned Teams -->
@php
    $unassignedTeams = $teams->filter(fn($t) => is_null($t->pool_id));
@endphp
@if($unassignedTeams->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-user-plus me-2"></i>Unassigned Teams</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Status</th>
                        <th>Players</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unassignedTeams as $team)
                    <tr>
                        <td>{{ $team->team_name }}</td>
                        <td><span class="badge bg-success">{{ ucfirst($team->approval_status) }}</span></td>
                        <td>{{ $team->squads()->count() }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Assign to Pool
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($pools as $pool)
                                    <li>
                                        <form action="{{ route('admin.tournaments.pools.assign-team', [$tournament->id, $pool->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                                            <button type="submit" class="dropdown-item">{{ $pool->name }}</button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<style>
.team-item {
    cursor: grab;
    transition: background-color 0.2s;
}
.team-item:hover {
    background-color: #f8f9fa;
}
.team-item:active {
    cursor: grabbing;
}
.team-item.drag-over {
    background-color: #e9ecef;
    border: 2px dashed #0d6efd;
}
.pool-teams {
    min-height: 50px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamItems = document.querySelectorAll('.team-item');
    const poolContainers = document.querySelectorAll('.pool-teams');

    // Drag and drop
    let draggedItem = null;

    teamItems.forEach(item => {
        item.addEventListener('dragstart', function(e) {
            draggedItem = this;
            this.classList.add('opacity-50');
            e.dataTransfer.effectAllowed = 'move';
        });

        item.addEventListener('dragend', function() {
            this.classList.remove('opacity-50');
            draggedItem = null;
            document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        });
    });

    poolContainers.forEach(container => {
        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        container.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        container.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            if (draggedItem) {
                const teamId = draggedItem.dataset.teamId;
                const poolId = this.dataset.poolId;

                // Move team to new pool via AJAX
                fetch('{{ route('admin.tournaments.pools.move-team', $tournament->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        team_id: teamId,
                        pool_id: poolId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error moving team');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error moving team');
                });
            }
        });
    });
});
</script>
@endsection
