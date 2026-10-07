@extends('layouts.staff')

@section('title', 'Player Progress - Coach Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Player Progress</h2>
                            <p class="mb-0">View detailed progress for {{ $player->first_name ?? 'Player' }}</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('coach.sessions') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left"></i> Back to Sessions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Player Info -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4>{{ $player->first_name ?? '' }} {{ $player->last_name ?? '' }}</h4>
                    <p class="text-muted">{{ $player->email ?? 'No email' }}</p>
                    <span class="badge bg-{{ $player->registration_status === 'Active' ? 'success' : 'warning' }}">
                        {{ $player->registration_status ?? 'Unknown' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success mb-3">{{ $attendanceRate }}%</div>
                    <h5>Attendance Rate</h5>
                    <p class="text-muted">Overall attendance percentage</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning mb-3">{{ $player->age_group ?? 'N/A' }}</div>
                    <h5>Age Group</h5>
                    <p class="text-muted">Player's age category</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Attendance History</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center py-4">Detailed attendance history will be displayed here</p>
                </div>
            </div>
        </div>
    </div>
@endsection
