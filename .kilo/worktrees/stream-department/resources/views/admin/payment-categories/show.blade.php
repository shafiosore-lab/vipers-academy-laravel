@extends('layouts.admin')

@section('title', $paymentCategory->name . ' - Mumias Vipers Academy')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $paymentCategory->name }}</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('admin.payment-categories.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Category Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Category Information</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.payment-categories.edit', $paymentCategory->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Monthly Amount</dt>
                            <dd class="col-sm-8">KSh {{ number_format($paymentCategory->monthly_amount, 2) }}</dd>

                            <dt class="col-sm-4">Joining Fee</dt>
                            <dd class="col-sm-8">KSh {{ number_format($paymentCategory->joining_fee, 2) }}</dd>

                            <dt class="col-sm-4">Payment Interval</dt>
                            <dd class="col-sm-8">{{ $paymentCategory->payment_interval_days }} days</dd>

                            <dt class="col-sm-4">Grace Period</dt>
                            <dd class="col-sm-8">{{ $paymentCategory->grace_period_days }} days</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                @if($paymentCategory->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Players</dt>
                            <dd class="col-sm-8">{{ $players->count() }}</dd>

                            <dt class="col-sm-4">Total Revenue</dt>
                            <dd class="col-sm-8 text-success">KSh {{ number_format($paymentCategory->getTotalRevenue(), 2) }}</dd>

                            <dt class="col-sm-4">Pending</dt>
                            <dd class="col-sm-8 text-warning">KSh {{ number_format($paymentCategory->getPendingAmount(), 2) }}</dd>
                        </dl>
                    </div>
                </div>

                @if($paymentCategory->description)
                <div class="row mt-3">
                    <div class="col-12">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $paymentCategory->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Players in this Category -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Players in this Category ({{ $players->count() }})</h3>
            </div>
            <div class="card-body">
                @if($players->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Registration No</th>
                                    <th>Program</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players as $player)
                                <tr>
                                    <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                                    <td>{{ $player->registration_number ?? 'N/A' }}</td>
                                    <td>{{ $player->program->name ?? 'N/A' }}</td>
                                    <td>{{ $player->created_at->format('M j, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No players in this category</p>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment History</h3>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Player</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Paid Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td><span class="font-monospace">{{ $payment->payment_reference }}</span></td>
                                    <td>{{ $payment->player->first_name ?? 'N/A' }} {{ $payment->player->last_name ?? '' }}</td>
                                    <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->getStatusBadgeClass() }}">
                                            {{ $payment->payment_status }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->due_date ? $payment->due_date->format('M j, Y') : 'N/A' }}</td>
                                    <td>{{ $payment->paid_at ? $payment->paid_at->format('M j, Y') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $payments->links() }}
                @else
                    <p class="text-muted text-center py-4">No payment history</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
