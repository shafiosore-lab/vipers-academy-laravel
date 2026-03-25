@extends('layouts.admin')

@section('title', __('Attendance Management - Vipers Academy Admin'))

@php
// Determine route prefix based on the current URL path (not just user role)
$currentPath = request()->path();
if (str_starts_with($currentPath, 'super-admin')) {
    $routePrefix = 'super-admin';
} elseif (str_starts_with($currentPath, 'organization')) {
    $routePrefix = 'organization';
} else {
    $routePrefix = 'admin';
}
$baseUrl = '/' . $routePrefix;

// Check which routes are available for the current prefix
$canCreate = in_array($routePrefix, ['admin', 'organization']);
@endphp

@push('styles')
<style>
    .compact-header { margin-bottom: 12px; }
    .compact-header h1 { font-size: 1.25rem; margin-bottom: 0; }
    .compact-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .compact-actions .btn { font-size: 0.875rem; padding: 6px 12px; }
    .compact-card { margin-bottom: 12px; }
    .compact-card .card-header { padding: 8px 12px; }
    .compact-card .card-body { padding: 12px; }
    .compact-card h5 { font-size: 0.95rem; margin-bottom: 0; }
    .compact-table { font-size: 0.875rem; }
    .compact-table th { font-size: 0.8rem; padding: 6px 8px; }
    .compact-table td { padding: 6px 8px; vertical-align: middle; }
    .compact-badge { font-size: 0.7rem; padding: 2px 6px; }
    .compact-btn { font-size: 0.75rem; padding: 4px 8px; }
    .compact-input { font-size: 0.875rem; padding: 6px 8px; height: 36px; }
    .compact-empty { padding: 16px; text-align: center; }
    .compact-empty i { font-size: 2rem; margin-bottom: 8px; }
    .compact-empty p { margin-bottom: 12px; font-size: 0.9rem; }
    .compact-stats { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 8px; }
    .compact-stat { background: #f8f9fa; padding: 8px; border-radius: 6px; font-size: 0.8rem; }
    .compact-stat strong { font-weight: 600; }
    .compact-actions-row { display: flex; gap: 6px; }
    .compact-actions-row .btn { font-size: 0.75rem; padding: 4px 8px; }
    .compact-color-swatch { width: 16px; height: 16px; border-radius: 3px; border: 1px solid #ddd; display: inline-block; margin-right: 6px; }
    .compact-meta { font-size: 0.75rem; color: #6c757d; }
    .compact-title { font-size: 0.9rem; font-weight: 600; }
    .compact-excerpt { font-size: 0.75rem; color: #6c757d; margin-bottom: 0; }
    .compact-alert { font-size: 0.8rem; padding: 8px 12px; margin-bottom: 8px; }
    .compact-session-card { padding: 8px; margin-bottom: 8px; }
    .compact-session-title { font-size: 0.85rem; font-weight: 600; }
    .compact-session-meta { font-size: 0.75rem; color: #6c757d; }
    .compact-session-actions { font-size: 0.75rem; }
    .compact-filters { font-size: 0.875rem; }
    .compact-filters .form-label { font-size: 0.8rem; margin-bottom: 4px; }
    .compact-player-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    .compact-player-name { font-size: 0.85rem; font-weight: 600; }
    .compact-player-meta { font-size: 0.75rem; color: #6c757d; }
    .compact-time { font-size: 0.8rem; font-weight: 600; }
    .compact-duration { font-size: 0.75rem; color: #6c757d; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center compact-header">
        <h1><i class="fas fa-calendar-check"></i> Attendance Management</h1>
        <div class="compact-actions">
            @if($canCreate)
            <a href="{{ route($routePrefix . '.attendance.create') }}" class="btn btn-primary compact-btn">
                <i class="fas fa-plus"></i> Record
            </a>
            @endif
            <a href="{{ route($routePrefix . '.attendance.export.page') }}" class="btn btn-outline-secondary compact-btn">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Active Sessions -->
    @if($activeSessions->count() > 0)
    <div class="card compact-card">
        <div class="card-header">
            <h5><i class="fas fa-play-circle me-2 text-warning"></i>Active Sessions</h5>
        </div>
        <div class="card-body p-0">
            <div class="row g-2">
                @foreach($activeSessions as $session)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-primary compact-session-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="compact-session-title">{{ ucfirst($session->session_type) }} - {{ $session->team_category }}</div>
                                <div class="compact-session-meta">
                                    {{ $session->scheduled_start_time->format('M j, Y H:i') }}<br>
                                    <span class="text-success fw-bold">{{ $session->formatted_elapsed_time }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="compact-session-meta">Players: {{ $session->players_admitted }}</div>
                                <a href="{{ route($routePrefix . '.training-sessions.show', $session) }}" class="btn btn-sm btn-primary compact-session-actions">
                                    <i class="fas fa-eye"></i> Manage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="card compact-card">
        <div class="card-header">
            <h5><i class="fas fa-filter me-2"></i>Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route($routePrefix . '.attendance.index') }}" class="row g-2 compact-filters">
                <div class="col-md-2">
                    <label for="date" class="form-label">Session Date</label>
                    <input type="date" class="form-control compact-input" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <label for="session_type" class="form-label">Session Type</label>
                    <select class="form-select compact-input" id="session_type" name="session_type">
                        <option value="">All Types</option>
                        <option value="training" {{ request('session_type') == 'training' ? 'selected' : '' }}>Training</option>
                        <option value="match" {{ request('session_type') == 'match' ? 'selected' : '' }}>Match</option>
                        <option value="office_time" {{ request('session_type') == 'office_time' ? 'selected' : '' }}>Office Time</option>
                        <option value="meeting" {{ request('session_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="team_category" class="form-label">Team</label>
                    <select class="form-select compact-input" name="team_category">
                        <option value="">All Teams</option>
                        <option value="U10" {{ request('team_category') == 'U10' ? 'selected' : '' }}>U10</option>
                        <option value="U12" {{ request('team_category') == 'U12' ? 'selected' : '' }}>U12</option>
                        <option value="U13" {{ request('team_category') == 'U13' ? 'selected' : '' }}>U13</option>
                        <option value="U14" {{ request('team_category') == 'U14' ? 'selected' : '' }}>U14</option>
                        <option value="U15" {{ request('team_category') == 'U15' ? 'selected' : '' }}>U15</option>
                        <option value="U16" {{ request('team_category') == 'U16' ? 'selected' : '' }}>U16</option>
                        <option value="U17" {{ request('team_category') == 'U17' ? 'selected' : '' }}>U17</option>
                        <option value="U18" {{ request('team_category') == 'U18' ? 'selected' : '' }}>U18</option>
                        <option value="U20" {{ request('team_category') == 'U20' ? 'selected' : '' }}>U20</option>
                        <option value="Senior" {{ request('team_category') == 'Senior' ? 'selected' : '' }}>Senior</option>
                        <option value="Veteran" {{ request('team_category') == 'Veteran' ? 'selected' : '' }}>Veteran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="school_category" class="form-label">Category</label>
                    <select class="form-select compact-input" id="school_category" name="school_category">
                        <option value="all">All Categories</option>
                        <option value="primary" {{ request('school_category') == 'primary' ? 'selected' : '' }}>Primary</option>
                        <option value="junior_secondary" {{ request('school_category') == 'junior_secondary' ? 'selected' : '' }}>Junior Secondary</option>
                        <option value="senior_secondary" {{ request('school_category') == 'senior_secondary' ? 'selected' : '' }}>Senior Secondary</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="session_id" class="form-label">Session</label>
                    <select class="form-select compact-input" id="session_id" name="session_id">
                        <option value="">All Sessions</option>
                        @foreach($activeSessions as $session)
                        <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                            {{ ucfirst($session->session_type) }} - {{ $session->team_category }} ({{ $session->scheduled_start_time->format('M j, Y H:i') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1 compact-btn">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route($routePrefix . '.attendance.index') }}" class="btn btn-outline-secondary compact-btn">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card compact-card">
        <div class="card-header">
            <h5><i class="fas fa-calendar-check me-2 text-primary"></i>Attendance Records</h5>
        </div>
        <div class="card-body p-0">
            @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table compact-table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%">Player</th>
                            <th style="width: 15%">Session</th>
                            <th style="width: 12%">Check In</th>
                            <th style="width: 12%">Lateness</th>
                            <th style="width: 12%">Training Time</th>
                            <th style="width: 12%">Status</th>
                            <th style="width: 12%">Recorded By</th>
                            <th style="width: 5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($attendance->player->image_path)
                                        <img src="{{ $attendance->player->image_url }}" alt="{{ $attendance->player->full_name }}" class="compact-player-avatar">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="ms-2">
                                        <div class="compact-player-name">{{ $attendance->player->full_name ?? 'Unknown Player' }}</div>
                                        <div class="compact-player-meta">ID: {{ $attendance->player_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($attendance->session)
                                    <div>
                                        <div class="compact-session-title">{{ ucfirst($attendance->session->session_type) }} - {{ $attendance->session->team_category }}</div>
                                        <div class="compact-session-meta">{{ $attendance->session->scheduled_start_time->format('M j, H:i') }}</div>
                                    </div>
                                @else
                                    <span class="badge bg-{{ $attendance->session_type === 'training' ? 'info' : 'success' }} compact-badge">{{ ucfirst($attendance->session_type) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_in_time)
                                    <div class="compact-time">{{ $attendance->check_in_time->format('H:i') }}</div>
                                    <div class="compact-duration">{{ $attendance->check_in_time->format('M j, Y') }}</div>
                                @else
                                    <span class="text-muted">Not checked in</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->lateness_category)
                                    @if($attendance->lateness_category == 'on_time')
                                        <span class="badge bg-success compact-badge">{{ $attendance->missed_minutes ?? 0 }} min</span>
                                    @elseif($attendance->lateness_category == 'late')
                                        <span class="badge bg-warning compact-badge">{{ $attendance->missed_minutes ?? 0 }} min late</span>
                                    @elseif($attendance->lateness_category == 'very_late')
                                        <span class="badge bg-danger compact-badge">{{ $attendance->missed_minutes ?? 0 }} min late</span>
                                    @else
                                        <span class="badge bg-secondary compact-badge">{{ $attendance->lateness_category }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->trained_minutes)
                                    <span class="badge bg-info compact-badge">{{ number_format($attendance->trained_minutes, 2) }} min</span>
                                @elseif($attendance->session && $attendance->session->status == 'active')
                                    <span class="text-success fw-bold">
                                        <i class="fas fa-clock me-1"></i>In Progress
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->session && $attendance->session->status == 'ended')
                                    @if($attendance->trained_minutes >= ($attendance->session->total_duration_minutes ?? 0) * 0.8)
                                        <span class="badge bg-success compact-badge">Full</span>
                                    @elseif($attendance->trained_minutes >= ($attendance->session->total_duration_minutes ?? 0) * 0.5)
                                        <span class="badge bg-warning compact-badge">Partial</span>
                                    @elseif($attendance->trained_minutes > 0)
                                        <span class="badge bg-danger compact-badge">Late</span>
                                    @else
                                        <span class="badge bg-dark compact-badge">Absent</span>
                                    @endif
                                @elseif($attendance->check_in_time)
                                    <span class="badge bg-info compact-badge">In Progress</span>
                                @else
                                    <span class="badge bg-secondary compact-badge">Not Started</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->recorder)
                                    <div>
                                        <div class="compact-player-name">{{ $attendance->recorder->name }}</div>
                                        <div class="compact-session-meta">{{ $attendance->created_at->diffForHumans() }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <div class="compact-actions-row">
                                    <a href="{{ route($routePrefix . '.attendance.show', $attendance) }}" class="btn btn-sm btn-outline-primary compact-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($attendance->session && $attendance->session->status == 'active' && !$attendance->check_in_time)
                                        <form method="POST" action="{{ route($routePrefix . '.training-sessions.admit-player', $attendance->session) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="player_id" value="{{ $attendance->player_id }}">
                                            <button type="submit" class="btn btn-sm btn-success compact-btn" title="Admit to Session">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                    <h5>No Attendance Records</h5>
                                    <p>Attendance records will appear here once players are admitted to training sessions.</p>
                                    <a href="{{ route($routePrefix . '.training-sessions.create') }}" class="btn btn-primary compact-btn">
                                        <i class="fas fa-plus me-2"></i>Create Training Session
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <div class="compact-empty">
                <i class="fas fa-calendar-times text-muted"></i>
                <p class="mb-2">No attendance records found.</p>
                <a href="{{ route($routePrefix . '.training-sessions.create') }}" class="btn btn-primary compact-btn">Create Training Session</a>
            </div>
            @endif

            <!-- Pagination -->
            @if($attendances->hasPages())
            <div class="p-2 border-top">
                <div class="d-flex justify-content-center">
                    {{ $attendances->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Session selection and player auto-population
document.getElementById('session_id').addEventListener('change', function() {
    const sessionId = this.value;

    if (sessionId) {
        // Show the quick attendance section
        document.getElementById('quickAttendanceSection').style.display = 'block';

        // Fetch session details and players
        fetch(`${baseUrl}/training-sessions/${sessionId}/players-for-attendance`)
            .then(response => response.json())
            .then(data => {
                // Update session info
                document.getElementById('selectedSessionInfo').textContent =
                    `${data.session.session_type} - ${data.session.team_category} (${data.session.scheduled_time})`;

                // Render players list
                renderPlayersList(data.players, sessionId, data.session);
            })
            .catch(error => {
                console.error('Error loading session players:', error);
                document.getElementById('quickAttendanceSection').style.display = 'none';
            });
    } else {
        // Hide the quick attendance section
        document.getElementById('quickAttendanceSection').style.display = 'none';
    }
});

function renderPlayersList(players, sessionId, session) {
    const container = document.getElementById('sessionPlayersList');

    if (players.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Players Found</h5>
                <p class="text-muted">No active players found for this team category.</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">${players.length} Players Available</h6>
                <button type="button" class="btn btn-success btn-sm" onclick="admitAllPlayers(${sessionId})">
                    <i class="fas fa-users me-1"></i>Admit All
                </button>
            </div>
            <div class="row">
    `;

    players.forEach(player => {
        const alreadyAdmitted = player.attendance_record !== null;
        const statusClass = alreadyAdmitted ? 'border-success' : 'border-secondary';
        const statusText = alreadyAdmitted ? 'Already Admitted' : 'Ready for Admission';

        html += `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 ${statusClass}">
                    <div class="card-body text-center">
                        ${player.image_path ?
                            `<img src="${player.image_url}" alt="${player.full_name}"
                                 class="rounded-circle mb-2" style="width: 50px; height: 50px; object-fit: cover;">` :
                            `<div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>`
                        }
                        <h6 class="mb-1">${player.full_name}</h6>
                        <small class="text-muted d-block">${player.position || 'Position not set'}</small>
                        <small class="badge ${alreadyAdmitted ? 'bg-success' : 'bg-secondary'}">${statusText}</small>

                        ${!alreadyAdmitted ?
                            `<form method="POST" action="${baseUrl}/training-sessions/${sessionId}/admit-player" class="mt-2">
                                @csrf
                                <input type="hidden" name="player_id" value="${player.id}">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-check me-1"></i>Admit
                                </button>
                            </form>` :
                            `<div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Admitted ${player.attendance_record.check_in_time ? 'at ' + player.attendance_record.check_in_time : ''}
                                </small>
                            </div>`
                        }
                    </div>
                </div>
            </div>
        `;
    });

    html += `
            </div>
        </div>
    `;

    container.innerHTML = html;
}

function admitAllPlayers(sessionId) {
    if (confirm('Are you sure you want to admit all players to this session?')) {
        // Get all player forms and submit them
        const forms = document.querySelectorAll('#sessionPlayersList form');
        forms.forEach(form => {
            // Create a hidden form to submit each player individually
            const hiddenForm = document.createElement('form');
            hiddenForm.method = 'POST';
            hiddenForm.action = form.action;
            hiddenForm.style.display = 'none';

            // Copy CSRF token and player_id
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ||
                            document.querySelector('input[name="_token"]');
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken.value || csrfToken.content;
                hiddenForm.appendChild(tokenInput);
            }

            const playerIdInput = form.querySelector('input[name="player_id"]');
            if (playerIdInput) {
                const newPlayerIdInput = document.createElement('input');
                newPlayerIdInput.type = 'hidden';
                newPlayerIdInput.name = 'player_id';
                newPlayerIdInput.value = playerIdInput.value;
                hiddenForm.appendChild(newPlayerIdInput);
            }

            document.body.appendChild(hiddenForm);
            hiddenForm.submit();
        });
    }
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('status', this.value);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
});

document.getElementById('teamFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    if (this.value) {
        url.searchParams.set('team_category', this.value);
    } else {
        url.searchParams.delete('team_category');
    }
    window.location.href = url.toString();
});
</script>
@endpush
