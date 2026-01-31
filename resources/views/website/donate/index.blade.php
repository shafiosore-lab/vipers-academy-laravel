@extends('layouts.academy')

@section('title', 'Support Scholarships - Vipers Academy')

@section('meta_description', 'Help support scholarships for talented football players at Vipers Academy. Your donation can transform lives and provide education through sport.')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 70vh;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);"></div>
    <div class="container position-relative h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-8">
                <div class="hero-content text-white" data-aos="fade-right">
                    <div class="badge bg-success bg-opacity-90 mb-3 px-3 py-2">
                        <i class="fas fa-heart me-2"></i>Make a Difference Today
                    </div>
                    <h1 class="display-4 fw-bold mb-4">
                        Support <span class="text-warning">Scholarships</span> for Tomorrow's Stars
                    </h1>
                    <p class="lead mb-4 fs-5 opacity-95" style="max-width: 700px;">
                        Your generosity can provide scholarships that open doors to quality education and bright futures for talented young players from Mumias and surrounding communities.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <button class="btn btn-warning btn-lg px-5 py-3 fw-semibold shadow" onclick="scrollToDonate()">
                            <i class="fas fa-hand-holding-heart me-2"></i>Support a Scholarship
                        </button>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Your Impact</h2>
            <p class="text-muted">See how your support transforms lives</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-3">Education Access</h3>
                        <p class="text-muted">
                            Scholarships provide access to quality secondary education, opening doors that were previously closed due to financial constraints.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-futbol fa-3x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-3">Football Development</h3>
                        <p class="text-muted">
                            Your support ensures talented players can focus on their football development while receiving academic support.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-users fa-3x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-3">Community Impact</h3>
                        <p class="text-muted">
                            Every scholarship creates a ripple effect, inspiring other youth and strengthening community bonds.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scholarship Stats -->
<section class="stats-section py-5 bg-gradient text-white" style="background: linear-gradient(135deg, #198754 0%, #0d6832 100%);">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Scholarship Impact</h2>
            <p class="opacity-90">Real results from your support</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                    </div>
                    <h3 class="display-5 fw-bold mb-2">20+</h3>
                    <p class="mb-0 opacity-90">Active Scholarships</p>
                    <small class="opacity-75">Students in High School</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-trophy fa-2x opacity-75"></i>
                    </div>
                    <h3 class="display-5 fw-bold mb-2">7</h3>
                    <p class="mb-0 opacity-90">Years of Success</p>
                    <small class="opacity-75">Since 2017</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                    <h3 class="display-5 fw-bold mb-2">100+</h3>
                    <p class="mb-0 opacity-90">Lives Transformed</p>
                    <small class="opacity-75">Students & Families</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-heart fa-2x opacity-75"></i>
                    </div>
                    <h3 class="display-5 fw-bold mb-2">85%</h3>
                    <p class="mb-0 opacity-90">Academic Success Rate</p>
                    <small class="opacity-75">Scholarship Recipients</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Options -->
