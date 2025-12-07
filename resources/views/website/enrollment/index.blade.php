@extends('layouts.academy')

@section('title', 'Enroll in a Program - Vipers Academy')

@section('meta_description', 'Enroll in Vipers Academy football programs. Choose your program and start your journey to football excellence in Kenya.')

@section('content')


<!-- Main Enrollment Section -->
<section class="enrollment-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                @auth
                <!-- Authenticated User Enrollment -->
                <article class="enrollment-card card border-0 shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <header class="card-header bg-success text-white text-center py-4">
                        <div class="header-icon mb-3">
                            <i class="fas fa-user-check fa-3x"></i>
                        </div>
                        <h2 class="h4 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                        <p class="mb-0 opacity-90">Ready to enroll in a program?</p>
                    </header>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('programs.enroll') }}" method="POST" id="enrollmentForm" novalidate>
                            @csrf

                            <!-- Program Selection -->
                            <div class="form-group mb-4">
                                <label for="program_id" class="form-label fw-bold h5 mb-3 d-flex align-items-center">
                                    <i class="fas fa-futbol text-success me-3 fs-4"></i>
                                    <span>Select Program</span>
                                </label>
                                <select name="program_id" id="program_id" class="form-select form-select-lg border-0 shadow-sm" required aria-describedby="programHelp">
                                    <option value="" disabled selected>Choose a program...</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}"
                                            data-regular-fee="{{ $program->regular_fee }}"
                                            data-mumias-fee="{{ $program->mumias_fee ?? $program->regular_fee }}">
                                        {{ $program->title }} - {{ $program->age_group }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="programHelp" class="form-text mt-2">Select a program to view fees and enrollment options</div>
                            </div>

                            <!-- Fee Display Cards -->
                            <div class="fee-display mb-4">
                                <h3 class="h5 fw-bold mb-4 text-center">Available Programs & Pricing</h3>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="fee-card card h-100 border-2 border-primary shadow-sm">
                                            <div class="card-body text-center p-4 d-flex flex-column">
                                                <div class="fee-icon mb-3">
                                                    <i class="fas fa-star fa-2x text-primary"></i>
                                                </div>
                                                <h4 class="text-primary fw-bold mb-3">Regular Fee</h4>
                                                <div class="fee-amount display-6 text-primary fw-bold mb-auto" id="regular-fee">
                                                    Select a program
                                                </div>
                                                <small class="text-muted mt-2">Standard enrollment fee</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fee-card card h-100 border-2 border-success shadow-sm">
                                            <div class="card-body text-center p-4 d-flex flex-column">
                                                <div class="fee-icon mb-3">
                                                    <i class="fas fa-heart fa-2x text-success"></i>
                                                </div>
                                                <h4 class="text-success fw-bold mb-3">Community Fee</h4>
                                                <div class="fee-amount display-6 text-success fw-bold mb-auto" id="mumias-fee">
                                                    Select a program
                                                </div>
                                                <small class="text-muted mt-2">Special community pricing</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="terms-section mb-4">
                                <div class="form-check border rounded p-4 bg-light">
                                    <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required aria-describedby="termsText">
                                    <label class="form-check-label fw-semibold" for="terms_accepted">
                                        <span class="d-block">I agree to the program terms and enrollment policy</span>
                                        <small class="text-muted d-block mt-1">
                                            By enrolling, you agree to our
                                            <a href="#" class="text-success text-decoration-none fw-bold">Terms & Conditions</a>
                                            and <a href="#" class="text-success text-decoration-none fw-bold">Enrollment Policy</a>
                                        </small>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg py-3 fw-bold" id="enrollBtn" disabled>
                                    <span class="btn-text">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        <span>Enroll Now</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </article>

                <!-- Enrollment Benefits Section -->
                <section class="benefits-section mt-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefits-card card border-0 shadow">
                        <div class="card-body p-4 p-md-5">
                            <header class="text-center mb-5">
                                <h3 class="h2 fw-bold mb-3">
                                    <i class="fas fa-star text-warning me-3"></i>
                                    What You Get
                                </h3>
                                <p class="text-muted lead">Premium benefits included with your enrollment</p>
                            </header>

                            <div class="row g-4">
                                <div class="col-md-4 text-center">
                                    <div class="benefit-item">
                                        <div class="benefit-icon mb-4 p-3 bg-primary bg-opacity-10 rounded-circle d-inline-block">
                                            <i class="fas fa-brain fa-3x text-primary"></i>
                                        </div>
                                        <h4 class="h6 fw-bold mb-3">AI Performance Analytics</h4>
                                        <p class="text-muted small mb-0">Track your progress with advanced analytics and personalized insights</p>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="benefit-item">
                                        <div class="benefit-icon mb-4 p-3 bg-success bg-opacity-10 rounded-circle d-inline-block">
                                            <i class="fas fa-users fa-3x text-success"></i>
                                        </div>
                                        <h4 class="h6 fw-bold mb-3">Expert Coaching</h4>
                                        <p class="text-muted small mb-0">Learn from professional coaches and experienced mentors</p>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="benefit-item">
                                        <div class="benefit-icon mb-4 p-3 bg-warning bg-opacity-10 rounded-circle d-inline-block">
                                            <i class="fas fa-trophy fa-3x text-warning"></i>
                                        </div>
                                        <h4 class="h6 fw-bold mb-3">Pro Career Path</h4>
                                        <p class="text-muted small mb-0">Direct pathway to professional football opportunities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @else
                <!-- Guest User - Sign In Prompt -->
                <article class="enrollment-card card border-0 shadow-lg text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-5">
                        <div class="signin-icon mb-4">
                            <i class="fas fa-sign-in-alt fa-4x text-primary"></i>
                        </div>
                        <h2 class="h3 mb-3">Sign In Required</h2>
                        <p class="lead text-muted mb-4">
                            Please sign in to your account to enroll in programs. Don't have an account yet?
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>
                            <div class="register-section">
                                <x-register-dropdown />
                            </div>
                        </div>
                    </div>
                </article>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Enrollment Successful!
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="success-icon mb-4">
                    <i class="fas fa-graduation-cap fa-4x text-success"></i>
                </div>
                <h4 class="mb-3">Welcome to Vipers Academy!</h4>
                <p class="mb-4">Your enrollment has been processed successfully. Our team will contact you shortly with next steps and training schedule.</p>
                <a href="{{ route('enrollments') }}" class="btn btn-success">
                    <i class="fas fa-list me-2"></i>View My Enrollments
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('program_id');
    const regularFeeElement = document.getElementById('regular-fee');
    const mumiasFeeElement = document.getElementById('mumias-fee');
    const enrollBtn = document.getElementById('enrollBtn');
    const enrollmentForm = document.getElementById('enrollmentForm');

    // Handle program selection
    if (programSelect) {
        programSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const regularFee = selectedOption.getAttribute('data-regular-fee');
            const mumiasFee = selectedOption.getAttribute('data-mumias-fee');

            if (regularFee && mumiasFee) {
                regularFeeElement.textContent = 'KSH ' + parseInt(regularFee).toLocaleString();
                mumiasFeeElement.textContent = 'KSH ' + parseInt(mumiasFee).toLocaleString();
                enrollBtn.disabled = false;
                enrollBtn.innerHTML = '<i class="fas fa-graduation-cap me-2"></i>Enroll Now';
            } else {
                regularFeeElement.textContent = 'Select a program';
                mumiasFeeElement.textContent = 'Select a program';
                enrollBtn.disabled = true;
            }
        });
    }

    // Handle form submission
    if (enrollmentForm) {
        enrollmentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            enrollBtn.disabled = true;
            enrollBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();

                        // Reset form
                        enrollmentForm.reset();
                        regularFeeElement.textContent = 'Select a program';
                        mumiasFeeElement.textContent = 'Select a program';
                        enrollBtn.disabled = true;
                    } else {
                        alert(data.message || 'Error processing enrollment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing enrollment. Please try again.');
                })
                .finally(() => {
                    enrollBtn.disabled = false;
                    enrollBtn.innerHTML = '<i class="fas fa-graduation-cap me-2"></i>Enroll Now';
                });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Hero Section Styles */
