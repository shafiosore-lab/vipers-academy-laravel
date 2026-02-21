@extends('layouts.admin')

@section('title', 'Edit Payment Category - Mumias Vipers Academy')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Payment Category</h1>
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
        <form action="{{ route('admin.payment-categories.update', $paymentCategory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Category Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Category Name *</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $paymentCategory->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">Slug *</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $paymentCategory->slug) }}" required>
                                @error('slug')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $paymentCategory->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monthly_amount">Monthly Amount (KSh) *</label>
                                <input type="number" name="monthly_amount" id="monthly_amount"
                                    class="form-control @error('monthly_amount') is-invalid @enderror"
                                    value="{{ old('monthly_amount', $paymentCategory->monthly_amount) }}" step="0.01" min="0" required>
                                @error('monthly_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="joining_fee">One-time Joining Fee (KSh) *</label>
                                <input type="number" name="joining_fee" id="joining_fee"
                                    class="form-control @error('joining_fee') is-invalid @enderror"
                                    value="{{ old('joining_fee', $paymentCategory->joining_fee) }}" step="0.01" min="0" required>
                                @error('joining_fee')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_interval_days">Payment Interval (days) *</label>
                                <input type="number" name="payment_interval_days" id="payment_interval_days"
                                    class="form-control @error('payment_interval_days') is-invalid @enderror"
                                    value="{{ old('payment_interval_days', $paymentCategory->payment_interval_days) }}" min="1" required>
                                @error('payment_interval_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grace_period_days">Grace Period (days) *</label>
                                <input type="number" name="grace_period_days" id="grace_period_days"
                                    class="form-control @error('grace_period_days') is-invalid @enderror"
                                    value="{{ old('grace_period_days', $paymentCategory->grace_period_days) }}" min="0" required>
                                @error('grace_period_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $paymentCategory->sort_order) }}" min="0">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                        value="1" {{ old('is_active', $paymentCategory->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                    <a href="{{ route('admin.payment-categories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
