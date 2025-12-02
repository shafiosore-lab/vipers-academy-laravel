@extends('layouts.academy')

@section('title', 'Vipers Academy - Professional Football Training & Development')

@section('meta_description', 'Join Vipers Academy for world-class football training. Professional coaching, modern
facilities, and comprehensive youth development programs.')

@section('content')
<!-- Hero Section - Full Image Background -->
<section class="hero-section-full position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 80vh;">
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
                        Join Kenya's premier football academy. Professional coaching, state-of-the-art facilities, and
                        proven pathways to professional football careers.
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



<!-- Features Section - Modern Cards -->
<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Why Choose Vipers Academy?</h2>
            <p class="lead text-muted">Experience world-class football training with our comprehensive development
                programs</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="feature-icon-wrapper mb-4">
                            <div class="feature-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-user-tie fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Expert Coaching Staff</h4>
                        <p class="card-text text-muted">Learn from former professional players and certified coaches
                            with decades of experience in elite football development.</p>
                        <div class="mt-3">
                            <span class="badge bg-primary">Certified Coaches</span>
                            <span class="badge bg-success ms-1">Pro Experience</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="feature-icon-wrapper mb-4">
                            <div class="feature-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-building fa-2x text-success"></i>
                            </div>
                        </div>
                        <h4 class="card-title fw-bold mb-3">State-of-the-Art Facilities</h4>
                        <p class="card-text text-muted">Train on professional-grade pitches, access modern gym
                            equipment, and utilize advanced sports technology for optimal development.</p>
                        <div class="mt-3">
                            <span class="badge bg-success">Modern Equipment</span>
                            <span class="badge bg-info ms-1">Pro Pitches</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="feature-icon-wrapper mb-4">
                            <div class="feature-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-seedling fa-2x text-warning"></i>
                            </div>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Holistic Development</h4>
                        <p class="card-text text-muted">Beyond football skills, we focus on character development,
                            academic excellence, and life skills for well-rounded individuals.</p>
                        <div class="mt-3">
                            <span class="badge bg-warning">Character Building</span>
                            <span class="badge bg-primary ms-1">Academic Support</span>
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
            <h2 class="display-5 fw-bold mb-3">Our Training Programs</h2>
            <p class="lead text-muted">Choose from our comprehensive range of football development programs</p>
        </div>

        <div class="row g-3">
            @foreach(\App\Models\Program::take(3)->get() as $program)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="program-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="program-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-football-ball"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold">{{ $program->title }}</h5>
                                <small class="text-muted">{{ $program->age_group }}</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">{{ Str::limit($program->description, 120) }}</p>
                        <div class="program-details mb-3">
                            <small class="text-muted d-block"><i
                                    class="fas fa-calendar me-1"></i>{{ $program->schedule }}</small>
                        </div>
                        <a href="{{ route('programs') }}" class="btn btn-primary w-100">Learn More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('programs') }}" class="btn btn-outline-primary btn-lg px-4 py-3">
                <i class="fas fa-list me-2"></i>View All Programs
            </a>
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

<!-- Latest News Section -->
<section class="news-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Latest Academy News</h2>
            <p class="lead text-muted">Stay updated with the latest developments and achievements</p>
        </div>

        <div class="row g-3">
            @foreach(\App\Models\News::latest()->take(3)->get() as $item)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="news-card card border-0 shadow-sm h-100">
                    @if($item->image)
                    <div class="news-image-wrapper">
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="news-category-badge">
                            <span class="badge bg-primary">{{ $item->category }}</span>
                        </div>
                    </div>
                    @else
                    <div class="news-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80"
                            class="card-img-top" alt="Football News" style="height: 200px; object-fit: cover;">
                        <div class="news-category-badge">
                            <span class="badge bg-primary">{{ $item->category }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">{{ $item->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($item->content, 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i
                                    class="fas fa-calendar me-1"></i>{{ $item->published_at ? $item->published_at->format('M d, Y') : $item->created_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('news.show', $item->id) }}" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('news') }}" class="btn btn-outline-primary btn-lg px-4 py-3">
                <i class="fas fa-newspaper me-2"></i>View All News
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-2 bg-primary text-white">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Ready to Start Your Football Journey?</h2>
        <p class="lead mb-4 opacity-75">Join Vipers Academy today and unlock your potential on the professional stage
        </p>
        <div class="d-flex flex-column flex-lg-row gap-3 justify-content-center align-items-center">
            @auth
            <a href="{{ route('player.portal.dashboard') }}" class="btn btn-success btn-lg px-5 py-3 fw-semibold">
                <i class=></i> Portal
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class=></i> Login
            </a>
            @endauth
            <x-register-dropdown />
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-phone me-2"></i>Contact Our Team
            </a>
        </div>
        <div class="mt-4">
            <small class="opacity-75">Limited spots available • Professional coaching • Modern facilities</small>
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
    min-height: 80vh;
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
</style>
