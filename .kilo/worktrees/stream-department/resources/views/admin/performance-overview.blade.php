@extends('layouts.admin')

@section('title', __('Performance Overview - Vipers Academy Admin'))

@section('breadcrumb')
<nav aria="{{ __('Breadcrumb navigation') }}">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large me-1"></i>{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-chart-line me-1" aria-hidden="true"></i>{{ __('Performance Overview') }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1">📊 Performance Overview</h2>
                        <p class="mb-0 opacity-75">Comprehensive analytics and metrics for your academy</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Player Metrics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">👥 Player Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalPlayers }}</div>
                            <small class="text-muted">Total Players</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $activePlayers }}</div>
                            <small class="text-muted">Active Players</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $pendingPlayers }}</div>
                            <small class="text-muted">Pending Players</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-secondary">{{ $inactivePlayers }}</div>
                            <small class="text-muted">Inactive Players</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $youthPlayers }}</div>
                            <small class="text-muted">Youth Players (U-)</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-dark">{{ $seniorPlayers }}</div>
                            <small class="text-muted">Senior Players</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $malePlayers }}</div>
                            <small class="text-muted">Male Players</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-pink">{{ $femalePlayers }}</div>
                            <small class="text-muted">Female Players</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚽ Performance Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h3 mb-0 text-success">{{ number_format($totalGoals) }}</div>
                            <small class="text-muted">Total Goals</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h3 mb-0 text-info">{{ number_format($totalAssists) }}</div>
                            <small class="text-muted">Total Assists</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h3 mb-0 text-warning">{{ number_format($totalMatches) }}</div>
                            <small class="text-muted">Matches Played</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $highPerformers }}</div>
                            <small class="text-muted">High Performers (8.0+)</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ number_format($averageRating, 1) }}</div>
                            <small class="text-muted">Average Rating</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $topPerformers->count() }}</div>
                            <small class="text-muted">Top Performers</small>
                        </div>
                    </div>
                </div>

                @if($topPerformers->count() > 0)
                <div class="mt-3">
                    <h6 class="mb-3">🏆 Top Performers</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Player Name</th>
                                    <th>Position</th>
                                    <th>Rating</th>
                                    <th>Goals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPerformers as $player)
                                <tr>
                                    <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                                    <td>{{ $player->position ?? 'N/A' }}</td>
                                    <td><span class="badge bg-success">{{ number_format($player->performance_rating, 1) }}</span></td>
                                    <td>{{ $player->goals_scored ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Academic Metrics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">📚 Academic Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $excellentAcademic }}</div>
                            <small class="text-muted">Excellent</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $goodAcademic }}</div>
                            <small class="text-muted">Good</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $averageAcademic }}</div>
                            <small class="text-muted">Average</small>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-danger">{{ $poorAcademic }}</div>
                            <small class="text-muted">Poor</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ number_format($averageAcademicGPA, 2) }}</div>
                            <small class="text-muted">Average GPA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contract & Career -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">📝 Contract & Career</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $playersWithContracts }}</div>
                            <small class="text-muted">With Contracts</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $nationalContracts }}</div>
                            <small class="text-muted">National Contracts</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $internationalContracts }}</div>
                            <small class="text-muted">International</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $fifaRegistered }}</div>
                            <small class="text-muted">FIFA Registered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">🌍 International & Eligibility</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $internationalPlayers }}</div>
                            <small class="text-muted">Internationally Eligible</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-danger">{{ $playersNeedingAttention }}</div>
                            <small class="text-muted">Need Attention</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $overdueFollowUps }}</div>
                            <small class="text-muted">Overdue Follow-ups</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Partnership & Programs -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">🤝 Partnership Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalPartners }}</div>
                            <small class="text-muted">Total Partners</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $activePartners }}</div>
                            <small class="text-muted">Active Partners</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $pendingPartners }}</div>
                            <small class="text-muted">Pending Partners</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-danger">{{ $inactivePartners }}</div>
                            <small class="text-muted">Inactive Partners</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">🎯 Program Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalPrograms }}</div>
                            <small class="text-muted">Total Programs</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $activePrograms }}</div>
                            <small class="text-muted">Active Programs</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $upcomingPrograms }}</div>
                            <small class="text-muted">Upcoming</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial & Content -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">💰 Financial Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">${{ number_format($totalRevenue, 2) }}</div>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">${{ number_format($monthlyRevenue, 2) }}</div>
                            <small class="text-muted">Monthly Revenue</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">${{ number_format($pendingPayments, 2) }}</div>
                            <small class="text-muted">Pending Payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">📰 Content Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalNews }}</div>
                            <small class="text-muted">Total News</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $publishedNews }}</div>
                            <small class="text-muted">Published News</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-info">{{ $totalGallery }}</div>
                            <small class="text-muted">Gallery Items</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Health -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">💚 System Health</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-primary">{{ $totalOrders }}</div>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-warning">{{ $pendingOrders }}</div>
                            <small class="text-muted">Pending Orders</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="p-3 border rounded text-center">
                            <div class="h4 mb-0 text-success">{{ $completedOrders }}</div>
                            <small class="text-muted">Completed Orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
