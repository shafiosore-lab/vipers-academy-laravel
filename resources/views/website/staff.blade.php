@extends('layouts.academy')

@section('title', 'Leadership Team - Vipers Football Academy')
@section('meta_description', 'Meet the visionary leadership team driving Vipers Football Academy towards excellence in youth development and professional football training.')

@section('content')
<!-- Hero Section -->



<!-- Team Roles Section -->
<section class="roles-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Staff</h2>
            <p class="section-subtitle">Our expert team brings diverse skills and experience to guide our mission</p>
        </div>
        <div class="roles-grid">
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="role-title">Academy Director</h3>
                <p class="role-description">Strategic Vision</p>
            </div>
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="role-title">Technical Director</h3>
                <p class="role-description">Coaching Excellence</p>
            </div>
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3 class="role-title">Youth Development</h3>
                <p class="role-description">Talent Pipeline</p>
            </div>
            <div class="role-card">
                <div class="role-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="role-title">Scouting Director</h3>
                <p class="role-description">Talent Acquisition</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Members Section -->
<section class="team-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meet Our Leaders</h2>
            <p class="section-subtitle">Dedicated professionals committed to developing the next generation of football talent</p>
        </div>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-card">
                    <div class="member-photo">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                             alt="Dr. James Kiprop - Academy Director" loading="lazy">
                        <div class="member-overlay">
                            <div class="member-social">
                                <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Email Contact"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Dr. James Kiprop</h3>
                        <p class="member-role">Academy Director</p>
                        <p class="member-credentials">PhD Sports Management, UEFA Pro License</p>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <div class="member-card">
                    <div class="member-photo">
                        <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                             alt="Coach Michael Oduya - Technical Director" loading="lazy">
                        <div class="member-overlay">
                            <div class="member-social">
                                <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Email Contact"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Coach Michael Oduya</h3>
                        <p class="member-role">Technical Director</p>
                        <p class="member-credentials">UEFA A License, Sports Science MSc</p>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <div class="member-card">
                    <div class="member-photo">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                             alt="Grace Wanjiku - Youth Development Head" loading="lazy">
                        <div class="member-overlay">
                            <div class="member-social">
                                <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Email Contact"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name">Grace Wanjiku</h3>
                        <p class="member-role">Youth Development Head</p>
                        <p class="member-credentials">Sports Psych MSc, Safeguarding Lead</p>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <div class="member-card">
                    <div class="member-photo">
                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                             alt="Ahmed Hassan - Scouting Director" loading="lazy">
                        <div class="member-overlay">
                            <div class="member-social">
                                <a href="#" aria-label="LinkedIn Profile"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Email Contact"><i class="fas fa-envelope"></i></a>
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
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Join Our Mission?</h2>
            <p class="cta-text">Connect with our leadership team to learn more about partnerships, sponsorships, or how you can contribute to Kenyan football development.</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i>Contact Leadership
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline">
                    <i class="fas fa-info-circle me-2"></i>Learn More
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Counter Animation Function
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const end = target;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const current = Math.floor(start + (end - start) * easeOutQuart);

        element.textContent = current;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = end;
        }
    }

    requestAnimationFrame(update);
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Counter animations with intersection observer
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.dataset.target);
                if (target && !counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    animateCounter(counter, target);
                }
                counterObserver.unobserve(counter);
            }
        });
    }, { threshold: 0.3 });

    // Observe all stat counters
    document.querySelectorAll('.stat-number').forEach(counter => {
        counterObserver.observe(counter);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation for images
    const images = document.querySelectorAll('.member-photo img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
        if (img.complete) {
            img.classList.add('loaded');
        }
    });
});
</script>
@endpush

<style>
/* ===== MODERN LEADERSHIP TEAM STYLES ===== */

/* CSS Variables for consistent theming */
:root {
    --primary-color: #ea1c4d;
    --primary-dark: #c0173f;
    --primary-light: #ff2d5f;
    --secondary-color: #1a1a1a;
    --accent-color: #ffd700;
    --success-color: #28a745;
    --info-color: #17a2b8;
    --warning-color: #ffc107;
    --text-dark: #1e293b;
    --text-medium: #64748b;
    --text-light: #94a3b8;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
    --border-color: #e2e8f0;
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.16);
    --shadow-xl: 0 32px 64px rgba(0, 0, 0, 0.2);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;
}

/* ===== HERO SECTION ===== */
.leadership-hero {
    position: relative;
    overflow: hidden;
    min-height: 70vh;
    display: flex;
    align-items: center;
    color: white;
    padding: 120px 0 80px;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.hero-background img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    filter: brightness(0.4) contrast(1.1);
}

/* Fallback for when image fails to load */
.hero-background img[src=""] {
    display: none;
}

.hero-background:has(img[src=""])::after,
.hero-background img[alt=""]:not([src]) {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg,
        rgba(234, 28, 77, 0.85) 0%,
        rgba(192, 23, 63, 0.9) 50%,
        rgba(26, 26, 26, 0.95) 100%);
    z-index: 0;
}

