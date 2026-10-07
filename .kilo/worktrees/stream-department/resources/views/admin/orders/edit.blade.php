@extends('layouts.admin')

@section('title', __('Edit Order - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Edit Order') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.orders.index') }}">{{ __('Orders') }}</a>
            <span>{{ $order->order_number }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>{{ __('View Order') }}
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Orders') }}
        </a>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('Update Order Status') }}</h5>
    </div>
    <div class="content-card-body">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_status" class="form-label">{{ __('Order Status') }}</label>
                        <select class="form-select" id="order_status" name="order_status">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            <option value="refunded" {{ $order->order_status == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">{{ __('Payment Status') }}</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ $order->notes }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-alibaba-primary">
                    <i class="fas fa-save me-2"></i>{{ __('Update Order') }}
                </button>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
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

    .btn-alibaba-primary {
        background: #ea1c4d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-alibaba-primary:hover {
        background: #d0173f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.3);
    }
</style>
@endpush
