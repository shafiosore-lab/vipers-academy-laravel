@extends('layouts.admin')

@section('title', 'Analytics - Super Admin')

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Subscription Analytics</h5>
        <a href="{{ route('super-admin.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Active Subscriptions</div>
                            <div class="h4 mb-0">{{ $subscriptionStats['active'] }}</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Trial Subscriptions</div>
                            <div class="h4 mb-0">{{ $subscriptionStats['trialing'] }}</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Canceled Subscriptions</div>
                            <div class="h4 mb-0">{{ $subscriptionStats['canceled'] }}</div>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Monthly Revenue Trend</h6>
        </div>
        <div class="card-body">
            @if(!empty($monthlyRevenue))
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyRevenue as $revenue)
                                <tr>
                                    <td>{{ $revenue['month'] }}</td>
                                    <td class="text-end">${{ number_format($revenue['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th>Total</th>
                                <th class="text-end">${{ number_format(array_sum(array_column($monthlyRevenue, 'amount')), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">No revenue data available.</p>
            @endif
        </div>
    </div>

    <!-- Plan Distribution -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Plan Distribution</h6>
        </div>
        <div class="card-body">
            @if(!empty($planDistribution) && $planDistribution->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Plan ID</th>
                                <th class="text-end">Subscriptions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($planDistribution as $planId => $count)
                                <tr>
                                    <td>Plan #{{ $planId }}</td>
                                    <td class="text-end">{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">No subscription data available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
