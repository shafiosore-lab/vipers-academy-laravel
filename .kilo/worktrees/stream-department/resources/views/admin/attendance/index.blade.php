@extends('layouts.admin')

@section('title', __('Attendance Management - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Attendance Management') }}</h1>
                    <p class="text-muted">{{ __('Track player attendance for training sessions and matches') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Record Attendance') }}
                    </a>
                    <a href="{{ route('admin.attendance.export.page') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-2"></i>{{ __('Export CSV') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Selection & Quick Actions -->
    @if($activeSessions->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0"><i class="fas fa-play-circle me-2 text-warning"></i>{{ __('Active Sessions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($activeSessions as $session)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">{{ ucfirst($session->session_type) }} - {{ $session->team_category }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Scheduled: {{ $session->scheduled_start_time->format('M j, Y H:i') }}<br>
                                            Elapsed: <span class="text-success fw-bold">{{ $session->formatted_elapsed_time }}</span><br>
                                            Players: {{ $session->players_admitted }}
                                        </small>
                                    </p>
                                    <a href="{{ route('admin.training-sessions.show', $session) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Manage Session
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Attendance Recording -->
    <div class="row mb-4" id="quickAttendanceSection" style="display: none;">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success bg-opacity-10">
                    <h5 class="mb-0"><i class="fas fa-users me-2 text-success"></i>{{ __('Quick Attendance Recording') }}</h5>
                    <small class="text-muted" id="selectedSessionInfo"></small>
                </div>
                <div class="card-body">
                    <div id="sessionPlayersList">
                        <!-- Players will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="date" class="form-label">{{ __('Session Date') }}</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                   id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="session_type" class="form-label">{{ __('Session Type') }}</label>
                            <select class="form-select @error('session_type') is-invalid @enderror"
                                    id="session_type" name="session_type">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="training" {{ request('session_type') == 'training' ? 'selected' : '' }}>{{ __('Training') }}</option>
                                <option value="match" {{ request('session_type') == 'match' ? 'selected' : '' }}>{{ __('Match') }}</option>
                                <option value="office_time" {{ request('session_type') == 'office_time' ? 'selected' : '' }}>{{ __('Office Time') }}</option>
                                <option value="meeting" {{ request('session_type') == 'meeting' ? 'selected' : '' }}>{{ __('Meeting') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="session_id" class="form-label">{{ __('Training Session') }}</label>
                            <select class="form-select @error('session_id') is-invalid @enderror"
                                    id="session_id" name="session_id">
                                <option value="">{{ __('All Sessions') }}</option>
                                @foreach(\App\Models\TrainingSession::with('startedBy')->orderBy('scheduled_start_time', 'desc')->limit(50)->get() as $session)
                                <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                    {{ ucfirst($session->session_type) }} - {{ $session->team_category }} ({{ $session->scheduled_start_time->format('M j, Y H:i') }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>{{ __('Filter') }}
                                </button>
                                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>{{ __('Clear') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>{{ __('Attendance Records') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-2">{{ __('Player') }}</th>
                            <th class="py-2">{{ __('Session') }}</th>
                            <th class="py-2">{{ __('Check In') }}</th>
                            <th class="py-2">{{ __('Lateness') }}</th>
                            <th class="py-2">{{ __('Training Time') }}</th>
                            <th class="py-2">{{ __('Category') }}</th>
                            <th class="py-2">{{ __('Recorded By') }}</th>
                            <th class="py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td class="py-1 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                                        <i class="fas fa-user text-primary small"></i>
                                    </div>
                                    <div class="small">
                                        <div class="fw-semibold">{{ $attendance->player->full_name ?? 'Unknown Player' }}</div>
                                        <small class="text-muted">{{ __('ID') }}: {{ $attendance->player_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 align-middle small">
                                @if($attendance->session)
                                    <div>
                                        <div class="fw-semibold">{{ ucfirst($attendance->session->session_type) }} - {{ $attendance->session->team_category }}</div>
                                        <small class="text-muted">{{ $attendance->session->scheduled_start_time->format('M j, H:i') }}</small>
                                    </div>
                                @else
                                    <span class="badge bg-{{ $attendance->session_type === 'training' ? 'info' : 'success' }}">
                                        {{ ucfirst($attendance->session_type) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                @if($attendance->check_in_time)
                                    <div>
                                        <div class="fw-semibold">{{ $attendance->check_in_time->format('H:i') }}</div>
                                        <small class="text-muted">{{ $attendance->check_in_time->format('M j, Y') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">{{ __('Not checked in') }}</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @if($attendance->lateness_category)
                                    @if($attendance->lateness_category == 'on_time')
                                        <span class="badge bg-success">{{ $attendance->missed_minutes ?? 0 }} min</span>
                                    @elseif($attendance->lateness_category == 'late')
                                        <span class="badge bg-warning">{{ $attendance->missed_minutes ?? 0 }} min late</span>
                                    @elseif($attendance->lateness_category == 'very_late')
                                        <span class="badge bg-danger">{{ $attendance->missed_minutes ?? 0 }} min late</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $attendance->lateness_category }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @if($attendance->trained_minutes)
                                    <span class="badge bg-info">{{ $attendance->trained_minutes }} min</span>
                                @elseif($attendance->session && $attendance->session->status == 'active')
                                    <span class="text-success fw-bold">
                                        <i class="fas fa-clock me-1"></i>In Progress
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                @if($attendance->session && $attendance->session->status == 'ended')
                                    @if($attendance->trained_minutes >= ($attendance->session->total_duration_minutes ?? 0) * 0.8)
                                        <span class="badge bg-success">Full</span>
                                    @elseif($attendance->trained_minutes >= ($attendance->session->total_duration_minutes ?? 0) * 0.5)
                                        <span class="badge bg-warning">Partial</span>
                                    @elseif($attendance->trained_minutes > 0)
                                        <span class="badge bg-danger">Late</span>
                                    @else
                                        <span class="badge bg-dark">Absent</span>
                                    @endif
                                @elseif($attendance->check_in_time)
                                    <span class="badge bg-info">In Progress</span>
                                @else
                                    <span class="badge bg-secondary">Not Started</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">
                                @if($attendance->recorder)
                                    <div>
                                        <div class="fw-semibold">{{ $attendance->recorder->name }}</div>
                                        <small class="text-muted">{{ $attendance->created_at->diffForHumans() }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">{{ __('System') }}</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.attendance.show', $attendance) }}" class="btn btn-sm btn-outline-primary py-0 px-1" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($attendance->session && $attendance->session->status == 'active' && !$attendance->check_in_time)
                                        <form method="POST" action="{{ route('admin.training-sessions.admit-player', $attendance->session) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="player_id" value="{{ $attendance->player_id }}">
                                            <button type="submit" class="btn btn-sm btn-success py-0 px-1" title="{{ __('Admit to Session') }}">
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
                                    <h5>{{ __('No Attendance Records') }}</h5>
                                    <p>{{ __('Attendance records will appear here once players are admitted to training sessions.') }}</p>
                                    <a href="{{ route('admin.training-sessions.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>{{ __('Create Training Session') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendances->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script>
// Session selection and player auto-population
document.getElementById('session_id').addEventListener('change', function() {
    const sessionId = this.value;

    if (sessionId) {
        // Show the quick attendance section
        document.getElementById('quickAttendanceSection').style.display = 'block';

        // Fetch session details and players
        fetch(`/admin/training-sessions/${sessionId}/players-for-attendance`)
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
                    <i class="fas fa-users me-1"></i>Admit All Players
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
                            `<form method="POST" action="/admin/training-sessions/${sessionId}/admit-player" class="mt-2">
                                @csrf
                                <input type="hidden" name="player_id" value="${player.id}">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-check me-1"></i>Admit Player
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
