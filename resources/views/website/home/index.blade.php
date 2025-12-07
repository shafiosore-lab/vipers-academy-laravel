@extends('layouts.academy')

@section('title', 'Vipers Academy - Professional Football Training & Development')

@section('meta_description', 'Join Vipers Academy for world-class football training. Professional coaching, modern
facilities, and comprehensive youth development programs.')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 76vh;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);"></div>
    <div class="container position-relative h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-8">
                <div class="hero-content text-white" data-aos="fade-right">
                    <h1 class="display-3 fw-bold mb-4">
                        Develop Your <span class="text-white">Football Potential</span>
                    </h1>
                    <p class="lead mb-4 fs-5 opacity-90">
                        Building Champions in Football, Academics & Technology. A holistic academy developing
                        disciplined, skilled, and tech-enabled young athletes in Mumias.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="{{ route('programs') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            Explore Programs
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            Learn More
                        </a>
                    </div>
                    <div class="trust-indicators d-flex align-items-center gap-4 flex-wrap">
                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-star text-warning me-1"></i>
                            <span class="fw-semibold">4.9/5 Rating</span>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-users text-success me-1"></i>
                            <span class="fw-semibold">100+ Players</span>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-trophy text-primary me-1"></i>
                            <span class="fw-semibold">Pro Graduates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Preview -->
<section class="programs-preview py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3 text-black">
                Football <span class="text-success">|</span> Academics <span class="text-success">|</span> Technology
            </h2>
            <p class="lead" style="color:#000;">Choose from our comprehensive range of football development programs
            </p>
        </div>


        <div class="row g-4">
            <!-- Football Training -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="program-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-futbol"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">Football Training</h5>
                                <small class="text-muted">Professional Skills Development</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Weekend sessions</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Theory classes</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Age-based groups</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tournaments</li>
                        </ul>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-clock me-1"></i>Weekend Programs
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-money-bill-wave me-1"></i>Affordable Monthly Plans
                            </small>
                        </div>
                        <a href="{{ route('programs') }}" class="btn btn-success w-100">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Academic Mentorship -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="program-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">Academic Mentorship</h5>
                                <small class="text-muted">CBC-Aligned Support</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Study discipline</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>CBC homework support
                            </li>
                            <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Life-skills coaching
                            </li>
                            <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i>Behavior tracking</li>
                        </ul>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-book me-1"></i>Study Support
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-users me-1"></i>Personal Mentorship
                            </small>
                        </div>
                        <a href="{{ route('programs') }}" class="btn btn-warning w-100">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Digital Skills -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="program-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">Digital Skills</h5>
                                <small class="text-muted">Technology Integration</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i>Computer basics</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i>Coding</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i>Digital safety</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i>Tech creativity projects
                            </li>
                        </ul>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-desktop me-1"></i>Computer Lab Access
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-code me-1"></i>Hands-on Learning
                            </small>
                        </div>
                        <a href="{{ route('programs') }}" class="btn btn-info w-100">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Vipers -->
<section class="why-vipers-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Why Choose Vipers Academy</h2>
            <p class="text-muted">What makes us the premier choice for youth development</p>
        </div>

        <div class="row g-4">
            <!-- Why Parents Choose Us -->
            <div class="col-12" data-aos="fade-up">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div>
                                <h5 class="mb-0 fw-bold text-primary">Why Parents Choose Vipers</h5>
                                <small class="text-muted">Our Core Strengths</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Academic discipline &
                                    improved school performance</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Safe supervised
                                    environment</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Builds confidence & social
                                    skills</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Early exposure to
                                    computers and coding</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Elite football development
                                </p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional coaching &
                                    modern equipment</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Affordable & flexible
                                    programs</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Pathway to scholarships &
                                    opportunities</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Talent identification &
                                    growth tracking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What Makes Us Different -->
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">

                            <div>
                                <h5 class="mb-0 fw-bold text-success">What Makes Us Different</h5>
                                <small class="text-muted">Our Unique Approach</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Holistic approach:
                                    Football + Academics + Digital Skills</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>CBC-aligned mentorship &
                                    character building</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Tech integration with
                                    coding programs</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Affordable for all
                                    families</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Safe & structured
                                    environment</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Tournaments & exchange
                                    programs</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional coaching
                                    staff</p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced skill development
                                </p>
                                <p class="mb-2"><i class="fas fa-check text-success me-2"></i>Proven track record of
                                    success</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Trusted by Players & Parents</h2>
                <div class="d-flex align-items-center mb-3">


                </div>
                <div class="d-flex align-items-center mb-3">


                </div>
                <div class="d-flex align-items-center">


                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <i class="fas fa-quote-left fa-2x text-primary opacity-25 mb-3"></i>
                        <p class="lead mb-4">Vipers Academy transformed my son’s football career. Coming from Mumias, we
                            never imagined he would earn a sports scholarship from high school all the way to
                            university. The coaches believed in him, pushed him, and shaped him into a disciplined,
                            confident athlete. We are forever grateful to Vipers Academy for opening doors we never
                            thought possible</p>
                        <div class="d-flex align-items-center">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80"
                                alt="Parent testimonial" class="rounded-circle me-3"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 fw-bold">Benard Owino</h6>
                                <small class="text-muted">Parent of Senior team Player</small>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.stats-number');
                counters.forEach(counter => {
                    const target = parseInt(counter.textContent.replace(/,/g, ''));
                    let current = 0;
                    const increment = target / 100;
                    const step = 20;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target.toLocaleString();
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current)
                                .toLocaleString();
                        }
                    }, step);
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>
@endpush

<style>
/* Hero Section */
.hero-section {
    min-height: 76vh;
}

.hero-content {
    position: relative;
    z-index: 2;
}

/* Program Cards */
.program-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Why Vipers Section */
.why-vipers-section .card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.why-vipers-section .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

/* Testimonials */
.testimonials-section .card {
    border-radius: 15px;
}

/* Responsive Hero */
@media (max-width: 576px) {
    .hero-section {
        min-height: 82vh;
    }

    .hero-content h1 {
        font-size: 2.5rem !important;
    }

    .hero-content .lead {
        font-size: 1rem !important;
    }
}

@media (min-width: 577px) and (max-width: 768px) {
    .hero-section {
        min-height: 78vh;
    }
}

@media (min-width: 1200px) {
    .hero-section {
        min-height: 74vh;
    }
}
</style>
