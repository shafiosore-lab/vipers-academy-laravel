@extends('layouts.admin')

@section('title', $trainingSession->team_category . ' ' . ucfirst($trainingSession->session_type) . ' Session - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Session Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-lg me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">{{ $trainingSession->team_category }} {{ ucfirst($trainingSession->session_type) }}</h4>
                                <small class="opacity-75">
                                    Scheduled: {{ $trainingSession->scheduled_start_time->format('l, F j, Y g:i A') }}
                                    @if($trainingSession->actual_start_time)
                                        | Started: {{ $trainingSession->actual_start_time->format('g:i A') }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @switch($trainingSession->status)
                                @case('scheduled')
                                    <span class="badge bg-secondary fs-6">Scheduled</span>
                                    @break
                                @case('active')
                                    <span class="badge bg-success fs-6">Active</span>
                                    @break
                                @case('ended')
                                    <span class="badge bg-primary fs-6">Ended</span>
                                    @break
                            @endswitch
                            @if($trainingSession->status == 'scheduled')
                                <a href="{{ route('admin.training-sessions.edit', $trainingSession) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.training-sessions.destroy', $trainingSession) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.training-sessions.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Session Stats -->
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card border-primary text-center h-100">
                                        <div class="card-body">
                                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                            <h4 class="mb-0">{{ $trainingSession->players_admitted }}</h4>
                                            <small class="text-muted">Players Admitted</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-warning text-center h-100">
                                        <div class="card-body">
                                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                            <h4 class="mb-0">{{ $trainingSession->late_arrivals }}</h4>
                                            <small class="text-muted">Late Arrivals</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-info text-center h-100">
                                        <div class="card-body">
                                            <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                                            <h4 class="mb-0">{{ $trainingSession->punctuality_rate }}%</h4>
                                            <small class="text-muted">Punctuality Rate</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success text-center h-100">
                                        <div class="card-body">
                                            <i class="fas fa-stopwatch fa-2x text-success mb-2"></i>
                                            <h4 class="mb-0" id="elapsedTime">
                                                @if($trainingSession->status == 'active')
                                                    {{ $trainingSession->formatted_elapsed_time }}
                                                @elseif($trainingSession->status == 'ended')
                                                    {{ $trainingSession->total_duration_minutes }}m
                                                @else
                                                    -
                                                @endif
                                            </h4>
                                            <small class="text-muted">Duration</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Session Controls -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Session Controls</h6>
                                </div>
                                <div class="card-body">
                                    @if($trainingSession->status == 'scheduled')
                                        <form action="{{ route('admin.training-sessions.start', $trainingSession) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg w-100 mb-2"
                                                    onclick="return confirm('Start this training session now? The timer will begin immediately.')">
                                                <i class="fas fa-play fa-lg me-2"></i>
                                                <strong>START SESSION</strong>
                                            </button>
                                        </form>
                                        <p class="text-muted small mb-0">This will begin the official training session timer.</p>
                                    @elseif($trainingSession->status == 'active')
                                        <form action="{{ route('admin.training-sessions.end', $trainingSession) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-lg w-100 mb-2"
                                                    onclick="return confirm('End this training session? This will calculate final training times for all players.')">
                                                <i class="fas fa-stop fa-lg me-2"></i>
                                                <strong>END SESSION</strong>
                                            </button>
                                        </form>
                                        <p class="text-success small mb-0">Session is currently active. Timer running.</p>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Session completed successfully.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Player Admission Section -->
            @if($trainingSession->status == 'active')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-user-plus me-2"></i>Admit Players</h6>
                        </div>
                        <div class="card-body">
                            @if($availablePlayers->count() > 0)
                                <div class="row">
                                    @foreach($availablePlayers as $player)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                @if($player->image_path)
                                                    <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}"
                                                         class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-1">{{ $player->full_name }}</h6>
                                                <small class="text-muted">{{ $player->position }}</small>
                                                <form action="{{ route('admin.training-sessions.admit-player', $trainingSession) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="player_id" value="{{ $player->id }}">
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-check me-1"></i>Admit Player
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">All Eligible Players Admitted</h5>
                                    <p class="text-muted">No more players available for admission to this session.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Admitted Players List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Admitted Players ({{ $trainingSession->attendances->count() }})</h6>
                        </div>
                        <div class="card-body">
                            @if($trainingSession->attendances->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Player</th>
                                                <th>Position</th>
                                                <th>Check-in Time</th>
                                                <th>Lateness</th>
                                                <th>Training Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trainingSession->attendances->sortBy('check_in_time') as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($attendance->player->image_path)
                                                            <img src="{{ $attendance->player->image_url }}" alt="{{ $attendance->player->full_name }}"
                                                                 class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $attendance->player->full_name }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $attendance->player->position }}</td>
                                                <td>
                                                    @if($attendance->check_in_time)
                                                        {{ $attendance->check_in_time->format('g:i A') }}
                                                    @else
                                                        <span class="text-muted">Absent</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($attendance->lateness_category == 'absent')
                                                        <span class="badge bg-secondary">Absent</span>
                                                    @elseif($attendance->lateness_category == 'on_time')
                                                        <span class="badge bg-success">On Time</span>
                                                    @elseif($attendance->lateness_category == 'late')
                                                        <span class="badge bg-warning">{{ $attendance->missed_minutes }} min late</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $attendance->missed_minutes }} min late</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($trainingSession->status == 'ended')
                                                        {{ $attendance->trained_minutes }} minutes
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($trainingSession->status == 'ended')
                                                        @if($attendance->lateness_category == 'absent')
                                                            <span class="badge bg-secondary">Absent</span>
                                                        @elseif($attendance->trained_minutes >= $trainingSession->total_duration_minutes * 0.8)
                                                            <span class="badge bg-success">Full Session</span>
                                                        @elseif($attendance->trained_minutes >= $trainingSession->total_duration_minutes * 0.5)
                                                            <span class="badge bg-warning">Partial</span>
                                                        @else
                                                            <span class="badge bg-danger">Very Late</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info">In Progress</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Players Admitted Yet</h5>
                                    <p class="text-muted">
                                        @if($trainingSession->status == 'active')
                                            Use the "Admit Player" buttons above to check in arriving players.
                                        @else
                                            The session hasn't started yet.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if($trainingSession->status == 'active')
<script>
// Live timer update for active sessions
function updateLiveData() {
    fetch('{{ route("admin.training-sessions.live-data", $trainingSession) }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('elapsedTime').textContent = data.elapsed_time;
            // Could update other live stats here
        })
        .catch(error => console.log('Live update failed:', error));
}

// Update every 30 seconds for active sessions
setInterval(updateLiveData, 30000);

// Initial update
updateLiveData();
</script>
@endif
