@extends('layouts.academy')

@section('title', 'Leadership Team - Vipers Football Academy')
@section('meta_description', 'Meet the visionary leadership team driving Vipers Football Academy towards excellence.')

@section('content')
<style>
:root {
    --primary: #ea1c4d;
    --primary-dark: #c0173f;
    --accent: #ffd700;
    --dark: #1a1a1a;
    --gray-900: #1e293b;
    --gray-600: #64748b;
    --gray-300: #e2e8f0;
    --gray-100: #f8fafc;
    --white: #ffffff;
    --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
    --radius: 16px;
    --transition: 0.3s ease;
}

/* Section Spacing */
.section {
    padding: 60px 20px;
}

/* Section Header */
.section-header {
    text-align: center;
    max-width: 700px;
    margin: 0 auto 50px;
}

.section-title {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--gray-600);
    line-height: 1.6;
    margin-top: 1.5rem;
}

/* Team Grid */
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* Member Card */
.member-card {
    background: var(--white);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.member-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

/* Member Photo */
.member-photo {
    position: relative;
    height: 280px;
    overflow: hidden;
    background: var(--gray-100);
}

.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.member-card:hover .member-photo img {
    transform: scale(1.05);
}

/* LinkedIn Button */
.linkedin-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 44px;
    height: 44px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    transition: var(--transition);
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 10;
}

.linkedin-btn:hover {
    background: var(--primary);
    color: var(--white);
    transform: scale(1.1);
}

/* Member Info */
.member-info {
    padding: 1.75rem;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.member-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.member-role {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.member-credentials {
    font-size: 0.9rem;
    color: var(--gray-600);
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .section {
        padding: 40px 15px;
    }

    .team-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .member-photo {
        height: 240px;
    }

    .member-info {
        padding: 1.5rem;
    }

    .section-header {
        margin-bottom: 40px;
    }
}

@media (max-width: 480px) {
    .member-photo {
        height: 220px;
    }

    .member-name {
        font-size: 1.25rem;
    }

    .linkedin-btn {
        width: 40px;
        height: 40px;
        top: 12px;
        right: 12px;
    }
}

/* Fade-in Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.member-card {
    animation: fadeInUp 0.6s ease-out backwards;
}

.member-card:nth-child(1) {
    animation-delay: 0.1s;
}

.member-card:nth-child(2) {
    animation-delay: 0.2s;
}

.member-card:nth-child(3) {
    animation-delay: 0.3s;
}

.member-card:nth-child(4) {
    animation-delay: 0.4s;
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}

.linkedin-btn:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}
</style>

<!-- Team Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Meet Our Leaders</h1>
            <p class="section-subtitle">
                Dedicated professionals committed to developing the next generation of football talent
            </p>
        </div>

        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('assets/img/staff/colo.jpeg') }}"
                         alt="Dr. James Kiprop - Academy Director"
                         loading="lazy">
                    <a href="#" class="linkedin-btn" aria-label="Dr. James Kiprop LinkedIn Profile">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                <div class="member-info">
                    <h3 class="member-name">Dr. Collins W</h3>
                    <p class="member-role">Academy Director</p>
                    <p class="member-credentials">PhD Sports Management, UEFA Pro License</p>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('assets/img/gallery/kids.jpeg') }}"
                         alt="Coach Michael Oduya - Technical Director"
                         loading="lazy">
                    <a href="#" class="linkedin-btn" aria-label="Coach Michael Oduya LinkedIn Profile">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                <div class="member-info">
                    <h3 class="member-name">Coach Michael Oduya</h3>
                    <p class="member-role">Technical Director</p>
                    <p class="member-credentials">UEFA A License, Sports Science MSc</p>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('assets/img/gallery/kids.jpeg') }}"
                         alt="Grace Wanjiku - Youth Development Head"
                         loading="lazy">
                    <a href="#" class="linkedin-btn" aria-label="Grace Wanjiku LinkedIn Profile">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                <div class="member-info">
                    <h3 class="member-name">Grace Wanjiku</h3>
                    <p class="member-role">Youth Development Head</p>
                    <p class="member-credentials">Sports Psych MSc, Safeguarding Lead</p>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('assets/img/gallery/kids.jpeg') }}"
                         alt="Ahmed Hassan - Scouting Director"
                         loading="lazy">
                    <a href="#" class="linkedin-btn" aria-label="Ahmed Hassan LinkedIn Profile">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
                <div class="member-info">
                    <h3 class="member-name">Ahmed Hassan</h3>
                    <p class="member-role">Scouting Director</p>
                    <p class="member-credentials">Data Analytics BSc, AI Specialist</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Lazy load images
    const images = document.querySelectorAll('.member-photo img');

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.addEventListener('load', () => {
                    img.style.opacity = '1';
                });
                imageObserver.unobserve(img);
            }
        });
    }, {
        rootMargin: '50px'
    });

    images.forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s';
        imageObserver.observe(img);
    });
});
</script>
@endpush
