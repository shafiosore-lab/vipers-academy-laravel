@extends('player.portal.layout')

@section('title', 'My Payments - Mumias Vipers Academy')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Payments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('player.portal.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Payments</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Payment Category Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">
                                    <i class="fas fa-crown me-2"></i>
                                    {{ $paymentCategory ? $paymentCategory->name : 'Standard Category' }}
                                </h4>
                                <p class="mb-0">
                                    Monthly: KSh {{ number_format($paymentCategory ? $paymentCategory->monthly_amount : ($player->fee_category === 'B' ? 500 : 200), 0) }} |
                                    Joining Fee: KSh {{ number_format($paymentCategory ? $paymentCategory->joining_fee : ($player->fee_category === 'B' ? 1000 : 100), 0) }}
                                </p>
                            </div>
                            <div class="text-end">
                                @if($overduePayments->count() > 0)
                                    <span class="badge bg-danger">Overdue: KSh {{ number_format($totalOverdue, 0) }}</span>
                                @elseif($pendingPayments->count() > 0)
                                    <span class="badge bg-warning">Pending: KSh {{ number_format($totalPending, 0) }}</span>
                                @else
                                    <span class="badge bg-success">All Clear</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-success">KSh {{ number_format($totalPaid, 0) }}</div>
                        <p class="text-muted mb-0">Total Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-warning">KSh {{ number_format($totalPending, 0) }}</div>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-danger">KSh {{ number_format($totalOverdue, 0) }}</div>
                        <p class="text-muted mb-0">Overdue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4">{{ $payments->count() }}</div>
                        <p class="text-muted mb-0">Transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Payment Alert -->
        @if($upcomingPayment)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="alert-heading"><i class="fas fa-bell me-2"></i>Upcoming Payment</h5>
                            <p class="mb-0">
                                <strong>{{ $upcomingPayment['type'] }}</strong> of
                                KSh {{ number_format($upcomingPayment['amount'], 0) }}
                                is due on {{ $upcomingPayment['due_date']->format('M j, Y') }}
                            </p>
                        </div>
                        <button class="btn btn-primary">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Overdue Payments -->
        @if($overduePayments->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Payments</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overduePayments as $payment)
                                    <tr>
                                        <td><span class="font-monospace">{{ $payment->payment_reference }}</span></td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                        <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->due_date->format('M j, Y') }}</td>
                                        <td><span class="badge bg-danger">{{ $payment->due_date->diffInDays(now()) }} days</span></td>
                                        <td><button class="btn btn-sm btn-danger">Pay Now</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment History -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment History</h3>
                    </div>
                    <div class="card-body">
                        @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Reference</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td><span class="font-monospace">{{ $payment->payment_reference }}</span></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->getStatusBadgeClass() }}">
                                                    {{ $payment->payment_status }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->due_date ? $payment->due_date->format('M j, Y') : 'N/A' }}</td>
                                            <td>{{ $payment->paid_at ? $payment->paid_at->format('M j, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($payment->isCompleted())
                                                    <button class="btn btn-sm btn-outline-primary" title="Download Receipt">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $payments->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <h4>No Payment History</h4>
                                <p class="text-muted">Your payment history will appear here once you make payments.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
