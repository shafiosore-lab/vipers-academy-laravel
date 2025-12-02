@extends('layouts.academy')

@section('title', 'Vipers Academy - Professional Football Training & Development')

@section('meta_description', 'Join Vipers Academy for world-class football training. Professional coaching, modern
facilities, and comprehensive youth development programs.')

@section('content')
<!-- Hero Section - Full Image Background -->
<section class="hero-section-full position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 76vh;">
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
                    Building Champions in Football, Academics & Technology
Subtext: A holistic academy developing disciplined, skilled, and tech-enabled young athletes in Mumias.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="{{ route('programs') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            <i class=></i>Explore Programs
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
                            <span class="fw-semibold">{{ \App\Models\Player::count() }}+ Players</span>
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
            <h2 class="display-5 fw-bold mb-3">Football + Academics + Technology</h2>
            <p class="lead text-muted">Choose from our comprehensive range of football development programs</p>
        </div>

        <div class="row g-3">
            <!-- Football Training Card -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
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
                        <ul class="list-unstyled card-text text-muted mb-3">
                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Weekend sessions</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Theory classes</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Age-based groups</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Tournaments</li>
                        </ul>
                        <div class="program-details mb-3">
                            <small class="text-muted d-block">
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

            <!-- Academic Mentorship Card -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
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
                        <ul class="list-unstyled card-text text-muted mb-3">
                            <li class="mb-1"><i class="fas fa-check-circle text-warning me-2"></i>Study discipline</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-warning me-2"></i>CBC homework support</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-warning me-2"></i>Life-skills coaching</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-warning me-2"></i>Behavior & discipline tracking</li>
                        </ul>
                        <div class="program-details mb-3">
                            <small class="text-muted d-block">
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

            <!-- Digital Skills Card -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
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
                        <ul class="list-unstyled card-text text-muted mb-3">
                            <li class="mb-1"><i class="fas fa-check-circle text-info me-2"></i>Computer basics</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-info me-2"></i>Coding</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-info me-2"></i>Digital safety</li>
                            <li class="mb-1"><i class="fas fa-check-circle text-info me-2"></i>Tech creativity projects</li>
                        </ul>
                        <div class="program-details mb-3">
                            <small class="text-muted d-block">
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


<!-- What Makes Us Different -->
<section class="why-vipers-section py-5 bg-light">
    <div class="container-fluid px-0">
        <div class="text-center mb-3" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-2">Why Vipers</h2>
            <p class="text-muted">Why Parents Choose Vipers</p>
        </div>

        <div class="row g-0">

            <!-- Why Parents Choose Vipers Card - Full Width -->
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="why-vipers-card card border-0 shadow-lg h-100 mx-3 mb-3">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="why-vipers-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                        style="width: 50px; height: 50px;">

                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-primary">Why Parents Choose Vipers</h5>
                                        <small class="text-muted">Our Core Strengths</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Academic discipline & improved school performance</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Safe supervised environment</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Builds confidence & social skills</span></p>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Early exposure to computers and coding</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Elite football development with theory + practical</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Professional coaching & modern training equipment</span></p>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Affordable & flexible programs</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Pathway to Scholarships & Opportunities</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Talent Identification & Growth Tracking</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('programs') }}" class="btn btn-primary btn-sm px-4">Learn More About Our Programs</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What Makes Us Different Card - Full Width -->
            <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                <div class="what-makes-us-card card border-0 shadow-lg h-100 mx-3 mt-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="what-makes-us-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold text-success">What Makes Us Different</h5>
                                        <small class="text-muted">Our Unique Approach</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <div class="row g-2">
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Holistic Approach: Football + Academics + Digital Skills</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">CBC-Aligned Mentorship: Discipline, study habits & character building</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Tech Integration: Children learn computers & beginner coding</span></p>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Affordable for All: Low monthly subscription plans</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Safe & Structured Environment: Professional coaches and mentors</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Tournaments & Exchange Programs: Exposure beyond Mumias</span></p>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Professional coaching & modern training equipment</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Advanced skill development programs</span></p>
                                        <p class="card-text mb-2 fs-6 d-flex align-items-center"><i class="fas fa-check text-success me-2 flex-shrink-0" style="line-height: 1;"></i><span class="flex-grow-1">Proven track record of success</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('about') }}" class="btn btn-success btn-sm px-4">Discover Our Difference</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Testimonials/Trust Section -->
