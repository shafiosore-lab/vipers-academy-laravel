@extends('layouts.admin')

@section('title', __('Orders Management - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Orders Management') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <span>{{ __('Orders') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.create') }}" class="btn btn-alibaba-primary">
            <i class="fas fa-plus me-2"></i>{{ __('Create Order') }}
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $stats['total_orders'] }}</div>
                    <div class="stat-card-label">{{ __('Total Orders') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <div class="stat-card-value">KES {{ number_format($stats['total_revenue'], 0) }}</div>
                    <div class="stat-card-label">{{ __('Total Revenue') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $stats['pending_orders'] }}</div>
                    <div class="stat-card-label">{{ __('Pending Orders') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon info">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $stats['paid_orders'] }}</div>
                    <div class="stat-card-label">{{ __('Paid Orders') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('All Orders') }}</h5>
        <div class="d-flex gap-2">
            <!-- Search Form -->
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search orders...') }}" value="{{ request('search') }}" style="width: 200px;">
                <select name="status" class="form-select" style="width: 150px;">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
                <select name="payment_status" class="form-select" style="width: 150px;">
                    <option value="">{{ __('All Payments') }}</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="content-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Order #') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Order Status') }}</th>
                        <th>{{ __('Payment Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-semibold">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td class="fw-semibold">KES {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                <div class="text-muted">{{ __('No orders found') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e8e8e8;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-card-icon.primary { background: #fff5f0; color: #ea1c4d; }
    .stat-card-icon.success { background: #f0fdf4; color: #059669; }
    .stat-card-icon.warning { background: #fffbeb; color: #f59e0b; }
    .stat-card-icon.info { background: #f0f9ff; color: #0891b2; }

    .stat-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .stat-card-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

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
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 20px;
    }
</style>
@endpush
