@extends('layouts.staff')

@section('title', 'Match Records')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Match Records</h2>
        <p class="text-muted mb-0">View match participation and performance</p>
    </div>
</div>

<!-- Player Selector -->
@if($players && $players->count() > 1)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex align-items-center gap-3">
            <label class="text-muted mb-0">Viewing matches for:</label>
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

<!-- Match Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Appearances</p>
                <h3 class="mb-0">{{ $totalAppearances }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Minutes</p>
                <h3 class="mb-0">{{ number_format($totalMinutes) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Goals</p>
                <h3 class="mb-0 text-success">{{ $totalGoals }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Assists</p>
                <h3 class="mb-0 text-primary">{{ $totalAssists }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Yellow Cards</p>
                <h3 class="mb-0 text-warning">{{ $totalYellowCards }}</h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <p class="text-muted mb-1 small">Red Cards</p>
                <h3 class="mb-0 text-danger">{{ $totalRedCards }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Stats Chart -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Monthly Match Statistics</h5>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
            <canvas id="matchChart"></canvas>
        </div>
    </div>
</div>

<!-- Match Records -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Match History</h5>
    </div>
    <div class="card-body p-0">
        @if($matches && $matches->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Date</th>
                        <th>Opponent</th>
                        <th>Position</th>
                        <th>Minutes</th>
                        <th>Goals</th>
                        <th>Assists</th>
                        <th>Cards</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matches as $match)
                    <tr>
                        <td class="px-4 fw-semibold">{{ $match->match_date->format('M d, Y') }}</td>
                        <td>{{ $match->match->opponent ?? 'TBD' }}</td>
                        <td>{{ ucfirst($match->position_played ?? $selectedPlayer->position ?? 'N/A') }}</td>
                        <td>{{ $match->minutes_played ?? 0 }}</td>
                        <td>
                            @if(($match->goals_scored ?? 0) > 0)
                            <span class="text-success fw-semibold">{{ $match->goals_scored }}</span>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if(($match->assists ?? 0) > 0)
                            <span class="text-primary fw-semibold">{{ $match->assists }}</span>
                            @else
                            <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @if(($match->yellow_cards ?? 0) > 0)
                                <span class="badge bg-warning" title="{{ $match->yellow_cards }} yellow card(s)">Y</span>
                                @endif
                                @if(($match->red_cards ?? 0) > 0)
                                <span class="badge bg-danger" title="{{ $match->red_cards }} red card(s)">R</span>
                                @endif
                                @if(($match->yellow_cards ?? 0) == 0 && ($match->red_cards ?? 0) == 0)
                                <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $matches->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-futbol fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No match records found.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('matchChart').getContext('2d');
    const monthlyMatchStats = @json($monthlyMatchStats);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyMatchStats.map(s => s.month),
            datasets: [{
                label: 'Goals',
                data: monthlyMatchStats.map(s => s.goals),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Assists',
                data: monthlyMatchStats.map(s => s.assists),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
@endsection
