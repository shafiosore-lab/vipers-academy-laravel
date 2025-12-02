@extends('layouts.academy')

@section('title', 'Contact Us - Vipers Academy')

@section('content')
<

<!-- Interactive Contact Cards -->
<section class="contact-cards-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="contact-card-modern phone-card">
                    <div class="card-glow"></div>
                    <div class="card-icon-wrapper">
                        <div class="icon-bg"></div>
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="card-title">Call Us Direct</h3>
                    <p class="card-description">Speak with our admission experts instantly</p>
                    <div class="contact-links">
                        <a href="tel:+254700000000" class="contact-link-modern">
                            <i class="fas fa-mobile-alt"></i>
                            <span>+254 700 000 000</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="tel:+254711000000" class="contact-link-modern">
                            <i class="fas fa-mobile-alt"></i>
                            <span>+254 711 000 000</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="availability-badge">
                        <span class="status-dot"></span>
                        <span>Available Mon-Sat, 8AM-6PM</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="contact-card-modern email-card">
                    <div class="card-glow"></div>
                    <div class="card-icon-wrapper">
                        <div class="icon-bg"></div>
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="card-title">Email Inquiry</h3>
                    <p class="card-description">Get detailed responses within 1 hour</p>
                    <div class="contact-links">
                        <a href="mailto:info@vipersacademy.com" class="contact-link-modern">
                            <i class="fas fa-paper-plane"></i>
                            <span>info@vipersacademy.com</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="mailto:admissions@vipersacademy.com" class="contact-link-modern">
                            <i class="fas fa-paper-plane"></i>
                            <span>admissions@vipers.com</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="availability-badge">
                        <span class="status-dot"></span>
                        <span>Fast Response Guaranteed</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12" data-aos="zoom-in" data-aos-delay="300">
                <div class="contact-card-modern location-card">
                    <div class="card-glow"></div>
                    <div class="card-icon-wrapper">
                        <div class="icon-bg"></div>
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="card-title">Visit Our Facility</h3>
                    <p class="card-description">Tour our world-class training center</p>
                    <div class="location-details">
                        <div class="location-item">
                            <i class="fas fa-map-pin"></i>
                            <div>
                                <strong>Moi International Sports Centre</strong>
                                <p>Kasarani Road, Nairobi, Kenya</p>
                            </div>
                        </div>
                    </div>
                    <a href="https://maps.google.com" target="_blank" class="btn-get-directions">
                        <i class="fas fa-directions"></i>
                        <span>Get Directions</span>
                    </a>
                    <div class="availability-badge">
                        <span class="status-dot"></span>
                        <span>Open for Tours Daily</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Advanced Contact Form & Map -->