.hero-overlay::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="football" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23football)"/></svg>');
    opacity: 0.6;
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-10px) rotate(1deg); }
    66% { transform: translateY(5px) rotate(-1deg); }
}

.hero-content {
    text-align: center;
    position: relative;
    z-index: 3;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.1;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    letter-spacing: -0.02em;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2.5vw, 1.5rem);
    font-weight: 400;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.4;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-accent {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), var(--primary-light));
    margin: 0 auto;
    border-radius: 2px;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
}

/* ===== STATISTICS SECTION ===== */
.stats-section {
    padding: 80px 0;
    background: var(--bg-light);
    position: relative;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    border-radius: 2px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.stat-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 2.5rem 2rem;
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    transform: scaleX(0);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
}

.stat-icon::after {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0.3;
    z-index: -1;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--primary-color);
    line-height: 1;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(234, 28, 77, 0.2);
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== ROLES SECTION ===== */
.roles-section {
    padding: 80px 0;
    background: var(--bg-white);
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 20px;
}

.section-title {
    font-size: clamp(2rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-medium);
    line-height: 1.6;
}

.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.role-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.role-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0;
    transition: var(--transition);
    z-index: -1;
}

.role-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.role-card:hover::before {
    opacity: 0.05;
}

.role-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 1.8rem;
    box-shadow: var(--shadow-md);
}

.role-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.role-description {
    font-size: 1rem;
    color: var(--text-medium);
    font-weight: 500;
}

/* ===== TEAM MEMBERS SECTION ===== */
.team-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--bg-light) 0%, #f1f5f9 100%);
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.team-member {
    perspective: 1000px;
}

.member-card {
    background: var(--bg-white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
    height: 100%;
    position: relative;
}

.member-card:hover {
    transform: translateY(-12px);
    box-shadow: var(--shadow-xl);
}

.member-photo {
    position: relative;
    overflow: hidden;
    height: 280px;
}

.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
    filter: grayscale(0);
}

.member-card:hover .member-photo img {
    transform: scale(1.1);
    filter: grayscale(0.1);
}

.member-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(234, 28, 77, 0.9), rgba(192, 23, 63, 0.9));
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
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.member-social a:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.member-info {
    padding: 2rem;
    text-align: center;
}

.member-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.member-role {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.member-credentials {
    font-size: 0.9rem;
    color: var(--text-medium);
    line-height: 1.5;
    margin: 0;
}

/* ===== CTA SECTION ===== */
.cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    filter: blur(60px);
}

.cta-content {
    text-align: center;
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.cta-title {
    font-size: clamp(2rem, 4vw, 2.5rem);
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.cta-text {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    font-size: 1rem;
    border: 2px solid transparent;
    cursor: pointer;
}

.btn-primary {
    background: var(--accent-color);
    color: var(--text-dark);
    border-color: var(--accent-color);
}

.btn-primary:hover {
    background: #ffed4e;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
}

.btn-outline {
    background: transparent;
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
    transform: translateY(-2px);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .leadership-hero {
        padding: 100px 0 60px;
        min-height: 50vh;
    }

    .hero-background img {
        object-position: center top;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .stats-grid,
    .roles-grid,
    .team-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .stat-card,
    .role-card {
        padding: 2rem 1.5rem;
    }

    .member-photo {
        height: 240px;
    }

    .member-info {
        padding: 1.5rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .leadership-hero {
        padding: 80px 0 40px;
    }

    .hero-content {
        padding: 0 15px;
    }

    .hero-title {
        font-size: 2rem;
    }

    .stats-section,
    .roles-section,
    .team-section,
    .cta-section {
        padding: 60px 0;
    }

    .section-header {
        margin-bottom: 3rem;
    }

    .stat-card,
    .role-card {
        padding: 1.5rem 1rem;
    }

    .stat-number {
        font-size: 2.8rem;
    }

    .member-photo {
        height: 200px;
    }
}

/* ===== ACCESSIBILITY ===== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus states */
.btn:focus,
.member-social a:focus {
    outline: 2px solid var(--accent-color);
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .stat-card,
    .role-card,
    .member-card {
        border: 2px solid var(--text-dark);
    }
}

/* ===== ANIMATIONS ===== */
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

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Apply animations on scroll */
.stat-card,
.role-card,
.team-member {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }

.role-card:nth-child(1) { animation-delay: 0.1s; }
.role-card:nth-child(2) { animation-delay: 0.2s; }
.role-card:nth-child(3) { animation-delay: 0.3s; }
.role-card:nth-child(4) { animation-delay: 0.4s; }

.team-member:nth-child(1) { animation-delay: 0.1s; }
.team-member:nth-child(2) { animation-delay: 0.2s; }
.team-member:nth-child(3) { animation-delay: 0.3s; }
.team-member:nth-child(4) { animation-delay: 0.4s; }

/* ===== PRINT STYLES ===== */
@media print {
    .leadership-hero,
    .cta-section {
        background: white !important;
        color: black !important;
    }

    .hero-background,
    .hero-overlay,
    .hero-background::before,
    .hero-overlay::before {
        display: none !important;
    }

    .leadership-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    }

    .member-overlay {
        display: none !important;
    }
}
</style>
