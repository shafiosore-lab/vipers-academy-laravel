@extends('layouts.admin')

@section('title', 'Attendance Details - ' . $attendance->player->full_name . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Attendance Details</h4>
                            <small class="opacity-75">{{ $attendance->player->full_name }} - {{ ucfirst($attendance->session_type) }}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Attendance Header -->
                    <div class="row mb-4">
                        <div class="col-lg-4 text-center">
                            @if($attendance->player->image_path)
                                <img src="{{ $attendance->player->image_url }}" alt="{{ $attendance->player->full_name }}"
                                     class="img-fluid rounded-circle shadow mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mb-1">{{ $attendance->player->full_name }}</h5>
                            <p class="text-muted mb-2">{{ $attendance->player->position }}</p>
                            <span class="badge bg-{{ $attendance->player->registration_status == 'Active' ? 'success' : 'secondary' }}">
                                {{ $attendance->player->registration_status }}
                            </span>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>Session Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>Session Type:</strong> {{ ucfirst($attendance->session_type) }}</div>
                                                <div class="col-12"><strong>Session Date:</strong> {{ $attendance->session_date->format('l, F j, Y') }}</div>
                                                <div class="col-12"><strong>Recorded By:</strong> {{ $attendance->recorder->name ?? 'System' }}</div>
                                                <div class="col-12"><strong>Recorded At:</strong> {{ $attendance->created_at->format('M j, Y g:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-success mb-3"><i class="fas fa-clock me-2"></i>Time Tracking</h6>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <strong>Check In:</strong>
                                                    @if($attendance->check_in_time)
                                                        <span class="text-success">{{ $attendance->check_in_time->format('g:i A') }}</span>
                                                    @else
                                                        <span class="text-muted">Not checked in</span>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <strong>Check Out:</strong>
                                                    @if($attendance->check_out_time)
                                                        <span class="text-success">{{ $attendance->check_out_time->format('g:i A') }}</span>
                                                    @else
                                                        <span class="text-muted">Not checked out</span>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <strong>Duration:</strong>
                                                    @if($attendance->total_duration_minutes)
                                                        <span class="text-info">{{ $attendance->total_duration_minutes }} minutes</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <strong>Status:</strong>
                                                    @if($attendance->is_completed)
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif($attendance->check_in_time)
                                                        <span class="badge bg-warning">In Progress</span>
                                                    @else
                                                        <span class="badge bg-secondary">Scheduled</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Player Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Player Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Basic Details</h6>
                                            <p><strong>Age:</strong> {{ $attendance->player->age ?? 'N/A' }} years</p>
                                            <p><strong>Position:</strong> {{ $attendance->player->position }}</p>
                                            <p><strong>Registration Status:</strong> {{ $attendance->player->registration_status }}</p>
                                            <p><strong>Approval Status:</strong>
                                                @if($attendance->player->isFullyApproved())
                                                    <span class="badge bg-success">Fully Approved</span>
                                                @elseif($attendance->player->isTemporarilyApproved())
                                                    <span class="badge bg-warning">Temporary Approval</span>
                                                @else
                                                    <span class="badge bg-danger">Not Approved</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Contact Information</h6>
                                            <p><strong>Phone:</strong> {{ $attendance->player->phone ?? 'N/A' }}</p>
                                            <p><strong>Email:</strong> {{ $attendance->player->email ?? 'N/A' }}</p>
                                            <p><strong>Emergency Contact:</strong> {{ $attendance->player->emergency_contact_name ?? 'N/A' }}</p>
                                            <p><strong>Emergency Phone:</strong> {{ $attendance->player->emergency_contact_phone ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.players.show', $attendance->player->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Full Player Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    @if(!$attendance->check_in_time)
                                        <form action="{{ route('admin.attendance.check-in', $attendance->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to check in {{ $attendance->player->full_name }} for this {{ $attendance->session_type }} session?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-sign-in-alt me-2"></i>Check In
                                            </button>
                                        </form>
                                    @elseif(!$attendance->check_out_time)
                                        <form action="{{ route('admin.attendance.check-out', $attendance->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to check out {{ $attendance->player->full_name }} from this {{ $attendance->session_type }} session?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-sign-out-alt me-2"></i>Check Out
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Session Completed
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Attendance List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .card-header .btn {
        border-radius: 20px;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card-body p {
        margin-bottom: 0.5rem;
    }

    .card-body h6 {
        border-bottom: 2px solid currentColor;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
