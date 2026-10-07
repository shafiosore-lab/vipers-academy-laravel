@extends('layouts.admin')

@section('title', __('Create Order - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Create New Order') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.orders.index') }}">{{ __('Orders') }}</a>
            <span>{{ __('Create') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Orders') }}
        </a>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('Order Information') }}</h5>
    </div>
    <div class="content-card-body">
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">{{ __('Customer Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">{{ __('Customer Email') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">{{ __('Customer Phone') }}</label>
                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="">{{ __('Select Payment Method') }}</option>
                            <option value="mpesa" {{ old('payment_method') == 'mpesa' ? 'selected' : '' }}>{{ __('M-Pesa') }}</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>{{ __('Credit/Debit Card') }}</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                            <option value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>{{ __('Cash on Delivery') }}</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">{{ __('Shipping Address') }}</label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="billing_address" class="form-label">{{ __('Billing Address') }}</label>
                        <textarea class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" rows="3">{{ old('billing_address') }}</textarea>
                        @error('billing_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="subtotal" class="form-label">{{ __('Subtotal') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('subtotal') is-invalid @enderror" id="subtotal" name="subtotal" value="{{ old('subtotal') }}" required>
                        @error('subtotal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="tax_amount" class="form-label">{{ __('Tax Amount') }}</label>
                        <input type="number" step="0.01" class="form-control @error('tax_amount') is-invalid @enderror" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', 0) }}">
                        @error('tax_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="shipping_cost" class="form-label">{{ __('Shipping Cost') }}</label>
                        <input type="number" step="0.01" class="form-control @error('shipping_cost') is-invalid @enderror" id="shipping_cost" name="shipping_cost" value="{{ old('shipping_cost', 0) }}">
                        @error('shipping_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">{{ __('Discount Amount') }}</label>
                        <input type="number" step="0.01" class="form-control @error('discount_amount') is-invalid @enderror" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}">
                        @error('discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label">{{ __('Total Amount') }} <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required>
                @error('total_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('Order Items') }} <span class="text-danger">*</span></label>
                <textarea class="form-control @error('order_items') is-invalid @enderror" id="order_items" name="order_items" rows="4" placeholder='[{"name": "Product Name", "quantity": 1, "price": 100.00}]'>{{ old('order_items') }}</textarea>
                <small class="form-text text-muted">{{ __('Enter order items as JSON array') }}</small>
                @error('order_items')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-alibaba-primary">
                    <i class="fas fa-save me-2"></i>{{ __('Create Order') }}
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
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
