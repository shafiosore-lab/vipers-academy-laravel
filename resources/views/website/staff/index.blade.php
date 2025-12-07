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
        transform: scale(1.08);
    }

    /* Photo Overlay */
    .member-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(234, 28, 77, 0.95), rgba(192, 23, 63, 0.95));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .member-card:hover .member-overlay {
        opacity: 1;
    }

    .member-social {
        display: flex;
        gap: 1rem;
    }

    .member-social a {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        transition: var(--transition);
        font-size: 1.2rem;
    }

    .member-social a:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    /* Member Info */
    .member-info {
        padding: 1.75rem;
        text-align: center;
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

        .member-social a {
            width: 44px;
            height: 44px;
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

    .member-card:nth-child(1) { animation-delay: 0.1s; }
    .member-card:nth-child(2) { animation-delay: 0.2s; }
    .member-card:nth-child(3) { animation-delay: 0.3s; }
    .member-card:nth-child(4) { animation-delay: 0.4s; }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }

    .member-social a:focus {
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
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                         alt="Dr. James Kiprop"
                         loading="lazy">
                    <div class="member-overlay">
                        <div class="member-social">
                            <a href="#" aria-label="LinkedIn Profile">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="mailto:james@vipersacademy.com" aria-label="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="member-info">
                    <h3 class="member-name">Dr. James Kiprop</h3>
                    <p class="member-role">Academy Director</p>
                    <p class="member-credentials">PhD Sports Management, UEFA Pro License</p>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="member-card">
                <div class="member-photo">
                    <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                         alt="Coach Michael Oduya"
                         loading="lazy">
                    <div class="member-overlay">
                        <div class="member-social">
                            <a href="#" aria-label="LinkedIn Profile">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="mailto:michael@vipersacademy.com" aria-label="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
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
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                         alt="Grace Wanjiku"
                         loading="lazy">
                    <div class="member-overlay">
                        <div class="member-social">
                            <a href="#" aria-label="LinkedIn Profile">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="mailto:grace@vipersacademy.com" aria-label="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
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
                    <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                         alt="Ahmed Hassan"
                         loading="lazy">
                    <div class="member-overlay">
                        <div class="member-social">
                            <a href="#" aria-label="LinkedIn Profile">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="mailto:ahmed@vipersacademy.com" aria-label="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
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
    }, { rootMargin: '50px' });

    images.forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s';
        imageObserver.observe(img);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
</script>
@endpush