<section class="form-map-section" id="contact-form">
    <div class="container">
        <div class="row g-5 align-items-start">
            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-right">
                <div class="form-container-modern">
                    <div class="form-header-modern">
                        <span class="form-badge">Send Message</span>
                        <h2>Tell Us About Your Goals</h2>
                        <p>Fill out the form and our team will get back to you within 24 hours.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert-modern alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div class="alert-content">
                                <strong>Success!</strong>
                                <p>{{ session('success') }}</p>
                            </div>
                            <button type="button" class="alert-close" data-bs-dismiss="alert">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="modern-contact-form">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-field-modern">
                                    <label for="name" class="field-label">
                                        <i class="fas fa-user"></i>
                                        <span>Full Name</span>
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="field-input @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="John Doe"
                                           required>
                                    @error('name')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-field-modern">
                                    <label for="email" class="field-label">
                                        <i class="fas fa-envelope"></i>
                                        <span>Email Address</span>
                                        <span class="required">*</span>
                                    </label>
                                    <input type="email"
                                           class="field-input @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="john@example.com"
                                           required>
                                    @error('email')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-field-modern">
                                    <label for="phone" class="field-label">
                                        <i class="fas fa-phone"></i>
                                        <span>Phone Number</span>
                                    </label>
                                    <input type="tel"
                                           class="field-input @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="+254 XXX XXX XXX">
                                    @error('phone')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-field-modern">
                                    <label for="subject" class="field-label">
                                        <i class="fas fa-tag"></i>
                                        <span>Subject</span>
                                        <span class="required">*</span>
                                    </label>
                                    <select class="field-input @error('subject') is-invalid @enderror"
                                            id="subject"
                                            name="subject"
                                            required>
                                        <option value="">Choose a subject...</option>
                                        <option value="admissions" {{ old('subject') == 'admissions' ? 'selected' : '' }}>Admissions Inquiry</option>
                                        <option value="programs" {{ old('subject') == 'programs' ? 'selected' : '' }}>Program Information</option>
                                        <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                        <option value="careers" {{ old('subject') == 'careers' ? 'selected' : '' }}>Careers</option>
                                        <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    </select>
                                    @error('subject')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-field-modern">
                            <label for="message" class="field-label">
                                <i class="fas fa-comment-dots"></i>
                                <span>Your Message</span>
                                <span class="required">*</span>
                            </label>
                            <textarea class="field-input field-textarea @error('message') is-invalid @enderror"
                                      id="message"
                                      name="message"
                                      rows="5"
                                      placeholder="Tell us how we can help you..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-checkbox-modern">
                            <input type="checkbox"
                                   id="newsletter"
                                   name="newsletter"
                                   value="1"
                                   {{ old('newsletter') ? 'checked' : '' }}>
                            <label for="newsletter">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">Subscribe to our newsletter for updates and training tips</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-submit-modern">
                            <span class="btn-content">
                                <i class="fas fa-paper-plane"></i>
                                <span>Send Message</span>
                            </span>
                            <span class="btn-loader">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>

                        <p class="form-privacy-note">
                            <i class="fas fa-shield-alt"></i>
                            Your information is secure. We respect your privacy and will never share your data.
                        </p>
                    </form>
                </div>
            </div>

            <!-- Map & Quick Info -->
            <div class="col-lg-5" data-aos="fade-left">
                <div class="map-info-container">
                    <!-- Map Placeholder -->
                    <div class="map-modern">
                        <div class="map-overlay-content">
                            <div class="map-icon-large">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h4>Find Us Here</h4>
                            <p>Moi International Sports Centre<br>Kasarani, Nairobi</p>
                            <a href="https://maps.google.com" target="_blank" class="btn-map-directions">
                                <i class="fas fa-location-arrow"></i>
                                <span>Open in Maps</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Info Cards -->
                    <div class="quick-info-cards">
                        <div class="info-card-mini">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <strong>Office Hours</strong>
                                <p>Mon-Fri: 8AM-6PM<br>Saturday: 9AM-4PM</p>
                            </div>
                        </div>

                        <div class="info-card-mini">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="info-content">
                                <strong>24/7 Support</strong>
                                <p>Emergency hotline available for enrolled students</p>
                            </div>
                        </div>

                        <div class="info-card-mini">
                            <div class="info-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <strong>Schedule a Visit</strong>
                                <p>Book a personalized tour of our facilities</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modern FAQ Section -->
