@extends('layouts.staff')

@section('title', 'AI Insights')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">AI-Powered Insights</h2>
        <p class="text-muted mb-0">Data-driven analysis of your child's performance</p>
    </div>
    <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
        <i class="fas fa-sync-alt me-1"></i> Refresh
    </button>
</div>

<!-- Player Selector -->
@if($players && $players->count() > 1)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex align-items-center gap-3">
            <label class="text-muted mb-0">Viewing insights for:</label>
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

@if($selectedPlayer)
<!-- Performance Overview -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Performance Score</p>
                <h3 class="mb-0 {{ $performanceScore >= 70 ? 'text-success' : ($performanceScore >= 50 ? 'text-warning' : 'text-danger') }}">
                    {{ number_format($performanceScore, 1) }}%
                </h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Mental Strength</p>
                <h3 class="mb-0 text-primary">{{ number_format($mentalStrength, 1) }}%</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Training Consistency</p>
                <h3 class="mb-0 text-info">{{ number_format($trainingConsistency, 1) }}%</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Match Rating</p>
                <h3 class="mb-0 text-warning">{{ number_format($matchRating, 1) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Radar Chart - Skills -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Skills Assessment</h5>
    </div>
    <div class="card-body">
        <div style="height: 350px;">
            <canvas id="skillsRadar"></canvas>
        </div>
    </div>
</div>

<!-- Progress Over Time -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Performance Trend</h5>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="progressChart"></canvas>
        </div>
    </div>
</div>

<!-- AI Recommendations -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">
            <i class="fas fa-robot text-primary me-2"></i>AI Recommendations
        </h5>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @if($recommendations && count($recommendations) > 0)
                @foreach($recommendations as $recommendation)
                <div class="list-group-item px-0 py-3 border-bottom">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-{{ $recommendation['priority'] === 'high' ? 'danger' : ($recommendation['priority'] === 'medium' ? 'warning' : 'success') }} bg-opacity-10 rounded p-2 flex-shrink-0">
                            <i class="fas fa-{{ $recommendation['priority'] === 'high' ? 'exclamation-triangle' : ($recommendation['priority'] === 'medium' ? 'lightbulb' : 'check-circle') }} text-{{ $recommendation['priority'] === 'high' ? 'danger' : ($recommendation['priority'] === 'medium' ? 'warning' : 'success') }}"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $recommendation['title'] }}</h6>
                            <p class="text-muted mb-0 small">{{ $recommendation['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-check-circle fs-1 mb-2 d-block opacity-25 text-success"></i>
                <p class="mb-0">Great progress! No specific recommendations at this time.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Weak Areas -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Areas for Improvement</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @if($weakAreas && count($weakAreas) > 0)
                @foreach($weakAreas as $area)
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">{{ $area['name'] }}</span>
                            <span class="badge bg-{{ $area['score'] < 40 ? 'danger' : 'warning' }}">{{ $area['score'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $area['score'] < 40 ? 'danger' : 'warning' }}"
                                 style="width: {{ $area['score'] }}%"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">{{ $area['tip'] }}</p>
                    </div>
                </div>
                @endforeach
            @else
            <div class="col-12 text-center py-3 text-muted">
                <p class="mb-0">No weak areas identified. Keep up the good work!</p>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<!-- No Player Selected -->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-user-circle text-muted fs-1 mb-3 d-block opacity-25"></i>
        <h5>No Player Selected</h5>
        <p class="text-muted mb-0">Please select a player to view their insights.</p>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Radar Chart - Skills
    const radarCtx = document.getElementById('skillsRadar').getContext('2d');
    new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: ['Technical', 'Tactical', 'Physical', 'Mental', 'Communication'],
            datasets: [{
                label: 'Current Skills',
                data: [{{ implode(',', array_column($skillsData, 'score')) }}],
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgb(59, 130, 246)',
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.1)' },
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    pointLabels: { font: { size: 12 } },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            }
        }
    });

    // Progress Chart
    const progressCtx = document.getElementById('progressChart').getContext('2d');
    new Chart(progressCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($progressData, 'month')) !!},
            datasets: [{
                label: 'Performance Score',
                data: {!! json_encode(array_column($progressData, 'score')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });
</script>
@endpush
@endsection
