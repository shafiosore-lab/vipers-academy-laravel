@extends('layouts.academy')

@section('title', 'Contact Us - Vipers Academy')

@section('content')
<div class="contact-page">


    <!-- Main Contact Section -->
    <section class="contact-main">
        <div class="container">
            <div class="row g-4">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <h2>Send Us A Message</h2>

                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                            @csrf

                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    placeholder="+254 XXX XXX XXX">
                                @error('phone')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <select id="subject" name="subject"
                                    class="form-control @error('subject') is-invalid @enderror" required>
                                    <option value="">Select a subject...</option>
                                    <option value="admissions" {{ old('subject') == 'admissions' ? 'selected' : '' }}>
                                        Admissions</option>
                                    <option value="programs" {{ old('subject') == 'programs' ? 'selected' : '' }}>
                                        Programs</option>
                                    <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>
                                        Partnership</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General
                                        Inquiry</option>
                                </select>
                                @error('subject')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message"
                                    class="form-control @error('message') is-invalid @enderror" rows="5"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-5">
                    <div class="contact-info-wrapper">
                        <h2>Contact Information</h2>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-text">
                                <h4>Location</h4>
                                <p>Mumias JuaKali <br>Mumias, Kakamega County, Kenya</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <h4>Phone</h4>
                                <p><a href="tel:+254716305905">+254 716 305 905</a></p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <h4>Email</h4>
                                <p><a href="mailto:info@vipersacademy.com">info@vipersacademy.com</a></p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-text">
                                <h4>Office Hours</h4>
                                <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 2:00 PM</p>
                            </div>
                        </div>

                        <div class="social-links">
                            <h4>Follow Us</h4>
                            <div class="social-icons">
                                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                                <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="faq-header">
                <h2>Frequently Asked Questions</h2>
                <p>Quick answers to common questions</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-list">
                        <div class="faq-item">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I enroll my child?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Visit our registration page, complete the online form, and our team will contact you
                                    within 24 hours to schedule a trial session at our Mumias facility.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>What age groups do you accept?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>We accept players from age 5 to 14 years old. Our programs are designed for different
                                    skill levels and age groups.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Do you train daily?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Yes, we offer training on a regular schedule, but not every program trains every
                                    single day. Our typical structure includes 4–6 training sessions per week, with rest
                                    and recovery days built into the program to prevent injuries and ensure peak
                                    performance.

                                    Players also have access to:

                                    Optional extra sessions

                                    Strength & conditioning days

                                    Video analysis sessions

                                    Weekend matches / friendliesitions.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Do you offer scholarships?</span>
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Yes. We link players with our partnered schools, colleges, and universities for
                                    sports scholarships. Through these partnerships, talented athletes get opportunities
                                    to further their education while advancing their football careers.

                                    For more details, contact our admissions team at admissions@vipersacademy.com
                                    .
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
/* General Styles */
.contact-page {
    background: #f8f9fa;
}

/* Hero Section */
.contact-hero {
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    padding: 80px 0 60px;
    color: white;
    text-align: center;
}

.hero-content h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 15px;
}

.hero-content p {
    font-size: 18px;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

/* Main Contact Section */
.contact-main {
    padding: 60px 0;
}

.contact-form-wrapper,
.contact-info-wrapper {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.contact-form-wrapper h2,
.contact-info-wrapper h2 {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #1a1a1a;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #ea1c4d;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.error-text {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}

.btn-submit {
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(234, 28, 77, 0.3);
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Contact Info */
.info-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.info-text h4 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #1a1a1a;
}

.info-text p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

.info-text a {
    color: #ea1c4d;
    text-decoration: none;
    transition: color 0.3s;
}

.info-text a:hover {
    color: #c0173f;
}

/* Social Links */
.social-links {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
}

.social-links h4 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #1a1a1a;
}

.social-icons {
    display: flex;
    gap: 10px;
}

.social-icons a {
    width: 40px;
    height: 40px;
    background: #f0f0f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    text-decoration: none;
    transition: all 0.3s;
}

.social-icons a:hover {
    background: #ea1c4d;
    color: white;
    transform: translateY(-3px);
}

/* FAQ Section */
.faq-section {
    padding: 60px 0;
    background: white;
}

.faq-header {
    text-align: center;
    margin-bottom: 50px;
}

.faq-header h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.faq-header p {
    font-size: 18px;
    color: #666;
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.faq-item {
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.faq-item:hover {
    border-color: #ea1c4d;
}

.faq-question {
    width: 100%;
    background: none;
    border: none;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    text-align: left;
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
}

.faq-question i {
    color: #ea1c4d;
    transition: transform 0.3s;
}

.faq-question.active i {
    transform: rotate(45deg);
}

.faq-answer {
    display: none;
    padding: 0 25px 20px 25px;
    color: #666;
    line-height: 1.7;
}

.faq-answer p {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-hero {
        padding: 60px 0 40px;
    }

    .hero-content h1 {
        font-size: 32px;
    }

    .hero-content p {
        font-size: 16px;
    }

    .contact-form-wrapper,
    .contact-info-wrapper {
        padding: 30px 20px;
    }

    .faq-header h2 {
        font-size: 28px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleFAQ(button) {
    const item = button.parentElement;
    const answer = item.querySelector('.faq-answer');
    const allButtons = document.querySelectorAll('.faq-question');
    const allAnswers = document.querySelectorAll('.faq-answer');

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
</script>
@endpush