<section class="donation-section py-5 bg-light" id="donateSection">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Support a Scholarship</h2>
            <p class="text-muted">Choose how you'd like to contribute to our mission</p>
        </div>

        <div class="row g-4">
            <!-- One-Time Donation -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-hand-holding-heart fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">One-Time Donation</h4>
                        <p class="text-muted mb-4">
                            Make a single contribution to support scholarships for talented youth players.
                        </p>
                        <div class="amount-options mb-4">
                            <div class="btn-group-vertical w-100" role="group">
                                <input type="radio" class="btn-check" name="amount" id="amount1" value="2500" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount1">KES 2,500 (School Fees for 1 Month)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount2" value="12500" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount2">KES 12,500 (School Fees for 1 Term)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount3" value="25000" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount3">KES 25,000 (Full Year Scholarship)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount4" value="other" autocomplete="off">
                                <label class="btn btn-outline-success" for="amount4">Other Amount</label>
                            </div>
                        </div>
                        <button class="btn btn-success btn-lg w-100" onclick="selectDonation('one-time')">
                            <i class="fas fa-heart me-2"></i>Donate Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Monthly Sponsorship -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-calendar-heart fa-3x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Monthly Sponsorship</h4>
                        <p class="text-muted mb-4">
                            Become a monthly sponsor and provide ongoing support for scholarships.
                        </p>
                        <div class="amount-options mb-4">
                            <div class="btn-group-vertical w-100" role="group">
                                <input type="radio" class="btn-check" name="monthly" id="monthly1" value="1000" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly1">KES 1,000/month (School Supplies)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly2" value="2500" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly2">KES 2,500/month (Partial Scholarship)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly3" value="5000" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly3">KES 5,000/month (Full Scholarship)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly4" value="custom" autocomplete="off">
                                <label class="btn btn-outline-warning" for="monthly4">Custom Amount</label>
                            </div>
                        </div>
                        <button class="btn btn-warning btn-lg w-100" onclick="selectDonation('monthly')">
                            <i class="fas fa-calendar-plus me-2"></i>Start Sponsorship
                        </button>
                    </div>
                </div>
            </div>

            <!-- Corporate Partnership -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-handshake fa-3x text-info"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Corporate Partnership</h4>
                        <p class="text-muted mb-4">
                            Partner with us to create lasting impact through corporate social responsibility.
                        </p>
                        <div class="partnership-options mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Scholarship Programs</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Equipment Donations</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Facility Support</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Community Events</li>
                            </ul>
                        </div>
                        <a href="{{ route('contact') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-building me-2"></i>Partner With Us
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="contact-info mt-5" data-aos="fade-up">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h5 class="fw-bold mb-3">Questions about donating?</h5>
                            <p class="mb-0 text-muted">
                                Contact us for more information about scholarships, tax deductions, or how your contribution makes a difference.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Get in Touch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="stories-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Success Stories</h2>
            <p class="text-muted">Lives transformed through your generosity</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="story-card card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="story-header d-flex align-items-center mb-3">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80"
                                alt="Scholarship recipient" class="rounded-circle me-3 shadow"
                                style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 fw-bold">Michael Kiprop</h6>
                                <small class="text-muted">Grade 11 Student</small>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            "Thanks to Vipers Academy and generous donors like you, I can focus on both my studies and football. The scholarship means I don't have to worry about school fees, and I can chase my dream of becoming a professional footballer."
                        </blockquote>
                        <div class="story-footer">
                            <span class="badge bg-success">Academic Excellence</span>
                            <span class="badge bg-warning">Football Captain</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="story-card card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="story-header d-flex align-items-center mb-3">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?auto=format&fit=crop&w=80&q=80"
                                alt="Parent testimonial" class="rounded-circle me-3 shadow"
                                style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 fw-bold">Grace Wanjiku</h6>
                                <small class="text-muted">Parent</small>
                            </div>
                        </div>
                        <blockquote class="mb-3">
                            "The scholarship program has changed everything for my family. My son now has access to quality education that was once impossible. Vipers Academy doesn't just teach football — they build character and open doors to the future."
                        </blockquote>
                        <div class="story-footer">
                            <span class="badge bg-info">Community Impact</span>
                            <span class="badge bg-success">Family Transformation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function scrollToDonate() {
    document.getElementById('donateSection').scrollIntoView({
        behavior: 'smooth'
    });
}

function selectDonation(type) {
    // Placeholder for donation processing
    // In a real implementation, this would integrate with payment gateways like M-Pesa, Stripe, etc.
    alert(`Thank you for choosing to support our scholarships! The ${type} donation feature is coming soon. Please contact us directly for now.`);
}

// Auto-select first donation option
document.addEventListener('DOMContentLoaded', function() {
    const firstAmount = document.getElementById('amount1');
    if (firstAmount) {
        firstAmount.checked = true;
    }
});
</script>
@endpush

<style>
/* Hero Section */
.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-content .badge {
    font-size: 0.9rem;
    font-weight: 600;
}

/* Impact Cards */
.impact-card {
    transition: transform 0.3s ease;
}

.impact-card:hover {
    transform: translateY(-5px);
}

.impact-icon {
    transition: transform 0.3s ease;
}

.impact-card:hover .impact-icon {
    transform: scale(1.1);
}

/* Donation Cards */
.donation-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.donation-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
}

.donation-icon {
    transition: transform 0.3s ease;
}

.donation-card:hover .donation-icon {
    transform: scale(1.1);
}

.amount-options .btn-outline-success:hover,
.amount-options .btn-outline-warning:hover {
    transform: scale(1.02);
}

/* Story Cards */
.story-card {
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.story-card:hover {
    transform: translateY(-3px);
}

.story-header img {
    border: 3px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Stats Cards */
.stat-card {
    padding: 2rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.stat-icon {
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        min-height: 60vh;
    }

    .hero-content h1 {
        font-size: 2rem !important;
    }

    .donation-card {
        margin-bottom: 2rem;
    }

    .story-card {
        margin-bottom: 2rem;
    }
}
</style>
