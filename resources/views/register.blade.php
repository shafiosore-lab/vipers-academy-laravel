@extends('layouts.academy')

@section('title', 'Register - Vipers Academy')

@section('content')
<div class="register-choice-container">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="bg-grid"></div>
        <div class="floating-orbs">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row g-4 align-items-center">

                    <!-- Left Side - Hero Content (Desktop Only) -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="hero-content text-white">
                            <!-- Logo & Title -->
                            <div class="logo-section mb-4">
                                <div class="logo-wrapper mb-3">
                                    <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers Academy Logo" class="hero-logo" />
                                </div>
                                <h1 class="hero-title">Vipers Academy</h1>
                                <p class="hero-subtitle">Excellence in Motion</p>
                            </div>

                            <!-- Main Headline -->
                            <div class="headline-section mb-4">
                                <h2 class="main-headline">
                                    Join Our
                                    <span class="highlight-text">Community</span>
                                </h2>
                                <p class="hero-description">
                                    Choose your path to excellence. Whether you're a young player looking to develop your skills
                                    or an organization seeking partnership opportunities, we have the perfect program for you.
                                </p>
                            </div>

                            <!-- Stats -->
                            <div class="stats-section mb-4">
                                <div class="row g-3">
                                    <div class="col-4 text-center">
                                        <div class="stat-number">500+</div>
                                        <div class="stat-label">Active Players</div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="stat-number">50+</div>
                                        <div class="stat-label">Partner Organizations</div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="stat-number">98%</div>
                                        <div class="stat-label">Satisfaction Rate</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Registration Choice -->
                    <div class="col-lg-6 col-md-8 col-sm-10 mx-auto">
                        <div class="registration-choice-card">

                            <!-- Form Header -->
                            <div class="choice-header">
                                <div class="text-center">
                                    <div class="choice-icon mb-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h3 class="choice-title">Choose Your Path</h3>
                                    <p class="choice-subtitle">Select how you'd like to join Vipers Academy</p>
                                </div>
                            </div>

                            <!-- Choice Options -->
                            <div class="choice-body">
                                <div class="choice-options">

                                    <!-- Player Registration -->
                                    <a href="{{ route('register.player') }}" class="choice-option player-option">
                                        <div class="option-icon">
                                            <i class="fas fa-futbol"></i>
                                        </div>
                                        <div class="option-content">
                                            <h4 class="option-title">Join as a Player</h4>
                                            <p class="option-description">
                                                Register as an individual player to access our training programs,
                                                coaching sessions, and development opportunities.
                                            </p>
                                            <div class="option-features">
                                                <span class="feature-tag">Individual Training</span>
                                                <span class="feature-tag">Skill Development</span>
                                                <span class="feature-tag">Competition Ready</span>
                                            </div>
                                        </div>
                                        <div class="option-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>

                                    <!-- Partner Registration -->
                                    <a href="{{ route('register.partner') }}" class="choice-option partner-option">
                                        <div class="option-icon">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <div class="option-content">
                                            <h4 class="option-title">Partner with Us</h4>
                                            <p class="option-description">
                                                Join as an organization, school, or academy to access our platform,
                                                training resources, and partnership opportunities.
                                            </p>
                                            <div class="option-features">
                                                <span class="feature-tag">Platform Access</span>
                                                <span class="feature-tag">Bulk Training</span>
                                                <span class="feature-tag">Custom Solutions</span>
                                            </div>
                                        </div>
                                        <div class="option-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>

                                </div>

                                <!-- Login Link -->
                                <div class="text-center mt-4 pt-3 border-top border-light">
                                    <p class="login-text">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="login-link">Sign In</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="trust-badges mt-4">
                            <span class="trust-badge">
                                <i class="fas fa-shield-alt me-1"></i>Secure Registration
                            </span>
                            <span class="trust-badge">
                                <i class="fas fa-check-circle me-1"></i>Easy Process
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Registration Choice Page Styles */
.register-choice-container {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
}

/* Animated Background */
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1e293b 0%, #7c3aed 50%, #1e293b 100%);
    z-index: -1;
}

