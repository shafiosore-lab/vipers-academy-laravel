@extends('layouts.academy')

@section('title', 'Enroll in a Program - Vipers Academy')

@section('meta_description', 'Enroll in Vipers Academy football programs. Choose your program and start your journey to
football excellence in Kenya.')

@section('content')
<section class="enrollment-hero py-5"
    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div data-aos="fade-up">
                    <h1 class="display-4 fw-bold mb-4">Enroll in a Program</h1>
                    <p class="lead fs-5 mb-0 opacity-90">
                        Choose your program and start your journey to football excellence
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="enrollment-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @auth
                <!-- Authenticated User Enrollment -->
                <div class="enrollment-card card border-0 shadow-lg" data-aos="fade-up">
                    <div class="card-header bg-success text-white text-center py-4">
                        <i class="fas fa-user-check fa-3x mb-3"></i>
                        <h4 class="mb-0">Welcome back, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0 opacity-75">Ready to enroll in a program?</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('programs.enroll') }}" method="POST" id="enrollmentForm">
                            @csrf

                            <!-- Program Selection -->
                            <div class="mb-4">
                                <label for="program_id" class="form-label fw-bold fs-5 mb-3">
                                    <i class="fas fa-futbol text-primary me-2"></i>Select Program
                                </label>
                                <select name="program_id" id="program_id"
                                    class="form-select form-select-lg border-0 shadow-sm"
                                    style="border-radius: 10px; font-size: 1.1rem; padding: 1rem;" required>
                                    <option value="" disabled selected>Choose a program...</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}" data-regular-fee="{{ $program->regular_fee }}"
                                        data-mumias-fee="{{ $program->mumias_fee ?? $program->regular_fee }}">
                                        {{ $program->title }} - {{ $program->age_group }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fee Display -->
                            <div class="fee-display mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="fee-card card h-100 border-primary">
                                            <div class="card-body text-center p-3">
                                                <h6 class="text-primary fw-bold mb-2">Regular Fee</h6>
                                                <div class="fee-amount h4 text-primary fw-bold mb-0" id="regular-fee">
                                                    Select a program to see fees
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fee-card card h-100 border-success">
                                            <div class="card-body text-center p-3">
                                                <h6 class="text-success fw-bold mb-2">Mumias Community Fee</h6>
                                                <div class="fee-amount h4 text-success fw-bold mb-0" id="mumias-fee">
                                                    Select a program to see fees
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check d-flex align-items-center p-3 border rounded"
                                    style="background: #f8f9fa;">
                                    <input class="form-check-input me-3" type="checkbox" id="terms_accepted"
                                        name="terms_accepted" required style="transform: scale(1.2);">
                                    <label class="form-check-label fw-semibold" for="terms_accepted">
                                        I agree to the <a href="#" class="text-primary text-decoration-none">Terms &
                                            Conditions</a> and <a href="#"
                                            class="text-primary text-decoration-none">Enrollment Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg py-3 fw-bold" id="enrollBtn"
                                    disabled>
                                    <i class="fas fa-graduation-cap me-2"></i>Enroll Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Enrollment Benefits -->
                <div class="row mt-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-12">
                        <div class="benefits-card card border-0 shadow">
                            <div class="card-body p-4">
                                <h4 class="text-center mb-4 fw-bold">
                                    <i class="fas fa-star text-warning me-2"></i>What You Get
                                </h4>
                                <div class="row g-4">
                                    <div class="col-md-4 text-center">
                                        <div class="benefit-icon mb-3">
                                            <i class="fas fa-brain fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="fw-bold">AI Performance Analytics</h6>
                                        <p class="text-muted small">Track your progress with advanced analytics</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="benefit-icon mb-3">
                                            <i class="fas fa-users fa-3x text-success"></i>
                                        </div>
                                        <h6 class="fw-bold">Expert Coaching</h6>
                                        <p class="text-muted small">Learn from professional coaches and mentors</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="benefit-icon mb-3">
                                            <i class="fas fa-trophy fa-3x text-warning"></i>
                                        </div>
                                        <h6 class="fw-bold">Pro Career Path</h6>
                                        <p class="text-muted small">Pathway to professional football opportunities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Guest User - Redirect to Login -->
                <div class="enrollment-card card border-0 shadow-lg text-center" data-aos="fade-up">
                    <div class="card-body p-5">
                        <i class="fas fa-sign-in-alt fa-4x text-primary mb-4"></i>
                        <h3 class="mb-3">Sign In Required</h3>
                        <p class="lead text-muted mb-4">
                            Please sign in to your account to enroll in programs. Don't have an account yet?
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>

                            <div class="top-bar-right">

                                <x-register-dropdown />
                            </div>






                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Enrollment Successful!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-graduation-cap fa-4x text-success mb-3"></i>
                <h4 class="mb-3">Welcome to Vipers Academy!</h4>
                <p class="mb-4">Your enrollment has been processed successfully. Our team will contact you shortly with
                    next steps and training schedule.</p>
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
    programSelect?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const regularFee = selectedOption.getAttribute('data-regular-fee');
        const mumiasFee = selectedOption.getAttribute('data-mumias-fee');

        if (regularFee && mumiasFee) {
            regularFeeElement.textContent = 'KSH ' + parseInt(regularFee).toLocaleString();
            mumiasFeeElement.textContent = 'KSH ' + parseInt(mumiasFee).toLocaleString();
            enrollBtn.disabled = false;
            enrollBtn.innerHTML = '<i class="fas fa-graduation-cap me-2"></i>Enroll Now';
        } else {
            regularFeeElement.textContent = 'Select a program to see fees';
            mumiasFeeElement.textContent = 'Select a program to see fees';
            enrollBtn.disabled = true;
        }
    });

    // Handle form submission
    enrollmentForm?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        enrollBtn.disabled = true;
        enrollBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                        'content') || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    const successModal = new bootstrap.Modal(document.getElementById(
                        'successModal'));
                    successModal.show();

                    // Reset form
                    enrollmentForm.reset();
                    regularFeeElement.textContent = 'Select a program to see fees';
                    mumiasFeeElement.textContent = 'Select a program to see fees';
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
});
</script>
@endpush

<style>
.enrollment-hero {
    min-height: 300px;
    display: flex;
    align-items: center;
}

.enrollment-card {
    border-radius: 15px;
    overflow: hidden;
}

.fee-card {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.fee-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.fee-display .card {
    border-width: 2px;
}

.benefits-card {
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.benefit-icon {
    transition: all 0.3s ease;
}

.benefit-icon:hover {
    transform: scale(1.1);
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn:disabled {
    opacity: 0.6;
}
</style>
