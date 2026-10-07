@extends('layouts.academy')

@section('title', $data['title'] . ' - Vipers Academy')

@section('content')
<div class="success-container">
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
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-sm-12">
                        <div class="success-card">

                            <!-- Success Header -->
                            <div class="success-header">
                                <div class="text-center">
                                    <!-- Success Icon -->
                                    <div class="success-icon-wrapper mb-4">
                                        <div class="success-icon" style="background: linear-gradient(135deg, {{ $data['color'] }}, {{ $data['color'] }}dd);">
                                            <i class="{{ $data['icon'] }}"></i>
                                        </div>
                                        <div class="success-checkmark">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>

                                    <!-- Success Title -->
                                    <h1 class="success-title">{{ $data['title'] }}</h1>

                                    <!-- Success Message -->
                                    <div class="success-message">
                                        <p class="welcome-text">{{ $data['message'] }}</p>
                                        <p class="description-text">{{ $data['description'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Success Body -->
                            <div class="success-body">
                                <!-- Email Notification -->
                                <div class="email-notification">
                                    <div class="email-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="email-content">
                                        <h4>Welcome Email Sent!</h4>
                                        <p>Please check your email inbox (and spam folder) for your welcome message with account details and next steps.</p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    <a href="{{ route('home') }}" class="btn btn-home">
                                        <i class="fas fa-home me-2"></i>
                                        Back to Home
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-login">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Sign In Now
                                    </a>
                                </div>

                                <!-- Additional Info -->
                                <div class="additional-info">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="info-content">
                                            <h5>Account Activation</h5>
                                            <p>Your account will be activated within 24-48 hours after admin review.</p>
                                        </div>
                                    </div>

                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                        <div class="info-content">
                                            <h5>Need Help?</h5>
                                            <p>Contact our support team if you have any questions about your registration.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Success Page Styles */
.success-container {
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

/* Success Card */
.success-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    animation: slideInUp 1s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Success Header */
.success-header {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed, #06b6d4);
    padding: 4rem 2rem 3rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.success-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
}

.success-icon-wrapper {
    position: relative;
    z-index: 2;
}

.success-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    margin: 0 auto;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.success-checkmark {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 40px;
    height: 40px;
    background: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    border: 3px solid white;
    animation: bounceIn 0.6s ease-out 0.3s both;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.success-title {
    position: relative;
    z-index: 2;
    font-size: 2.5rem;
    font-weight: 700;
    margin: 1.5rem 0 1rem 0;
    animation: fadeInUp 1s ease-out 0.5s both;
}

.success-message {
    position: relative;
    z-index: 2;
    animation: fadeInUp 1s ease-out 0.7s both;
}

.welcome-text {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #e9d5ff;
}

.description-text {
    font-size: 1rem;
    color: #c4b5fd;
    line-height: 1.6;
    opacity: 0.9;
}

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

/* Success Body */
.success-body {
    padding: 3rem;
}

/* Email Notification */
.email-notification {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #0ea5e9;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2.5rem;
    animation: slideInLeft 1s ease-out 0.9s both;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.email-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.email-content h4 {
    color: #0c4a6e;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.email-content p {
    color: #0369a1;
    margin: 0;
    line-height: 1.5;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out 1.1s both;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    min-width: 160px;
}

.btn-home {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    border: 2px solid #6b7280;
}

.btn-home:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
}

.btn-login {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    border: 2px solid #8b5cf6;
}

.btn-login:hover {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
}

/* Additional Info */
.additional-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    animation: fadeInUp 1s ease-out 1.3s both;
}

.info-card {
    display: flex;
    align-items: flex-start;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.info-card:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content h5 {
    color: #1e293b;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-content p {
    color: #64748b;
    margin: 0;
    line-height: 1.5;
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .success-container {
        padding: 1rem 0;
    }

    .success-card {
        margin: 1rem;
    }

    .success-header {
        padding: 2.5rem 1.5rem 2rem;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }

    .success-title {
        font-size: 2rem;
    }

    .success-body {
        padding: 2rem 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
        align-items: center;
    }

    .btn {
        width: 100%;
        max-width: 300px;
    }

    .email-notification {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }

    .email-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .additional-info {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .success-title {
        font-size: 1.8rem;
    }

    .welcome-text {
        font-size: 1.1rem;
    }

    .description-text {
        font-size: 0.95rem;
    }
}
</style>
@endsection