<section class="faq-modern-section">
    <div class="container">
        <div class="section-header-center" data-aos="fade-up">
            <span class="section-badge">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>Everything you need to know about Vipers Academy</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-accordion-modern">
                    <div class="faq-item-modern" data-aos="fade-up" data-aos-delay="100">
                        <button class="faq-question active" onclick="toggleFAQ(this)">
                            <span class="faq-icon">
                                <i class="fas fa-user-graduate"></i>
                            </span>
                            <span class="question-text">How do I enroll my child in Vipers Academy?</span>
                            <span class="faq-toggle">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                        <div class="faq-answer" style="display: block;">
                            <p>Enrollment is simple! Visit our registration page, complete the online application form, and our admissions team will contact you within 24 hours to schedule a trial session and discuss the next steps. We'll guide you through every stage of the process.</p>
                        </div>
                    </div>

                    <div class="faq-item-modern" data-aos="fade-up" data-aos-delay="200">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span class="faq-icon">
                                <i class="fas fa-users"></i>
                            </span>
                            <span class="question-text">What age groups do you accept?</span>
                            <span class="faq-toggle">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <p>We accept players from age 6 to 18 years old. Our programs are carefully designed for different skill levels and age groups, ensuring every player receives appropriate training tailored to their developmental stage.</p>
                        </div>
                    </div>

                    <div class="faq-item-modern" data-aos="fade-up" data-aos-delay="300">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span class="faq-icon">
                                <i class="fas fa-dumbbell"></i>
                            </span>
                            <span class="question-text">What facilities do you have?</span>
                            <span class="faq-toggle">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <p>Our facilities at Moi International Sports Centre include professional-grade football pitches, modern fitness centers, fully-equipped changing rooms, recovery areas, and medical facilities. We maintain FIFA-standard conditions for optimal training.</p>
                        </div>
                    </div>

                    <div class="faq-item-modern" data-aos="fade-up" data-aos-delay="400">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span class="faq-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <span class="question-text">Do you offer scholarships or financial aid?</span>
                            <span class="faq-toggle">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, we offer merit-based scholarships and flexible payment plans. Contact our admissions team at admissions@vipersacademy.com to learn about eligibility requirements and application procedures for financial assistance.</p>
                        </div>
                    </div>

                    <div class="faq-item-modern" data-aos="fade-up" data-aos-delay="500">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span class="faq-icon">
                                <i class="fas fa-handshake"></i>
                            </span>
                            <span class="question-text">How can I become a partner or sponsor?</span>
                            <span class="faq-toggle">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                        <div class="faq-answer">
                            <p>We welcome partnerships and sponsorships that align with our mission. Contact our partnership team at partnerships@vipersacademy.com to discuss collaboration opportunities, sponsorship packages, and corporate partnership programs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('styles')
