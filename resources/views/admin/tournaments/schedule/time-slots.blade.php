@extends('layouts.admin')

@section('title', 'Available Time Slots - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-clock"></i> Available Time Slots
            </h1>
            <p class="text-muted mb-0">{{ $tournament->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Schedule
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-calendar-range"></i> Total Available Slots</h6>
                    <h2 class="mb-0">{{ count($availableSlots) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-building"></i> Available Venues</h6>
                    <h2 class="mb-0">{{ $tournament->venues->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-calendar-check"></i> Tournament Days</h6>
                    <h2 class="mb-0">{{ $totalDays }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Slots -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Available Time Slots</h5>
                </div>
                <div class="card-body">
                    @if(count($availableSlots) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableSlots as $slot)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($slot['date'])->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($slot['date'])->format('l') }}</td>
                                        <td>{{ $slot['time'] }}</td>
                                        <td>{{ $slot['venue'] }}</td>
                                        <td>
                                            <span class="badge bg-success">Available</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <h4 class="mt-3">No Available Slots</h4>
                            <p class="text-muted">
                                No available time slots found. Please check your tournament dates and venue configuration.
                            </p>
                            <a href="{{ route('admin.tournaments.schedule.config', $tournament->id) }}" class="btn btn-primary mt-2">
                                <i class="bi bi-gear"></i> Configure Settings
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Summary -->
    @if(count($availableSlots) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Configuration Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Tournament Start:</strong></p>
                            <p class="text-muted">{{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Tournament End:</strong></p>
                            <p class="text-muted">{{ $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Match Duration:</strong></p>
                            <p class="text-muted">{{ $config['match_duration'] ?? 90 }} minutes</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Time Range:</strong></p>
                            <p class="text-muted">{{ $config['match_start_time'] ?? '09:00' }} - {{ $config['match_end_time'] ?? '18:00' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