.enrollment-hero {
    min-height: 350px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hero-background {
    background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.hero-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Card Enhancements */
.enrollment-card {
    border-radius: 20px;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.card-header {
    border-radius: 0;
    position: relative;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #28a745, #20c997, #17a2b8);
}

/* Fee Cards */
.fee-card {
    border-radius: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.fee-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.fee-card:hover::before {
    opacity: 1;
}

.fee-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.fee-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Benefits Section */
.benefits-card {
    border-radius: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.benefits-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,193,7,0.1) 0%, transparent 70%);
    transform: translate(50%, -50%);
}

.benefit-item {
    padding: 1rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.benefit-item:hover {
    background: rgba(255,255,255,0.5);
    transform: translateY(-5px);
}

.benefit-icon {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.benefit-icon:hover {
    transform: scale(1.1) rotate(5deg);
}

/* Form Enhancements */
.form-select {
    border-radius: 12px;
    font-size: 1.1rem;
    padding: 1rem 1.25rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    transform: translateY(-2px);
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
}

/* Button Enhancements */
.btn {
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:disabled {
    opacity: 0.6;
    transform: none !important;
}

.btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Terms Section */
.form-check {
    transition: all 0.3s ease;
}

.form-check:hover {
    background: #f0f9ff !important;
    border-color: #28a745 !important;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* Modal Enhancements */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.success-icon {
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .enrollment-hero {
        min-height: 250px;
    }

    .hero-title {
        font-size: 2.5rem !important;
    }

    .enrollment-card .card-body {
        padding: 2rem 1.5rem;
    }

    .fee-card .card-body {
        padding: 2rem 1.5rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem !important;
    }

    .display-6 {
        font-size: 2rem !important;
    }
}

/* Accessibility Enhancements */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .fee-card {
        border-width: 3px !important;
    }

    .btn {
        border-width: 2px;
    }
}

/* Focus styles for accessibility */
.btn:focus,
.form-select:focus,
.form-check-input:focus {
    outline: 3px solid rgba(40, 167, 69, 0.5);
    outline-offset: 2px;
}

/* Loading state */
.btn.loading {
    pointer-events: none;
    position: relative;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush
