@extends('layouts.academy')

@section('title', 'Vipers Academy Merchandise - Official Football Gear')

@section('meta_description', 'Shop official Vipers Academy merchandise including football boots, bibs, balls, jerseys and custom jersey branding services. Order via WhatsApp.')

@section('content')


<!-- Products Section -->
<section class="products-section py-3 bg-light" id="products">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up" style="margin-top: 2rem;">
            <h2 class="display-6 fw-bold mb-2">Football Equipment Store</h2>
            <p class="text-muted small">Professional football gear and equipment for players and teams</p>

            <!-- Admin Image Section -->
            @if(auth()->check() && auth()->user()->hasRole(['admin', 'super-admin', 'org-admin']))
                <div class="admin-image-section mt-3 p-3 bg-white rounded shadow-sm border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning text-dark">Admin Section</span>
                        <small class="text-muted">Upload hero image for this section</small>
                    </div>

                    @if(isset($merchandiseHeroImage) && $merchandiseHeroImage)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $merchandiseHeroImage) }}"
                                 alt="Merchandise Hero Image"
                                 class="img-fluid rounded"
                                 style="max-height: 200px; object-fit: cover;">
                            <div class="mt-2">
                                <form action="{{ route('admin.page-content.update', ['page' => 'merchandise', 'section' => 'hero-image']) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Remove Image
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="border rounded p-3 text-center text-muted">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <p class="mb-0 small">No hero image uploaded yet</p>
                        </div>
                    @endif

                    <form action="{{ route('admin.page-content.update', ['page' => 'merchandise', 'section' => 'hero-image']) }}"
                          method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <input type="file" name="image" class="form-control form-control-sm" accept="image/*" required>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-upload me-1"></i>Upload Image
                            </button>
                        </div>
                        <small class="text-muted">Recommended size: 1200x400px, Max: 2MB</small>
                    </form>
                </div>
            @endif
        </div>

        <div class="row g-4">
            <!-- Football Boots -->
            <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="100">
                <div class="product-item card border-0 shadow-sm h-100">
                    <div class="product-image-container">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                             alt="Football Boots" class="product-image">
                        <div class="product-overlay">
                            <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20order%20football%20boots"
                               target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>Contact Seller
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="product-title fw-bold mb-2">Professional Football Boots</h6>
                        <p class="product-description text-muted small mb-2">
                            High-quality soccer boots designed for optimal performance on various playing surfaces.
                        </p>
                        <div class="product-features mb-3">
                            <span class="badge bg-success text-white me-1">Premium Quality</span>
                            <span class="badge bg-primary text-white me-1">Multiple Sizes</span>
                            <span class="badge bg-info text-white">Adult & Youth</span>
                        </div>
                        <div class="product-meta small">
                            <i class="fas fa-shipping-fast me-1 text-success"></i>
                            <span class="text-secondary">Fast Delivery Available</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training Bibs -->
            <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200">
                <div class="product-item card border-0 shadow-sm h-100">
                    <div class="product-image-container">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                             alt="Training Bibs" class="product-image">
                        <div class="product-overlay">
                            <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20order%20training%20bibs"
                               target="_blank" class="btn btn-primary btn-sm text-white">
                                <i class="fab fa-whatsapp me-1"></i>Contact Seller
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="product-title fw-bold mb-2">Training Bibs & Vests</h6>
                        <p class="product-description text-muted small mb-2">
                            Essential training equipment for team identification and organization.
                        </p>
                        <div class="product-features mb-3">
                            <span class="badge bg-primary text-white me-1">Various Colors</span>
                            <span class="badge bg-warning text-dark me-1">Numbered</span>
                            <span class="badge bg-success text-white">Team Sets</span>
                        </div>
                        <div class="product-meta small">
                            <i class="fas fa-users me-1 text-primary"></i>
                            <span class="text-secondary">Perfect for Team Training</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Football Balls -->
            <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="300">
                <div class="product-item card border-0 shadow-sm h-100">
                    <div class="product-image-container">
                        <img src="https://images.unsplash.com/photo-1519861531473-9200262188bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                             alt="Football Balls" class="product-image">
                        <div class="product-overlay">
                            <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20order%20football%20balls"
                               target="_blank" class="btn btn-warning btn-sm text-dark">
                                <i class="fab fa-whatsapp me-1"></i>Contact Seller
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="product-title fw-bold mb-2">Match & Training Balls</h6>
                        <p class="product-description text-muted small mb-2">
                            FIFA approved footballs suitable for matches and training sessions.
                        </p>
                        <div class="product-features mb-3">
                            <span class="badge bg-warning text-dark me-1">FIFA Approved</span>
                            <span class="badge bg-success text-white me-1">Match Quality</span>
                            <span class="badge bg-info text-white">Training Balls</span>
                        </div>
                        <div class="product-meta small">
                            <i class="fas fa-trophy me-1 text-warning"></i>
                            <span class="text-secondary">Official Match Standards</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Football Jerseys -->
            <div class="col-lg-3 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="400">
                <div class="product-item card border-0 shadow-sm h-100">
                    <div class="product-image-container">
                        <img src="https://images.unsplash.com/photo-1522778119026-d647f0596c20?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                             alt="Football Jerseys" class="product-image">
                        <div class="product-overlay">
                            <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20order%20football%20jerseys"
                               target="_blank" class="btn btn-info btn-sm text-white">
                                <i class="fab fa-whatsapp me-1"></i>Contact Seller
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="product-title fw-bold mb-2">Team Football Jerseys</h6>
                        <p class="product-description text-muted small mb-2">
                            Custom designed jerseys with player names and team branding.
                        </p>
                        <div class="product-features mb-3">
                            <span class="badge bg-info text-white me-1">Custom Design</span>
                            <span class="badge bg-primary text-white me-1">Player Names</span>
                            <span class="badge bg-success text-white">Team Branding</span>
                        </div>
                        <div class="product-meta small">
                            <i class="fas fa-palette me-1 text-info"></i>
                            <span class="text-secondary">Custom Designs Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-4">
            <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20see%20more%20products%20and%20pricing"
               target="_blank" class="btn btn-outline-primary btn-lg">
                <i class="fab fa-whatsapp me-2"></i>View More Products & Get Pricing
            </a>
        </div>
    </div>