<section class="trust-section py-5 position-relative">
    <div class="trust-bg-image position-absolute top-0 start-0 w-100 h-100"
        style="background-image: url('https://images.unsplash.com/photos/man-in-black-t-shirt-through-soccer-goal-post--nY0KBNXhhU'); background-size: cover; background-position: center; opacity: 0.15;">
    </div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Trusted by Players & Parents</h2>
                <div class="trust-stats mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="trust-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">4.9/5</h4>
                            <small class="text-muted">Average Rating</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="trust-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">500+</h4>
                            <small class="text-muted">Happy Families</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="trust-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">50+</h4>
                            <small class="text-muted">Pro Graduates</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="testimonial-card card border-0 shadow-lg position-relative">
                    <div class="testimonial-bg position-absolute top-0 start-0 w-100 h-100 rounded"
                        style="background-image: url('https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80'); background-size: cover; background-position: center; opacity: 0.1;">
                    </div>
                    <div class="card-body p-3 position-relative">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-primary opacity-25"></i>
                        </div>
                        <p class="lead mb-4">"Vipers Academy transformed my son's football career. The professional
                            coaching and facilities are unmatched. He's now playing for a premier league team!"</p>
                        <div class="d-flex align-items-center">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80"
                                alt="Parent testimonial" class="rounded-circle me-3"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 fw-bold">Maria Johnson</h6>
                                <small class="text-muted">Parent of U-16 Player</small>
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
// Initialize Swiper if needed for future carousel features
// For now, keeping it simple with AOS animations

// Enhanced count-up animation
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
                    const duration = 2000; // 2 seconds
                    const step = duration / 100;

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

    // Observe stats section
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>
@endpush

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.hero-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.min-vh-75 {
    min-height: 75vh;
}

.hero-image-container {
    position: relative;
    z-index: 2;
}

.hero-image-wrapper {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.floating-card {
    z-index: 3;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

.stats-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.stats-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.stats-icon {
    transition: transform 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1);
}

.feature-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.feature-icon-wrapper {
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon-wrapper {
    transform: scale(1.05);
}

.program-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.news-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.news-image-wrapper {
    position: relative;
    overflow: hidden;
}

.news-category-badge {
    position: absolute;
    top: 15px;
    right: 15px;
}

.testimonial-card {
    border-radius: 15px;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.trust-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.1;
}

/* Responsive Hero Section Adjustments */
.hero-section-full {
    min-height: 76vh;
}

/* Mobile adjustments - slightly taller on mobile for better readability */
@media (max-width: 576px) {
    .hero-section-full {
        min-height: 82vh;
    }

    .hero-content h1 {
        font-size: 2.5rem !important;
    }

    .hero-content .lead {
        font-size: 1rem !important;
    }
}

/* Tablet adjustments */
@media (min-width: 577px) and (max-width: 768px) {
    .hero-section-full {
        min-height: 78vh;
    }
}

/* Desktop - standard height */
@media (min-width: 769px) {
    .hero-section-full {
        min-height: 76vh;
    }
}

/* Large desktop - slight reduction for very large screens */
@media (min-width: 1200px) {
    .hero-section-full {
        min-height: 74vh;
    }
}

/* Unique Value Section Styles */
.unique-value-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
}

.unique-value-showcase-card {
    border-radius: 25px !important;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.unique-value-showcase-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.card-bg-gradient {
    z-index: 0;
}

.unique-value-showcase-card .card-body {
    position: relative;
    z-index: 2;
}

.floating-shapes {
    pointer-events: none;
    overflow: hidden;
}

.shape {
    animation: float 6s ease-in-out infinite;
}

.shape-2 {
    animation-delay: -2s;
}

.shape-3 {
    animation-delay: -4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(120deg); }
    66% { transform: translateY(10px) rotate(240deg); }
}

.value-item {
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 15px;
    position: relative;
}

.value-item:hover {
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(5px);
    transform: translateY(-5px);
}

.value-icon-wrapper {
    position: relative;
    display: inline-block;
}

.value-icon {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { transform: scale(1); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); }
    50% { transform: scale(1.05); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3); }
}

.value-item:hover .value-icon {
    transform: scale(1.15) rotate(10deg);
    animation: none;
}

.value-title {
    transition: all 0.3s ease;
    position: relative;
}

.value-item:hover .value-title {
    color: #667eea !important;
}

.value-description {
    font-size: 0.95rem;
    line-height: 1.6;
    transition: all 0.3s ease;
}

