@extends('layouts.staff')

@section('title', 'Finance Officer Dashboard - Vipers Academy')

@section('content')
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Finance Officer Dashboard</h2>
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

    <!-- Financial Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">KSh {{ number_format($totalRevenue ?? 0, 0) }}</div>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">KSh {{ number_format($monthlyRevenue ?? 0, 0) }}</div>
                    <p class="text-muted mb-0">This Month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">KSh {{ number_format($pendingPayments ?? 0, 0) }}</div>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-danger">KSh {{ number_format($overduePayments ?? 0, 0) }}</div>
                    <p class="text-muted mb-0">Overdue</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Statistics -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="display-6">{{ $totalOrders ?? 0 }}</div>
                            <p class="text-muted">Total</p>
                        </div>
                        <div class="col-4">
                            <div class="display-6 text-warning">{{ $pendingOrders ?? 0 }}</div>
                            <p class="text-muted">Pending</p>
                        </div>
                        <div class="col-4">
                            <div class="display-6 text-success">{{ $completedOrders ?? 0 }}</div>
                            <p class="text-muted">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments by Method -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payments by Method</h5>
                </div>
                <div class="card-body">
                    @if(isset($paymentsByMethod) && $paymentsByMethod->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($paymentsByMethod as $method)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ ucfirst($method->payment_method) }}</span>
                                    <div class="text-end">
                                        <div class="fw-bold">KSh {{ number_format($method->total, 0) }}</div>
                                        <small class="text-muted">{{ $method->count }} transactions</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No payment data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Payments</h5>
                    <a href="{{ route('finance.payments') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentPayments) && $recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->player->first_name ?? '' }} {{ $payment->player->last_name ?? 'N/A' }}</td>
                                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $payment->payment_status }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent payments</p>
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
                            <a href="{{ route('finance.payments.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-money-bill-wave mb-2 d-block"></i>
                                Record Payment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.reports') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-invoice-dollar mb-2 d-block"></i>
                                Generate Report
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.reminders') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-exclamation-circle mb-2 d-block"></i>
                                Send Reminders
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('finance.analytics') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-pie mb-2 d-block"></i>
                                Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
