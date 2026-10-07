@extends('layouts.admin')

@section('title', __('Create Staff Member - Vipers Academy Admin'))

@push('styles')
<style>
    .role-category {
        border-left: 3px solid #6c757d;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    .role-category.coaching { border-color: #0d6efd; }
    .role-category.management { border-color: #198754; }
    .role-category.admin_operations { border-color: #dc3545; }
    .role-category.media { border-color: #fd7e14; }
    .role-category.welfare { border-color: #20c997; }
    .role-category.finance { border-color: #6f42c1; }
    .role-category.platform_administration { border-color: #000; }
    .role-category.organization_administration { border-color: #0dcaf0; }

    .subscription-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .restricted-role {
        opacity: 0.7;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Create New Staff Member') }}</h1>
                    <p class="text-muted">{{ __('Add a new staff member to the academy team') }}</p>
                </div>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Staff') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>{{ __('Staff Information') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('Personal Details') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">{{ __('Date of Birth') }}</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-briefcase me-2"></i>{{ __('Employment Details') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">{{ __('Employee ID') }}</label>
                                <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                       id="employee_id" name="employee_id" value="{{ old('employee_id') }}"
                                       placeholder="Auto-generated if empty">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" required>
                                    <option value="">{{ __('Select Role') }}</option>
                                    @forelse($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }} {{ $role->is_subscription_restricted ? 'restricted-role' : '' }}>
                                            {{ $role->name }}
                                            @if($role->description)
                                                - {{ $role->description }}
                                            @endif
                                            @if($role->is_subscription_restricted && isset($role->restriction_status))
                                                ({{ $role->restriction_status }})
                                            @endif
                                        </option>
                                    @empty
                                        <option value="" disabled>{{ __('No roles available. Please contact administrator.') }}</option>
                                    @endforelse
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    @if(isset($subscriptionContext) && $subscriptionContext['is_super_admin'])
                                        <span class="badge bg-dark"><i class="fas fa-crown me-1"></i>{{ __('Super Admin: All roles available') }}</span>
                                    @elseif(isset($subscriptionContext) && $subscriptionContext['is_org_admin'])
                                        <span class="badge bg-info"><i class="fas fa-building me-1"></i>{{ __('Organization Admin: Limited by subscription') }}</span>
                                        @if(isset($subscriptionContext['subscription_plan']))
                                            <span class="badge bg-success ms-1">{{ $subscriptionContext['subscription_plan'] }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">{{ __('Available roles based on your permission level') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">{{ __('Department') }}</label>
                                <select class="form-select @error('department') is-invalid @enderror"
                                        id="department" name="department">
                                    <option value="">{{ __('Select Department') }}</option>
                                    <option value="coaching" {{ old('department') == 'coaching' ? 'selected' : '' }}>{{ __('Coaching') }}</option>
                                    <option value="administration" {{ old('department') == 'administration' ? 'selected' : '' }}>{{ __('Administration') }}</option>
                                    <option value="medical" {{ old('department') == 'medical' ? 'selected' : '' }}>{{ __('Medical') }}</option>
                                    <option value="facilities" {{ old('department') == 'facilities' ? 'selected' : '' }}>{{ __('Facilities') }}</option>
                                    <option value="other" {{ old('department') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hire_date" class="form-label">{{ __('Hire Date') }}</label>
                                <input type="date" class="form-control @error('hire_date') is-invalid @enderror"
                                       id="hire_date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}">
                                @error('hire_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">{{ __('Monthly Salary') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">KES</span>
                                    <input type="number" class="form-control @error('salary') is-invalid @enderror"
                                           id="salary" name="salary" value="{{ old('salary') }}" min="0" step="0.01">
                                </div>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-lock me-2"></i>{{ __('Account Setup') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>{{ __('Additional Information') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label">{{ __('Emergency Contact Name') }}</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                       id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_phone" class="form-label">{{ __('Emergency Contact Phone') }}</label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                       id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="qualifications" class="form-label">{{ __('Qualifications & Experience') }}</label>
                                <textarea class="form-control @error('qualifications') is-invalid @enderror"
                                          id="qualifications" name="qualifications" rows="3"
                                          placeholder="{{ __('List relevant qualifications, certifications, and experience...') }}">{{ old('qualifications') }}</textarea>
                                @error('qualifications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">{{ __('Additional Notes') }}</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="{{ __('Any additional information about this staff member...') }}">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Staff Member') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>{{ __('Information') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Subscription Context -->
                    @if(isset($subscriptionContext))
                    <div class="alert @if($subscriptionContext['is_super_admin']) alert-dark @elseif($subscriptionContext['is_org_admin']) alert-info @else alert-secondary @endif mb-3">
                        <h6><i class="fas @if($subscriptionContext['is_super_admin']) fa-crown @elseif($subscriptionContext['is_org_admin']) fa-building @else fa-user-shield @endif me-2"></i>
                        @if($subscriptionContext['is_super_admin'])
                            {{ __('Super Administrator') }}
                        @elseif($subscriptionContext['is_org_admin'])
                            {{ __('Organization Administrator') }}
                        @else
                            {{ __('Staff Manager') }}
                        @endif
                        </h6>
                        @if($subscriptionContext['is_super_admin'])
                            <p class="mb-0 small">{{ __('You have access to all roles in the system.') }}</p>
                        @elseif($subscriptionContext['is_org_admin'])
                            <p class="mb-0 small">{{ __('You can only assign roles available in your subscription plan.') }}</p>
                            @if(isset($subscriptionContext['subscription_plan']))
                                <strong>{{ __('Current Plan:') }}</strong> {{ $subscriptionContext['subscription_plan'] }}
                            @endif
                        @else
                            <p class="mb-0 small">{{ __('You can assign basic staff roles.') }}</p>
                        @endif
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>{{ __('Staff Registration') }}</h6>
                        <p class="mb-0 small">{{ __('Creating a staff member will send them an email with login credentials and their account details.') }}</p>
                    </div>

                    <h6 class="mt-3 mb-2">{{ __('Required Fields') }}</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('First Name') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Last Name') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Email Address') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Phone Number') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Role') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Password') }}</li>
                    </ul>

                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="fas fa-shield-alt me-2 text-warning"></i>{{ __('Security Note') }}</h6>
                        <p class="small mb-0">{{ __('Staff accounts will have access based on their assigned role and department.') }}</p>
                    </div>

                    <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded">
                        <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>{{ __('Employee ID') }}</h6>
                        <p class="small mb-0">{{ __('If not provided, an employee ID will be auto-generated based on the staff member\'s role and join date.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
