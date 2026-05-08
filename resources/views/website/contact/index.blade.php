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
                                <h4>
                                    @php
                                        $locationTitle = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'location_title') : null;
                                    @endphp
                                    {{ $locationTitle?->value ?: 'Location' }}
                                </h4>
                                <p>
                                    @php
                                        $locationAddress = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'location_address') : null;
                                    @endphp
                                    {!! $locationAddress?->value ?: 'Mumias JuaKali <br>Mumias, Kakamega County, Kenya' !!}
                                </p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <h4>
                                    @php
                                        $phoneTitle = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'phone_title') : null;
                                    @endphp
                                    {{ $phoneTitle?->value ?: 'Phone' }}
                                </h4>
                                <p>
                                    @php
                                        $phoneNumber = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'phone_number') : null;
                                    @endphp
                                    <a href="tel:{{ $phoneNumber?->value ?: '+254716305905' }}">{{ $phoneNumber?->value ?: '+254 716 305 905' }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <h4>
                                    @php
                                        $emailTitle = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'email_title') : null;
                                    @endphp
                                    {{ $emailTitle?->value ?: 'Email' }}
                                </h4>
                                <p>
                                    @php
                                        $emailAddress = isset($pageContent['contact_info']) ? $pageContent['contact_info']->firstWhere('key', 'email_address') : null;
                                    @endphp
                                    <a href="mailto:{{ $emailAddress?->value ?: 'info@vipersacademy.com' }}">{{ $emailAddress?->value ?: 'info@vipersacademy.com' }}</a>
                                </p>
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
                                     within 24 hours to schedule a trial session at our Mumias facility. The academy uses football as a platform for youth empowerment in digital skills, education, and life skills development.</p>
                             </div>
                         </div>

                        <div class="faq-item">
                             <button class="faq-question" onclick="toggleFAQ(this)">
                                 <span>What age groups do you accept?</span>
                                 <i class="fas fa-plus"></i>
                             </button>
                             <div class="faq-answer">
                                 <p>We accept players from age 5 to 18 years old for our community-based youth development programs. Our programs are designed for different
                                     skill levels and age groups, with a focus on holistic development through football, academics, and digital skills.</p>
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
                                     Over 20+ scholarship pathways have been created through our community-driven programs.

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
/* Contact Page - Using unified CSS variables from layout */
.contact-page {
    background: var(--gray-100);
}

/* Contact Hero (if needed) */
.contact-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 5rem 0 3.75rem;
    color: var(--white);
    text-align: center;
}

/* Main Contact Section */
.contact-main {
    padding: 3.75rem 0;
}

.contact-form-wrapper,
.contact-info-wrapper {
    background: var(--white);
    padding: 2.5rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    height: 100%;
}

.contact-form-wrapper h2,
.contact-info-wrapper h2 {
    font-size: 1.625rem;
    font-weight: 700;
    margin-bottom: 1.875rem;
    color: var(--gray-900);
}

/* Form Styles */
.contact-form .form-group {
    margin-bottom: 1.25rem;
}

.contact-form .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gray-700);
    font-size: 0.875rem;
}

.contact-form .form-control {
    width: 100%;
    padding: 0.75rem 0.9375rem;
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    transition: border-color var(--transition-fast);
    background: var(--white);
}

.contact-form .form-control:focus {
    outline: none;
    border-color: var(--primary);
}

.contact-form .form-control.is-invalid {
    border-color: var(--danger);
}

.contact-form .error-text {
    color: var(--danger);
    font-size: 0.8125rem;
    margin-top: 0.3125rem;
    display: block;
}

/* Submit Button */
.contact-form .btn-submit {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    padding: 0.9375rem 2.5rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
}

.contact-form .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(234, 28, 77, 0.3);
}

