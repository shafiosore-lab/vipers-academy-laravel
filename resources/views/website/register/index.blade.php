<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Vipers Academy</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Figtree', sans-serif;
        height: 100vh;
        width: 100vw;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    /* Header */
    .header {
        background: white;
        padding: 0.75rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    /* Main Container */
    .register-split-container {
        display: flex;
        flex: 1;
        width: 100vw;
        overflow: hidden;
    }

    /* Left Section - Image (50% width) */
    .left-section {
        width: 50vw;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1.5rem 1.5rem 2rem;
    }

    .image-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .player-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg,
            rgba(40, 167, 69, 0.25) 0%,
            rgba(32, 201, 151, 0.15) 50%,
            rgba(40, 167, 69, 0.25) 100%);
    }

    /* Right Section - Content (50% width) */
    .right-section {
        width: 50vw;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 2rem 1.5rem 1.5rem;
    }

    .forms-container {
        width: 100%;
        max-width: 550px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        padding: 1.75rem 1.75rem;
    }

    /* Forms Header */
    .forms-header {
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .forms-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.3rem;
    }

    .forms-subtitle {
        color: #6b7280;
        font-size: 0.825rem;
    }

    /* Registration Options */
    .registration-options {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        margin-bottom: 1.15rem;
    }

    .option-card {
        display: flex;
        align-items: center;
        padding: 0.95rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .option-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.12);
    }

    .player-card:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .partner-card:hover {
        border-color: #10b981;
        background: #ecfdf5;
    }

    .card-icon {
        width: 46px;
        height: 46px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-right: 0.9rem;
        flex-shrink: 0;
    }

    .player-card .card-icon {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
    }

    .partner-card .card-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .card-content {
        flex: 1;
    }

    .card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .card-description {
        color: #6b7280;
        line-height: 1.35;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
    }

    .card-features {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .feature-tag {
        background: rgba(139, 92, 246, 0.1);
        color: #7c3aed;
        padding: 0.15rem 0.55rem;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .player-card .feature-tag {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .partner-card .feature-tag {
        background: rgba(16, 185, 129, 0.1);
        color: #047857;
    }

    .card-arrow {
        margin-left: 0.75rem;
        color: #9ca3af;
        font-size: 1.05rem;
        transition: all 0.3s ease;
    }

    .option-card:hover .card-arrow {
        transform: translateX(3px);
    }

    .player-card:hover .card-arrow {
        color: #3b82f6;
    }

    .partner-card:hover .card-arrow {
        color: #10b981;
    }

    /* Login Section */
    .login-section {
        text-align: center;
        margin-bottom: 1.1rem;
        padding-top: 1.1rem;
        border-top: 1px solid #e5e7eb;
    }

    .login-text {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .login-link {
        color: #8b5cf6;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .login-link:hover {
        color: #7c3aed;
        text-decoration: underline;
    }

    /* Trust Badges */
    .trust-badges {
        display: flex;
        justify-content: center;
        gap: 1.25rem;
    }

    .trust-badge {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        color: #6b7280;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .trust-badge i {
        color: #10b981;
        font-size: 0.8rem;
    }

    /* Footer */
    .footer {
        background: white;
        padding: 0.65rem 2rem;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .footer p {
        font-size: 0.8rem;
        color: #6b7280;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .register-split-container {
            flex-direction: column;
            overflow-y: auto;
        }

        .left-section,
        .right-section {
            width: 100%;
        }

        .left-section {
            padding: 1.5rem;
            min-height: 300px;
        }

        .right-section {
            padding: 1.5rem;
        }

        .forms-container {
            max-width: 100%;
            max-height: none;
            padding: 2rem 1.75rem;
        }
    }

    @media (max-width: 768px) {
        .header {
            padding: 0.65rem 1.5rem;
        }

        .header h1 {
            font-size: 1.15rem;
        }

        .left-section {
            padding: 1.25rem;
            min-height: 250px;
        }

        .right-section {
            padding: 1.25rem;
        }

        .forms-container {
            padding: 1.75rem 1.5rem;
        }

        .forms-title {
            font-size: 1.6rem;
        }

        .card-description {
            font-size: 0.8rem;
        }

        .option-card {
            padding: 1rem;
        }

        .card-icon {
            width: 46px;
            height: 46px;
            font-size: 1.15rem;
            margin-right: 0.95rem;
        }

        .trust-badges {
            flex-direction: column;
            gap: 0.65rem;
            align-items: center;
        }
    }

    @media (max-width: 576px) {
        .header {
            padding: 0.6rem 1rem;
        }

        .header h1 {
            font-size: 1.05rem;
        }

        .footer {
            padding: 0.6rem 1rem;
        }

        .footer p {
            font-size: 0.75rem;
        }

        .left-section {
            padding: 1rem;
            min-height: 200px;
        }

        .right-section {
            padding: 1rem;
        }

        .forms-container {
            padding: 1.5rem 1.25rem;
            border-radius: 16px;
        }

        .forms-title {
            font-size: 1.4rem;
        }

        .registration-options {
            gap: 0.95rem;
        }

        .card-features {
            gap: 0.3rem;
        }

        .feature-tag {
            font-size: 0.64rem;
            padding: 0.15rem 0.5rem;
        }
    }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Vipers Academy</h1>
    </div>

    <!-- Main Content -->
    <div class="register-split-container">
        <!-- Left Section: Image (50% width) -->
        <div class="left-section">
            <div class="image-wrapper">
                <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                     alt="Football Training"
                     class="player-image">
                <div class="image-overlay"></div>
            </div>
        </div>

        <!-- Right Section: Content (50% width) -->
        <div class="right-section">
            <div class="forms-container">
                <!-- Forms Header -->
                <div class="forms-header">
                    <h3 class="forms-title">Choose Your Path</h3>
                    <p class="forms-subtitle">Select how you'd like to join Vipers Academy</p>
                </div>

                <!-- Registration Options -->
                <div class="registration-options">
                    <!-- Player Registration -->
                    <a href="/register/player" class="option-card player-card">
                        <div class="card-icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Join as a Player</h4>
                            <p class="card-description">
                                Register as an individual player to access our training programs,
                                coaching sessions, and development opportunities.
                            </p>
                            <div class="card-features">
                                <span class="feature-tag">Individual Training</span>
                                <span class="feature-tag">Skill Development</span>
                                <span class="feature-tag">Competition Ready</span>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- Partner Registration -->
                    <a href="/register/partner" class="option-card partner-card">
                        <div class="card-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">Partner with Us</h4>
                            <p class="card-description">
                                Join as an organization, school, or academy to access our platform,
                                training resources, and partnership opportunities.
                            </p>
                            <div class="card-features">
                                <span class="feature-tag">Platform Access</span>
                                <span class="feature-tag">Bulk Training</span>
                                <span class="feature-tag">Custom Solutions</span>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>

                <!-- Login Link -->
                <div class="login-section">
                    <p class="login-text">
                        Already have an account?
                        <a href="/login" class="login-link">Sign In</a>
                    </p>
                </div>

                <!-- Trust Badges -->
                <div class="trust-badges">
                    <span class="trust-badge">
                        <i class="fas fa-shield-alt"></i> Secure Registration
                    </span>
                    <span class="trust-badge">
                        <i class="fas fa-check-circle"></i> Easy Process
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© Vipers 2025</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects enhancement
        const cards = document.querySelectorAll('.option-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
    </script>
</body>

</html>
