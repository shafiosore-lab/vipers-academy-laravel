@extends('layouts.staff')

@section('title', 'Send Payment Reminders - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Payment Reminders</h2>
                            <p class="mb-0">Send payment reminders to players</p>
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

    <!-- Overdue Payments -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Overdue Payments</h5>
                </div>
                <div class="card-body">
                    @if(isset($overduePayments) && $overduePayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllOverdue" class="form-check-input">
                                        </th>
                                        <th>Player</th>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overduePayments as $payment)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input payment-checkbox"
                                                    value="{{ $payment->id }}">
                                            </td>
                                            <td>{{ $payment->player->first_name ?? '' }} {{ $payment->player->last_name ?? 'N/A' }}</td>
                                            <td><span class="font-monospace">{{ $payment->payment_reference }}</span></td>
                                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->due_date ? $payment->due_date->format('M j, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    {{ $payment->due_date->diffInDays(now()) }} days
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-envelope"></i> Send Reminder
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-danger" id="sendOverdueReminder">
                                <i class="fas fa-paper-plane me-1"></i> Send Reminder to Selected
                            </button>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No overdue payments</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Due Payments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Payments Due Within 7 Days</h5>
                </div>
                <div class="card-body">
                    @if(isset($pendingPayments) && $pendingPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllPending" class="form-check-input">
                                        </th>
                                        <th>Player</th>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Days Remaining</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input pending-checkbox"
                                                    value="{{ $payment->id }}">
                                            </td>
                                            <td>{{ $payment->player->first_name ?? '' }} {{ $payment->player->last_name ?? 'N/A' }}</td>
                                            <td><span class="font-monospace">{{ $payment->payment_reference }}</span></td>
                                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->due_date ? $payment->due_date->format('M j, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    {{ now()->diffInDays($payment->due_date) }} days
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-envelope"></i> Send Reminder
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-warning" id="sendPendingReminder">
                                <i class="fas fa-paper-plane me-1"></i> Send Reminder to Selected
                            </button>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No payments due within 7 days</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
