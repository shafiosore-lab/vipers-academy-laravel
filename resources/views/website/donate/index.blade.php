@extends('layouts.academy')

@section('title', 'Support Mumias Vipers Academy - Goodwill Ambassadors | M-PESA Paybill 400200')

@section('meta_description', 'Support Mumias Vipers Football Academy\'s community-based youth development programs via M-PESA Paybill 400200, Account 1122028. Your contribution empowers youth through football, STEM & education in Kenya.')

@section('content')

<!-- Impact Section -->
<section class="impact-section py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Your Impact</h2>
            <p class="text-muted">See how your support transforms lives</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-3">Education Access</h3>
                        <p class="text-muted">
                            Scholarships provide access to quality secondary education, opening doors that were previously closed due to financial constraints.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-futbol fa-3x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-3">Football Development</h3>
                        <p class="text-muted">
                            Your support ensures talented players can focus on their football development while receiving academic support.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="impact-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-users fa-3x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-3">Community Impact</h3>
                        <p class="text-muted">
                            Every scholarship creates a ripple effect, inspiring other youth and strengthening community bonds.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Options -->
<section class="donation-section py-5 bg-light" id="donateSection">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-3">Support a Scholarship</h2>
            <p class="text-muted">Choose how you'd like to contribute to our mission</p>
        </div>

        <div class="row g-4">
            <!-- One-Time Donation -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-hand-holding-heart fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3">One-Time Donation</h4>
                        <p class="text-muted mb-4">
                            Make a single contribution to support scholarships for talented youth players.
                        </p>
                        <div class="amount-options mb-4">
                            <div class="btn-group-vertical w-100" role="group">
                                <input type="radio" class="btn-check" name="amount" id="amount1" value="2500" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount1">KES 2,500 (School Fees for 1 Month)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount2" value="12500" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount2">KES 12,500 (School Fees for 1 Term)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount3" value="25000" autocomplete="off">
                                <label class="btn btn-outline-success mb-2" for="amount3">KES 25,000 (Full Year Scholarship)</label>

                                <input type="radio" class="btn-check" name="amount" id="amount4" value="other" autocomplete="off">
                                <label class="btn btn-outline-success" for="amount4">Other Amount</label>
                            </div>
                        </div>
                        <button class="btn btn-success btn-lg w-100" onclick="selectDonation('one-time')">
                            <i class="fas fa-heart me-2"></i>Donate Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Monthly Sponsorship -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-calendar-heart fa-3x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Monthly Sponsorship</h4>
                        <p class="text-muted mb-4">
                            Become a monthly sponsor and provide ongoing support for scholarships.
                        </p>
                        <div class="amount-options mb-4">
                            <div class="btn-group-vertical w-100" role="group">
                                <input type="radio" class="btn-check" name="monthly" id="monthly1" value="1000" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly1">KES 1,000/month (School Supplies)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly2" value="2500" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly2">KES 2,500/month (Partial Scholarship)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly3" value="5000" autocomplete="off">
                                <label class="btn btn-outline-warning mb-2" for="monthly3">KES 5,000/month (Full Scholarship)</label>

                                <input type="radio" class="btn-check" name="monthly" id="monthly4" value="custom" autocomplete="off">
                                <label class="btn btn-outline-warning" for="monthly4">Custom Amount</label>
                            </div>
                        </div>
                        <button class="btn btn-warning btn-lg w-100" onclick="selectDonation('monthly')">
                            <i class="fas fa-calendar-plus me-2"></i>Start Sponsorship
                        </button>
                    </div>
                </div>
            </div>

            <!-- Corporate Partnership -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="donation-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="donation-icon mb-3">
                            <i class="fas fa-handshake fa-3x text-info"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Corporate Partnership</h4>
                        <p class="text-muted mb-4">
                            Partner with us to create lasting impact through corporate social responsibility.
                        </p>
                        <div class="partnership-options mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Scholarship Programs</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Equipment Donations</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Facility Support</li>
                                <li class="mb-2"><i class="fas fa-check text-info me-2"></i>Community Events</li>
                            </ul>
                        </div>
                        <a href="{{ route('contact') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-building me-2"></i>Partner With Us
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Options Bar -->
        <div class="payment-options-bar mt-4" data-aos="fade-up">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3"><i class="fas fa-mobile-alt text-success me-2"></i>Quick Donate via M-PESA</h5>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="quick-paybill">
                                <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                    <div class="text-center">
                                        <small class="text-muted d-block">Paybill</small>
                                        <strong class="text-success fs-4">400200</strong>
                                    </div>
                                    <div class="text-center">
                                        <small class="text-muted d-block">Account</small>
                                        <strong class="text-success fs-4">1122028</strong>
                                    </div>
                                    <button class="btn btn-success px-4" onclick="copyPaybill()">
                                        <i class="fas fa-copy me-2"></i>Copy
                                    </button>
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    Go to M-PESA → Lipa na M-PESA → Paybill → Enter 400200 → Account 1122028 → Amount → Enter PIN → Send
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="contact-info mt-4" data-aos="fade-up">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h5 class="fw-bold mb-3">Questions about donating?</h5>
                            <p class="mb-0 text-muted">
                                Contact us for more information about scholarships, tax deductions, or how your contribution makes a difference.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Get in Touch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function scrollToDonate() {
    document.getElementById('donateSection').scrollIntoView({
        behavior: 'smooth'
    });
}

