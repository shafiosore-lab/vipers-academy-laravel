@extends('layouts.admin')

@section('title', 'Tournament Management - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 text-gray-800">Tournament Management</h1>
        <div>
            <span class="text-muted small me-3"><i class="fas fa-calendar"></i> {{ now()->format('M d, Y') }}</span>
            <a href="{{ route('super-admin.tournaments.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> New Tournament
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Tournament name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Organization</label>
                    <select name="organization_id" class="form-select form-select-sm">
                        <option value="">All Organizations</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tournaments Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-2 px-2">Tournament</th>
                            <th class="py-2 px-2">Organization</th>
                            <th class="py-2 px-2">Season</th>
                            <th class="py-2 px-2">Status</th>
                            <th class="py-2 px-2">Visibility</th>
                            <th class="py-2 px-2">Teams</th>
                            <th class="py-2 px-2">Registration</th>
                            <th class="py-2 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tournaments as $tournament)
                        <tr>
                            <td class="py-2 align-middle">
                                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}" class="fw-bold text-decoration-none">
                                    {{ $tournament->name }}
                                </a>
                            </td>
                            <td class="py-2 align-middle">{{ $tournament->organization->name ?? 'N/A' }}</td>
                            <td class="py-2 align-middle">{{ $tournament->season ?? '-' }}</td>
                            <td class="py-2 align-middle">
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'open' => 'success',
                                        'closed' => 'warning',
                                        'ongoing' => 'primary',
                                        'completed' => 'info',
                                        'cancelled' => 'danger'
                                    ];
                                    $color = $statusColors[$tournament->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($tournament->status) }}</span>
                            </td>
                            <td class="py-2 align-middle">
                                @if($tournament->is_public)
                                    <span class="badge bg-success"><i class="fas fa-globe-americas"></i> Public</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-lock"></i> Private</span>
                                @endif
                            </td>
                            <td class="py-2 align-middle">
                                {{ $tournament->teams()->count() }} / {{ $tournament->max_teams ?? '∞' }}
                            </td>
                            <td class="py-2 align-middle">
                                @if($tournament->registration_deadline)
                                    <span class="{{ $tournament->registration_deadline->isPast() ? 'text-danger' : 'text-success' }}">
                                        {{ $tournament->registration_deadline->format('M d, Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 align-middle">
                                @if($tournament->start_date)
                                    {{ $tournament->start_date->format('M d') }}
                                    @if($tournament->end_date && $tournament->end_date != $tournament->start_date)
                                        - {{ $tournament->end_date->format('M d, Y') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-2 align-middle">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle py-0 px-1" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.show', $tournament->id) }}">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.edit', $tournament->id) }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.show', $tournament->id) }}#teams">
                                            <i class="fas fa-users me-1"></i> Manage Teams
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('super-admin.tournaments.players.index', $tournament->id) }}">
                                            <i class="fas fa-user-friends me-1"></i> View Players
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h6 class="dropdown-header">Quick Actions</h6></li>
                                        @if(in_array($tournament->status, ['draft', 'closed']))
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.open-registration', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-door-open me-1"></i> Open Registration
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($tournament->status == 'open')
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.close-registration', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-door-closed me-1"></i> Close Registration
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($tournament->status == 'closed')
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.start', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-play me-1"></i> Start Tournament
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($tournament->status == 'ongoing')
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.complete', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-flag-checkered me-1"></i> Complete Tournament
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.toggle-visibility', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-eye-slash me-1"></i> Toggle Visibility
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('super-admin.tournaments.destroy', $tournament->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">
                                <i class="fas fa-trophy fa-2x mb-2 d-block opacity-50"></i>
                                No tournaments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($tournaments->hasPages())
    <div class="mt-3">
        {{ $tournaments->appends(request()->query())->links() }}
    </div>
    @endif
    </div>
@endsection
