@extends('layouts.admin')

@section('title', 'Organization Dashboard - Analytics Overview')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-0 text-gray-800">Organization Dashboard</h1>
                    <p class="text-muted mb-0">Analytics overview for all organizations</p>
                </div>
                <div>
                    <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Management
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="organizationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="dashboard-tab" href="{{ route('super-admin.organizations.dashboard') }}" role="tab" aria-selected="true">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="management-tab" href="{{ route('super-admin.organizations.index') }}" role="tab" aria-selected="false">
                        <i class="fas fa-cog"></i> Management
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="recent-tab" href="{{ route('super-admin.organizations.recent') }}" role="tab" aria-selected="false">
                        <i class="fas fa-clock"></i> Recent
                    </a>
                </li>
            </ul>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <!-- Compact Metrics Row -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $stats['total_organizations'] }}</h5>
                                    <small>Total Orgs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $stats['active_organizations'] }}</h5>
                                    <small>Active</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $stats['trial_organizations'] }}</h5>
                                    <small>Trials</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $stats['suspended_organizations'] }}</h5>
                                    <small>Suspended</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Metrics Row -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">${{ number_format($stats['monthly_revenue'], 2) }}</h6>
                                    <small>Monthly Rev</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">${{ number_format($stats['annual_revenue'], 2) }}</h6>
                                    <small>Annual Rev</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $stats['active_users'] }}</h6>
                                    <small>Active Users</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ ucfirst($stats['system_status']) }}</h6>
                                    <small>System Status</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Organization Growth</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="organizationGrowthChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Subscription Distribution</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="subscriptionDistributionChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Organization Growth Chart
    const growthCtx = document.getElementById('organizationGrowthChart');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json(collect($analytics['growth_data'])->pluck('month')),
                datasets: [{
                    label: 'New Organizations',
                    data: @json(collect($analytics['growth_data'])->pluck('count')),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
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
    }

    // Subscription Distribution Chart
    const subscriptionCtx = document.getElementById('subscriptionDistributionChart');
    if (subscriptionCtx) {
        new Chart(subscriptionCtx, {
            type: 'doughnut',
            data: {
                labels: @json($analytics['subscription_distribution']->pluck('plan.name')),
                datasets: [{
                    data: @json($analytics['subscription_distribution']->pluck('count')),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
</script>
@endsection
