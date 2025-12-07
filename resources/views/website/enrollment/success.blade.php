@extends('layouts.academy')

@section('title', 'Application Submitted - Vipers Academy Careers')

@section('meta_description', 'Thank you for your application to Vipers Academy. We have received your submission and will review it shortly.')

@section('content')
<!-- Success Section -->
<section class="success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>

                    <!-- Success Message -->
                    <h1 class="display-4 fw-bold text-success mb-3">Application Submitted Successfully!</h1>
                    <p class="lead text-muted mb-4">
                        Thank you for your interest in joining Vipers Academy. We have received your application and will review it carefully.
                    </p>

                    <!-- What Happens Next -->
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4"><i class="fas fa-clock me-2 text-primary"></i>What Happens Next?</h4>
                            <div class="row text-start">
                                <div class="col-md-4 mb-3">
                                    <div class="step-item">
                                        <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                             style="width: 40px; height: 40px;">
                                            1
                                        </div>
                                        <h6 class="fw-bold">Application Review</h6>
                                        <p class="text-muted small">Our HR team will review your application within 3-5 business days.</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="step-item">
                                        <div class="step-number bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                             style="width: 40px; height: 40px;">
                                            2
                                        </div>
                                        <h6 class="fw-bold">Interview Process</h6>
                                        <p class="text-muted small">If selected, we'll contact you to schedule an interview.</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="step-item">
                                        <div class="step-number bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                             style="width: 40px; height: 40px;">
                                            3
                                        </div>
                                        <h6 class="fw-bold">Decision</h6>
                                        <p class="text-muted small">We'll notify you of our decision and next steps.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="alert alert-info mb-4">
                        <strong>Questions about your application?</strong>
                        Feel free to contact our HR team at <a href="mailto:careers@vipersacademy.com">careers@vipersacademy.com</a>
                        or call us at +254 XXX XXX XXX.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-lg-row gap-3 justify-content-center align-items-center">
                        <a href="{{ route('careers.index') }}" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Careers
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-4 py-3">
                            <i class="fas fa-home me-2"></i>Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Information -->
<section class="additional-info py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="info-icon mb-3">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Join Our Community</h5>
                        <p class="card-text text-muted">Become part of a passionate team dedicated to developing the next generation of football talent.</p>
                        <a href="{{ route('about') }}" class="btn btn-outline-primary">Learn About Us</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="info-icon mb-3">
                            <i class="fas fa-futbol fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Football Passion</h5>
                        <p class="card-text text-muted">Work in an environment where your love for football meets professional excellence.</p>
                        <a href="{{ route('programs') }}" class="btn btn-outline-success">View Programs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
.success-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.success-icon {
    animation: checkmark 0.8s ease-in-out;
}

@keyframes checkmark {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.step-item {
    text-align: center;
}

.step-number {
    margin: 0 auto;
    font-weight: bold;
    font-size: 1.2rem;
}

.card {
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.info-icon {
    opacity: 0.8;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>

<script>
// Auto redirect after 10 seconds (optional)
setTimeout(function() {
    // Uncomment the line below if you want auto-redirect
    // window.location.href = '{{ route("careers.index") }}';
}, 10000);
</script>
