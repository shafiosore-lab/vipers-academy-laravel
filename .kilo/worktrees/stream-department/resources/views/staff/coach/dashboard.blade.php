@extends('layouts.staff')

@section('title', 'Coach Dashboard - Vipers Academy')

@section('content')
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Coach Dashboard</h2>
                            <p class="mb-0">
                                Welcome back, {{ auth()->user()->name }}!
                                @if(auth()->user()->hasRole('head-coach'))
                                    <span class="badge bg-warning text-dark ms-2">Head Coach</span>
                                @elseif(auth()->user()->hasRole('assistant-coach'))
                                    <span class="badge bg-info ms-2">Assistant Coach</span>
                                @elseif(auth()->user()->hasRole('coach'))
                                    <span class="badge bg-light text-dark ms-2">Coach</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                            <p class="mb-0">{{ now()->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">{{ $players ?? 0 }}</div>
                    <p class="text-muted mb-0">Total Players</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">{{ $activePlayers ?? 0 }}</div>
                    <p class="text-muted mb-0">Active Players</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $highPerformers ?? 0 }}</div>
                    <p class="text-muted mb-0">High Performers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-danger">{{ $playersNeedingAttention ?? 0 }}</div>
                    <p class="text-muted mb-0">Need Attention</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Today's Training Sessions</h5>
                </div>
                <div class="card-body">
                    @if(isset($todaySessions) && $todaySessions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($todaySessions as $session)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $session->title }}</h6>
                                        <small class="text-muted">{{ $session->scheduled_start_time ? $session->scheduled_start_time->format('g:i A') : 'TBD' }} - {{ $session->end_time ? $session->end_time->format('g:i A') : 'TBD' }}</small>
                                    </div>
                                    <span class="badge bg-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $session->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No training sessions scheduled for today</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Upcoming Sessions</h5>
                </div>
                <div class="card-body">
                    @if(isset($upcomingSessions) && $upcomingSessions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingSessions as $session)
                                <div class="list-group-item">
                                    <h6 class="mb-1">{{ $session->title }}</h6>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i> {{ $session->scheduled_start_time ? $session->scheduled_start_time->format('M j, Y g:i A') : 'TBD' }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No upcoming sessions</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Attendance</h5>
                    <a href="{{ route('coach.sessions') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentAttendance) && $recentAttendance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Session</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAttendance as $attendance)
                                        <tr>
                                            <td>{{ $attendance->player->name ?? 'N/A' }}</td>
                                            <td>{{ $attendance->trainingSession->title ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                                    {{ $attendance->status }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent attendance records</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('coach.sessions') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus-circle mb-2 d-block"></i>
                                Training Sessions
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('coach.sessions') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-user-check mb-2 d-block"></i>
                                Mark Attendance
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('coach.players') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-users mb-2 d-block"></i>
                                View Players
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-line mb-2 d-block"></i>
                                Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
