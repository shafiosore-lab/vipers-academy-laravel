@extends('layouts.admin')

@section('title', 'Pool Reshuffle - ' . $tournament->name)

@php
    // Determine route prefix based on URL or use admin as default
    $requestPath = request()->path();
    $isSuperAdmin = str_contains($requestPath, 'super-admin');
    $routePrefix = $isSuperAdmin ? 'super-admin.tournaments.pools' : 'admin.tournaments.pools';
    $tournamentRoutePrefix = $isSuperAdmin ? 'super-admin.tournaments' : 'admin.tournaments';
@endphp

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-shuffle me-2"></i>
                            Pool Reshuffle - {{ $tournament->name }}
                        </h5>
                        <small class="text-white-50">FIFA-style team distribution with drag-and-drop</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Reshuffle Counter -->
                        <div class="badge bg-{{ $reshuffleCount >= $maxReshuffles ? 'danger' : 'warning' }} fs-6">
                            <i class="fas fa-redo me-1"></i>
                            Reshuffles: {{ $reshuffleCount }}/{{ $maxReshuffles }}
                        </div>
                        <a href="{{ route($tournamentRoutePrefix . '.show', $tournament->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Tournament
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reshuffle Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <!-- Reshuffle Button -->
                        <form method="POST" action="{{ route($routePrefix . '.reshuffle.perform', $tournament->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary" {{ $reshuffleCount >= $maxReshuffles ? 'disabled' : '' }}>
                                <i class="fas fa-shuffle me-1"></i>
                                {{ $reshuffleCount == 0 ? 'Auto-Shuffle Teams' : 'Reshuffle Again' }}
                            </button>
                        </form>

                        <!-- Performance-based Option -->
                        <form method="POST" action="{{ route($routePrefix . '.reshuffle.perform', $tournament->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="use_performance" value="1">
                            <button type="submit" class="btn btn-info text-white" {{ $reshuffleCount >= $maxReshuffles ? 'disabled' : '' }}>
                                <i class="fas fa-trophy me-1"></i>
                                Shuffle by Performance
                            </button>
                        </form>

                        <!-- Reset Counter -->
                        @if($reshuffleCount > 0)
                        <form method="POST" action="{{ route($routePrefix . '.reshuffle.reset', $tournament->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-1"></i> Reset Counter
                            </button>
                        </form>
                        @endif

                        <!-- Info -->
                        <div class="ms-auto text-muted">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Teams are distributed based on seed numbers if available, otherwise randomly.
                                Drag and drop to manually adjust positions.
                            </small>
                        </div>
                    </div>

                    @if($reshuffleCount >= $maxReshuffles)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Maximum reshuffles reached!</strong> You can still manually move teams between pools using drag and drop.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Teams by Pool -->
    <div class="row" id="pools-container">
        @forelse($pools as $pool)
        <div class="col-md-6 col-lg-4 mb-4 pool-column" data-pool-id="{{ $pool->id }}">
            <div class="card h-100 pool-card">
                <div class="card-header bg-{{ $loop->index % 2 == 0 ? 'dark' : 'secondary' }} text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        {{ $pool->name }}
                        <span class="badge bg-light text-dark ms-2">
                            {{ isset($teamsByPool[$pool->id]) ? count($teamsByPool[$pool->id]) : 0 }} teams
                        </span>
                    </h6>
                </div>
                <div class="card-body p-2 pool-teams" data-pool-id="{{ $pool->id }}">
                    @php
                        $poolTeams = isset($teamsByPool[$pool->id]) ? $teamsByPool[$pool->id] : [];
                    @endphp

                    @forelse($poolTeams as $team)
                    <div class="card mb-2 team-card" draggable="true" data-team-id="{{ $team->id }}">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="drag-handle me-2 text-muted">
                                        <i class="bi bi-grip-vertical"></i>
                                    </span>
                                    <div>
                                        <strong>{{ $team->getDisplayNameAttribute() }}</strong>
                                        @if($team->seed_number)
                                        <span class="badge bg-info ms-1">Seed #{{ $team->seed_number }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($team->standing)
                                    <small class="text-muted">
                                        <i class="bi bi-trophy text-warning"></i> {{ $team->standing->points }} pts
                                    </small>
                                    @endif
                                    <span class="position-badge badge bg-secondary ms-1">{{ $team->pool_position ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4 empty-pool">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mb-0">No teams assigned</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No pools defined yet. Click "Auto-Shuffle Teams" to automatically create pools and distribute teams.
            </div>
        </div>
        @endforelse

        <!-- Unassigned Teams Column -->
        @if(count($unassignedTeams) > 0)
        <div class="col-md-6 col-lg-4 mb-4 pool-column" data-pool-id="unassigned">
            <div class="card h-100 pool-card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        Unassigned Teams
                        <span class="badge bg-dark ms-2">{{ count($unassignedTeams) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2 pool-teams" data-pool-id="unassigned">
                    @foreach($unassignedTeams as $team)
                    <div class="card mb-2 team-card" draggable="true" data-team-id="{{ $team->id }}">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="drag-handle me-2 text-muted">
                                        <i class="bi bi-grip-vertical"></i>
                                    </span>
                                    <div>
                                        <strong>{{ $team->getDisplayNameAttribute() }}</strong>
                                        @if($team->seed_number)
                                        <span class="badge bg-info ms-1">Seed #{{ $team->seed_number }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Save Positions Button -->
    <div class="row mt-3">
        <div class="col-12 text-center">
            <button type="button" id="save-positions" class="btn btn-success btn-lg">
                <i class="bi bi-save me-2"></i> Save Team Positions
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for saving positions -->
<form id="positions-form" method="POST" action="{{ route($routePrefix . '.update-positions', $tournament->id) }}" style="display: none;">
    @csrf
    <input type="hidden" name="teams" id="teams-data">
</form>

@push('styles')
<style>
    .pool-card {
        min-height: 300px;
    }

    .pool-teams {
        min-height: 200px;
        background-color: #f8f9fa;
        border-radius: 0 0 4px 4px;
    }

    .team-card {
        cursor: grab;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .team-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .team-card:active {
        cursor: grabbing;
    }

    .team-card.dragging {
        opacity: 0.5;
        transform: scale(0.95);
    }

    .pool-teams.drag-over {
        background-color: #e3f2fd;
        border: 2px dashed #2196f3;
    }

    .drag-handle {
        cursor: grab;
    }

    .position-badge {
        font-size: 0.7rem;
    }

    .empty-pool {
        border: 2px dashed #dee2e6;
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamCards = document.querySelectorAll('.team-card');
    const poolContainers = document.querySelectorAll('.pool-teams');
    const saveButton = document.getElementById('save-positions');
    const positionsForm = document.getElementById('positions-form');
    const teamsDataInput = document.getElementById('teams-data');

    let draggedTeam = null;
    let originalPool = null;

    // Initialize drag events for team cards
    teamCards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });

    // Initialize drop zones for pool containers
    poolContainers.forEach(container => {
        container.addEventListener('dragover', handleDragOver);
        container.addEventListener('dragenter', handleDragEnter);
        container.addEventListener('dragleave', handleDragLeave);
        container.addEventListener('drop', handleDrop);
    });

    function handleDragStart(e) {
        draggedTeam = this;
        originalPool = this.parentElement.dataset.poolId;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.teamId);
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedTeam = null;
        originalPool = null;

        // Remove drag-over class from all pools
        poolContainers.forEach(container => {
            container.classList.remove('drag-over');
        });
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDragEnter(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        if (draggedTeam) {
            // Move the team card to this pool
            this.appendChild(draggedTeam);

            // Update position numbers
            updatePositions(this);
        }
    }

    function updatePositions(poolContainer) {
        const teams = poolContainer.querySelectorAll('.team-card');
        teams.forEach((team, index) => {
            const badge = team.querySelector('.position-badge');
            if (badge) {
                badge.textContent = index + 1;
            }
        });
    }

    // Update positions when teams are moved to a new pool
    poolContainers.forEach(container => {
        const observer = new MutationObserver(() => {
            updatePositions(container);
        });
        observer.observe(container, { childList: true });
    });

    // Save positions button click handler
    saveButton.addEventListener('click', function() {
        const teams = [];

        poolContainers.forEach(container => {
            const poolId = container.dataset.poolId;
            const teamCards = container.querySelectorAll('.team-card');

            teamCards.forEach((card, index) => {
                teams.push({
                    id: parseInt(card.dataset.teamId),
                    pool_id: poolId === 'unassigned' ? null : parseInt(poolId),
                    position: index + 1
                });
            });
        });

        teamsDataInput.value = JSON.stringify(teams);

        // Show loading state
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

        // Submit form
        fetch(positionsForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ teams: teams })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveButton.classList.remove('btn-success');
                saveButton.classList.add('btn-success');
                saveButton.innerHTML = '<i class="bi bi-check-circle me-2"></i> Saved!';

                setTimeout(() => {
                    saveButton.innerHTML = '<i class="bi bi-save me-2"></i> Save Team Positions';
                    saveButton.disabled = false;
                }, 2000);
            } else {
                alert('Error saving positions: ' + (data.error || 'Unknown error'));
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="bi bi-save me-2"></i> Save Team Positions';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving positions. Please try again.');
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="bi bi-save me-2"></i> Save Team Positions';
        });
    });
});
</script>
@endpush
@endsection
