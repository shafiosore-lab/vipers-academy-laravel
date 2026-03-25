<?php $__env->startSection('title', 'GameSuite - Professional Football Management Software'); ?>

<?php $__env->startSection('meta_description', 'GameSuite is a comprehensive football management platform offering tournament management, academy management, and financial tracking for football organizations worldwide.'); ?>

<?php $__env->startSection('content'); ?>

<!-- GameSuite Landing Page - Full Version -->
<style>
    /* Page-specific overrides - disable parent constraints for full scrolling */
    .content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }

    /* Prevent horizontal scroll on mobile */
    body {
        overflow-x: hidden;
        max-width: 100vw;
    }

    /* Hero Section */
    .gs-hero-section {
        min-height: 90vh;
        background: linear-gradient(135deg, #0a1628 0%, #1a3a52 50%, #0f2744 100%);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .gs-hero-pattern {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0.05;
        background-image: repeating-linear-gradient(90deg, transparent, transparent 50px, rgba(255,255,255,0.05) 50px, rgba(255,255,255,0.05) 51px);
    }

    .gs-floating-orb {
        position: absolute;
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .gs-floating-orb-1 {
        top: 10%;
        left: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.15) 0%, transparent 70%);
    }

    .gs-floating-orb-2 {
        bottom: 20%;
        right: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(74,222,128,0.1) 0%, transparent 70%);
        animation-direction: reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .gs-hero-content {
        position: relative;
        z-index: 2;
        margin-bottom: 1.5rem;
    }

    .gs-badge {
        display: inline-block;
        background: rgba(255, 215, 0, 0.15);
        color: #ffd700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 215, 0, 0.3);
    }

    .gs-hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .gs-hero-title span {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gs-hero-desc {
        color: rgba(255,255,255,0.85);
        font-size: 1rem;
        margin-bottom: 1rem;
        max-width: 500px;
    }

    .gs-hero-cta {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        width: 100%;
        box-sizing: border-box;
        overflow: visible;
    }

    .gs-btn-primary {
        padding: 12px 24px;
        min-height: 44px;
        background: linear-gradient(135deg, #ffd700 0%, #ffb700 100%);
        color: #0a1628;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .gs-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        color: #0a1628;
    }

    .gs-btn-secondary {
        padding: 12px 24px;
        min-height: 44px;
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .gs-btn-secondary:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }

    .gs-trust-indicators {
        display: flex;
        gap: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        flex-wrap: wrap;
    }

    .gs-trust-indicators .row {
        margin: 0;
    }

    .gs-trust-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .gs-trust-number {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .gs-trust-label {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.7);
    }

    /* Dashboard Preview Card */
    .gs-dashboard-card {
        background: rgba(255,255,255,0.98);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        box-sizing: border-box;
        margin-bottom: 1.5rem;
    }

    .gs-dashboard-header {
        background: linear-gradient(135deg, #1a3a52 0%, #0a1628 100%);
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .gs-dashboard-title {
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .gs-dashboard-subtitle {
        color: rgba(255,255,255,0.7);
        font-size: 0.7rem;
    }

    .gs-live-badge {
        background: #22c55e;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.65rem;
    }

    .gs-dashboard-body {
        padding: 1rem;
    }

    .gs-metrics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .gs-metric-card {
        padding: 0.75rem;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .gs-metric-label {
        font-size: 0.7rem;
        color: #64748b;
    }

    .gs-metric-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .gs-metric-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .gs-metric-bar-fill {
        height: 100%;
        border-radius: 2px;
    }

    .gs-dashboard-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .gs-dashboard-tab {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gs-dashboard-tab:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .gs-dashboard-tab.active {
        background: linear-gradient(135deg, #1a3a52 0%, #0a1628 100%);
        color: #fff;
    }

    .gs-dashboard-tab.active:hover {
        background: linear-gradient(135deg, #153044 0%, #081222 100%);
        color: #fff;
    }

    .gs-quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .gs-quick-action {
        padding: 0.6rem;
        border-radius: 8px;
        color: #fff;
        font-size: 0.75rem;
        text-align: center;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .gs-quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Dashboard Images */
    .gs-dashboard-images {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        box-sizing: border-box;
    }

    .gs-dashboard-img {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 10px;
        padding: 0.75rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .gs-dashboard-img:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .gs-dashboard-img i {
        margin-bottom: 0.25rem;
    }

    .gs-dashboard-img span {
        font-size: 0.65rem;
        font-weight: 600;
        color: #475569;
        margin-top: 0.25rem;
    }

    .text-primary { color: #0284c7; }
    .text-success { color: #16a34a; }
    .text-warning { color: #f59e0b; }
    .text-info { color: #0ea5e9; }

    /* Features Section */
    .gs-features-section {
        padding: 5rem 0;
        background: #fff;
    }

    .gs-section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .gs-section-tag {
        display: inline-block;
        background: rgba(26, 58, 82, 0.1);
        color: #1a3a52;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .gs-section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0a1628;
        margin-bottom: 0.5rem;
    }

    .gs-section-desc {
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
    }

    .gs-feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .gs-feature-card {
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid;
        transition: all 0.3s ease;
    }

    .gs-feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .gs-feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .gs-feature-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0a1628;
        margin-bottom: 0.5rem;
    }

    .gs-feature-desc {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .gs-feature-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .gs-feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #475569;
    }

    /* Stats Section */
    .gs-stats-section {
        padding: 4rem 0;
        background: linear-gradient(135deg, #0a1628 0%, #1a3a52 100%);
    }

    .gs-stats-title {
        text-align: center;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .gs-stats-subtitle {
        text-align: center;
        color: rgba(255,255,255,0.7);
        margin-bottom: 2rem;
    }

    .gs-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        text-align: center;
    }

    .gs-stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .gs-stat-label {
        color: rgba(255,255,255,0.7);
        font-size: 0.8rem;
    }

    /* CTA Section */
    .gs-cta-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #0a1628 0%, #1a3a52 100%);
        text-align: center;
    }

    .gs-cta-title {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
    }

    .gs-cta-desc {
        color: rgba(255,255,255,0.8);
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    .gs-cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        width: 100%;
        box-sizing: border-box;
    }

    /* Scroll indicator */
    .gs-scroll-indicator {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,0.6);
        text-align: center;
        animation: bounce 2s infinite;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gs-scroll-indicator:hover {
        color: #fff;
        transform: translateX(-50%) translateY(-5px);
    }

    .gs-scroll-indicator i {
        font-size: 1.2rem;
    }

    @keyframes bounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(-10px); }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .gs-hero-section {
            min-height: auto;
            padding: 2rem 0;
        }

        .gs-hero-title {
            font-size: 2rem;
        }

        .gs-metrics-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .gs-feature-grid {
            grid-template-columns: 1fr;
        }

        .gs-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Reduce floating orbs on tablet/mobile */
        .gs-floating-orb-1,
        .gs-floating-orb-2 {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .gs-hero-title {
            font-size: 1.5rem;
        }

        .gs-hero-desc {
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .gs-metrics-row {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: auto;
            grid-auto-flow: row;
        }

        .gs-stats-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        .gs-stat-number {
            font-size: 1.5rem;
        }

        .gs-stat-label {
            font-size: 0.7rem;
        }

        /* Hide scroll indicator on mobile to save space and reduce gap */
        .gs-scroll-indicator {
            display: none;
        }

        /* Trust indicators mobile layout with Bootstrap grid */
        .gs-trust-indicators {
            flex-direction: row;
            gap: 1rem;
            padding-top: 1rem;
        }

        .gs-trust-indicators .row {
            width: 100% !important;
        }

        .gs-trust-indicators .col-4 {
            display: flex;
            justify-content: center;
        }

        /* Mobile Dashboard - Full width with proper scaling */
        .gs-dashboard-card {
            width: 100%;
            max-width: 100%;
            margin: 0 auto 1rem;
            overflow-x: visible;
        }

        .gs-dashboard-images {
            grid-template-columns: repeat(4, 1fr);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .gs-dashboard-img {
            min-height: 60px;
            padding: 0.5rem;
        }

        .gs-dashboard-img i {
            font-size: 1.25rem !important;
        }

        .gs-dashboard-img span {
            font-size: 0.55rem;
        }

        .gs-quick-actions {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: auto;
            grid-auto-flow: row;
        }

        .gs-dashboard-body {
            padding: 0.75rem;
        }

        .gs-metric-card {
            padding: 0.5rem;
        }

        .gs-metric-value {
            font-size: 1rem;
        }
    }

    /* Mobile CTA buttons - 320px to 480px viewport */
    @media (max-width: 480px) {
        /* Reduce vertical spacing between dashboard and trust indicators */
        .gs-trust-indicators {
            padding-top: 1rem !important;
            margin-top: 1rem !important;
        }

        /* Container constraints for mobile */
        .gs-hero-section .container {
            max-width: 100%;
            padding-left: 12px;
            padding-right: 12px;
        }

        .gs-hero-section .row {
            margin-left: -8px;
            margin-right: -8px;
        }

        .gs-hero-section .col-lg-5,
        .gs-hero-section .col-lg-7 {
            padding-left: 8px;
            padding-right: 8px;
        }

        /* Hero Section CTA - Keep buttons side by side */
        .gs-hero-cta {
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: flex-start;
            gap: 0.5rem !important;
            width: 100%;
            margin-bottom: 1rem !important;
        }

        .gs-hero-cta .gs-btn-primary,
        .gs-hero-cta .gs-btn-secondary {
            flex: 1;
            min-width: 0;
            padding: 10px 12px;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            justify-content: center;
        }

        .gs-hero-cta .gs-btn-primary i,
        .gs-hero-cta .gs-btn-secondary i {
            display: none;
        }

        /* CTA Section buttons */
        .gs-cta-buttons {
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: center;
            gap: 0.5rem !important;
            width: 100%;
            padding: 0 0.5rem;
        }

        .gs-cta-buttons .gs-btn-primary,
        .gs-cta-buttons .gs-btn-secondary {
            flex: 1;
            min-width: 0;
            padding: 10px 12px;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            justify-content: center;
        }

        .gs-cta-buttons .gs-btn-primary i,
        .gs-cta-buttons .gs-btn-secondary i {
            display: none;
        }
    }

    /* Extra small devices - 320px and below */
    @media (max-width: 360px) {
        .gs-hero-cta,
        .gs-cta-buttons {
            gap: 0.35rem !important;
        }

        .gs-hero-cta .gs-btn-primary,
        .gs-hero-cta .gs-btn-secondary,
        .gs-cta-buttons .gs-btn-primary,
        .gs-cta-buttons .gs-btn-secondary {
            padding: 8px 10px;
            font-size: 0.75rem;
        }
    }
</style>

<script>
    function scrollToSection(sectionId) {
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>

<!-- Hero Section -->
<section class="gs-hero-section">
    <div class="gs-hero-pattern"></div>
    <div class="gs-floating-orb gs-floating-orb-1"></div>
    <div class="gs-floating-orb gs-floating-orb-2"></div>

    <div class="container">
        <div class="row align-items-center">
            <!-- Left Content -->
            <div class="col-lg-5 gs-hero-content">
                <div class="gs-badge">
                    <i class="fas fa-bolt me-2"></i>TRUSTED BY 500+ ORGANIZATIONS
                </div>

                <h1 class="gs-hero-title">
                    Run Your Football Organization
                    <span>Like a Pro</span>
                </h1>

                <p class="gs-hero-desc">
                    Complete tournament management, academy operations, and player development—all in one powerful platform built for grassroots football.
                </p>

                <div class="gs-hero-cta">
                    <a href="#demo" class="gs-btn-primary">
                        <i class="fas fa-play-circle me-2"></i>Request Demo
                    </a>
                    <a href="<?php echo e(route('register')); ?>?trial=true" class="gs-btn-secondary">
                        Start Free Trial
                    </a>
                </div>

                <div class="gs-trust-indicators">
                    <div class="row w-100 g-2">
                        <div class="col-4">
                            <div class="gs-trust-item">
                                <div class="gs-trust-number" style="color: #ffd700;"><?php echo e(number_format($totalPlayers)); ?>+</div>
                                <div class="gs-trust-label">Active Players</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="gs-trust-item">
                                <div class="gs-trust-number" style="color: #4ade80;"><?php echo e($activePrograms); ?>+</div>
                                <div class="gs-trust-label">Programs</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="gs-trust-item">
                                <div class="gs-trust-number" style="color: #60a5fa;">99.9%</div>
                                <div class="gs-trust-label">Uptime</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Dashboard Preview -->
            <div class="col-lg-7">
                <div class="gs-dashboard-card">
                    <div class="gs-dashboard-header">
                        <div>
                            <div class="gs-dashboard-title">
                                <i class="fas fa-futbol me-2"></i>GameSuite Dashboard
                            </div>
                            <div class="gs-dashboard-subtitle">Real-time analytics & metrics</div>
                        </div>
                        <span class="gs-live-badge">
                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Live
                        </span>
                    </div>

                    <div class="gs-dashboard-body">
                        <!-- Metrics -->
                        <div class="gs-metrics-row">
                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Players</div>
                                <div class="gs-metric-value"><?php echo e(number_format($totalPlayers)); ?></div>
                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill" style="width: <?php echo e($playerCompletion); ?>%; background: #0284c7;"></div>
                                </div>
                            </div>
                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Programs</div>
                                <div class="gs-metric-value"><?php echo e($activePrograms); ?></div>
                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill" style="width: <?php echo e($programCompletion); ?>%; background: #16a34a;"></div>
                                </div>
                            </div>
                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Goals</div>
                                <div class="gs-metric-value"><?php echo e(number_format($totalGoals)); ?></div>
                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill" style="width: 75%; background: #f59e0b;"></div>
                                </div>
                            </div>
                            <div class="gs-metric-card">
                                <div class="gs-metric-label">Revenue</div>
                                <div class="gs-metric-value">$<?php echo e(number_format($totalRevenue, 0)); ?></div>
                                <div class="gs-metric-bar">
                                    <div class="gs-metric-bar-fill" style="width: 85%; background: #db2777;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="gs-dashboard-tabs">
                            <button class="gs-dashboard-tab active">Actions</button>
                            <button class="gs-dashboard-tab">Stats</button>
                            <button class="gs-dashboard-tab">Players</button>
                            <button class="gs-dashboard-tab">Activity</button>
                        </div>

                        <!-- Quick Actions -->
                        <div class="gs-quick-actions">
                            <a href="<?php echo e(route('admin.players.create')); ?>" class="gs-quick-action" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                <i class="fas fa-user-plus me-1"></i>Add Player
                            </a>
                            <a href="<?php echo e(route('admin.tournaments.create')); ?>" class="gs-quick-action" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                                <i class="fas fa-trophy me-1"></i>Create Tournament
                            </a>
                            <a href="<?php echo e(route('admin.messaging.quick')); ?>" class="gs-quick-action" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="fas fa-envelope me-1"></i>Send Message
                            </a>
                            <a href="<?php echo e(route('admin.training-sessions.create')); ?>" class="gs-quick-action" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                <i class="fas fa-calendar-plus me-1"></i>Schedule Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="gs-scroll-indicator" onclick="scrollToSection('features')">
        <i class="fas fa-chevron-down fa-lg mb-1"></i>
        <div class="small">Explore Dashboard</div>
    </div>
</section>

<!-- Features Section -->
<section class="gs-features-section" id="features">
    <div class="container">
        <div class="gs-section-header">
            <span class="gs-section-tag">CORE FEATURES</span>
            <h2 class="gs-section-title">Everything You Need, Nothing You Don't</h2>
            <p class="gs-section-desc">Professional-grade tools designed specifically for football organizations</p>
        </div>

        <div class="gs-feature-grid">
            <!-- Tournament Management -->
            <div class="gs-feature-card" style="background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%); border-color: #e0f2fe;">
                <div class="gs-feature-icon" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <i class="fas fa-trophy fa-lg text-white"></i>
                </div>
                <h4 class="gs-feature-title">Tournament Management</h4>
                <p class="gs-feature-desc">Run professional tournaments from registration to final whistle</p>
                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Automated fixture generation</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Real-time standings & results</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Team registration & verification</span>
                    </div>
                </div>
            </div>

            <!-- Academy Management -->
            <div class="gs-feature-card" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-color: #dcfce7;">
                <div class="gs-feature-icon" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <i class="fas fa-graduation-cap fa-lg text-white"></i>
                </div>
                <h4 class="gs-feature-title">Academy Management</h4>
                <p class="gs-feature-desc">Comprehensive player development & tracking system</p>
                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Centralized player database</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Performance tracking & analytics</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Parent communication portal</span>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="gs-feature-card" style="background: linear-gradient(135deg, #fef3c7 0%, #ffffff 100%); border-color: #fde68a;">
                <div class="gs-feature-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-shield-alt fa-lg text-white"></i>
                </div>
                <h4 class="gs-feature-title">Enterprise Security</h4>
                <p class="gs-feature-desc">Bank-level data protection & compliance</p>
                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>256-bit encryption</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Role-based access control</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>99.9% uptime guarantee</span>
                    </div>
                </div>
            </div>

            <!-- System Control -->
            <div class="gs-feature-card" style="background: linear-gradient(135deg, #fce7f3 0%, #ffffff 100%); border-color: #fbcfe8;">
                <div class="gs-feature-icon" style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);">
                    <i class="fas fa-sliders-h fa-lg text-white"></i>
                </div>
                <h4 class="gs-feature-title">System Control</h4>
                <p class="gs-feature-desc">Complete flexibility & customization</p>
                <div class="gs-feature-list">
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Flexible subscription plans</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Advanced analytics dashboard</span>
                    </div>
                    <div class="gs-feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>API integrations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="gs-stats-section">
    <div class="container">
        <h2 class="gs-stats-title">Trusted by Organizations Worldwide</h2>
        <p class="gs-stats-subtitle">Real impact, measurable results</p>

        <div class="gs-stats-grid">
            <div>
                <div class="gs-stat-number" style="color: #ffd700;"><?php echo e(number_format($totalPlayers)); ?>+</div>
                <div class="gs-stat-label">Active Players</div>
            </div>
            <div>
                <div class="gs-stat-number" style="color: #4ade80;"><?php echo e($totalPrograms * 10); ?>+</div>
                <div class="gs-stat-label">Tournaments</div>
            </div>
            <div>
                <div class="gs-stat-number" style="color: #60a5fa;"><?php echo e($totalPartners); ?>+</div>
                <div class="gs-stat-label">Partners</div>
            </div>
            <div>
                <div class="gs-stat-number" style="color: #f472b6;"><?php echo e($yearsActive); ?></div>
                <div class="gs-stat-label">Years Active</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gs-cta-section" id="demo">
    <div class="container">
        <h2 class="gs-cta-title">Ready to Transform Your Organization?</h2>
        <p class="gs-cta-desc">
            Join 500+ organizations running professional football operations with GameSuite
        </p>

        <div class="gs-cta-buttons">
            <a href="<?php echo e(route('register')); ?>?trial=true" class="gs-btn-primary">
                <i class="fas fa-rocket me-2"></i>Start Free Trial
            </a>
            <a href="<?php echo e(route('contact')); ?>" class="gs-btn-secondary">
                Contact Sales
            </a>
        </div>

        <div class="gs-trust-indicators" style="justify-content: center; margin-top: 2rem;">
            <div class="gs-trust-item">
                <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                <span style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">Setup in 48 hours</span>
            </div>
            <div class="gs-trust-item">
                <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                <span style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">No credit card required</span>
            </div>
            <div class="gs-trust-item">
                <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                <span style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">14-day free trial</span>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.academy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
