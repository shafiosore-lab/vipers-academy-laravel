@extends('layouts.staff')

@section('title', 'Team Manager Dashboard - Vipers Academy')

@section('content')
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Team Manager Dashboard</h2>
                            <p class="mb-0">Welcome back, {{ auth()->user()->name }}!</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">{{ $players ?? 0 }}</div>
                    <p class="text-muted mb-0">Total Players</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">{{ $activePrograms ?? 0 }}</div>
                    <p class="text-muted mb-0">Active Programs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $pendingOrders ?? 0 }}</div>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-info">{{ $pendingRegistrations ?? 0 }}</div>
                    <p class="text-muted mb-0">New Registrations</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Payment Overview -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payment Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="display-6 text-success">KSh {{ number_format($completedPayments ?? 0, 2) }}</div>
                            <p class="text-muted">Completed</p>
                        </div>
                        <div class="col-6">
                            <div class="display-6 text-warning">KSh {{ number_format($pendingPayments ?? 0, 2) }}</div>
                            <p class="text-muted">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders as $order)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                        <small class="text-muted">{{ $order->created_at->format('M j, Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $order->order_status === 'pending' ? 'warning' : 'success' }}">
                                        {{ $order->order_status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent orders</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus mb-2 d-block"></i>
                                New Registration
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-success w-100">
                                <i class="fas fa-clipboard-check mb-2 d-block"></i>
                                Approve Registrations
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100">
                                <i class="fas fa-box mb-2 d-block"></i>
                                Manage Orders
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="fas fa-calendar-alt mb-2 d-block"></i>
                                Schedule Programs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
