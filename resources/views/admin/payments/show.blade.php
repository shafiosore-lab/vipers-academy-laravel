@extends('layouts.admin')

@section('title', __('Payment Details - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Payment Details') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.payments.index') }}">{{ __('Payments') }}</a>
            <span>{{ $payment->payment_reference }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>{{ __('Edit Payment') }}
        </a>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Payments') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Payment Information -->
    <div class="col-lg-8 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Payment Information') }}</h5>
            </div>
            <div class="content-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Reference') }}</label>
                            <p class="mb-0">{{ $payment->payment_reference }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Transaction ID') }}</label>
                            <p class="mb-0">{{ $payment->transaction_id ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Type') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Status') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Amount') }}</label>
                            <p class="mb-0 fw-semibold fs-5">{{ $payment->getFormattedAmount() }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Method') }}</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Gateway') }}</label>
                            <p class="mb-0">{{ $payment->payment_gateway ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Date Created') }}</label>
                            <p class="mb-0">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold mb-3">{{ __('Payer Information') }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payer Type') }}</label>
                            <p class="mb-0">{{ ucfirst($payment->payer_type) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payer Name') }}</label>
                            <p class="mb-0">{{ $payment->getPayerName() }}</p>
                        </div>
                    </div>
                </div>

                @if($payment->description)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Description') }}</label>
                        <p class="mb-0">{{ $payment->description }}</p>
                    </div>
                @endif

                @if($payment->due_date)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Due Date') }}</label>
                        <p class="mb-0">{{ $payment->due_date->format('M d, Y') }}</p>
                    </div>
                @endif

                @if($payment->paid_at)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Paid At') }}</label>
                        <p class="mb-0">{{ $payment->paid_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif

                @if($payment->notes)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Notes') }}</label>
                        <p class="mb-0">{{ $payment->notes }}</p>
                    </div>
                @endif

                @if($payment->gateway_response)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Gateway Response') }}</label>
                        <pre class="bg-light p-2 rounded">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="col-lg-4 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Payment Summary') }}</h5>
            </div>
            <div class="content-card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-semibold">{{ __('Amount') }}</span>
                    <span class="fw-bold fs-4">{{ $payment->getFormattedAmount() }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>{{ __('Status') }}</span>
                    <span class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>{{ __('Type') }}</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>{{ __('Method') }}</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>

                @if($payment->due_date)
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Due Date') }}</span>
                        <span>{{ $payment->due_date->format('M d, Y') }}</span>
                    </div>
                @endif

                @if($payment->paid_at)
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Paid Date') }}</span>
                        <span>{{ $payment->paid_at->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Quick Actions') }}</h5>
            </div>
            <div class="content-card-body">
                @if($payment->isPending())
                    <form method="POST" action="{{ route('admin.payments.updateStatus', $payment) }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>{{ __('Mark as Completed') }}
                        </button>
                    </form>
                @endif

                @if($payment->isPending())
                    <form method="POST" action="{{ route('admin.payments.updateStatus', $payment) }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="status" value="failed">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-2"></i>{{ __('Mark as Failed') }}
                        </button>
                    </form>
                @endif

                @if($payment->isCompleted())
                    <form method="POST" action="{{ route('admin.payments.updateStatus', $payment) }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="status" value="refunded">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-undo me-2"></i>{{ __('Mark as Refunded') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e8e8e8;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .content-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e8e8e8;
        background: #f8f9fa;
    }

    .content-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .content-card-body {
        padding: 1.5rem;
    }

    .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 20px;
    }

    pre {
        font-size: 12px;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush
