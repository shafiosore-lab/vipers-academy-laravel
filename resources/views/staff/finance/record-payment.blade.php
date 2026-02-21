@extends('layouts.staff')

@section('title', 'Record Payment - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Record Payment</h2>
                            <p class="mb-0">View and manage pending payments</p>
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
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $pendingPayments->count() ?? 0 }}</div>
                    <p class="text-muted mb-0">Pending Payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">KSh {{ number_format($pendingPayments->sum('amount') ?? 0, 0) }}</div>
                    <p class="text-muted mb-0">Total Pending Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-info">
                        @php
                            $overdue = $pendingPayments->filter(function($p) {
                                return $p->due_date && $p->due_date->isPast();
                            })->count();
                        @endphp
                        {{ $overdue }}
                    </div>
                    <p class="text-muted mb-0">Overdue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Payments</h5>
                    <span class="badge bg-warning">{{ $pendingPayments->count() ?? 0 }} pending</span>
                </div>
                <div class="card-body">
                    @if(isset($pendingPayments) && $pendingPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Player</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                        <tr class="{{ $payment->due_date && $payment->due_date->isPast() ? 'table-danger' : '' }}">
                                            <td>
                                                <span class="font-monospace">{{ $payment->payment_reference }}</span>
                                            </td>
                                            <td>{{ $payment->player->first_name ?? '' }} {{ $payment->player->last_name ?? 'N/A' }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                @if($payment->due_date)
                                                    @if($payment->due_date->isPast())
                                                        <span class="text-danger">{{ $payment->due_date->format('M j, Y') }}</span>
                                                    @else
                                                        {{ $payment->due_date->format('M j, Y') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payment->getStatusBadgeClass() }}">
                                                    {{ $payment->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" title="Mark as Paid">
                                                    <i class="fas fa-check"></i> Mark Paid
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No pending payments</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
