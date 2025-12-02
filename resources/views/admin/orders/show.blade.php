@extends('layouts.admin')

@section('title', __('Order Details - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Order Details') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.orders.index') }}">{{ __('Orders') }}</a>
            <span>{{ $order->order_number }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>{{ __('Edit Order') }}
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Orders') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Order Information') }}</h5>
            </div>
            <div class="content-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Order Number') }}</label>
                            <p class="mb-0">{{ $order->order_number }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Order Date') }}</label>
                            <p class="mb-0">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Order Status') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Payment Status') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold mb-3">{{ __('Customer Information') }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Customer Name') }}</label>
                            <p class="mb-0">{{ $order->customer_name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Customer Email') }}</label>
                            <p class="mb-0">{{ $order->customer_email }}</p>
                        </div>
                    </div>
                    @if($order->customer_phone)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('Customer Phone') }}</label>
                                <p class="mb-0">{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($order->shipping_address)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Shipping Address') }}</label>
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    </div>
                @endif

                @if($order->notes)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('Notes') }}</label>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Order Items') }}</h5>
            </div>
            <div class="content-card-body">
                @if($order->order_items)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Item') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->order_items as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? 'Unknown Item' }}</td>
                                        <td>{{ $item['quantity'] ?? 1 }}</td>
                                        <td>KES {{ number_format($item['price'] ?? 0, 2) }}</td>
                                        <td class="fw-semibold">KES {{ number_format(($item['quantity'] ?? 1) * ($item['price'] ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                        <div class="text-muted">{{ __('No order items found') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Order Summary') }}</h5>
            </div>
            <div class="content-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ __('Subtotal') }}</span>
                    <span>KES {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Tax') }}</span>
                        <span>KES {{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                @endif
                @if($order->shipping_cost > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Shipping') }}</span>
                        <span>KES {{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                @endif
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>{{ __('Discount') }}</span>
                        <span>-KES {{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>{{ __('Total') }}</span>
                    <span>KES {{ number_format($order->total_amount, 2) }}</span>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold">{{ __('Payment Method') }}</label>
                    <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Order Timeline') }}</h5>
            </div>
            <div class="content-card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">{{ __('Order Created') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>

                    @if($order->paid_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="fw-semibold">{{ __('Payment Completed') }}</div>
                                <small class="text-muted">{{ $order->paid_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    @endif

                    @if($order->shipped_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="fw-semibold">{{ __('Order Shipped') }}</div>
                                <small class="text-muted">{{ $order->shipped_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    @endif

                    @if($order->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="fw-semibold">{{ __('Order Delivered') }}</div>
                                <small class="text-muted">{{ $order->delivered_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
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

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e8e8e8;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e8e8e8;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e8e8e8;
    }

    .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 20px;
    }

    .table th {
        background: #f8f9fa;
        border-bottom: 2px solid #e8e8e8;
        font-weight: 600;
        font-size: 14px;
        color: #495057;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }
</style>
@endpush
