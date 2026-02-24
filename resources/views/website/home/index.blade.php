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
                        @php
                            $heroTitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'hero_title') : null;
                        @endphp
                        {{ $heroTitle?->value ?: 'Transforming Lives Through Football & Education' }}
                    </h1>
                    <p class="hero-subtitle mb-4">
                        @php
                            $heroSubtitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'hero_subtitle') : null;
                        @endphp
                        {{ $heroSubtitle?->value ?: 'Founded in 2016, Mumias Vipers Academy is a community-based youth development organization using football to nurture talent, discipline, and education — with over 20 players currently on high school sports scholarships, accessing quality education that was once out of reach.' }}
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

<!-- Partners Section -->
@if(isset($partners) && $partners->count() > 0)
<section class="partners-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                Our <span class="text-success">Partners</span> & Collaborators
            </h2>
            <p class="section-subtitle text-muted">Proud to work with leading organizations committed to youth development</p>
        </div>

        <!-- Main Featured Partners -->
        <div class="row g-4 mb-5">
            @php
                $platinumPartners = $partners->filter(function($p) {
                    $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                    return isset($details['sponsorship_level']) && $details['sponsorship_level'] === 'Platinum';
                });
                $goldPartners = $partners->filter(function($p) {
                    $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                    return isset($details['sponsorship_level']) && $details['sponsorship_level'] === 'Gold';
                });
                $otherPartners = $partners->filter(function($p) {
                    $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                    return !isset($details['sponsorship_level']) || !in_array($details['sponsorship_level'], ['Platinum', 'Gold']);
                });
            @endphp

            @if($platinumPartners->count() > 0)
            <div class="col-12 mb-4">
                <div class="text-center mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="fas fa-crown me-1"></i> Platinum Partners
                    </span>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach($platinumPartners as $partner)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="partner-card-platinum card border-0 shadow-lg h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="partner-logo-container-platinum me-3">
                                        <div class="partner-logo-platinum">
                                            {{ strtoupper(substr($partner->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title fw-bold mb-1">{{ $partner->name }}</h5>
                                        <span class="badge bg-warning bg-opacity-25 text-warning mb-2">
                                            <i class="fas fa-crown me-1"></i> Platinum
                                        </span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($partner->company_description, 120) }}</p>
                                @if($partner->company_website)
                                <a href="{{ $partner->company_website }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-external-link-alt me-1"></i> Visit Website
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($goldPartners->count() > 0)
            <div class="col-12 mb-4">
                <div class="text-center mb-3">
                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-2">
                        <i class="fas fa-medal me-1"></i> Gold Partners
                    </span>
                </div>
                <div class="row g-3 justify-content-center">
                    @foreach($goldPartners as $partner)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="150">
                        <div class="partner-card-gold card border-0 shadow-sm h-100">
                            <div class="card-body p-3 text-center">
                                <div class="partner-logo-container-gold mx-auto mb-2">
                                    <div class="partner-logo-gold">
                                        {{ strtoupper(substr($partner->name, 0, 2)) }}
                                    </div>
                                </div>
                                <h6 class="card-title fw-bold mb-1 small">{{ $partner->name }}</h6>
                                <small class="text-muted d-block mb-2">{{ $partner->industry ?? 'Partner' }}</small>
                                @if($partner->company_website)
                                <a href="{{ $partner->company_website }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($otherPartners->count() > 0)
            <div class="col-12">
                <div class="text-center mb-3">
                    <span class="badge bg-primary bg-opacity-25 text-primary px-3 py-2">
                        <i class="fas fa-handshake me-1"></i> Our Partners
                    </span>
                </div>
                <div class="row g-3 justify-content-center">
                    @foreach($otherPartners as $partner)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="partner-card-silver card border-0 shadow-sm h-100">
                            <div class="card-body p-3 text-center">
                                <div class="partner-logo-container-silver mx-auto mb-2">
                                    <div class="partner-logo-silver">
                                        {{ strtoupper(substr($partner->name, 0, 2)) }}
                                    </div>
                                </div>
                                <h6 class="card-title fw-bold mb-1 small">{{ Str::limit($partner->name, 20) }}</h6>
                                <small class="text-muted">{{ $partner->industry ?? 'Partner' }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Partner Stats -->
        <div class="row g-4 mt-2">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card card border-0 bg-success bg-opacity-10 h-100">
                    <div class="card-body text-center py-4">
                        <div class="stats-icon mb-3">
                            <i class="fas fa-building fa-2x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ $partners->count() }}+</h3>
                        <p class="mb-0 text-muted">Partner Organizations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card card border-0 bg-warning bg-opacity-10 h-100">
                    <div class="card-body text-center py-4">
                        <div class="stats-icon mb-3">
                            <i class="fas fa-hand-holding-heart fa-2x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">KSh {{ number_format($partners->sum(function($p) {
                            $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                            return $details['annual_contribution'] ?? 0;
                        })) }}</h3>
                        <p class="mb-0 text-muted">Annual Support Value</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card card border-0 bg-info bg-opacity-10 h-100">
                    <div class="card-body text-center py-4">
                        <div class="stats-icon mb-3">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ $partners->filter(function($p) { return $p->created_at > now()->subYear(); })->count() }}+</h3>
                        <p class="mb-0 text-muted">New Partnerships This Year</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Become a Partner CTA -->
        <div class="text-center mt-5" data-aos="fade-up">
            <div class="card border-0 bg-gradient shadow-lg" style="background: linear-gradient(135deg, #0d6efd 0%, #198754 100%);">
                <div class="card-body py-5">
                    <h3 class="fw-bold text-white mb-2">Become a Partner</h3>
                    <p class="text-white-50 mb-4">Join our network of partners and help shape the future of Kenyan youth through football and education</p>
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-5">
                        <i class="fas fa-handshake me-2"></i>Partner With Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

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

/* Partners Section Styles */
.partners-section {
    position: relative;
    overflow: hidden;
}

.partners-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0d6efd, #198754, #0d6efd);
}

