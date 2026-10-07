@extends('layouts.admin')

@section('title', __('Edit Partner - Vipers Academy Admin'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ __('Edit Partner') }}</h1>
                <p class="text-muted">{{ __('Update partnership information') }}</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Partners') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Partner Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.partners.update', $partner) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Account Information -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('Account Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $partner->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $partner->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Organization Information -->
                        <div class="col-md-6 mb-3">
                            <label for="organization_name" class="form-label">{{ __('Organization Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                                   id="organization_name" name="organization_name"
                                   value="{{ old('organization_name', $partner->partner_details['organization_name'] ?? '') }}" required>
                            @error('organization_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_type" class="form-label">{{ __('Organization Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('organization_type') is-invalid @enderror"
                                    id="organization_type" name="organization_type" required>
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="football_club" {{ old('organization_type', $partner->partner_details['organization_type'] ?? '') === 'football_club' ? 'selected' : '' }}>{{ __('Football Club') }}</option>
                                <option value="school" {{ old('organization_type', $partner->partner_details['organization_type'] ?? '') === 'school' ? 'selected' : '' }}>{{ __('School') }}</option>
                                <option value="academy" {{ old('organization_type', $partner->partner_details['organization_type'] ?? '') === 'academy' ? 'selected' : '' }}>{{ __('Academy') }}</option>
                                <option value="other" {{ old('organization_type', $partner->partner_details['organization_type'] ?? '') === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                            @error('organization_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6 mb-3">
                            <label for="contact_person" class="form-label">{{ __('Contact Person') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                   id="contact_person" name="contact_person"
                                   value="{{ old('contact_person', $partner->partner_details['contact_person'] ?? '') }}" required>
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_position" class="form-label">{{ __('Contact Position') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_position') is-invalid @enderror"
                                   id="contact_position" name="contact_position"
                                   value="{{ old('contact_position', $partner->partner_details['contact_position'] ?? '') }}" required>
                            @error('contact_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone"
                                   value="{{ old('phone', $partner->partner_details['phone'] ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Partnership Details -->
                        <div class="col-md-6 mb-3">
                            <label for="partnership_type" class="form-label">{{ __('Partnership Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('partnership_type') is-invalid @enderror"
                                    id="partnership_type" name="partnership_type" required>
                                <option value="">{{ __('Select Partnership Type') }}</option>
                                <option value="platform_access" {{ old('partnership_type', $partner->partner_details['partnership_type'] ?? '') === 'platform_access' ? 'selected' : '' }}>{{ __('Platform Access') }}</option>
                                <option value="scouting_services" {{ old('partnership_type', $partner->partner_details['partnership_type'] ?? '') === 'scouting_services' ? 'selected' : '' }}>{{ __('Scouting Services') }}</option>
                                <option value="training_programs" {{ old('partnership_type', $partner->partner_details['partnership_type'] ?? '') === 'training_programs' ? 'selected' : '' }}>{{ __('Training Programs') }}</option>
                                <option value="custom_solutions" {{ old('partnership_type', $partner->partner_details['partnership_type'] ?? '') === 'custom_solutions' ? 'selected' : '' }}>{{ __('Custom Solutions') }}</option>
                            </select>
                            @error('partnership_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Information -->
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" required>{{ old('address', $partner->partner_details['address'] ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city"
                                   value="{{ old('city', $partner->partner_details['city'] ?? '') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                   id="country" name="country"
                                   value="{{ old('country', $partner->partner_details['country'] ?? '') }}" required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Expected Users -->
                        <div class="col-md-6 mb-3">
                            <label for="expected_users" class="form-label">{{ __('Expected Users') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('expected_users') is-invalid @enderror"
                                   id="expected_users" name="expected_users" min="1" max="10000"
                                   value="{{ old('expected_users', $partner->partner_details['expected_users'] ?? '') }}" required>
                            @error('expected_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Number of users expected to use the platform') }}</div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $partner->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="pending" {{ old('status', $partner->status) === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="rejected" {{ old('status', $partner->status) === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Requirements -->
                        <div class="col-md-12 mb-3">
                            <label for="additional_requirements" class="form-label">{{ __('Additional Requirements') }}</label>
                            <textarea class="form-control @error('additional_requirements') is-invalid @enderror"
                                      id="additional_requirements" name="additional_requirements" rows="4"
                                      placeholder="{{ __('Any specific requirements or notes...') }}">{{ old('additional_requirements', $partner->partner_details['additional_requirements'] ?? '') }}</textarea>
                            @error('additional_requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Update Partner') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Partner Information Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Partner Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-building text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $partner->partner_details['organization_name'] ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ ucfirst($partner->partner_details['organization_type'] ?? 'other') }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-user text-success fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $partner->partner_details['contact_person'] ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $partner->partner_details['contact_position'] ?? '' }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-envelope text-info fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $partner->email }}</h6>
                            <small class="text-muted">{{ __('Account Email') }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-phone text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $partner->partner_details['phone'] ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ __('Contact Number') }}</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="mb-2">{{ __('Partnership Information') }}</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Type') }}</small>
                            <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $partner->partner_details['partnership_type'] ?? 'platform_access')) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Expected Users') }}</small>
                            <strong>{{ $partner->partner_details['expected_users'] ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Status') }}</small>
                            @if($partner->status === 'active')
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @elseif($partner->status === 'pending')
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Registered') }}</small>
                            <small>{{ $partner->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>

                @if($partner->partner_details['additional_requirements'] ?? null)
                    <hr>
                    <div>
                        <h6 class="mb-2">{{ __('Additional Requirements') }}</h6>
                        <p class="mb-0 small text-muted">{{ $partner->partner_details['additional_requirements'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-2"></i>{{ __('View Details') }}
                    </a>

                    @if($partner->status === 'pending')
                        <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="d-grid">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('Are you sure you want to approve this partner?') }}')">
                                <i class="fas fa-check me-2"></i>{{ __('Approve Partner') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.partners.reject', $partner) }}" class="d-grid">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('Are you sure you want to reject this partner?') }}')">
                                <i class="fas fa-times me-2"></i>{{ __('Reject Partner') }}
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('Are you sure you want to delete this partner? This action cannot be undone.') }}')">
                            <i class="fas fa-trash me-2"></i>{{ __('Delete Partner') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format phone number
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 10) {
            value = value.substring(0, 10);
            // Format as XXX-XXX-XXXX
            if (value.length === 10) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            }
        }
        e.target.value = value;
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('{{ __('Please fill in all required fields.') }}');
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75em;
}

@media (max-width: 768px) {
    .d-flex.justify-content-end.gap-2 {
        flex-direction: column;
    }

    .d-flex.justify-content-end.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
