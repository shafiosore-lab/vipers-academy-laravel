@extends('layouts.academy')

@section('title', 'Vipers Academy - Transforming Lives Through Football & Education')

@section('meta_description', 'Mumias Vipers Academy: Community-based youth development using football to nurture talent, discipline, and education. Over 20 players on high school sports scholarships.')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.4) 100%);"></div>
    <div class="container position-relative h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-9">
                <div class="hero-content text-white" data-aos="fade-right">
                    <h1 class="hero-title fw-bold mb-3">
                        Transforming Lives Through <span class="text-warning">Football & Education</span>
                    </h1>
                    <p class="hero-subtitle mb-4">
                        Founded in 2016, Mumias Vipers Academy is a community-based youth development organization using football to nurture talent, discipline, and education — with over 20 players currently on high school sports scholarships, accessing quality education that was once out of reach.
                    </p>
                    <div class="hero-buttons d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('programs') }}" class="btn btn-warning btn-lg px-4 py-3 fw-semibold shadow">
                            Explore Programs
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            Our Story
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="what-we-do py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative">
                    <img src="{{ asset('assets/img/home/WhatsApp Image 2026-01-21 at 12.47.01.jpeg') }}"
                         alt="Mumias Vipers Academy football training" class="img-fluid rounded-3 shadow-lg">
                    <div class="play-button-overlay">
                        <i class="fas fa-play-circle fa-3x text-white"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title fw-bold mb-3">
                    Connecting Talent with <span class="text-primary">Opportunity</span>
                </h2>
                <p class="section-text mb-4 text-muted">
                    We are dedicated to developing young talent from the ground up. We identify vulnerable and promising children, nurture their abilities through structured training and mentorship, and shape them into disciplined, competitive players. Beyond the pitch, we connect outstanding talents to sports scholarships and career opportunities, while promoting positive behavior change and lasting impact within our community.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="feature-item d-flex align-items-center">
                            <i class="fas fa-graduation-cap text-success me-2 fa-lg"></i>
                            <div>
                                <div class="fw-semibold small">Education Access</div>
                                <small class="text-muted">Sports scholarships</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item d-flex align-items-center">
                            <i class="fas fa-users text-primary me-2 fa-lg"></i>
                            <div>
                                <div class="fw-semibold small">Community Building</div>
                                <small class="text-muted">Family involvement</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item d-flex align-items-center">
                            <i class="fas fa-heart text-warning me-2 fa-lg"></i>
                            <div>
                                <div class="fw-semibold small">Behavior Change</div>
                                <small class="text-muted">Positive values</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item d-flex align-items-center">
                            <i class="fas fa-running text-info me-2 fa-lg"></i>
                            <div>
                                <div class="fw-semibold small">Healthy Lifestyles</div>
                                <small class="text-muted">Physical activity</small>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="btn btn-primary px-4 py-2">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="success-stories py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                Success <span class="text-success">Stories</span>
            </h2>
            <p class="section-subtitle text-muted">Real lives transformed through football and education</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="story-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <img src="{{ asset('assets/img/home/WhatsApp Image 2026-01-21 at 12.47.02 (1).jpeg') }}"
                             alt="Scholarship recipient" class="rounded-circle mb-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <h5 class="card-title fw-bold mb-2">Sports Scholarship Success</h5>
                        <p class="card-text text-muted mb-3 small">
                            "Thanks to Vipers Academy, I received a full sports scholarship to secondary school. Football opened doors to education I never thought possible."
                        </p>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <small class="text-muted">Scholarship Recipient, Class of 2024</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="story-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <img src="{{ asset('assets/img/home/WhatsApp Image 2026-01-21 at 12.47.02.jpeg') }}"
                             alt="Community member" class="rounded-circle mb-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <h5 class="card-title fw-bold mb-2">Community Transformation</h5>
                        <p class="card-text text-muted mb-3 small">
                            "The academy has brought our community together. My son learned discipline and now helps coach younger players. It's changed our whole family."
                        </p>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <small class="text-muted">Parent & Community Member</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="story-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <img src="{{ asset('assets/img/home/WhatsApp Image 2026-01-21 at 12.47.02 (2).jpeg') }}"
                             alt="Young player" class="rounded-circle mb-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <h5 class="card-title fw-bold mb-2">Life Skills Development</h5>
                        <p class="card-text text-muted mb-3 small">
                            "Vipers taught me more than football. I learned responsibility, teamwork, and how to balance sports with studies. These skills will help me for life."
                        </p>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <small class="text-muted">Brian Onyango, Age 15</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="programs-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                Football <span class="text-success">|</span> Academics <span class="text-success">|</span> Technology
            </h2>
            <p class="section-subtitle text-muted">Choose from our comprehensive range of development programs</p>
        </div>

        <div class="row g-4">
            <!-- Football Training -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="program-icon mb-3">
                            <i class="fas fa-futbol fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-1">Football Training</h5>
                        <small class="text-muted d-block mb-3">Professional Skills Development</small>
                        <ul class="list-unstyled mb-3 text-start">
                            <li class="mb-2 small"><i class="fas fa-check-circle text-success me-2"></i>Weekend training sessions</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-success me-2"></i>Theory & tactical classes</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-success me-2"></i>Age-appropriate groups</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-success me-2"></i>Tournament participation</li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <span class="badge bg-success">Weekend Programs</span>
                            <span class="badge bg-success bg-opacity-25 text-success">Affordable Plans</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Mentorship -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="program-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-1">Academic Mentorship</h5>
                        <small class="text-muted d-block mb-3">CBC-Aligned Support</small>
                        <ul class="list-unstyled mb-3 text-start">
                            <li class="mb-2 small"><i class="fas fa-check-circle text-warning me-2"></i>Study discipline coaching</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-warning me-2"></i>CBC homework support</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-warning me-2"></i>Life-skills mentorship</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-warning me-2"></i>Behavior tracking system</li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <span class="badge bg-warning text-dark">Study Support</span>
                            <span class="badge bg-warning bg-opacity-25 text-warning">Personal Mentorship</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Digital Skills -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="program-icon mb-3">
                            <i class="fas fa-laptop fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-1">Digital Skills</h5>
                        <small class="text-muted d-block mb-3">Technology Integration</small>
                        <ul class="list-unstyled mb-3 text-start">
                            <li class="mb-2 small"><i class="fas fa-check-circle text-info me-2"></i>Computer basics training</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-info me-2"></i>Introduction to coding</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-info me-2"></i>Digital safety education</li>
                            <li class="mb-2 small"><i class="fas fa-check-circle text-info me-2"></i>Creative tech projects</li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <span class="badge bg-info">Computer Lab Access</span>
                            <span class="badge bg-info bg-opacity-25 text-info">Tech Creativity</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4" data-aos="fade-up">
            <a href="{{ route('enrol') }}" class="btn btn-primary px-4 py-3 me-2 mb-2">
                Enroll Your Child
            </a>
            <a href="{{ route('programs') }}" class="btn btn-outline-primary px-4 py-3 mb-2">
                View Program Details
            </a>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
    padding: 3rem 0;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 2.5rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1rem;
    line-height: 1.6;
    opacity: 0.95;
    max-width: 750px;
}

