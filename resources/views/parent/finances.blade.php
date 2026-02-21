@extends('layouts.staff')

@section('title', 'Financial Overview')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Financial Overview</h2>
        <p class="text-muted mb-0">View payment history and billing information</p>
    </div>
</div>

<!-- Player Selector -->
@if($players && $players->count() > 1)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex align-items-center gap-3">
            <label class="text-muted mb-0">Viewing finances for:</label>
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

<!-- Financial Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Outstanding Balance</p>
                        <h3 class="mb-0 {{ $currentBalance > 0 ? 'text-danger' : 'text-success' }}">KSh {{ number_format($currentBalance) }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded p-3">
                        <i class="fas fa-wallet text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Monthly Fee</p>
                        <h3 class="mb-0">KSh {{ number_format($monthlyFee) }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded p-3">
                        <i class="fas fa-calendar-alt text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total Paid</p>
                        <h3 class="mb-0 text-success">KSh {{ number_format($totalPaid) }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pending Payments</p>
                        <h3 class="mb-0 text-warning">KSh {{ number_format($totalPending) }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <i class="fas fa-clock text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($currentBalance > 0)
<!-- Alert for Outstanding Balance -->
<div class="alert alert-danger mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
        <div>
            <strong>Outstanding Balance</strong>
            <p class="mb-0">Please clear your outstanding balance number_format($currentBalance) }} to of KSh {{ avoid service interruption.</p>
        </div>
    </div>
</div>
@endif

<!-- Billing History -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Billing History</h5>
    </div>
    <div class="card-body p-0">
        @if($billings && $billings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Month</th>
                        <th>Monthly Fee</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billings as $billing)
                    <tr>
                        <td class="px-4 fw-semibold">{{ \Carbon\Carbon::parse($billing->month_year)->format('M Y') }}</td>
                        <td>KSh {{ number_format($billing->monthly_fee) }}</td>
                        <td class="text-success">KSh {{ number_format($billing->amount_paid) }}</td>
                        <td class="{{ $billing->closing_balance > 0 ? 'text-danger' : 'text-success' }}">
                            KSh {{ number_format($billing->closing_balance) }}
                        </td>
                        <td>
                            @if($billing->closing_balance <= 0)
                            <span class="badge bg-success">Paid</span>
                            @elseif($billing->amount_paid > 0)
                            <span class="badge bg-warning text-dark">Partial</span>
                            @else
                            <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-receipt fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No billing history available.</p>
        </div>
        @endif
    </div>
</div>

<!-- Payment History -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Payment History</h5>
    </div>
    <div class="card-body p-0">
        @if($payments && $payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td class="px-4">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td>{{ $payment->description ?? 'Payment' }}</td>
                        <td class="fw-semibold">KSh {{ number_format($payment->amount) }}</td>
                        <td>
                            @if($payment->payment_status === 'completed')
                            <span class="badge bg-success">Completed</span>
                            @elseif($payment->payment_status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                            @else
                            <span class="badge bg-danger">{{ ucfirst($payment->payment_status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $payments->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-money-bill-wave fs-1 mb-2 d-block opacity-25"></i>
            <p class="mb-0">No payment history available.</p>
        </div>
        @endif
    </div>
</div>
@endsection
