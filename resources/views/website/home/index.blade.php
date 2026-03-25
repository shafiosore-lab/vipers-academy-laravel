@extends('layouts.academy')

@section('title', 'Vipers Academy - Transforming Lives Through Football & Education')

@section('meta_description', 'Mumias Vipers Academy: Community-based youth development using football to nurture talent, discipline, and education. Over 20 players on high school sports scholarships.')

@section('content')
<!-- Hero Section -->
<section class="hero-section home-hero-section" style="background-image: url('{{ asset('assets/img/home/teamb.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
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
                    <div class="hero-buttons d-flex flex-wrap gap-2">
                        <a href="{{ route('programs') }}" class="btn btn-warning btn-lg px-4 py-3 fw-semibold shadow">
                            <i class="fas fa-compass me-2"></i>Explore Programs
                        </a>
                        <a href="{{ route('enrol') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            <i class="fas fa-user-plus me-2"></i>Enroll Now
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4 py-3 fw-semibold shadow">
                            <i class="fas fa-play me-2"></i>Start Free Trial
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            <i class="fas fa-calendar-alt me-2"></i>Request Demo
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

                <div class="text-center mt-4">
                    <a href="{{ route('enrol') }}" class="btn btn-primary px-4 py-3 fw-semibold shadow">
                        <i class="fas fa-user-plus me-2"></i>Enroll Your Child
                    </a>
                </div>
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
                        <ul class="program-features list-unstyled mb-3 text-start text-md-start text-center">
                            <li class="mb-2 small"><i class="fas fa-calendar-week program-icon-desktop me-2 text-success"></i>Weekend training sessions</li>
                            <li class="mb-2 small"><i class="fas fa-chalkboard-teacher program-icon-desktop me-2 text-success"></i>Theory & tactical classes</li>
                            <li class="mb-2 small"><i class="fas fa-users program-icon-desktop me-2 text-success"></i>Age-appropriate groups</li>
                            <li class="mb-2 small"><i class="fas fa-trophy program-icon-desktop me-2 text-success"></i>Tournament participation</li>
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
                        <ul class="program-features list-unstyled mb-3 text-start text-md-start text-center">
                            <li class="mb-2 small"><i class="fas fa-book-open program-icon-desktop me-2 text-warning"></i>Study discipline coaching</li>
                            <li class="mb-2 small"><i class="fas fa-clipboard-list program-icon-desktop me-2 text-warning"></i>CBC homework support</li>
                            <li class="mb-2 small"><i class="fas fa-hands-helping program-icon-desktop me-2 text-warning"></i>Life-skills mentorship</li>
                            <li class="mb-2 small"><i class="fas fa-chart-line program-icon-desktop me-2 text-warning"></i>Behavior tracking system</li>
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
                        <ul class="program-features list-unstyled mb-3 text-start text-md-start text-center">
                            <li class="mb-2 small"><i class="fas fa-desktop program-icon-desktop me-2 text-info"></i>Computer basics training</li>
                            <li class="mb-2 small"><i class="fas fa-code program-icon-desktop me-2 text-info"></i>Introduction to coding</li>
                            <li class="mb-2 small"><i class="fas fa-shield-alt program-icon-desktop me-2 text-info"></i>Digital safety education</li>
                            <li class="mb-2 small"><i class="fas fa-lightbulb program-icon-desktop me-2 text-info"></i>Creative tech projects</li>
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

    <!-- Impact/Stats Section - Transferred from About Page -->
    <section class="impact-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title fw-bold mb-2">
                    Our Impact in Numbers
                </h2>
                <p class="section-subtitle text-muted">Real results from your support</p>
            </div>

            <div class="row g-4 text-center" id="impact-stats">
                <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card h-100 p-4 bg-white rounded-3 shadow-sm transition-all">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                        <div class="stat-number fw-bold" data-target="20" data-suffix="+">0</div>
                        <p class="stat-label mb-0 text-muted">Active Scholarships</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card h-100 p-4 bg-white rounded-3 shadow-sm transition-all">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                        </div>
                        <div class="stat-number fw-bold" data-target="7">0</div>
                        <p class="stat-label mb-0 text-muted">Years of Success Since 2017</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card h-100 p-4 bg-white rounded-3 shadow-sm transition-all">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-heart fa-2x text-warning"></i>
                        </div>
                        <div class="stat-number fw-bold" data-target="100" data-suffix="+">0</div>
                        <p class="stat-label mb-0 text-muted">Lives Transformed</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card h-100 p-4 bg-white rounded-3 shadow-sm transition-all">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-award fa-2x text-info"></i>
                        </div>
                        <div class="stat-number fw-bold" data-target="85" data-suffix="%">0</div>
                        <p class="stat-label mb-0 text-muted">Academic Success Rate</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('donate') }}" class="btn btn-primary btn-lg px-4 py-3 fw-semibold shadow">
                    <i class="fas fa-heart me-2"></i>Support a Scholarship
                </a>
            </div>
        </div>
    </section>

