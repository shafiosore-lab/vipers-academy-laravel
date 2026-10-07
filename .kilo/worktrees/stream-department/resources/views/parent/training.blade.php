@extends('layouts.staff')

@section('title', 'Training & Attendance')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Training & Attendance</h2>
        <p class="text-muted mb-0">View training session attendance records</p>
    </div>
</div>

<!-- Player Selector -->
@if($players && $players->count() > 1)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex align-items-center gap-3">
            <label class="text-muted mb-0">Viewing attendance for:</label>
            <select name="player_id" onchange="this.form.submit()" class="form-select" style="width: auto;">
                @foreach($players as $player)
                    <option value="{{ $player->id }}" {{ $selectedPlayer && $selectedPlayer->id == $player->id ? 'selected' : '' }}>
                        {{ $player->full_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
@endif

<!-- Attendance Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Attendance Rate</p>
                <h2 class="mb-0">{{ $attendanceRate }}%</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Total Sessions</p>
                <h2 class="mb-0">{{ $totalSessions }}</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Training Minutes</p>
                <h2 class="mb-0">{{ number_format($totalMinutes) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">This Month</p>
                <h2 class="mb-0">{{ $monthlyStats[count($monthlyStats)-1]['sessions'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Stats Chart -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Monthly Training Statistics</h5>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
</div>

<!-- Attendance Records -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Training Session History</h5>
    </div>
    <div class="card-body p-0">
        @if($attendances && $attendances->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Date</th>
                        <th>Session</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td class="px-4 fw-semibold">{{ $attendance->session_date->format('M d, Y') }}</td>
                        <td>{{ $attendance->session->title ?? 'Training' }}</td>
                        <td>
                            @if($attendance->check_in_time)
                            <span class="text-success">{{ $attendance->check_in_time->format('H:i') }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_out_time)
                            <span class="text-primary">{{ $attendance->check_out_time->format('H:i') }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $attendance->formatted_duration ?? 'N/A' }}</td>
                        <td>
                            @if($attendance->check_in_time && $attendance->check_out_time)
                            <span class="badge bg-success">Complete</span>
                            @elseif($attendance->check_in_time)
                            <span class="badge bg-warning">In Progress</span>
                            @else
                            <span class="badge bg-danger">Absent</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $attendances->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-check fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No training attendance records found.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const monthlyStats = @json($monthlyStats);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyStats.map(s => s.month),
            datasets: [{
                label: 'Sessions Attended',
                data: monthlyStats.map(s => s.attended),
                backgroundColor: 'rgba(234, 28, 77, 0.8)',
                borderColor: 'rgb(234, 28, 77)',
                borderWidth: 1
            }, {
                label: 'Total Minutes',
                data: monthlyStats.map(s => s.minutes),
                backgroundColor: 'rgba(101, 193, 110, 0.8)',
                borderColor: 'rgb(101, 193, 110)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Sessions' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Minutes' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
</script>
@endpush
@endsection