</section>

<!-- Scholarship Impact Section -->
<section class="scholarship-section py-4" id="scholarship" style="background-color: #f0f0f0;">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-2" style="color: #000000 !important;">Scholarship Impact</h2>
            <p class="small mb-0" style="color: #000000 !important;">Real results from your support</p>
        </div>

        <!-- Single row, all 4 cards side by side -->
        <div class="scholarship-row">
            <!-- Card 1 -->
            <div class="scholarship-col" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">20+</div>
                    <div class="stat-label">Active Scholarships</div>
                    <div class="stat-sublabel">Students in High School</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="scholarship-col" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">7</div>
                    <div class="stat-label">Years of Success</div>
                    <div class="stat-sublabel">Since 2017</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="scholarship-col" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Lives Transformed</div>
                    <div class="stat-sublabel">Students &amp; Families</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="scholarship-col" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-card">
                    <div class="stat-number">85%</div>
                    <div class="stat-label">Academic Success Rate</div>
                    <div class="stat-sublabel">Scholarship Recipients</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-3 bg-light">
    <div class="container">
        <div class="text-center mb-3" data-aos="fade-up">
            <h2 class="display-6 fw-bold mb-2">Jersey Branding Services</h2>
            <p class="text-muted small">Professional custom branding for teams and organizations</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="service-card card border-0 shadow">
                    <div class="card-body p-4 text-center">
                        <div class="service-icon mb-3">
                            <i class="fas fa-palette fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title mb-3 fw-bold">Custom Jersey Branding</h4>
                        <p class="text-secondary mb-3">
                            Professional jersey customization services for teams, schools, and organizations.
                            We handle logos, sponsors, player names, and numbers with precision and quality.
                        </p>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <div class="service-feature p-2 bg-white rounded small text-start">
                                    <i class="fas fa-users text-primary me-1"></i>
                                    <strong class="text-dark">Team Branding</strong>
                                    <span class="text-secondary"> - Complete team kits with logos and sponsors</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="service-feature p-2 bg-white rounded small text-start">
                                    <i class="fas fa-school text-success me-1"></i>
                                    <strong class="text-dark">School Teams</strong>
                                    <span class="text-secondary"> - Custom designs for educational institutions</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="service-feature p-2 bg-white rounded small text-start">
                                    <i class="fas fa-building text-warning me-1"></i>
                                    <strong class="text-dark">Corporate Teams</strong>
                                    <span class="text-secondary"> - Professional branding for company teams</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="service-feature p-2 bg-white rounded small text-start">
                                    <i class="fas fa-star text-info me-1"></i>
                                    <strong class="text-dark">Special Events</strong>
                                    <span class="text-secondary"> - Custom jerseys for tournaments and events</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 mb-3 small text-start">
                            <strong>Ready to brand your team?</strong>
                            <span> Contact us via WhatsApp for custom quotes.</span>
                        </div>

                        <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20inquire%20about%20custom%20jersey%20branding%20services"
                           target="_blank" class="btn btn-success px-4 py-2 fw-semibold text-white">
                            <i class="fab fa-whatsapp me-1"></i>Get Custom Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section py-2 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-2 mb-lg-0" data-aos="fade-right">
                <h5 class="fw-bold mb-2 text-dark">Need Help with Your Order?</h5>
                <p class="text-secondary small mb-3">
                    Our team assists with product selection, sizing, and custom requirements via WhatsApp.
                </p>
                <div class="contact-info small">
                    <p class="mb-1 text-dark"><i class="fas fa-phone text-success me-2"></i><strong>+254 716 305 905</strong></p>
                    <p class="mb-1 text-dark"><i class="fas fa-envelope text-primary me-2"></i>info@vipersacademy.com</p>
                    <p class="mb-0 text-dark"><i class="fas fa-map-marker-alt text-warning me-2"></i>Mumias, Kenya</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="text-center">
                    <div class="mb-2">
                        <i class="fab fa-whatsapp fa-4x text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Order via WhatsApp</h5>
                    <p class="text-secondary small mb-3">
                        Get instant responses and personalized service.
                    </p>
                    <a href="https://wa.me/254716305905?text=Hi%20Vipers%20Academy,%20I%20would%20like%20to%20inquire%20about%20your%20merchandise"
                       target="_blank" class="btn btn-success px-4 py-2 fw-semibold text-white">
                        <i class="fab fa-whatsapp me-1"></i>Start WhatsApp Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash;
    if (hash === '#products') {
        setTimeout(() => {
            document.getElementById('products').scrollIntoView({
                behavior: 'smooth'
            });
        }, 500);
    }
});
</script>
@endpush

