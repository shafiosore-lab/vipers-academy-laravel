@extends('layouts.admin')

@section('title', 'Tournaments')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Tournament Management</h4>
            <small class="text-muted">Manage all tournaments and competitions</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Create Tournament
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filters
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Status Filters</h6></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                        <i class="fas fa-circle text-secondary me-2"></i>All Statuses
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}">
                        <i class="fas fa-circle text-secondary me-2"></i>Draft
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'open']) }}">
                        <i class="fas fa-circle text-success me-2"></i>Open
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'closed']) }}">
                        <i class="fas fa-circle text-warning me-2"></i>Closed
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'ongoing']) }}">
                        <i class="fas fa-circle text-primary me-2"></i>Ongoing
                    </a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}">
                        <i class="fas fa-circle text-info me-2"></i>Completed
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Quick Actions</h6></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tournaments.index') }}">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
<!-- Filter Summary Card -->
@if(request()->anyFilled(['status', 'search', 'organization_id']))
    <div class="card tournament-card mb-3 shadow-sm border-0">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-filter text-primary"></i>
                    <div>
                        <h6 class="mb-0 fw-semibold">Active Filters</h6>
                        <small class="text-muted">Showing filtered results</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Clear All
                    </a>
                </div>
            </div>

            <div class="row mt-2">
                @if(request('status'))
                    <div class="col-auto">
                        <span class="badge bg-primary">{{ ucfirst(request('status')) }}</span>
                    </div>
                @endif
                @if(request('search'))
                    <div class="col-auto">
                        <span class="badge bg-info">Search: "{{ request('search') }}"</span>
                    </div>
                @endif
                @if(request('organization_id'))
                    <div class="col-auto">
                        <span class="badge bg-warning">Organization: {{ $organizations->find(request('organization_id'))?->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Search and Filter Card -->
<div class="card tournament-card mb-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0">
            <i class="fas fa-search me-2 text-primary"></i>Find Tournaments
        </h6>
    </div>
    <div class="card-body p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Search Tournament</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Tournament name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Organization</label>
                <select name="organization_id" class="form-select">
                    <option value="">All Organizations</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Actions</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tournament Cards Grid -->
@if($tournaments->count() > 0)
    <div class="tournament-card-grid">
        @foreach($tournaments as $tournament)
            <div class="card tournament-card shadow-sm border-0 h-100">
                <!-- Card Header with Status and Actions -->
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-{{ $statusColors[$tournament->status] ?? 'secondary' }} bg-opacity-10 rounded p-2">
                                <i class="fas {{ $statusIcons[$tournament->status] ?? 'fa-trophy' }} text-{{ $statusColors[$tournament->status] ?? 'secondary' }}"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $tournament->name }}</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark">{{ ucfirst($tournament->season ?? 'No season') }}</span>
                                    <span class="badge bg-{{ $statusColors[$tournament->status] ?? 'secondary' }}">
                                        {{ ucfirst($tournament->status) }}
                                    </span>
                                    @if($tournament->is_public)
                                        <span class="badge bg-success">
                                            <i class="fas fa-globe-americas me-1"></i>Public
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-lock me-1"></i>Private
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
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.show', $tournament->id) }}">
                                    <i class="fas fa-eye text-primary me-2"></i>View Details
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.tournaments.edit', $tournament->id) }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Quick Actions</h6></li>
                                @if(in_array($tournament->status, ['draft', 'closed']))
                                    <li>
                                        <form action="{{ route('admin.tournaments.open-registration', $tournament->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-door-open text-success me-2"></i>Open Registration
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($tournament->status == 'open')
                                    <li>
                                        <form action="{{ route('admin.tournaments.close-registration', $tournament->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-door-closed text-warning me-2"></i>Close Registration
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($tournament->status == 'closed')
                                    <li>
                                        <form action="{{ route('admin.tournaments.start', $tournament->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-play text-primary me-2"></i>Start Tournament
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($tournament->status == 'ongoing')
                                    <li>
                                        <form action="{{ route('admin.tournaments.complete', $tournament->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-flag-checkered text-info me-2"></i>Complete Tournament
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.tournaments.destroy', $tournament->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this tournament?')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Card Body with Details -->
                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- Organization Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-building text-info"></i>
                                <span class="fw-semibold">Organization:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-muted">{{ $tournament->organization->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Teams Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-users text-primary"></i>
                                <span class="fw-semibold">Teams:</span>
                            </div>
                            <div class="ms-4">
                                <span class="badge bg-primary">{{ $tournament->teams()->count() }}</span>
                                <span class="text-muted ms-2">of {{ $tournament->max_teams ?? '∞' }} max</span>
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-success"></i>
                                <span class="fw-semibold">Dates:</span>
                            </div>
                            <div class="ms-4">
                                @if($tournament->start_date)
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::parse($tournament->start_date)->format('M d') }}
                                        @if($tournament->end_date && $tournament->end_date != $tournament->start_date)
                                            - {{ \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">No dates set</span>
                                @endif
                            </div>
                        </div>

                        <!-- Venue Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                                <span class="fw-semibold">Venue:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-muted">{{ $tournament->venue ?? 'No venue specified' }}</span>
                            </div>
                        </div>

                        <!-- Registration Deadline -->
                        @if($tournament->registration_deadline)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-clock text-info"></i>
                                    <span class="fw-semibold">Registration:</span>
                                </div>
                                <div class="ms-4">
                                    <span class="{{ $tournament->registration_deadline->isPast() ? 'text-danger' : 'text-success' }}">
                                        {{ $tournament->registration_deadline->format('M d, Y') }}
                                    </span>
                                    @if($tournament->registration_deadline->isPast())
                                        <span class="badge bg-danger ms-2">Closed</span>
                                    @else
                                        <span class="badge bg-success ms-2">Open</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Format Info -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-cog text-secondary"></i>
                                <span class="fw-semibold">Format:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $tournament->format ?? 'round_robin')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Quick Stats -->
                <div class="card-footer bg-light border-0 p-3">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="h6 mb-0">{{ $tournament->matches()->count() }}</div>
                            <small class="text-muted">Matches</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0">{{ $tournament->pools()->count() }}</div>
                            <small class="text-muted">Pools</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0">{{ $tournament->squads()->count() }}</div>
                            <small class="text-muted">Players</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0">{{ $tournament->matches()->where('status', 'completed')->count() }}</div>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card-footer bg-white border-top p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i>Teams
                            </a>
                            <a href="{{ route('admin.tournaments.matches.index', $tournament->id) }}" class="btn btn-outline-info">
                                <i class="fas fa-futbol me-1"></i>Matches
                            </a>
                        </div>
                        <div>
                            <span class="text-muted small">
                                Created: {{ \Carbon\Carbon::parse($tournament->created_at)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($tournaments->hasPages())
        <div class="card tournament-card mt-4 shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            Showing {{ $tournaments->firstItem() }} to {{ $tournaments->lastItem() }} of {{ $tournaments->total() }} tournaments
                        </span>
                    </div>
                    <div>
                        {{ $tournaments->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <!-- Empty State -->
    <div class="card tournament-card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-trophy fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-2">No Tournaments Found</h4>
            <p class="text-muted mb-4">No tournaments match your current filters. Try adjusting your search criteria or create a new tournament.</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Create First Tournament
                </a>
                <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-refresh me-2"></i>Clear Filters
                </a>
            </div>
        </div>
    </div>
@endif

<style>
/* Additional styles for tournament index cards */
.tournament-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.tournament-card .card-header {
    border-bottom: 1px solid #e9ecef;
}

.tournament-card .card-footer {
    border-top: 1px solid #e9ecef;
}

.status-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tournament-card-grid {
        grid-template-columns: 1fr;
    }

    .tournament-card .card-body .row.g-3 {
        flex-direction: column;
    }

    .tournament-card .card-body .row.g-3 .col-md-6 {
        width: 100%;
    }

    .tournament-card .card-footer .row {
        flex-direction: column;
        gap: 10px;
    }
}

/* Hover effects for tournament cards */
.tournament-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Status badge animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

/* Filter summary animations */
.filter-summary {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Tournament index page specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tournament index cards initialized');

    // Add click functionality to tournament cards for quick navigation
    const tournamentCards = document.querySelectorAll('.tournament-card');
    tournamentCards.forEach(card => {
        // Make the entire card clickable for the main view action
        const viewButton = card.querySelector('.btn-outline-primary');
        if (viewButton) {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                // Don't trigger if clicking on buttons or links
                if (e.target.closest('button') || e.target.closest('a')) {
                    return;
                }
                window.location.href = viewButton.href;
            });
        }
    });

    // Add confirmation for delete actions
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this tournament? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
</script>

@endsection