function selectDonation(type) {
    // Placeholder for donation processing
    // In a real implementation, this would integrate with payment gateways like M-Pesa, Stripe, etc.
    alert(`Thank you for choosing to support our scholarships! The ${type} donation feature is coming soon. Please use M-PESA Paybill 400200 / Account 1122028 for direct contributions.`);
}

function copyPaybill() {
    const details = 'Paybill: 400200\nAccount: 1122028\n\nMumias Vipers Football Academy\nGoodwill Ambassadors';
    navigator.clipboard.writeText(details).then(() => {
        const btns = document.querySelectorAll('.copy-btn, .quick-paybill .btn-success');
        btns.forEach(btn => {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
            btn.classList.add('btn-success');
            btn.style.borderColor = '#198754';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.style.borderColor = '';
            }, 2500);
        });
    }).catch(() => {
        alert('Paybill: 400200 | Account: 1122028');
    });
}

// Auto-select first donation option
document.addEventListener('DOMContentLoaded', function() {
    const firstAmount = document.getElementById('amount1');
    if (firstAmount) {
        firstAmount.checked = true;
    }
});
</script>
@endpush

<style>
/* ========================================
   DONATE HERO SECTION - Goodwill Ambassadors
   ======================================== */

.donate-hero-section {
    position: relative;
    min-height: 85vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #0a0f2c 0%, #1a1040 30%, #0d1b3e 60%, #0a0f2c 100%);
    overflow: hidden;
    padding-top: 100px;
    padding-bottom: 60px;
}