<style>
    :root {
        --primary: #ea1c4d;
        --primary-dark: #c0173f;
        --secondary: #65c16e;
        --dark: #1a1a1a;
        --gray: #6b7280;
        --light: #f8fafc;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        --gradient-primary: linear-gradient(135deg, #ea1c4d, #c0173f);
        --gradient-secondary: linear-gradient(135deg, #65c16e, #4caf50);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--dark);
        line-height: 1.6;
    }

    .smooth-scroll {
        scroll-behavior: smooth;
    }

    /* Ultra-Modern Hero */
    .contact-hero-modern {
        position: relative;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        padding: 140px 0 100px;
        overflow: hidden;
    }

    .hero-bg-overlay {
        position: absolute;
        inset: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
        opacity: 0.4;
    }

    .hero-particles {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(234, 28, 77, 0.6);
        border-radius: 50%;
        animation: float-particle 15s infinite;
    }

    .particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 3s; }
    .particle:nth-child(3) { top: 40%; left: 50%; animation-delay: 6s; }
    .particle:nth-child(4) { top: 80%; left: 30%; animation-delay: 9s; }

    @keyframes float-particle {
        0%, 100% { transform: translate(0, 0); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translate(100px, -100px); opacity: 0; }
    }

    .hero-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 24px;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 24px;
    }

    .badge-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }

    .hero-title-modern {
        font-size: 56px;
        font-weight: 900;
        line-height: 1.1;
        color: white;
        margin-bottom: 24px;
    }

    .title-gradient {
        display: block;
        background: linear-gradient(135deg, #ea1c4d, #f59e0b, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle-modern {
        font-size: 20px;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 40px;
        max-width: 540px;
    }

    .hero-cta-group {
        display: flex;
        gap: 16px;
        margin-bottom: 48px;
        flex-wrap: wrap;
    }

    .btn-hero-primary,
    .btn-hero-secondary {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-hero-primary {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 8px 24px rgba(234, 28, 77, 0.4);
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(234, 28, 77, 0.5);
        color: white;
    }

    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    .trust-badges {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    .trust-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .trust-icon {
        width: 44px;
        height: 44px;
        background: var(--gradient-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .trust-text {
        text-align: left;
    }

    .trust-text strong {
        display: block;
        color: white;
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
    }

    .trust-text span {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    .hero-visual {
        position: relative;
        height: 400px;
    }

    .floating-contact-card {
        position: absolute;
        top: 20%;
        right: 20%;
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        animation: float-card 6s ease-in-out infinite;
    }

    @keyframes float-card {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .contact-mini {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .contact-mini i {
        width: 48px;
        height: 48px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .contact-mini-text .label {
        display: block;
        font-size: 12px;
        color: var(--gray);
        font-weight: 500;
    }

    .contact-mini-text strong {
        font-size: 14px;
        color: var(--dark);
    }

    /* Interactive Contact Cards */
    .contact-cards-section {
        padding: 80px 0;
        background: var(--light);
        margin-top: -50px;
        position: relative;
        z-index: 10;
    }

    .contact-card-modern {
        position: relative;
        background: white;
        border-radius: 20px;
        padding: 40px 32px;
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        transition: all 0.4s ease;
        height: 100%;
        overflow: hidden;
    }

    .contact-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(234, 28, 77, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .contact-card-modern:hover .card-glow {
        opacity: 1;
    }

    .card-icon-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
    }

    .icon-bg {
        position: absolute;
        inset: 0;
        background: var(--gradient-primary);
        border-radius: 20px;
        transform: rotate(45deg);
        transition: transform 0.4s ease;
    }

    .contact-card-modern:hover .icon-bg {
        transform: rotate(225deg);
    }

    .card-icon-wrapper i {
        position: relative;
        z-index: 2;
        color: white;
        font-size: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .card-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 12px;
        text-align: center;
    }

    .card-description {
        text-align: center;
        color: var(--gray);
        margin-bottom: 24px;
        font-size: 15px;
    }

    .contact-links {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .contact-link-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: var(--light);
        border-radius: 10px;
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .contact-link-modern:hover {
        background: white;
        border-color: var(--primary);
        color: var(--primary);
        transform: translateX(4px);
    }

    .contact-link-modern i:first-child {
        color: var(--primary);
        font-size: 18px;
    }

    .contact-link-modern span {
        flex: 1;
    }

    .contact-link-modern i:last-child {
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s ease;
    }

    .contact-link-modern:hover i:last-child {
        opacity: 1;
        transform: translateX(0);
    }

    .location-details {
        margin-bottom: 20px;
    }

    .location-item {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: var(--light);
        border-radius: 12px;
    }

    .location-item i {
        color: var(--primary);
        font-size: 20px;
        margin-top: 4px;
    }

    .location-item strong {
        display: block;
        color: var(--dark);
        font-size: 16px;
        margin-bottom: 4px;
    }

    .location-item p {
        color: var(--gray);
        font-size: 14px;
        margin: 0;
    }

    .btn-get-directions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 14px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        margin-bottom: 16px;
    }

    .btn-get-directions:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(234, 28, 77, 0.4);
        color: white;
    }

    .availability-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        background: #d1fae5;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #065f46;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-dot 2s infinite;
    }

    /* Form & Map Section */
    .form-map-section {
        padding: 100px 0;
        background: white;
    }

    .form-container-modern {
        background: white;
        border-radius: 24px;
        padding: 48px;
        box-shadow: var(--shadow-md);
        border: 1px solid #e5e7eb;
    }

    .form-header-modern {
        margin-bottom: 40px;
    }

    .form-badge {
        display: inline-block;
        padding: 8px 20px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    .form-header-modern h2 {
        font-size: 36px;
        font-weight: 900;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .form-header-modern p {
        font-size: 16px;
        color: var(--gray);
    }

    .alert-modern {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 32px;
        position: relative;
    }

    .alert-success {
        background: #d1fae5;
        border: 2px solid #10b981;
    }

    .alert-modern i:first-child {
        color: #065f46;
        font-size: 24px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        color: #065f46;
        font-size: 16px;
        margin-bottom: 4px;
    }

    .alert-content p {
        color: #047857;
        margin: 0;
    }

    .alert-close {
        background: none;
        border: none;
        color: #065f46;
        cursor: pointer;
        padding: 0;
        font-size: 18px;
    }

    .form-field-modern {
        margin-bottom: 24px;
    }

    .field-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 10px;
        font-size: 14px;
    }

    .field-label i {
        color: var(--primary);
        font-size: 16px;
    }

    .required {
        color: var(--primary);
    }

    .field-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(234, 28, 77, 0.1);
    }

    .field-input.is-invalid {
        border-color: #ef4444;
    }

    .field-textarea {
        resize: vertical;
        min-height: 140px;
    }

    .field-error {
        display: block;
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        font-weight: 500;
    }

    .form-checkbox-modern {
        margin-bottom: 32px;
    }

    .form-checkbox-modern input {
        display: none;
    }

    .form-checkbox-modern label {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
        margin-top: 2px;
    }

    .form-checkbox-modern input:checked + label .checkbox-custom {
        background: var(--gradient-primary);
        border-color: var(--primary);
    }

    .form-checkbox-modern input:checked + label .checkbox-custom::after {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: 700;
    }

    .checkbox-text {
        color: var(--gray);
        font-size: 14px;
        line-height: 1.5;
    }

    .btn-submit-modern {
        width: 100%;
        padding: 18px;
        background: var(--gradient-primary);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(234, 28, 77, 0.4);
    }

    .btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-loader {
        display: none;
    }

    .btn-submit-modern.loading .btn-content {
        display: none;
    }

    .btn-submit-modern.loading .btn-loader {
        display: block;
    }

    .form-privacy-note {
        text-align: center;
        color: var(--gray);
        font-size: 13px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .form-privacy-note i {
        color: var(--primary);
    }

    /* Map & Info */
    .map-info-container {
        position: sticky;
        top: 100px;
    }

    .map-modern {
        position: relative;
        height: 320px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .map-overlay-content {
        text-align: center;
        z-index: 2;
        padding: 24px;
    }

    .map-icon-large {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 32px;
        box-shadow: 0 8px 24px rgba(234, 28, 77, 0.3);
    }

    .map-overlay-content h4 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .map-overlay-content p {
        color: var(--gray);
        margin-bottom: 20px;
    }

    .btn-map-directions {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-map-directions:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(234, 28, 77, 0.4);
        color: white;
    }

    .quick-info-cards {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-card-mini {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .info-card-mini:hover {
        border-color: var(--primary);
        transform: translateX(4px);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-content strong {
        display: block;
        color: var(--dark);
        font-size: 16px;
        margin-bottom: 6px;
    }

    .info-content p {
        color: var(--gray);
        font-size: 14px;
        margin: 0;
        line-height: 1.5;
    }

    /* Modern FAQ */
    .faq-modern-section {
        padding: 100px 0;
        background: var(--light);
    }

    .section-header-center {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-badge {
        display: inline-block;
        padding: 8px 20px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    .section-header-center h2 {
        font-size: 42px;
        font-weight: 900;
        color: var(--dark);
        margin-bottom: 16px;
    }

    .section-header-center p {
        font-size: 18px;
        color: var(--gray);
    }

    .faq-accordion-modern {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .faq-item-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .faq-item-modern:hover {
        border-color: var(--primary);
    }

    .faq-question {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px;
        background: white;
        border: none;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .faq-question.active {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
    }

    .faq-icon {
        width: 44px;
        height: 44px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .question-text {
        flex: 1;
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }

    .faq-toggle {
        width: 32px;
        height: 32px;
        background: var(--light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .faq-question.active .faq-toggle {
        background: var(--primary);
        color: white;
        transform: rotate(45deg);
    }

    .faq-answer {
        display: none;
        padding: 0 24px 24px 84px;
        color: var(--gray);
        line-height: 1.7;
        font-size: 15px;
    }

    /* Social Connect */
    .social-connect-section {
        padding: 100px 0;
        background: var(--dark);
    }

    .social-connect-card {
        background: linear-gradient(135deg, #1e293b, #334155);
        border-radius: 24px;
        padding: 60px 48px;
        position: relative;
        overflow: hidden;
    }

    .social-connect-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(234, 28, 77, 0.1) 0%, transparent 70%);
    }

    .social-content-left h2 {
        font-size: 36px;
        font-weight: 900;
        color: white;
        margin-bottom: 16px;
    }

    .social-content-left p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 32px;
        line-height: 1.7;
    }

    .social-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-item strong {
        display: block;
        font-size: 32px;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .stat-item span {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
    }

    .social-links-modern {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .social-link-btn {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 24px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: white;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .social-link-btn i:first-child {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 18px;
    }

    .social-link-btn span {
        flex: 1;
    }

    .social-link-btn i:last-child {
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s ease;
    }

    .social-link-btn:hover {
        transform: translateX(8px);
        border-color: currentColor;
    }

    .social-link-btn:hover i:last-child {
        opacity: 1;
        transform: translateX(0);
    }

    .facebook:hover { border-color: #1877f2; color: #1877f2; }
    .facebook i:first-child { background: #1877f2; }

    .twitter:hover { border-color: #1da1f2; color: #1da1f2; }
    .twitter i:first-child { background: #1da1f2; }

    .instagram:hover { border-color: #e4405f; color: #e4405f; }
    .instagram i:first-child { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }

    .youtube:hover { border-color: #ff0000; color: #ff0000; }
    .youtube i:first-child { background: #ff0000; }

    /* Responsive */
    @media (max-width: 992px) {
        .hero-title-modern {
            font-size: 42px;
        }

        .form-container-modern,
        .social-connect-card {
            padding: 40px 32px;
        }

        .social-stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 768px) {
        .contact-hero-modern {
            padding: 100px 0 60px;
        }

        .hero-title-modern {
            font-size: 32px;
        }

        .hero-subtitle-modern {
            font-size: 16px;
        }

        .hero-visual {
            display: none;
        }

        .trust-badges {
            flex-direction: column;
        }

        .form-container-modern {
            padding: 32px 24px;
        }

        .form-header-modern h2,
        .social-content-left h2 {
            font-size: 28px;
        }

        .map-info-container {
            position: static;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleFAQ(button) {
        const item = button.parentElement;
        const answer = item.querySelector('.faq-answer');
        const allItems = document.querySelectorAll('.faq-item-modern');
        const allAnswers = document.querySelectorAll('.faq-answer');
        const allButtons = document.querySelectorAll('.faq-question');

        // Close all other FAQs
        allButtons.forEach(btn => {
            if (btn !== button) {
                btn.classList.remove('active');
            }
        });

        allAnswers.forEach(ans => {
            if (ans !== answer) {
                ans.style.display = 'none';
            }
        });

        // Toggle current FAQ
        button.classList.toggle('active');
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
        } else {
            answer.style.display = 'block';
        }
    }

    // Smooth scroll
    document.querySelectorAll('.smooth-scroll').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Form submission loading state
    document.querySelector('.modern-contact-form')?.addEventListener('submit', function() {
        const btn = this.querySelector('.btn-submit-modern');
        btn.classList.add('loading');
        btn.disabled = true;
    });
</script>
@endpush

@endsection