.hero-buttons .btn {
    font-size: 1rem;
}

/* Section Titles */
.section-title {
    font-size: 2rem;
    line-height: 1.3;
}

.section-subtitle {
    font-size: 1rem;
}

.section-text {
    font-size: 1rem;
    line-height: 1.6;
}

/* Program Cards */
.program-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.program-icon {
    transition: transform 0.3s ease;
}

.program-card:hover .program-icon {
    transform: scale(1.05);
}

/* Story Cards */
.story-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.story-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

/* Feature Items */
.feature-item {
    padding: 0.5rem;
    transition: transform 0.2s ease;
}

.feature-item:hover {
    transform: translateX(5px);
}

/* Sections Spacing */
.py-5 {
    padding-top: 3rem !important;
    padding-bottom: 3rem !important;
}

/* Mobile Responsive */
@media (max-width: 576px) {
    .hero-section {
        min-height: 85vh;
        padding: 2rem 0;
    }

    .hero-title {
        font-size: 1.75rem;
    }

    .hero-subtitle {
        font-size: 0.9rem;
    }

    .hero-buttons .btn {
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem !important;
    }

    .section-title {
        font-size: 1.5rem;
    }

    .section-subtitle,
    .section-text {
        font-size: 0.9rem;
    }

    .py-5 {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }

    .programs-section {
        padding-bottom: 1rem !important;
    }

    .program-card .card-body,
    .story-card .card-body {
        padding: 1.25rem !important;
    }

    .feature-item {
        padding: 0.25rem;
    }

    .feature-item i {
        font-size: 1rem !important;
    }

    .feature-item .fw-semibold {
        font-size: 0.85rem;
    }

    .feature-item small {
        font-size: 0.75rem;
    }
}

/* Tablet */
@media (min-width: 577px) and (max-width: 768px) {
    .hero-section {
        min-height: 75vh;
    }

    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 0.95rem;
    }

    .section-title {
        font-size: 1.75rem;
    }
}

/* Desktop */
@media (min-width: 1200px) {
    .hero-section {
        min-height: 65vh;
    }

    .hero-title {
        font-size: 3rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .section-title {
        font-size: 2.25rem;
    }

    .container {
        max-width: 1200px;
    }
}
</style>
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
                            counter.textContent = Math.floor(current).toLocaleString();
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
