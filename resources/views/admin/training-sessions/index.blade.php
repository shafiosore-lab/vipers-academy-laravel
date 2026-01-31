@extends('layouts.admin')

@section('title', 'Training Sessions - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Training Sessions</h4>
                            <small class="opacity-75">Manage time-tracked training sessions</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.training-sessions.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Schedule Session
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Ended</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="teamFilter">
                                <option value="">All Teams</option>
                                <option value="U13" {{ request('team_category') == 'U13' ? 'selected' : '' }}>U13</option>
                                <option value="U15" {{ request('team_category') == 'U15' ? 'selected' : '' }}>U15</option>
                                <option value="U17" {{ request('team_category') == 'U17' ? 'selected' : '' }}>U17</option>
                                <option value="Senior" {{ request('team_category') == 'Senior' ? 'selected' : '' }}>Senior</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sessions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Team</th>
                                    <th>Type</th>
                                    <th>Scheduled Time</th>
                                    <th>Status</th>
                                    <th>Started By</th>
                                    <th>Players</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $session->team_category }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $session->session_type == 'training' ? 'success' : 'warning' }}">
                                            {{ ucfirst($session->session_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $session->scheduled_start_time->format('M j, Y g:i A') }}
                                        @if($session->actual_start_time)
                                            <br><small class="text-muted">Started: {{ $session->actual_start_time->format('g:i A') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($session->status)
                                            @case('scheduled')
                                                <span class="badge bg-secondary">Scheduled</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">Active</span>
                                                @break
                                            @case('ended')
                                                <span class="badge bg-primary">Ended</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $session->startedBy->name ?? 'Not Started' }}
                                    </td>
                                    <td>
                                        {{ $session->players_admitted }}
                                        @if($session->late_arrivals > 0)
                                            <small class="text-danger">({{ $session->late_arrivals }} late)</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($session->status == 'ended')
                                            {{ $session->total_duration_minutes }} min
                                        @elseif($session->status == 'active')
                                            <span class="text-success">{{ $session->formatted_elapsed_time }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.training-sessions.show', $session) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($session->status == 'scheduled')
                                                <a href="{{ route('admin.training-sessions.edit', $session) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.training-sessions.destroy', $session) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.training-sessions.start', $session) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Start this training session now?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-play"></i> Start
                                                    </button>
                                                </form>
                                            @elseif($session->status == 'active')
                                                <form action="{{ route('admin.training-sessions.end', $session) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('End this training session? This will calculate final training times.')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-stop"></i> End
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Training Sessions Found</h5>
                                        <p class="text-muted">Schedule your first training session to get started.</p>
                                        <a href="{{ route('admin.training-sessions.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Schedule Session
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($sessions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $sessions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
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
