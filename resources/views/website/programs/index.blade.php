@extends('layouts.academy')

@section('title', 'Programs - Vipers Academy')

@section('content')

<!-- Hero Section -->
<section class="programs-hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
    </div>

    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="hero-content text-center text-white" data-aos="fade-up">
                    <h1 class="hero-title">Our Programs</h1>
                    <p class="hero-subtitle">Choose the perfect program to develop your child's potential</p>

                    <div class="hero-actions">
                        <a href="#programs-section" class="btn btn-warning btn-lg">
                            <i class="fas fa-search me-2"></i>Explore Programs
                        </a>
                        <a href="{{ route('enroll') }}" class="btn btn-outline-light btn-lg">
                            Enroll Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section id="programs-section" class="programs-section">
    <div class="container-fluid">
        <div class="programs-grid">

            <!-- Program 1: Weekend Football & Life-Skills -->
            <article class="program-card">
                <div class="program-layout">
                    <div class="program-content">
                        <span class="program-badge badge-primary">Football & Life Skills</span>
                        <h3 class="program-title">Weekend Football & Life-Skills Program</h3>

                        <div class="program-details">
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>KSH 500/month</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span>Full Year</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>Ages 6–18</span>
                            </div>
                        </div>

                        <p class="program-description">
                            A year-round weekend program combining structured football training, academic discipline,
                            digital literacy, character development, and CBC-aligned mentorship.
                        </p>

                        <div class="program-actions">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#weekendProgramModal">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </button>
                            <a href="{{ route('enroll') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Enroll Now
                            </a>
                        </div>
                    </div>

                    <div class="program-image">
                        <img src="{{ asset('assets/img/gallery/kids.jpeg') }}"
                             alt="Weekend Football & Life-Skills Program"
                             loading="lazy">
                    </div>
                </div>
            </article>

            <!-- Program 2: Long Holiday Intensive Camp -->
            <article class="program-card">
                <div class="program-layout reverse">
                   <div class="program-image">
                        <img src="{{ asset('assets/img/gallery/co.jpeg') }}"
                             alt="Weekend Football & Life-Skills Program"
                             loading="lazy">
                    </div>

                    <div class="program-content">
                        <span class="program-badge badge-warning">Holiday Intensive Camp</span>
                        <h3 class="program-title">Long Holiday Intensive Camp</h3>

                        <div class="program-details">
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>KSH 5,000/holiday</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span>April/Aug/Dec</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>Ages 7–17</span>
                            </div>
                        </div>

                        <p class="program-description">
                            A fully immersive holiday camp blending football training, academic mentorship,
                            computer exposure, tournaments, teamwork, and life-skills development.
                        </p>

                        <div class="program-actions">
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#holidayCampModal">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </button>
                            <a href="{{ route('enroll') }}" class="btn btn-outline-warning">
                                <i class="fas fa-user-plus me-2"></i>Enroll Now
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Program 3: Computer & Coding Classes -->
            <article class="program-card">
                <div class="program-layout">
                    <div class="program-content">
                        <span class="program-badge badge-success">Technology & Coding</span>
                        <h3 class="program-title">Computer & Coding Classes</h3>

                        <div class="program-details">
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>KSH 3,500/month</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>Flexible Schedule</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>Ages 7–18</span>
                            </div>
                        </div>

                        <p class="program-description">
                            Beginner-friendly tech classes where children learn coding, software basics,
                            problem-solving, and digital creativity — preparing them for future tech careers.
                        </p>

                        <div class="program-actions">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#codingClassesModal">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </button>
                            <a href="{{ route('enroll') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-2"></i>Enroll Now
                            </a>
                        </div>
                    </div>

                    <div class="program-image">
                        <img src="https://images.unsplash.com/photo-1515879218367-8466d910aaa4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                             alt="Computer & Coding Classes"
                             loading="lazy">
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>

<!-- Modals -->
@include('programs.modals.weekend-program')
@include('programs.modals.holiday-camp')
@include('programs.modals.coding-classes')

@endsection

@push('styles')
<style>
/* Hero Section */
.programs-hero {
    position: relative;
    min-height: 350px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(13, 71, 161, 0.9) 0%, rgba(25, 118, 210, 0.8) 100%);
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 70%, rgba(255, 193, 7, 0.1) 0%, transparent 50%);
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 40px 20px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.95;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-actions .btn {
    border-radius: 50px;
    font-weight: 600;
    padding: 0.75rem 2rem;
    transition: all 0.3s ease;
}

.hero-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Programs Section */
.programs-section {
    padding: 3rem 0;
}

.programs-grid {
    display: grid;
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Program Card */
.program-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.program-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 400px;
}

.program-layout.reverse {
    direction: rtl;
}

.program-layout.reverse > * {
    direction: ltr;
}

/* Program Content */
.program-content {
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.program-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
    width: fit-content;
}

.badge-primary {
    background: #0d47a1;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #000;
}

.badge-success {
    background: #28a745;
    color: white;
}

.program-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    color: #2c3e50;
}

.program-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: #555;
}

.detail-item i {
    width: 20px;
    color: #0d47a1;
}

.program-description {
    font-size: 1rem;
    line-height: 1.6;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.program-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.program-actions .btn {
    flex: 1;
    min-width: 140px;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.program-actions .btn:hover {
    transform: translateY(-2px);
}

/* Program Image */
.program-image {
    position: relative;
    overflow: hidden;
}

.program-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.program-card:hover .program-image img {
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 991px) {
    .program-layout,
    .program-layout.reverse {
        grid-template-columns: 1fr;
        direction: ltr;
    }

    .program-image {
        order: -1;
        min-height: 250px;
    }

    .program-content {
        padding: 2rem;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .program-title {
        font-size: 1.5rem;
    }

    .program-actions {
        flex-direction: column;
    }

    .program-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .programs-section {
        padding: 2rem 0;
    }

    .programs-grid {
        gap: 1.5rem;
    }

    .program-content {
        padding: 1.5rem;
    }

    .program-title {
        font-size: 1.3rem;
    }

    .program-image {
        min-height: 200px;
    }
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll to programs
    const exploreBtn = document.querySelector('a[href="#programs-section"]');
    if (exploreBtn) {
        exploreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('programs-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    // Scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.program-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