<!-- Partners Section -->
@if(isset($partners) && $partners->count() > 0)
<section class="partners-section py-5 bg-white">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="section-title fw-bold mb-2">
                Our <span class="text-success">Partners</span> & Collaborators
            </h2>
            <p class="section-subtitle text-muted">Proud to work with leading organizations committed to youth development</p>
        </div>

        <!-- Partner Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card card border-0 bg-success bg-opacity-10 h-100">
                    <div class="card-body text-center py-3">
                        <div class="stats-icon mb-2">
                            <i class="fas fa-building fa-2x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ $partners->count() }}+</h3>
                        <p class="text-muted mb-0 small">Partner Organizations</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card card border-0 bg-warning bg-opacity-10 h-100">
                    <div class="card-body text-center py-3">
                        <div class="stats-icon mb-2">
                            <i class="fas fa-hand-holding-heart fa-2x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">KSh {{ number_format($partners->sum(function($p) {
                            $details = is_array($p->partner_details) ? $p->partner_details : json_decode($p->partner_details, true);
                            return $details['annual_contribution'] ?? 0;
                        })) }}</h3>
                        <p class="text-muted mb-0 small">Annual Support Value</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card card border-0 bg-info bg-opacity-10 h-100">
                    <div class="card-body text-center py-3">
                        <div class="stats-icon mb-2">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ $partners->filter(function($p) { return $p->created_at > now()->subYear(); })->count() }}+</h3>
                        <p class="text-muted mb-0 small">New Partnerships This Year</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continuous Horizontal Scrolling Carousel -->
        <div class="partners-carousel-wrapper mb-4" data-aos="fade-up">
            <div class="partners-carousel-track">
                <!-- First set of partners -->
                @foreach($partners as $partner)
                <div class="partner-logo-item">
                    <div class="partner-logo-card">
                        <div class="partner-logo-wrapper">
                            @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="partner-logo-img">
                            @else
                            <div class="partner-initials">{{ strtoupper(substr($partner->name, 0, 2)) }}</div>
                            @endif
                        </div>
                        <h6 class="partner-name mb-0">{{ Str::limit($partner->name, 15) }}</h6>
                    </div>
                </div>
                @endforeach

                <!-- Duplicate set for seamless infinite scroll -->
                @foreach($partners as $partner)
                <div class="partner-logo-item">
                    <div class="partner-logo-card">
                        <div class="partner-logo-wrapper">
                            @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="partner-logo-img">
                            @else
                            <div class="partner-initials">{{ strtoupper(substr($partner->name, 0, 2)) }}</div>
                            @endif
                        </div>
                        <h6 class="partner-name mb-0">{{ Str::limit($partner->name, 15) }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Become a Partner CTA -->
        <div class="text-center mt-5" data-aos="fade-up">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #001f3f 0%, #003366 100%);">
                <div class="card-body py-4 px-4">
                    <h4 class="fw-bold text-white mb-2">Become a Partner</h4>
                    <p class="text-white-50 mb-3">Join our network of partners and help shape the future of Kenyan youth through football and education</p>
                    <a href="{{ route('contact') }}" class="btn btn-light">
                        <i class="fas fa-handshake me-2"></i>Partner With Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<style>
/* Home Page Hero Section Styles */

/* ========================================
   PROGRAM FEATURES - RESPONSIVE ICONS
   Desktop: Show icons | Mobile: Hide icons
   ======================================== */

/* Desktop (lg) and above - Show icons */
@media (min-width: 992px) {
    .program-features li {
        display: flex;
        align-items: center;
        padding-left: 0.25rem;
    }

    .program-icon-desktop {
        display: inline-block !important;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Add subtle bullet styling for desktop */
    .program-features li::before {
        content: '';
        display: none; /* Using icons instead */
    }
}

/* Mobile and Tablet - Hide icons, clean text layout */
@media (max-width: 991px) {
    .program-features li {
        display: block;
        text-align: left;
    }

    .program-icon-desktop {
        display: none !important;
    }

    /* Add clean bullet points for mobile without icons */
    .program-features li {
        position: relative;
        padding-left: 1rem;
    }

    .program-features li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: var(--gray-600);
    }
}

/* Hero Section - Desktop */
@media (min-width: 992px) {
    .hero-section {
        min-height: 85vh;
        max-height: 900px;
        padding-top: 80px;
    }

    .hero-content {
        padding: 2rem 0;
    }

    .hero-title {
        font-size: 3rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-buttons .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
}

/* Tablet */
@media (min-width: 576px) and (max-width: 991px) {
    .hero-section {
        min-height: 65vh;
        padding-top: 80px;
    }

    .hero-title {
        font-size: 2.25rem;
    }
}

/* Mobile */
@media (max-width: 575px) {
    .hero-section {
        min-height: 60vh;
        padding-top: 70px;
        padding-bottom: 2rem;
    }

    .hero-title {
        font-size: 1.75rem;
    }

    .hero-subtitle {
        font-size: 0.95rem;
    }

    /* ========================================
       HERO BUTTONS - 2x2 GRID LAYOUT ON MOBILE
       Two rows of two buttons each
       ======================================== */
    .hero-buttons {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
        width: 100%;
    }

    .hero-buttons .btn {
        width: 100% !important;
        text-align: center;
        padding: 14px 12px !important;
        font-size: 0.875rem !important;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        border-radius: 8px;
    }

    .hero-buttons .btn i {
        font-size: 0.9rem;
        margin-right: 6px;
    }
}

/* Partners Section - Desktop Override */
@media (min-width: 1200px) {
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
        background: linear-gradient(90deg, #ea1c4d, #fbc761, #ea1c4d);
    }
}

/* Mobile styles for partners */
@media (max-width: 576px) {
    .partners-section .stats-card {
        margin-bottom: 0.5rem;
    }

    .partners-carousel-container {
        padding: 0.5rem;
    }
}

/* ========================================
   CONTINUOUS HORIZONTAL SCROLLING CAROUSEL
   Seamless infinite scroll from right to left
   ======================================== */

.partners-carousel-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
    padding: 1rem 0;
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(248,249,250,1) 50%, rgba(255,255,255,1) 100%);
    border-top: 1px solid rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.partners-carousel-track {
    display: flex;
    gap: 1.5rem;
    width: max-content;
    animation: scroll-left 30s linear infinite;
    padding-left: 0;
    padding-right: 0;
}

.partners-carousel-track:hover {
    animation-play-state: paused;
}

@keyframes scroll-left {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.partner-logo-item {
    flex-shrink: 0;
    width: 180px;
}

.partner-logo-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.25rem 1rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.partner-logo-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.partner-logo-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
    width: 100%;
    margin-bottom: 0.75rem;
}

.partner-logo-img {
    max-width: 100%;
    max-height: 50px;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: grayscale(20%);
    transition: filter 0.3s ease;
}

.partner-logo-card:hover .partner-logo-img {
    filter: grayscale(0%);
}

.partner-initials {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.partner-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

/* ========================================
   CAROUSEL RESPONSIVE STYLES
   ======================================== */

/* Tablet */
@media (max-width: 991px) {
    .partners-carousel-track {
        gap: 1.25rem;
    }

    .partner-logo-item {
        width: 150px;
    }

    .partner-logo-card {
        padding: 1rem 0.75rem;
    }

    .partner-logo-wrapper {
        height: 50px;
    }

    .partner-logo-img {
        max-height: 40px;
    }
}

/* Mobile Landscape */
@media (max-width: 576px) {
    .partners-carousel-track {
        gap: 1rem;
        animation-duration: 25s;
    }

    .partner-logo-item {
        width: 130px;
    }

    .partner-logo-card {
        padding: 0.875rem 0.5rem;
        border-radius: 10px;
    }

    .partner-logo-wrapper {
        height: 45px;
        margin-bottom: 0.5rem;
    }

    .partner-logo-img {
        max-height: 35px;
    }

    .partner-initials {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }

    .partner-name {
        font-size: 0.75rem;
    }
}

/* Small Mobile */
@media (max-width: 400px) {
    .partners-carousel-track {
        gap: 0.75rem;
    }

    .partner-logo-item {
        width: 110px;
    }

    .partner-logo-card {
        padding: 0.75rem 0.375rem;
    }

    .partner-logo-wrapper {
        height: 40px;
    }

    .partner-logo-img {
        max-height: 30px;
    }

    .partner-initials {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }

    .partner-name {
        font-size: 0.7rem;
    }
}

/* ========================================
   STATS/IMPACT CARDS - MOBILE HEIGHT REDUCTION
   Reduce height by 50% on screens < 992px
   ======================================== */

@media (max-width: 991px) {
    .stat-card {
        min-height: auto !important;
        padding: 1rem !important;
    }

    .stat-card .stat-icon {
        margin-bottom: 0.5rem !important;
    }

    .stat-card .stat-icon i {
        font-size: 1.5rem !important;
    }

    .stat-card .stat-number {
        font-size: 1.5rem !important;
        margin-bottom: 0.25rem !important;
    }

    .stat-card .stat-label {
        font-size: 0.75rem !important;
        line-height: 1.3;
    }
}

/* ========================================
   STATS/IMPACT CARDS - 2-COLUMN GRID ON MOBILE
   Max-width 576px: 2-column grid layout
   ======================================== */

@media (max-width: 576px) {
    #impact-stats {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem !important;
    }

    #impact-stats > div {
        grid-column: span 1 !important;
    }

    #impact-stats .stat-card {
        padding: 0.75rem !important;
        min-height: auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        text-align: center !important;
    }

    #impact-stats .stat-card .stat-icon {
        margin-bottom: 0.35rem !important;
    }

    #impact-stats .stat-card .stat-icon i {
        font-size: 1.25rem !important;
    }

    #impact-stats .stat-card .stat-number {
        font-size: 1.25rem !important;
        margin-bottom: 0.15rem !important;
        line-height: 1.2;
    }

    #impact-stats .stat-card .stat-label {
        font-size: 0.65rem !important;
        line-height: 1.2;
        margin-bottom: 0 !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for count-up animation
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.3
    };

    const countUpObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statCards = entry.target.querySelectorAll('.stat-card');

                statCards.forEach((card, index) => {
                    const numberEl = card.querySelector('.stat-number');
                    if (numberEl) {
                        const target = parseInt(numberEl.dataset.target);
                        const suffix = numberEl.dataset.suffix || '';
                        const delay = index * 150;

                        setTimeout(() => {
                            animateCounter(numberEl, target, suffix);
                        }, delay);
                    }
                });

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const impactStats = document.getElementById('impact-stats');
    if (impactStats) {
        countUpObserver.observe(impactStats);
    }

    function animateCounter(element, target, suffix) {
        const duration = 2000;
        const startTime = performance.now();
        const startValue = 0;

        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = easeOutQuart(progress);
            const currentValue = Math.round(startValue + (target - startValue) * easedProgress);

            element.textContent = currentValue + suffix;
            element.classList.add('counting');

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target + suffix;
                element.classList.remove('counting');
            }
        }

        requestAnimationFrame(updateCounter);
    }
});
</script>
@endpush

