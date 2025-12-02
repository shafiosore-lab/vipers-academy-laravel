<!-- Product Footer  -->
<footer class="product-footer">
    <div class="footer-main">
        <div class="container">
            <div class="row g-4">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section footer-about">
                        <h5><i class="fas fa-futbol"></i>Vipers Merchandise</h5>
                        <p>#1 Football Merchandise Shop in Western Kenya. Official Vipers Academy gear with fast delivery, quality guarantee, and Lipa Polepole payment options.</p>

                        <div class="footer-social">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                            <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>

                        <div class="footer-contact">
                            <div class="footer-contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+254 700 000 000</span>
                            </div>
                            <div class="footer-contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>merchandise@vipersacademy.com</span>
                            </div>
                            <div class="footer-contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Western Kenya</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h5>Shop</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('products.index') }}">All Products</a></li>
                            <li><a href="{{ route('products.category', 'new') }}">New Arrivals</a></li>
                            <li><a href="{{ route('products.category', 'old') }}">Classics</a></li>
                            <li><a href="#jerseys">Jerseys</a></li>
                            <li><a href="#training">Training Gear</a></li>
                            <li><a href="#accessories">Accessories</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Customer Service -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h5>Customer Service</h5>
                        <ul class="footer-links">
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Size Guide</a></li>
                            <li><a href="#">Shipping Info</a></li>
                            <li><a href="#">Returns</a></li>
                            <li><a href="#">Track Order</a></li>
                            <li><a href="#" onclick="showInstallmentModal()">Lipa Polepole</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Payment & Delivery -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5>Payment & Delivery</h5>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <i class="fab fa-cc-visa"></i>
                                <span>Credit/Debit Cards</span>
                            </div>
                            <div class="payment-method">
                                <span class="mpesa-icon">M-PESA</span>
                                <span>Mobile Money</span>
                            </div>
                            <div class="payment-method">
                                <i class="fas fa-hand-holding-usd"></i>
                                <span>Lipa Polepole</span>
                            </div>
                        </div>

                        <div class="delivery-info">
                            <h6>Free Delivery Across Western Kenya</h6>
                            <ul>
                                <li><i class="fas fa-check"></i> Orders over KSh 2,000</li>
                                <li><i class="fas fa-check"></i> Express delivery available</li>
                                <li><i class="fas fa-check"></i> 30-day return policy</li>
                            </ul>
                        </div>

                        <div class="newsletter-form">
                            <h6>Stay Updated</h6>
                            <form>
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Enter your email" required>
                                    <button type="submit">Subscribe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    © 2024 <a href="{{ route('home') }}">Vipers Academy</a>. All rights reserved. |
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms of Service</a> |
                    <a href="#">Cookie Policy</a>
                </div>

                <div class="footer-badges">
                    <div class="trust-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Shopping</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-award"></i>
                        <span>#1 in Western Kenya</span>
                    </div>
                    <div class="trust-badge">
                        <i class="fas fa-truck"></i>
                        <span>Fast Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Product Footer Styles */
.product-footer {
    background: #1a1a1a;
    color: #ffffff;
    margin-top: 50px;
}

.footer-main {
    padding-top: 60px;
    padding-bottom: 40px;
    border-bottom: 1px solid #333;
}

.footer-section h5 {
    color: #ea1c4d;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links li a {
    color: #b3b3b3;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.footer-links li a:hover {
    color: #ea1c4d;
    padding-left: 5px;
}

.footer-about p {
    color: #b3b3b3;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 20px;
}

.footer-social {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.footer-social a {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s;
}

.footer-social a:hover {
    background: #ea1c4d;
    transform: translateY(-3px);
}

.footer-contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
    color: #b3b3b3;
    font-size: 14px;
}

.footer-contact-item i {
    color: #ea1c4d;
    margin-right: 10px;
    margin-top: 3px;
    font-size: 16px;
}

/* Payment Methods */
.payment-methods {
    margin-bottom: 30px;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 10px;
    background: #2a2a2a;
    border-radius: 6px;
}

.payment-method i {
    font-size: 24px;
    color: #ea1c4d;
}

.mpesa-icon {
    background: #4caf50;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
}

.payment-method span {
    font-size: 14px;
    color: #b3b3b3;
}

/* Delivery Info */
.delivery-info h6 {
    color: #ea1c4d;
    margin-bottom: 15px;
    font-size: 14px;
}

.delivery-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.delivery-info li {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 13px;
    color: #b3b3b3;
}

.delivery-info li i {
    color: #4caf50;
}

/* Newsletter */
.newsletter-form h6 {
    color: #ea1c4d;
    margin-bottom: 15px;
    font-size: 14px;
}

.newsletter-form .input-group {
    border-radius: 4px;
    overflow: hidden;
}

.newsletter-form input {
    border: 1px solid #333;
    background: #2a2a2a;
    color: #fff;
    padding: 12px 16px;
    font-size: 14px;
}

.newsletter-form input:focus {
    outline: none;
    border-color: #ea1c4d;
    background: #333;
}

.newsletter-form button {
    background: #ea1c4d;
    border: none;
    color: white;
    padding: 12px 24px;
    font-weight: 600;
    transition: background 0.3s;
}

.newsletter-form button:hover {
    background: #c0173f;
}

/* Footer Bottom */
.footer-bottom {
    padding: 25px 0;
    background: #0f0f0f;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.footer-copyright {
    color: #666;
    font-size: 13px;
}

.footer-copyright a {
    color: #ea1c4d;
    text-decoration: none;
}

.footer-badges {
    display: flex;
    gap: 20px;
    align-items: center;
}

.trust-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
    font-size: 12px;
}

.trust-badge i {
    color: #4caf50;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
    }

    .footer-badges {
        justify-content: center;
        flex-wrap: wrap;
    }

    .payment-method {
        flex-direction: column;
        text-align: center;
        gap: 5px;
    }
}
</style>

<script>
// Newsletter subscription
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for subscribing to Vipers Merchandise newsletter!');
            this.reset();
        });
    }
});

// Installment modal function (referenced from header)
function showInstallmentModal() {
    // This will be handled by the header script
}
</script>