.bg-grid {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        linear-gradient(rgba(139, 92, 246, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(139, 92, 246, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    animation: gridMove 20s linear infinite;
}

.floating-orbs {
    position: absolute;
    width: 100%;
    height: 100%;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.2;
    animation: floatOrb 7s ease-in-out infinite;
}

.orb-1 {
    width: 300px;
    height: 300px;
    background: #8b5cf6;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.orb-2 {
    width: 250px;
    height: 250px;
    background: #06b6d4;
    top: 20%;
    right: 10%;
    animation-delay: 2s;
}

.orb-3 {
    width: 200px;
    height: 200px;
    background: #ec4899;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes gridMove {
    0% { transform: translateY(0); }
    100% { transform: translateY(50px); }
}

@keyframes floatOrb {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

/* Hero Content */
.hero-content {
    padding: 3rem 2rem;
    animation: fadeInUp 1s ease-out;
}

.logo-section {
    animation: fadeInUp 1s ease-out;
}

.logo-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
}

.hero-logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #ffffff, #e879f9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 1rem 0 0.5rem 0;
}

.hero-subtitle {
    color: #c4b5fd;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
}

.headline-section {
    animation: fadeInUp 1s ease-out 0.3s both;
}

.main-headline {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
}

.highlight-text {
    display: block;
    background: linear-gradient(135deg, #8b5cf6, #ec4899, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.1rem;
    color: #cbd5e1;
    line-height: 1.6;
    opacity: 0.9;
}

.stats-section {
    animation: fadeInUp 1s ease-out 0.6s both;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #c4b5fd;
    opacity: 0.8;
}

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

/* Registration Choice Card */
.registration-choice-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    animation: slideInRight 1s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.choice-header {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed, #06b6d4);
    padding: 3rem 2rem 2.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.choice-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
}

.choice-icon {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    font-size: 1.8rem;
}

.choice-title {
    position: relative;
    z-index: 2;
    font-size: 2.2rem;
    font-weight: 700;
    margin: 1rem 0 0.5rem 0;
}

.choice-subtitle {
    position: relative;
    z-index: 2;
    color: #e9d5ff;
    opacity: 0.9;
}

.choice-body {
    padding: 2.5rem;
}

/* Choice Options */
.choice-options {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.choice-option {
    display: flex;
    align-items: center;
    padding: 2rem;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.choice-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.1), transparent);
    transition: left 0.5s;
}

.choice-option:hover::before {
    left: 100%;
}

.choice-option:hover {
    border-color: #8b5cf6;
    background: #faf5ff;
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(139, 92, 246, 0.15);
}

.player-option:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 12px 30px rgba(59, 130, 246, 0.15);
}

.partner-option:hover {
    border-color: #10b981;
    background: #ecfdf5;
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.15);
}

.option-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.player-option .option-icon {
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    color: white;
}

.partner-option .option-icon {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.option-content {
    flex: 1;
}

.option-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.option-description {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.option-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.feature-tag {
    background: rgba(139, 92, 246, 0.1);
    color: #7c3aed;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.player-option .feature-tag {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.partner-option .feature-tag {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
}

.option-arrow {
    margin-left: 1rem;
    color: #9ca3af;
    transition: all 0.3s ease;
}

.choice-option:hover .option-arrow {
    color: #8b5cf6;
    transform: translateX(4px);
}

.player-option:hover .option-arrow {
    color: #3b82f6;
}

.partner-option:hover .option-arrow {
    color: #10b981;
}

/* Login Link */
.login-text {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 0;
}

.login-link {
    color: #8b5cf6;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.login-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    transition: width 0.3s ease;
}

.login-link:hover::after {
    width: 100%;
}

.login-link:hover {
    color: #7c3aed;
}

/* Trust Badges */
.trust-badges {
    display: flex;
    justify-content: center;
    gap: 2rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
}

.trust-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .hero-content {
        display: none;
    }

    .registration-choice-card {
        margin: 2rem 1rem;
    }

    .choice-header {
        padding: 2rem 1.5rem 2rem;
    }

    .choice-body {
        padding: 2rem 1.5rem;
    }
}

@media (max-width: 576px) {
    .register-choice-container {
        padding: 1rem 0;
    }

    .registration-choice-card {
        margin: 1rem 0.5rem;
    }

    .choice-header {
        padding: 1.5rem 1rem 1.5rem;
    }

    .choice-body {
        padding: 1.5rem 1rem;
    }

    .main-headline {
        font-size: 2.5rem;
    }

    .choice-title {
        font-size: 1.8rem;
    }

    .choice-options {
        gap: 1rem;
    }

    .choice-option {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
    }

    .option-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .option-arrow {
        margin-left: 0;
        margin-top: 1rem;
    }

    .trust-badges {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endsection