<style>
/* =============================================
   PRODUCT CARDS
   ============================================= */
.product-item {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-item:hover .product-image {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.72);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-item:hover .product-overlay {
    opacity: 1;
}

.product-title {
    font-size: 1rem;
    line-height: 1.3;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.product-description {
    font-size: 0.85rem;
    line-height: 1.4;
    color: #555555;
}

.product-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.product-features .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    font-weight: 500;
}

.product-meta {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* =============================================
   SERVICE CARD
   ============================================= */
.service-card {
    border-radius: 20px;
    overflow: hidden;
    background: #ffffff;
}

.service-icon {
    transition: transform 0.3s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.1);
}

.service-feature {
    transition: transform 0.2s ease;
    border: 1px solid #e9ecef;
}

.service-feature:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* =============================================
   SCHOLARSHIP SECTION
   ============================================= */
.scholarship-section {
    background-color: #f0f0f0;
}

/* All 4 cards in one flex row, scrollable if needed */
.scholarship-row {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 10px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 4px;
}

.scholarship-row::-webkit-scrollbar {
    display: none;
}

.scholarship-col {
    flex: 1 1 0;
    min-width: 0;
}

.stat-card {
    background-color: #ffffff;
    border: 1px solid #bbbbbb;
    border-radius: 10px;
    padding: 14px 8px;
    text-align: center;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.14);
}

.stat-number {
    font-size: clamp(1.3rem, 3.5vw, 2.2rem);
    font-weight: 700;
    line-height: 1.1;
    color: #000000 !important;
    margin-bottom: 6px;
}

.stat-label {
    font-size: clamp(0.62rem, 1.8vw, 0.82rem);
    font-weight: 600;
    line-height: 1.2;
    color: #000000 !important;
    margin-bottom: 4px;
}

.stat-sublabel {
    font-size: clamp(0.55rem, 1.5vw, 0.72rem);
    font-weight: 400;
    line-height: 1.3;
    color: #000000 !important;
}

/* =============================================
   CONTACT SECTION
   ============================================= */
.contact-info p {
    font-size: 1rem;
}

/* =============================================
   LOAD MORE BUTTON
   ============================================= */
.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
}

/* =============================================
   RESPONSIVE — TABLET
   ============================================= */
@media (max-width: 768px) {
    .product-image-container {
        height: 160px;
    }

    .product-item .card-body {
        padding: 0.75rem;
    }

    .product-title {
        font-size: 0.95rem;
    }

    .product-description {
        font-size: 0.82rem;
    }

    .product-features .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }

    .products-section {
        padding: 2rem 0 1rem;
    }

    .services-section {
        padding: 2rem 0 1rem;
    }

    .contact-section {
        padding: 1.5rem 0;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .btn {
        min-height: 44px;
        padding: 10px 14px;
        font-size: 0.9rem;
    }

    .product-overlay .btn {
        padding: 8px 12px;
        font-size: 0.85rem;
        min-height: unset;
    }

    h2.display-6 {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
    }

}

/* =============================================
   RESPONSIVE — MOBILE
   ============================================= */
@media (max-width: 576px) {
    .product-image-container {
        height: 140px;
    }

    .service-card .card-body {
        padding: 1.5rem 1rem;
    }

    .contact-section .row {
        text-align: center;
        flex-direction: column;
        align-items: center;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }

    .contact-info p {
        text-align: center;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }

    .products-section {
        padding: 1.5rem 0 1rem;
    }

    .services-section {
        padding: 1.5rem 0 1rem;
    }

    .contact-section {
        padding: 1rem 0;
    }

    .btn-lg {
        padding: 14px 20px;
        font-size: 1rem;
        min-height: 48px;
    }

    .btn-outline-primary {
        padding: 12px 18px;
        font-size: 0.95rem;
    }

    .service-feature {
        font-size: 0.8rem;
        padding: 8px 10px;
    }

    .alert.alert-info {
        font-size: 0.85rem;
        padding: 10px 12px;
    }

    h5.fw-bold {
        font-size: 1.1rem;
    }

    p.small {
        font-size: 0.85rem;
    }

    .text-center.mt-4 {
        margin-top: 1.5rem !important;
    }

    /* Scholarship: tighter padding on small phones */
    .stat-card {
        padding: 10px 4px;
    }
}

/* =============================================
   VERY SMALL PHONES (< 360px)
   ============================================= */
@media (max-width: 360px) {
    .stat-card {
        padding: 8px 2px;
    }
}
</style>
