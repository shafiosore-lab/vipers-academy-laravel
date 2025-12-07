@extends('layouts.academy')

@section('title', 'Partner Registration - Vipers Academy')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white text-center py-4">
                    <i class="fas fa-handshake fa-3x mb-3"></i>
                    <h2 class="mb-0">Become a Vipers Academy Partner</h2>
                    <p class="mb-0 opacity-75">Join our network of elite football organizations</p>
                </div>

                <div class="card-body p-5">
                    <!-- Partnership Benefits -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-center mb-4 text-primary">Why Partner with Vipers Academy?</h4>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                                        <h5>Access to Talent Pool</h5>
                                        <p class="text-muted">Connect with our network of scouts and player development programs</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                                        <h5>Advanced Analytics</h5>
                                        <p class="text-muted">Access to player performance data and scouting reports</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <i class="fas fa-certificate fa-3x text-warning mb-3"></i>
                                        <h5>Certification Programs</h5>
                                        <p class="text-muted">Joint certification and training programs for coaches and players</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('register.partner.store') }}" method="POST">
                        @csrf

                        <!-- Account Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-primary border-bottom pb-2">
                                    <i class="fas fa-user-lock me-2"></i>Account Information
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">This will be your login email</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required minlength="8">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" required>
                                    <div class="form-text">Re-enter your password</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Organization Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-success border-bottom pb-2">
                                    <i class="fas fa-building me-2"></i>Organization Information
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="organization_name" class="form-label">Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                                           id="organization_name" name="organization_name" value="{{ old('organization_name') }}" required>
                                    @error('organization_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="organization_type" class="form-label">Organization Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('organization_type') is-invalid @enderror"
                                            id="organization_type" name="organization_type" required>
                                        <option value="">Select Type</option>
                                        <option value="football_club" {{ old('organization_type') == 'football_club' ? 'selected' : '' }}>Football Club</option>
                                        <option value="school" {{ old('organization_type') == 'school' ? 'selected' : '' }}>School/Academy</option>
                                        <option value="academy" {{ old('organization_type') == 'academy' ? 'selected' : '' }}>Youth Academy</option>
                                        <option value="other" {{ old('organization_type') == 'other' ? 'selected' : '' }}>Other Sports Organization</option>
                                    </select>
                                    @error('organization_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                           id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required>
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Full name of the primary contact</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_position" class="form-label">Position/Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_position') is-invalid @enderror"
                                           id="contact_position" name="contact_position" value="{{ old('contact_position') }}" required>
                                    @error('contact_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">e.g., Coach, Manager, Director</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-info border-bottom pb-2">
                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-select @error('country') is-invalid @enderror"
                                            id="country" name="country" required>
                                        <option value="">Select Country</option>
                                        <option value="Kenya" {{ old('country') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                        <option value="Tanzania" {{ old('country') == 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                        <option value="Uganda" {{ old('country') == 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                        <option value="Rwanda" {{ old('country') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                                        <option value="Burundi" {{ old('country') == 'Burundi' ? 'selected' : '' }}>Burundi</option>
                                        <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Partnership Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="mb-4 text-warning border-bottom pb-2">
                                    <i class="fas fa-handshake me-2"></i>Partnership Details
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="partnership_type" class="form-label">Partnership Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('partnership_type') is-invalid @enderror"
                                            id="partnership_type" name="partnership_type" required>
                                        <option value="">Select Partnership Type</option>
                                        <option value="platform_access" {{ old('partnership_type') == 'platform_access' ? 'selected' : '' }}>Platform Access</option>
                                        <option value="scouting_services" {{ old('partnership_type') == 'scouting_services' ? 'selected' : '' }}>Scouting Services</option>
                                        <option value="training_programs" {{ old('partnership_type') == 'training_programs' ? 'selected' : '' }}>Training Programs</option>
                                        <option value="custom_solutions" {{ old('partnership_type') == 'custom_solutions' ? 'selected' : '' }}>Custom Solutions</option>
                                    </select>
                                    @error('partnership_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expected_users" class="form-label">Expected Number of Users <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('expected_users') is-invalid @enderror"
                                           id="expected_users" name="expected_users" value="{{ old('expected_users') }}"
                                           min="1" max="10000" required>
                                    @error('expected_users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">How many users will access the platform?</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additional_requirements" class="form-label">Additional Requirements</label>
                                    <textarea class="form-control @error('additional_requirements') is-invalid @enderror"
                                              id="additional_requirements" name="additional_requirements" rows="4"
                                              placeholder="Please describe any specific requirements, customizations, or additional services you need...">{{ old('additional_requirements') }}</textarea>
                                    @error('additional_requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Describe your specific needs or requirements</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Terms and Conditions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                               type="checkbox" id="terms_accepted" name="terms_accepted" value="1" required>
                                        <label class="form-check-label" for="terms_accepted">
                                            I agree to the <a href="#" class="text-primary">Partnership Agreement</a>,
                                            <a href="#" class="text-primary">Terms and Conditions</a>, and
                                            <a href="#" class="text-primary">Privacy Policy</a> <span class="text-danger">*</span>
                                        </label>
                                        @error('terms_accepted')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Your partnership application will be reviewed by our team.
                                    You will receive an email confirmation and our partnership manager will contact you within 2-3 business days.
                                </div>

                                <div class="d-flex justify-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Partnership Application
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign In</a> |
                        Looking to join as a player? <a href="{{ route('register.player') }}" class="text-success">Player Registration</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
    }

    .alert-info {
        border-left: 4px solid #17a2b8;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 2rem 1rem !important;
        }
    }
</style>

<script>
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;

        if (password !== confirmPassword) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Dynamic partnership type description
    document.getElementById('partnership_type').addEventListener('change', function() {
        const descriptions = {
            'platform_access': 'Full access to our player management and analytics platform',
            'scouting_services': 'Access to our scouting network and player evaluation tools',
            'training_programs': 'Joint training programs and certification opportunities',
            'custom_solutions': 'Tailored solutions based on your specific requirements'
        };

        const description = descriptions[this.value] || '';
        // You can add a description display here if needed
    });
</script>
