@extends('layouts.staff')

@section('title', 'Finance Analytics - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Finance Analytics</h2>
                            <p class="mb-0">Comprehensive financial overview and insights</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('finance.dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success">
                        KSh {{ number_format($paymentsByStatus->where('payment_status', 'completed')->sum('total') ?? 0, 0) }}
                    </div>
                    <p class="text-muted mb-0">Total Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">
                        KSh {{ number_format($paymentsByStatus->where('payment_status', 'pending')->sum('total') ?? 0, 0) }}
                    </div>
                    <p class="text-muted mb-0">Total Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">
                        {{ $paymentsByStatus->sum('count') ?? 0 }}
                    </div>
                    <p class="text-muted mb-0">Total Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-info">
                        {{ $paymentsByMethod->count() ?? 0 }}
                    </div>
                    <p class="text-muted mb-0">Payment Methods</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Monthly Revenue (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    @if(isset($monthlyRevenue) && $monthlyRevenue->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalMonthly = $monthlyRevenue->sum('total'); @endphp
                                    @foreach($monthlyRevenue as $data)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($data->month)->format('F Y') }}</td>
                                            <td class="text-end">KSh {{ number_format($data->total, 2) }}</td>
                                            <td class="text-end">
                                                {{ $totalMonthly > 0 ? number_format(($data->total / $totalMonthly) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th>Total</th>
                                        <th class="text-end">KSh {{ number_format($totalMonthly, 2) }}</th>
                                        <th class="text-end">100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No revenue data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payments by Method -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payments by Method</h5>
                </div>
                <div class="card-body">
                    @if(isset($paymentsByMethod) && $paymentsByMethod->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($paymentsByMethod as $method)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ ucfirst($method->payment_method) }}</span>
                                    <div class="text-end">
                                        <div class="fw-bold">KSh {{ number_format($method->total, 0) }}</div>
                                        <small class="text-muted">{{ $method->count }} transactions</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payments by Status -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Payments by Status</h5>
                </div>
                <div class="card-body">
                    @if(isset($paymentsByStatus) && $paymentsByStatus->count() > 0)
                        <div class="row">
                            @foreach($paymentsByStatus as $status)
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <div class="h4 mb-1">
                                                <span class="badge bg-{{ $status->payment_status === 'completed' ? 'success' : ($status->payment_status === 'pending' ? 'warning' : ($status->payment_status === 'failed' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($status->payment_status) }}
                                                </span>
                                            </div>
                                            <div class="display-6">{{ $status->count }}</div>
                                            <p class="text-muted mb-0">KSh {{ number_format($status->total ?? 0, 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No status data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