/* Alert */
.contact-form .alert {
    padding: 0.9375rem 1.25rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5625rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.contact-form .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Contact Info Items */
.info-item {
    display: flex;
    gap: 1.25rem;
    margin-bottom: 1.875rem;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.info-text h4 {
    font-size: 1.125rem;
    font-weight: 700;
    margin-bottom: 0.3125rem;
    color: var(--gray-900);
}

.info-text p {
    color: var(--gray-600);
    margin: 0;
    line-height: 1.6;
}

.info-text a {
    color: var(--primary);
    text-decoration: none;
    transition: color var(--transition-fast);
}

.info-text a:hover {
    color: var(--primary-dark);
}

/* Social Links */
.social-links {
    margin-top: 2.5rem;
    padding-top: 1.875rem;
    border-top: 2px solid var(--gray-200);
}

.social-links h4 {
    font-size: 1.125rem;
    font-weight: 700;
    margin-bottom: 0.9375rem;
    color: var(--gray-900);
}

.social-icons {
    display: flex;
    gap: 0.625rem;
}

.social-icons a {
    width: 40px;
    height: 40px;
    background: var(--gray-200);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    text-decoration: none;
    transition: all var(--transition-fast);
}

.social-icons a:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateY(-3px);
}

/* FAQ Section */
.faq-section {
    padding: 3.75rem 0;
    background: var(--white);
}

.faq-header {
    text-align: center;
    margin-bottom: 3.125rem;
}

.faq-header h2 {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 0.625rem;
    color: var(--gray-900);
}

.faq-header p {
    font-size: 1.125rem;
    color: var(--gray-600);
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 0.9375rem;
}

.faq-item {
    background: var(--gray-100);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 2px solid transparent;
    transition: border-color var(--transition-fast);
}

.faq-item:hover {
    border-color: var(--primary);
}

.faq-question {
    width: 100%;
    background: none;
    border: none;
    padding: 1.25rem 1.5625rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    text-align: left;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
}

.faq-question i {
    color: var(--primary);
    transition: transform var(--transition-fast);
}

.faq-question.active i {
    transform: rotate(45deg);
}

.faq-answer {
    display: none;
    padding: 0 1.5625rem 1.25rem 1.5625rem;
    color: var(--gray-600);
    line-height: 1.7;
}

.faq-answer p {
    margin: 0;
}

/* Enhanced Mobile Responsiveness */
@media (max-width: 1024px) {
    .contact-main {
        padding: 4rem 0;
    }

    .contact-form-wrapper,
    .contact-info-wrapper {
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 3rem 0 2rem;
    }

    .contact-hero h1 {
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .contact-hero p {
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .contact-form-wrapper,
    .contact-info-wrapper {
        padding: 1.5rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .contact-form .form-group {
        margin-bottom: 1.25rem;
    }

    .contact-form label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .contact-form .form-control {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .contact-form .btn {
        width: 100%;
        padding: 0.875rem;
        font-size: 1rem;
        border-radius: 8px;
        min-height: 48px;
    }

    .contact-info-item {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
    }

    .contact-info-item i {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .contact-info-item h4 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .faq-header h2 {
        font-size: 1.5rem;
    }

    .faq-item {
        margin-bottom: 0.75rem;
    }

    .faq-question {
        padding: 1rem;
        font-size: 0.95rem;
        border-radius: 8px;
    }

    .faq-answer {
        padding: 0 1rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .contact-hero {
        padding: 2rem 0 1.5rem;
    }

    .contact-hero h1 {
        font-size: 1.5rem;
    }

    .contact-hero p {
        font-size: 0.9rem;
    }

    .contact-main {
        padding: 2rem 0;
    }

    .contact-form-wrapper,
    .contact-info-wrapper {
        padding: 1.25rem 0.875rem;
        margin-bottom: 1.25rem;
    }

    .contact-form .form-group {
        margin-bottom: 1rem;
    }

    .contact-info-item {
        padding: 0.875rem;
        margin-bottom: 0.875rem;
    }

    .faq-section {
        padding: 2rem 0;
    }

    .faq-header {
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .contact-hero {
        padding: 1.5rem 0 1rem;
    }

    .contact-hero h1 {
        font-size: 1.375rem;
    }

    .contact-main {
        padding: 1.5rem 0;
    }

    .contact-form-wrapper,
    .contact-info-wrapper {
        padding: 1rem 0.75rem;
        margin-bottom: 1rem;
    }

    .contact-form .btn {
        min-height: 44px;
    }

    .faq-section {
        padding: 1.5rem 0;
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
