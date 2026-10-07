@extends('layouts.admin')

@section('title', 'Create Organization')

@section('content')
<div class="container-fluid py-3">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h5 mb-0 text-gray-900">Create Organization</h1>
                    <small class="text-muted">Add a new organization to the system</small>
                </div>
                <div>
                    <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form action="{{ route('super-admin.organizations.store') }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Domain <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('domain') is-invalid @enderror"
                                       name="domain" value="{{ old('domain') }}" required>
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('country') is-invalid @enderror"
                                       name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('status') is-invalid @enderror"
                                        name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Subscription Information -->
                        <div class="border-top my-3"></div>
                        <h6 class="mb-3 fw-semibold">Subscription Details</h6>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subscription Plan <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('plan_id') is-invalid @enderror"
                                        name="plan_id" required>
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - ${{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Billing Cycle <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('billing_cycle') is-invalid @enderror"
                                        name="billing_cycle" required>
                                    <option value="">Select Billing Cycle</option>
                                    <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="annual" {{ old('billing_cycle') == 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                @error('billing_cycle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm @error('amount') is-invalid @enderror"
                                           name="amount" value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm @error('start_date') is-invalid @enderror"
                                       name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                                       name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row g-2">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Create Organization
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header py-2">
                    <h6 class="mb-0 fw-semibold">Quick Info</h6>
                </div>
                <div class="card-body p-2">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info"></i>
                            <small><strong>Organization Name:</strong> Must be unique across all organizations</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info"></i>
                            <small><strong>Domain:</strong> Used for email verification and organization identification</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info"></i>
                            <small><strong>Status:</strong> Active organizations can access the system</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-info-circle text-info"></i>
                            <small><strong>Subscription:</strong> Will be created automatically with the organization</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.querySelector('select[name="plan_id"]');
    const amountInput = document.querySelector('input[name="amount"]');

    // Update amount based on selected plan
    planSelect?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const priceMatch = selectedOption.text.match(/\$(\d+(\.\d{1,2})?)/);
            if (priceMatch) {
                amountInput.value = priceMatch[1];
            }
        }
    });

    // Set default dates
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    if (startDateInput && !startDateInput.value) {
        startDateInput.value = new Date().toISOString().split('T')[0];
    }

    if (endDateInput && !endDateInput.value) {
        const endDate = new Date();
        endDate.setFullYear(endDate.getFullYear() + 1);
        endDateInput.value = endDate.toISOString().split('T')[0];
    }
});
</script>
@endpush
@endsection
