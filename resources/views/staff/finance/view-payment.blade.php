@extends('layouts.staff')

@section('title', 'Payment Details - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Payment Details</h2>
                            <p class="text-muted mb-0">Reference: {{ $payment->payment_reference }}</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('finance.payments') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Information</h5>
                    <span class="badge bg-{{ $payment->getStatusBadgeClass() }}">{{ $payment->payment_status }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Reference</dt>
                                <dd class="col-sm-8 font-monospace">{{ $payment->payment_reference }}</dd>

                                <dt class="col-sm-4">Player</dt>
                                <dd class="col-sm-8">
                                    @if($payment->player)
                                        {{ $payment->player->first_name }} {{ $payment->player->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</dd>

                                <dt class="col-sm-4">Amount</dt>
                                <dd class="col-sm-8">KSh {{ number_format($payment->amount, 2) }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Method</dt>
                                <dd class="col-sm-8">{{ ucfirst($payment->payment_method ?? 'N/A') }}</dd>

                                <dt class="col-sm-4">Paid Date</dt>
                                <dd class="col-sm-8">{{ $payment->paid_at ? $payment->paid_at->format('M j, Y') : 'N/A' }}</dd>

                                <dt class="col-sm-4">Due Date</dt>
                                <dd class="col-sm-8">{{ $payment->due_date ? $payment->due_date->format('M j, Y') : 'N/A' }}</dd>

                                <dt class="col-sm-4">Category</dt>
                                <dd class="col-sm-8">{{ $payment->category->name ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($payment->description)
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $payment->description }}</p>
                    </div>
                    @endif

                    @if($payment->notes)
                    <div class="mt-2">
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Audit Trail</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Created</dt>
                        <dd class="col-sm-9">{{ $payment->created_at->format('M j, Y H:i:s') }}</dd>

                        @if($payment->createdBy)
                        <dt class="col-sm-3">Created By</dt>
                        <dd class="col-sm-9">{{ $payment->createdBy->name }}</dd>
                        @endif

                        @if($payment->updated_by)
                        <dt class="col-sm-3">Last Updated</dt>
                        <dd class="col-sm-9">{{ $payment->updated_at->format('M j, Y H:i:s') }}</dd>
                        @endif

                        @if($payment->updatedBy)
                        <dt class="col-sm-3">Updated By</dt>
                        <dd class="col-sm-9">{{ $payment->updatedBy->name }}</dd>
                        @endif

                        @if($payment->approved_at)
                        <dt class="col-sm-3">Approved</dt>
                        <dd class="col-sm-9">{{ $payment->approved_at->format('M j, Y H:i:s') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            @if($payment->isCompleted())
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Receipt</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                    <p>Download payment receipt</p>
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </button>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Payment
                        </a>
                        @if($payment->payment_status === 'pending')
                        <form action="{{ route('finance.payments.update', $payment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="player_id" value="{{ $payment->player_id }}">
                            <input type="hidden" name="payment_type" value="{{ $payment->payment_type }}">
                            <input type="hidden" name="amount" value="{{ $payment->amount }}">
                            <input type="hidden" name="payment_method" value="{{ $payment->payment_method }}">
                            <input type="hidden" name="paid_at" value="{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d') : date('Y-m-d') }}">
                            <input type="hidden" name="payment_status" value="completed">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-1"></i> Mark as Completed
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