.value-tags .badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.value-tags .badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Enhanced responsive design */
@media (max-width: 768px) {
    .unique-value-showcase-card .card-body {
        padding: 2rem 1.5rem !important;
    }

    .value-item {
        margin-bottom: 2rem;
    }

    .floating-shapes {
        display: none;
    }
}

/* Custom purple color class */
.bg-purple {
    background-color: #6f42c1 !important;
}

/* Enhanced Why Vipers Section - Full Width Styles */
.why-vipers-section {
    overflow-x: hidden;
    padding: 3rem 0;
}

.why-vipers-card,
.what-makes-us-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    max-width: none !important;
    width: 100%;
    min-height: auto;
}

.why-vipers-card:hover,
.what-makes-us-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.why-vipers-card .card-text,
.what-makes-us-card .card-text {
    font-size: 0.95rem;
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.why-vipers-icon,
.what-makes-us-icon {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.why-vipers-card:hover .why-vipers-icon,
.what-makes-us-card:hover .what-makes-us-icon {
    transform: scale(1.05);
}

/* Full width responsive adjustments */
@media (min-width: 1200px) {
    .why-vipers-card .mx-3,
    .what-makes-us-card .mx-3 {
        margin-left: 2rem !important;
        margin-right: 2rem !important;
    }
}

@media (max-width: 768px) {
    .why-vipers-section .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .why-vipers-card .card-text,
    .what-makes-us-card .card-text {
        font-size: 1rem;
        white-space: normal;
    }

    .why-vipers-card .col-lg-4,
    .what-makes-us-card .col-lg-4 {
        margin-bottom: 1.5rem;
    }

    .why-vipers-card .mx-3,
    .what-makes-us-card .mx-3 {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .why-vipers-card .fs-5,
    .what-makes-us-card .fs-5 {
        font-size: 1rem !important;
    }
}

/* Ensure full width on all screen sizes */
@media (min-width: 576px) {
    .why-vipers-section .row {
        margin-left: -15px;
        margin-right: -15px;
    }

    .why-vipers-section .row > [class*="col-"] {
        padding-left: 15px;
        padding-right: 15px;
    }
}

/* Split Card Section Styles */
.split-card-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2.5rem 0;
}

/* Full-width card - compacted height */
.split-card {
    width: 100%;
    min-height: 300px;
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Left side - 50% width */
.split-card__left {
    width: 50%;
    background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Right side - 50% width */
.split-card__right {
    width: 50%;
    background: #f5f5f5;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Title styling - reduced size */
.split-card__title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    line-height: 1.2;
}

/* List styling - tightened */
.split-card__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.split-card__list-item {
    font-size: 0.875rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    transition: all 0.3s ease;
    line-height: 1.3;
}

.split-card__right .split-card__list-item {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.split-card__list-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.split-card__right .split-card__list-item:hover {
    background: rgba(0, 0, 0, 0.05);
}

.split-card__icon {
    font-size: 0.875rem;
    flex-shrink: 0;
    line-height: 1.3;
}

/* Mobile responsive (≤768px) */
@media (max-width: 768px) {
    .split-card-section {
        padding: 2rem 0;
    }

    .split-card {
        flex-direction: column;
        min-height: auto;
        border-radius: 8px;
    }

    .split-card__left,
    .split-card__right {
        width: 100%;
        padding: 1.25rem;
    }

    .split-card__title {
        font-size: 1.25rem;
        margin-bottom: 0.875rem;
    }

    .split-card__list-item {
        font-size: 0.8125rem;
        padding: 0.375rem 0;
    }

    .split-card__icon {
        font-size: 0.8125rem;
    }
}

/* Tablet responsive (769px-1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
    .split-card-section {
        padding: 2.25rem 0;
    }

    .split-card {
        min-height: 250px;
    }

    .split-card__left,
    .split-card__right {
        padding: 1.25rem;
    }

    .split-card__title {
        font-size: 1.375rem;
        margin-bottom: 0.9375rem;
    }

    .split-card__list-item {
        font-size: 0.84375rem;
        padding: 0.4375rem 0;
    }

    .split-card__icon {
        font-size: 0.84375rem;
    }
}

/* Desktop responsive (>1024px) - current base styles apply */
@media (min-width: 1025px) {
    .split-card-section {
        padding: 2.5rem 0;
    }

    .split-card {
        min-height: 300px;
    }
}
</style>