.donate-hero-bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(ellipse at 20% 50%, rgba(234, 28, 77, 0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(251, 199, 97, 0.06) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 80%, rgba(101, 193, 110, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.donate-hero-content {
    position: relative;
    z-index: 2;
}

.donate-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(251, 199, 97, 0.3);
    border-radius: 50px;
    padding: 8px 20px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #fbc761;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 24px;
}

.donate-hero-title {
    font-size: clamp(2rem, 5vw, 3.25rem);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin-bottom: 16px;
}

.donate-hero-title .text-highlight {
    background: linear-gradient(135deg, #fbc761, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.donate-hero-subtitle {
    font-size: clamp(1rem, 1.8vw, 1.15rem);
    color: rgba(255, 255, 255, 0.75);
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.7;
}

/* Payment Cards */
.payment-card {
    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}

.payment-card:hover {
    transform: translateY(-6px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.payment-card-header {
    padding: 24px 24px 16px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

.payment-card-header h5 {
    color: #ffffff;
    font-size: 1.1rem;
}

.payment-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    margin-bottom: 12px;
}

.mpesa-icon {
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
    box-shadow: 0 8px 24px rgba(76, 175, 80, 0.3);
}

.globe-icon {
    background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
}

.payment-card-body {
    padding: 20px 24px 24px;
}

/* Paybill Details */
.paybill-details {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 14px;
    padding: 16px 20px;
}

.paybill-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
}

.paybill-label {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.paybill-value {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 800;
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
    background: linear-gradient(135deg, #4CAF50, #81C784);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.paybill-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    margin: 6px 0;
}

.copy-btn {
    border-radius: 50px;
    font-size: 0.8rem;
    padding: 8px 20px;
    border-color: rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    width: 100%;
}

.copy-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.4);
    color: #fff;
}

/* International Options */
.international-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.international-option {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.international-option:hover {
    background: rgba(0, 0, 0, 0.25);
    color: #fff;
}

.international-option i {
    width: 24px;
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
}

/* Mini Stats */
.support-mini-stats {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 50px;
    padding: 12px 24px;
    margin-top: 8px;
}

.mini-stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.mini-stat-icon {
    font-size: 1.1rem;
    line-height: 1;
}

.mini-stat-text {
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.7);
    white-space: nowrap;
}

.mini-stat-text strong {
    color: #fbc761;
    font-weight: 800;
}

.mini-stat-divider {
    width: 1px;
    height: 20px;
    background: rgba(255, 255, 255, 0.1);
}

/* Scroll Indicator */
.scroll-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    opacity: 0.5;
    animation: float 2.5s ease-in-out infinite;
}

.scroll-text {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 600;
}

.scroll-mouse {
    width: 24px;
    height: 38px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    display: flex;
    justify-content: center;
    padding-top: 8px;
}

.scroll-dot {
    width: 3px;
    height: 8px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 2px;
    animation: scrollBounce 2s ease-in-out infinite;
}

@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); opacity: 0.6; }
    50% { transform: translateY(8px); opacity: 0.2; }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(6px); }
}

/* Quick Paybill Bar */
.quick-paybill {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.quick-paybill .text-success.fs-4 {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

/* Existing Donate Page Styles */
.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-content .badge {
    font-size: 0.9rem;
    font-weight: 600;
}

.impact-card {
    transition: transform 0.3s ease;
}

.impact-card:hover {
    transform: translateY(-5px);
}

.impact-icon {
    transition: transform 0.3s ease;
}

.impact-card:hover .impact-icon {
    transform: scale(1.1);
}

.donation-card {
    transition: all 0.3s ease;
    border-radius: 20px;
}

.donation-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.donation-icon {
    transition: transform 0.3s ease;
}

.donation-card:hover .donation-icon {
    transform: scale(1.1);
}

.donation-card .amount-options .btn-outline-success:hover,
.donation-card .amount-options .btn-outline-warning:hover {
    transform: scale(1.02);
}

.story-card {
    border-radius: 16px;
    transition: transform 0.3s ease;
}

.story-card:hover {
    transform: translateY(-3px);
}

.story-header img {
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-card {
    padding: 2rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    backdrop-filter: blur(10px);
}

.stat-icon {
    opacity: 0.8;
}

/* Responsive */
@media (min-width: 992px) {
    .donate-hero-section { min-height: 80vh; padding-top: 120px; }
    .donate-hero-title { font-size: 3rem; }
}

@media (min-width: 576px) and (max-width: 991px) {
    .donate-hero-section { min-height: 60vh; padding-top: 100px; }
    .support-mini-stats { flex-wrap: wrap; justify-content: center; border-radius: 16px; padding: 12px 16px; gap: 8px; }
    .mini-stat-divider { display: none; }
}

@media (max-width: 575px) {
    .donate-hero-section { min-height: auto; padding-top: 90px; padding-bottom: 40px; }
    .donate-hero-title { font-size: 1.65rem; }
    .donate-hero-subtitle { font-size: 0.9rem; }
    .donate-hero-badge { font-size: 0.65rem; padding: 6px 14px; }
    .payment-card { border-radius: 16px; }
    .payment-card-header { padding: 18px 18px 12px; }
    .payment-card-body { padding: 16px 18px 20px; }
    .paybill-value { font-size: 1.1rem; }
    .support-mini-stats { flex-direction: column; gap: 8px; border-radius: 16px; padding: 14px 20px; }
    .mini-stat-divider { display: none; }
    .scroll-indicator { display: none; }
}

@media (max-width: 768px) {
    .donate-hero-section {
        min-height: 60vh;
    }
    .donation-card {
        margin-bottom: 2rem;
    }
    .story-card {
        margin-bottom: 2rem;
    }
}
</style>