/* Platinum Partner Cards */
.partner-card-platinum {
    border-radius: 16px;
    transition: all 0.4s ease;
    background: linear-gradient(145deg, #ffffff 0%, #fffbf0 100%);
}

.partner-card-platinum:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(255, 193, 7, 0.2) !important;
}

.partner-logo-container-platinum {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
}

.partner-logo-platinum {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

/* Gold Partner Cards */
.partner-card-gold {
    border-radius: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, #ffffff 0%, #f0fff4 100%);
}

.partner-card-gold:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(25, 135, 84, 0.15) !important;
}

.partner-logo-container-gold {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.partner-logo-gold {
    font-size: 1rem;
    font-weight: 700;
    color: white;
}

/* Silver Partner Cards */
.partner-card-silver {
    border-radius: 10px;
    transition: all 0.3s ease;
    background: #ffffff;
}

.partner-card-silver:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(13, 110, 253, 0.1) !important;
}

.partner-logo-container-silver {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #0d6efd 0%, #6ea8fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(13, 110, 253, 0.25);
}

.partner-logo-silver {
    font-size: 0.85rem;
    font-weight: 600;
    color: white;
}

/* Partner Stats Cards */
.stats-card {
    border-radius: 16px;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: scale(1.02);
}

.stats-icon i {
    transition: transform 0.3s ease;
}

.stats-card:hover .stats-icon i {
    transform: scale(1.1);
}

/* Partner CTA Gradient Card */
.bg-gradient {
    border-radius: 20px;
    overflow: hidden;
}

/* Partner Section Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.partner-card-platinum,
.partner-card-gold,
.partner-card-silver {
    animation: fadeInUp 0.6s ease forwards;
}

/* Responsive Partner Styles */
@media (max-width: 768px) {
    .partner-logo-container-platinum {
        width: 50px;
        height: 50px;
    }

    .partner-logo-platinum {
        font-size: 1rem;
    }

    .partner-logo-container-gold {
        width: 40px;
        height: 40px;
    }

    .partner-logo-gold {
        font-size: 0.85rem;
    }
}

/* Partner Badge Styles */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
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
