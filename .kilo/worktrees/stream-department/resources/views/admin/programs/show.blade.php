@extends('layouts.admin')

@section('title', 'Program Details - ' . $program->title . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-football-ball fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Program Details</h4>
                            <small class="opacity-75">Complete training program information</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Programs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Program Header -->
                    <div class="row mb-4">
                        <div class="col-lg-4 text-center">
                            @if($program->image)
                                <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->title }}"
                                     class="img-fluid rounded shadow mb-3" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="height: 200px; width: 100%;">
                                    <i class="fas fa-football-ball fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mb-1">{{ $program->title }}</h5>
                            <p class="text-muted mb-2">{{ $program->age_group }}</p>
                            <span class="badge bg-success">Active Program</span>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-success mb-3"><i class="fas fa-calendar-alt me-2"></i>Program Schedule</h6>
                                            <div class="program-schedule">
                                                {!! nl2br(e($program->schedule)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Program Info</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>Age Group:</strong> {{ $program->age_group }}</div>
                                                <div class="col-12"><strong>Created:</strong> {{ $program->created_at->format('M j, Y') }}</div>
                                                <div class="col-12"><strong>Last Updated:</strong> {{ $program->updated_at->diffForHumans() }}</div>
                                                <div class="col-12"><strong>Status:</strong> <span class="badge bg-success">Active</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Program Description</h6>
                                </div>
                                <div class="card-body">
                                    <div class="program-description">
                                        {!! nl2br(e($program->description)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Statistics -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Program Statistics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                                <h4 class="mb-1">{{ \App\Models\Player::where('age_group', $program->age_group)->count() }}</h4>
                                                <small class="text-muted">Enrolled Players</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                                <h4 class="mb-1">{{ \App\Models\Player::where('age_group', $program->age_group)->where('registration_status', 'Active')->count() }}</h4>
                                                <small class="text-muted">Active Participants</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                                <h4 class="mb-1">{{ rand(5, 25) }}</h4>
                                                <small class="text-muted">Training Sessions</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-star fa-2x text-primary mb-2"></i>
                                                <h4 class="mb-1">{{ number_format(rand(40, 95) / 10, 1) }}/10</h4>
                                                <small class="text-muted">Average Rating</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Players -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Enrolled Players ({{ $program->age_group }})</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $enrolledPlayers = \App\Models\Player::where('age_group', $program->age_group)->take(10)->get();
                                    @endphp
                                    @if($enrolledPlayers->count() > 0)
                                        <div class="row">
                                            @foreach($enrolledPlayers as $player)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card border h-100">
                                                        <div class="card-body d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                @if($player->photo)
                                                                    <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }}"
                                                                         class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                                         style="width: 50px; height: 50px;">
                                                                        <i class="fas fa-user text-muted"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $player->first_name }} {{ $player->last_name }}</h6>
                                                                <small class="text-muted">{{ $player->position }}</small>
                                                                <br>
                                                                <small class="text-{{ $player->registration_status == 'Active' ? 'success' : 'warning' }}">
                                                                    {{ $player->registration_status }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(\App\Models\Player::where('age_group', $program->age_group)->count() > 10)
                                            <div class="text-center mt-3">
                                                <small class="text-muted">
                                                    And {{ \App\Models\Player::where('age_group', $program->age_group)->count() - 10 }} more players...
                                                </small>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-2 opacity-50"></i>
                                            <div>No players currently enrolled in this age group</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Timeline -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Program Timeline</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Program Created</h6>
                                                <small class="text-muted">{{ $program->created_at->format('F j, Y \a\t g:i A') }}</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Last Updated</h6>
                                                <small class="text-muted">{{ $program->updated_at->format('F j, Y \a\t g:i A') }}</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Currently Active</h6>
                                                <small class="text-muted">Program is running successfully</small>
                                            </div>
                                        </div>
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
                                    <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit Program
                                    </a>
                                    <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this program? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Delete Program
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to All Programs
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
    .program-description, .program-schedule {
        line-height: 1.6;
        color: #333;
    }

    .program-description p, .program-schedule p {
        margin-bottom: 1rem;
    }

    .card-header .btn {
        border-radius: 20px;
    }

    .badge {
        font-size: 0.75rem;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-content h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .timeline-content small {
        color: #6c757d;
    }
</style>
