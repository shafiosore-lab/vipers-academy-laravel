@extends('layouts.admin')

@section('title', __('Create Payment - Vipers Academy Admin'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('Record New Payment') }}</h1>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.payments.index') }}">{{ __('Payments') }}</a>
            <span>{{ __('Create') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Payments') }}
        </a>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">{{ __('Payment Information') }}</h5>
    </div>
    <div class="content-card-body">
        <form method="POST" action="{{ route('admin.payments.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payer_type" class="form-label">{{ __('Payer Type') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('payer_type') is-invalid @enderror" id="payer_type" name="payer_type" required>
                            <option value="">{{ __('Select Payer Type') }}</option>
                            <option value="player" {{ old('payer_type') == 'player' ? 'selected' : '' }}>{{ __('Player') }}</option>
                            <option value="partner" {{ old('payer_type') == 'partner' ? 'selected' : '' }}>{{ __('Partner') }}</option>
                            <option value="customer" {{ old('payer_type') == 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                        </select>
                        @error('payer_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payer_id" class="form-label">{{ __('Payer') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('payer_id') is-invalid @enderror" id="payer_id" name="payer_id" required>
                            <option value="">{{ __('Select Payer') }}</option>
                        </select>
                        @error('payer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">{{ __('Payment Type') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type" required>
                            <option value="">{{ __('Select Payment Type') }}</option>
                            <option value="registration_fee" {{ old('payment_type') == 'registration_fee' ? 'selected' : '' }}>{{ __('Registration Fee') }}</option>
                            <option value="program_fee" {{ old('payment_type') == 'program_fee' ? 'selected' : '' }}>{{ __('Program Fee') }}</option>
                            <option value="merchandise" {{ old('payment_type') == 'merchandise' ? 'selected' : '' }}>{{ __('Merchandise') }}</option>
                            <option value="donation" {{ old('payment_type') == 'donation' ? 'selected' : '' }}>{{ __('Donation') }}</option>
                            <option value="sponsorship" {{ old('payment_type') == 'sponsorship' ? 'selected' : '' }}>{{ __('Sponsorship') }}</option>
                            <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="currency" class="form-label">{{ __('Currency') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                            <option value="KES" {{ old('currency', 'KES') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        </select>
                        @error('currency')
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
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>{{ __('Cheque') }}</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_gateway" class="form-label">{{ __('Payment Gateway') }}</label>
                        <select class="form-select @error('payment_gateway') is-invalid @enderror" id="payment_gateway" name="payment_gateway">
                            <option value="">{{ __('Select Gateway (Optional)') }}</option>
                            <option value="mpesa" {{ old('payment_gateway') == 'mpesa' ? 'selected' : '' }}>{{ __('M-Pesa') }}</option>
                            <option value="stripe" {{ old('payment_gateway') == 'stripe' ? 'selected' : '' }}>{{ __('Stripe') }}</option>
                            <option value="paypal" {{ old('payment_gateway') == 'paypal' ? 'selected' : '' }}>{{ __('PayPal') }}</option>
                            <option value="bank" {{ old('payment_gateway') == 'bank' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                            <option value="cash" {{ old('payment_gateway') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                        </select>
                        @error('payment_gateway')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="due_date" class="form-label">{{ __('Due Date') }}</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-alibaba-primary">
                    <i class="fas fa-save me-2"></i>{{ __('Record Payment') }}
                </button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payerTypeSelect = document.getElementById('payer_type');
    const payerIdSelect = document.getElementById('payer_id');

    payerTypeSelect.addEventListener('change', function() {
        const payerType = this.value;
        payerIdSelect.innerHTML = '<option value="">Loading...</option>';

        if (!payerType) {
            payerIdSelect.innerHTML = '<option value="">Select Payer</option>';
            return;
        }

        // Fetch payers based on type
        fetch(`/admin/payments/get-payers?type=${payerType}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select Payer</option>';
                data.forEach(payer => {
                    options += `<option value="${payer.id}">${payer.name}</option>`;
                });
                payerIdSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Error fetching payers:', error);
                payerIdSelect.innerHTML = '<option value="">Error loading payers</option>';
            });
    });
});
</script>
@endpush

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
