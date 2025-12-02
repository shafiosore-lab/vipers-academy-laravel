@extends('layouts.admin')

@section('title', __('Create Partner - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Create New Partner') }}</h1>
                    <p class="text-muted">{{ __('Add a new partner organization to the academy network') }}</p>
                </div>
                <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Partners') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>{{ __('Partner Information') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Organization Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-building me-2"></i>{{ __('Organization Details') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="organization_name" class="form-label">{{ __('Organization Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                                       id="organization_name" name="organization_name"
                                       value="{{ old('organization_name') }}" required>
                                @error('organization_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="organization_type" class="form-label">{{ __('Organization Type') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('organization_type') is-invalid @enderror"
                                        id="organization_type" name="organization_type" required>
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="football_club" {{ old('organization_type') == 'football_club' ? 'selected' : '' }}>{{ __('Football Club') }}</option>
                                    <option value="school" {{ old('organization_type') == 'school' ? 'selected' : '' }}>{{ __('School') }}</option>
                                    <option value="academy" {{ old('organization_type') == 'academy' ? 'selected' : '' }}>{{ __('Academy') }}</option>
                                    <option value="other" {{ old('organization_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                                @error('organization_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">{{ __('Contact Person') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person" name="contact_person"
                                       value="{{ old('contact_person') }}" required>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
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

                        <!-- Account Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('Account Information') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
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
                                <label for="partnership_type" class="form-label">{{ __('Partnership Type') }}</label>
                                <select class="form-select @error('partnership_type') is-invalid @enderror"
                                        id="partnership_type" name="partnership_type">
                                    <option value="">{{ __('Select Partnership Type') }}</option>
                                    <option value="sponsorship" {{ old('partnership_type') == 'sponsorship' ? 'selected' : '' }}>{{ __('Sponsorship') }}</option>
                                    <option value="training_facility" {{ old('partnership_type') == 'training_facility' ? 'selected' : '' }}>{{ __('Training Facility') }}</option>
                                    <option value="player_development" {{ old('partnership_type') == 'player_development' ? 'selected' : '' }}>{{ __('Player Development') }}</option>
                                    <option value="scouting" {{ old('partnership_type') == 'scouting' ? 'selected' : '' }}>{{ __('Scouting') }}</option>
                                    <option value="other" {{ old('partnership_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                                @error('partnership_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">{{ __('Website') }}</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       id="website" name="website" value="{{ old('website') }}"
                                       placeholder="https://example.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="{{ __('Any additional information about this partner...') }}">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Partner') }}
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
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>{{ __('Partner Registration') }}</h6>
                        <p class="mb-0 small">{{ __('Creating a partner will send them an email with login credentials and partnership details.') }}</p>
                    </div>

                    <h6 class="mt-3 mb-2">{{ __('Required Fields') }}</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Organization Name') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Organization Type') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Contact Person') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Full Name') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Email Address') }}</li>
                        <li><i class="fas fa-check text-success me-2"></i>{{ __('Password') }}</li>
                    </ul>

                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="fas fa-shield-alt me-2 text-warning"></i>{{ __('Security Note') }}</h6>
                        <p class="small mb-0">{{ __('Partner accounts will need to be approved by an administrator before they can access the system.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
