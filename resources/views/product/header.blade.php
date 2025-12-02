
<!-- Enhanced Product Header - Football Merchandise -->
<!-- Skip Link for Accessibility -->
<a href="#main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-primary focus:text-white focus:px-4 focus:py-2 focus:rounded">Skip to main content</a>

<header class="product-header">
    <!-- Top Utility Bar -->
    <div class="top-utility-bar">
        <div class="container-fluid">
            <div class="utility-content">
                <div class="utility-left">
                    <a href="tel:+254700000000" class="utility-link">
                        <i class="fas fa-phone-alt"></i>
                        <span>+254 700 000 000</span>
                    </a>
                    <a href="mailto:merchandise@vipersacademy.com" class="utility-link">
                        <i class="fas fa-envelope"></i>
                        <span class="hide-mobile">merchandise@vipersacademy.com</span>
                    </a>
                    <span class="utility-badge">
                        <i class="fas fa-truck-fast"></i>
                        <span>Free Delivery in Western Kenya</span>
                    </span>
                </div>
                <div class="utility-right">
                    <a href="{{ route('help.center') }}" class="utility-link">
                        <i class="fas fa-question-circle"></i>
                        <span>Help</span>
                    </a>
                    <a href="{{ route('orders.track') }}" class="utility-link">
                        <i class="fas fa-box"></i>
                        <span class="hide-mobile">Track Order</span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="utility-link">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <a href="{{ route('logout') }}" class="utility-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hide-mobile">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="utility-link">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In</span>
                        </a>
                        <a href="{{ route('register') }}" class="utility-link highlight">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="container-fluid">
            <div class="header-content">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Logo -->
                <div class="logo-section">
                    <a href="{{ url('/') }}" class="logo-link" title="Click here to go back to main page">
                        <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers Academy Merchandise" class="logo-img">
                        <div class="logo-text">
                            <span class="brand-name">Vipers Academy</span>
                            <span class="brand-tag">Official Merchandise</span>
                            <span class="logo-tooltip">Back to Homepage</span>
                        </div>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="search-section">
                    <form action="{{ route('products.search') }}" method="GET" class="search-form" id="searchForm">
                        <div class="search-input-group">
                            <select name="category" class="search-category" id="searchCategory">
                                <option value="">All Categories</option>
                                <option value="jerseys" {{ request('category') === 'jerseys' ? 'selected' : '' }}>Jerseys</option>
                                <option value="training" {{ request('category') === 'training' ? 'selected' : '' }}>Training Gear</option>
                                <option value="accessories" {{ request('category') === 'accessories' ? 'selected' : '' }}>Accessories</option>
                                <option value="footwear" {{ request('category') === 'footwear' ? 'selected' : '' }}>Footwear</option>
                                <option value="equipment" {{ request('category') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                            </select>
                            <input type="text"
                                   name="search"
                                   class="search-input"
                                   placeholder="Search jerseys, boots, training gear..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                                <span class="hide-mobile">Search</span>
                            </button>
                        </div>
                        <!-- Search Suggestions (populated dynamically) -->
                        <div class="search-suggestions" id="searchSuggestions" style="display: none;"></div>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    <!-- Wishlist -->
                    <div class="action-item">
                        <a href="{{ route('wishlist.index') }}" class="action-link" title="Wishlist">
                            <i class="fas fa-heart"></i>
                            <span class="action-label hide-mobile">Wishlist</span>
                            <span class="action-count wishlist-count" id="wishlist-count" style="display: none;">0</span>
                        </a>
                    </div>

                    <!-- Account -->
                    <div class="action-item dropdown">
                        <a href="{{ route('login') }}" class="action-link" title="Account">
                            <i class="fas fa-user"></i>
                            <span class="action-label hide-mobile">Account</span>
                        </a>
                        @auth
                        <div class="dropdown-menu account-dropdown">
                            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> My Orders</a>
                            <a href="{{ route('profile.edit') }}"><i class="fas fa-user-cog"></i> Profile</a>
                            <a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Wishlist</a>
                            <a href="{{ route('addresses.index') }}"><i class="fas fa-map-marker-alt"></i> Addresses</a>
                            <hr>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                        @endauth
                    </div>

                    <!-- Cart -->
                    <div class="action-item cart-item">
                        <a href="{{ route('cart.index') }}" class="action-link cart-link" title="Shopping Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="action-label hide-mobile">Cart</span>
                            <span class="action-count cart-count" id="header-cart-count">0</span>
                            <span class="cart-total hide-mobile" id="cart-total">KSh 0</span>
                        </a>
                        <!-- Mini Cart Dropdown -->
                        <div class="mini-cart-dropdown" id="miniCart" style="display: none;">
                            <div class="mini-cart-header">
                                <h4>Shopping Cart (<span id="mini-cart-count">0</span>)</h4>
                            </div>
                            <div class="mini-cart-items" id="mini-cart-items">
                                <!-- Cart items populated dynamically -->
                            </div>
                            <div class="mini-cart-footer">
                                <div class="mini-cart-total">
                                    <span>Subtotal:</span>
                                    <strong id="mini-cart-total">KSh 0</strong>
                                </div>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline">View Cart</a>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Navigation -->
    <nav class="category-nav">
        <div class="container-fluid">
            <ul class="category-list">
                <li class="category-item mega-menu-trigger {{ !request()->routeIs('products*') && !request('category') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}">
                        <i class="fas fa-th"></i>
                        <span>All Products</span>
                    </a>
                </li>
                <li class="category-item {{ request('filter') === 'new-arrivals' ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}">
                        <i class="fas fa-star"></i>
                        <span>New Arrivals</span>
                    </a>
                </li>
                <li class="category-item mega-menu-trigger {{ request('category') === 'jerseys' ? 'active' : '' }}">
                    <a href="{{ route('products.category', 'jerseys') }}">
                        <i class="fas fa-tshirt"></i>
                        <span>Jerseys</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-menu-content">
                            <div class="mega-menu-column">
                                <h4>By Type</h4>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'type' => 'home']) }}">Home Jerseys</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'type' => 'away']) }}">Away Jerseys</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'type' => 'third']) }}">Third Jerseys</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'type' => 'retro']) }}">Retro Classics</a>
                            </div>
                            <div class="mega-menu-column">
                                <h4>By Size</h4>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'size' => 'youth']) }}">Youth Sizes</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'size' => 'adult']) }}">Adult Sizes</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'size' => 'plus']) }}">Plus Sizes</a>
                            </div>
                            <div class="mega-menu-column">
                                <h4>Special</h4>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'custom' => '1']) }}">Custom Jerseys</a>
                                <a href="{{ route('products.index', ['category' => 'jerseys', 'signed' => '1']) }}">Signed Jerseys</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="category-item {{ request('category') === 'training' ? 'active' : '' }}">
                    <a href="{{ route('products.category', 'training') }}">
                        <i class="fas fa-dumbbell"></i>
                        <span>Training Gear</span>
                    </a>
                </li>
                <li class="category-item {{ request('category') === 'accessories' ? 'active' : '' }}">
                    <a href="{{ route('products.category', 'accessories') }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Accessories</span>
                    </a>
                </li>
                <li class="category-item {{ request('category') === 'footwear' ? 'active' : '' }}">
                    <a href="{{ route('products.category', 'footwear') }}">
                        <i class="fas fa-shoe-prints"></i>
                        <span>Footwear</span>
                    </a>
                </li>
                <li class="category-item sale-item {{ request('filter') === 'sale' ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['filter' => 'sale']) }}">
                        <i class="fas fa-tag"></i>
                        <span>Sale</span>
                    </a>
                </li>
                <li class="category-item special-item">
                    <a href="#" onclick="showInstallmentModal(event)">
                        <i class="fas fa-credit-card"></i>
                        <span>Lipa Polepole</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h3>Menu</h3>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-menu-content">
            <!-- Mobile menu items populated dynamically -->
        </div>
    </div>
</header>

<!-- Installment Modal -->
<div class="modal fade" id="installmentModal" tabindex="-1" aria-labelledby="installmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="installmentModalLabel">
                    <i class="fas fa-credit-card"></i> Lipa Polepole Payment Options
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="installment-option">
                    <div class="installment-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="installment-details">
                        <h6>M-Pesa Paybill</h6>
                        <p><strong>Paybill:</strong> 123456</p>
                        <p><strong>Account:</strong> Your Order Number</p>
                    </div>
                </div>
                <div class="installment-option">
                    <div class="installment-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="installment-details">
                        <h6>Flexible Payment Plans</h6>
                        <ul>
                            <li>3-month installment plans available</li>
                            <li>6-month plans for orders above KSh 10,000</li>
                            <li>0% interest for orders above KSh 5,000</li>
                            <li>Small processing fee applies</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>How it works:</strong> Select "Lipa Polepole" at checkout and choose your payment plan. Make your first payment to activate your order!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('installment.info') }}" class="btn btn-primary">Learn More</a>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Product Header Styles */
:root {
    --primary-color: #ea1c4d;
    --primary-dark: #c0173f;
    --secondary-color: #1a1a1a;
    --accent-color: #ffd700;
    --text-dark: #333;
    --text-light: #666;
    --border-color: #e5e7eb;
    --bg-light: #f8f9fa;
    --success-color: #28a745;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.12);
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
    --transition: all 0.3s ease;
}

* {
    box-sizing: border-box;
}

.product-header {
    background: white;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.product-header.fixed-nav {
    position: fixed;
    left: 0;
    right: 0;
}

/* Top Utility Bar */
.top-utility-bar {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    padding: 10px 0;
    font-size: 13px;
    color: white;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Main Header */
.main-header {
    padding: 20px 0;
    border-bottom: 1px solid var(--border-color);
}

/* Category Navigation */
.category-nav {
    background: linear-gradient(to bottom, #f8f9fa, #ffffff);
    border-bottom: 2px solid var(--border-color);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Fade States */
.top-utility-bar.fade-out {
    opacity: 0;
    transform: translateY(-100%);
}

.category-nav.fade-out {
    opacity: 0;
    transform: translateY(-100%);
}

/* Accessibility */
.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: var(--primary-color);
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    z-index: 1001;
    transition: top 0.3s ease;
}

.skip-link:focus {
    top: 6px;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Rest of the styles from the original file */
.utility-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.utility-left, .utility-right {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.utility-link, .utility-badge {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
    padding: 4px 8px;
    border-radius: 4px;
}

.utility-link:hover {
    color: white;
    background: rgba(255,255,255,0.1);
}

.utility-link.highlight {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
}

.utility-badge {
    background: rgba(255,215,0,0.2);
    color: var(--accent-color);
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 30px;
}

.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    color: var(--text-dark);
    cursor: pointer;
    padding: 8px;
}

.logo-section {
    flex-shrink: 0;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: var(--transition);
}

.logo-link:hover {
    transform: scale(1.02);
}

.logo-img {
    width: 55px;
    height: 55px;
    object-fit: contain;
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.logo-tooltip {
    font-size: 10px;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.logo-link:hover .logo-tooltip {
    opacity: 1;
}

.brand-name {
    font-size: 22px;
    font-weight: 800;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: -0.5px;
}

.brand-tag {
    font-size: 11px;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
}

.search-section {
    flex: 1;
    max-width: 700px;
    position: relative;
}

.search-form {
    width: 100%;
}

.search-input-group {
    display: flex;
    border: 2px solid var(--primary-color);
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.search-input-group:focus-within {
    box-shadow: var(--shadow-md);
    border-color: var(--primary-dark);
}

.search-category {
    border: none;
    padding: 14px 16px;
    font-size: 14px;
    color: var(--text-dark);
    background: var(--bg-light);
    cursor: pointer;
    outline: none;
    border-right: 1px solid var(--border-color);
    min-width: 140px;
}

.search-input {
    flex: 1;
    border: none;
    padding: 14px 16px;
    font-size: 14px;
    outline: none;
    min-width: 200px;
}

.search-input::placeholder {
    color: #999;
}

.search-btn {
    background: var(--primary-color);
    border: none;
    color: white;
    padding: 14px 24px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-btn:hover {
    background: var(--primary-dark);
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 0 0 8px 8px;
    box-shadow: var(--shadow-md);
    margin-top: 4px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
}

.user-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-shrink: 0;
}

.action-item {
    position: relative;
}

.action-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--text-dark);
    padding: 10px 12px;
    border-radius: 8px;
    transition: var(--transition);
    min-width: 70px;
    position: relative;
}

.action-link:hover {
    background: var(--bg-light);
    color: var(--primary-color);
    transform: translateY(-2px);
}

.action-link i {
    font-size: 22px;
    margin-bottom: 4px;
}

.action-label {
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.action-count {
    position: absolute;
    top: 4px;
    right: 4px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: var(--shadow-sm);
}

.cart-link {
    background: var(--primary-color);
    color: white !important;
}

.cart-link:hover {
    background: var(--primary-dark);
    color: white !important;
}

.cart-total {
    font-size: 11px;
    font-weight: 700;
    margin-top: 2px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--shadow-lg);
    min-width: 220px;
    padding: 8px 0;
    display: none;
    z-index: 1001;
    margin-top: 8px;
}

.action-item:hover .dropdown-menu {
    display: block;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: var(--text-dark);
    text-decoration: none;
    transition: var(--transition);
}

.dropdown-menu a:hover {
    background: var(--bg-light);
    color: var(--primary-color);
}

.dropdown-menu hr {
    margin: 8px 0;
    border: none;
    border-top: 1px solid var(--border-color);
}

.mini-cart-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--shadow-lg);
    width: 350px;
    max-height: 500px;
    overflow-y: auto;
    z-index: 1001;
    margin-top: 8px;
}

.cart-item:hover .mini-cart-dropdown {
    display: block !important;
}

.mini-cart-header {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
}

.mini-cart-header h4 {
    margin: 0;
    font-size: 16px;
    color: var(--text-dark);
}

.mini-cart-items {
    padding: 16px;
    max-height: 300px;
    overflow-y: auto;
}

.mini-cart-footer {
    padding: 16px;
    border-top: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.mini-cart-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
}

.category-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0;
    justify-content: center;
}

.category-item {
    position: relative;
}

.category-item > a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 20px;
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border-bottom: 3px solid transparent;
    white-space: nowrap;
}

.category-item > a i {
    font-size: 16px;
}

.category-item:hover > a,
.category-item.active > a {
    color: var(--primary-color);
    background: white;
    border-bottom-color: var(--primary-color);
}

.category-item.sale-item > a {
    color: var(--success-color);
    font-weight: 700;
}

.category-item.sale-item:hover > a,
.category-item.sale-item.active > a {
    color: var(--success-color);
    border-bottom-color: var(--success-color);
}

.category-item.special-item > a {
    background: linear-gradient(135deg, var(--accent-color), #ffa500);
    color: var(--secondary-color);
    font-weight: 700;
}

.mega-menu {
    position: absolute;
    top: calc(100% + 2px);
    left: -20px;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--shadow-lg);
    padding: 24px;
    min-width: 600px;
    width: max-content;
    max-width: calc(100vw - 40px);
    display: none;
    z-index: 1001;
    overflow: visible;
    transform-origin: top center;
}

/* Only show mega menu on hover for non-touch devices */
:not(.touch-device) .mega-menu-trigger:hover .mega-menu {
    display: block;
}

.mega-menu-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 32px;
    min-width: 600px;
    width: 100%;
}

.mega-menu-column h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 8px;
}

.mega-menu-column a {
    display: block;
    padding: 8px 0;
    color: var(--text-dark);
    text-decoration: none;
    transition: var(--transition);
    border-left: 3px solid transparent;
    padding-left: 8px;
}

.mega-menu-column a:hover {
    color: var(--primary-color);
    background: var(--bg-light);
    border-left-color: var(--primary-color);
    padding-left: 12px;
}



/* Active state for touch devices */
.mega-menu.active {
    display: block !important;
    visibility: visible !important;
}

/* Debug styles to make mega menu more visible */
.mega-menu-debug {
    border: 3px solid red !important;
    background: yellow !important;
    opacity: 0.9 !important;
}

.mega-menu-debug .mega-menu-column {
    border: 2px solid blue !important;
    padding: 10px !important;
    background: lightblue !important;
}

.mobile-menu-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1998;
}

.mobile-menu {
    display: none;
    position: fixed;
    top: 0;
    left: -100%;
    width: 300px;
    height: 100vh;
    background: white;
    z-index: 1999;
    transition: left 0.3s ease;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
}

.mobile-menu.active {
    left: 0;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    background: var(--primary-color);
    color: white;
}

.mobile-menu-header h3 {
    margin: 0;
    font-size: 18px;
}

.mobile-menu-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 4px;
}

.mobile-menu-content {
    padding: 16px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
}

.btn-secondary {
    background: var(--text-light);
    color: white;
}

.btn-secondary:hover {
    background: var(--text-dark);
}

.installment-option {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: var(--bg-light);
    border-radius: 8px;
    margin-bottom: 16px;
}

.installment-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.installment-details h6 {
    margin: 0 0 8px 0;
    color: var(--text-dark);
    font-size: 16px;
}

.installment-details p {
    margin: 4px 0;
    color: var(--text-light);
    font-size: 14px;
}

.installment-details ul {
    margin: 8px 0;
    padding-left: 20px;
}

.installment-details li {
    margin: 4px 0;
    color: var(--text-light);
    font-size: 14px;
}

.alert {
    padding: 16px;
    border-radius: 8px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.alert-info {
    background: #e3f2fd;
    color: #0d47a1;
    border: 1px solid #90caf9;
}

.alert i {
    font-size: 20px;
    flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .header-content {
        gap: 20px;
    }

    .search-section {
        max-width: 500px;
    }

    .category-list {
        justify-content: flex-start;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .category-item > a {
        padding: 14px 16px;
        font-size: 14px;
    }
}

@media (max-width: 992px) {
    .hide-mobile {
        display: none !important;
    }

    .utility-left, .utility-right {
        gap: 12px;
    }

    .header-content {
        gap: 15px;
    }

    .search-category {
        min-width: 100px;
        font-size: 13px;
    }

    .mega-menu {
        min-width: 500px;
    }

    .mega-menu-content {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: block;
    }

    .top-utility-bar {
        font-size: 11px;
        padding: 8px 0;
    }

    .utility-content {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .utility-left, .utility-right {
        flex-wrap: wrap;
        gap: 8px;
    }

    .main-header {
        padding: 12px 0;
    }

    .header-content {
        flex-wrap: wrap;
        gap: 12px;
    }

    .logo-img {
        width: 40px;
        height: 40px;
    }

    .brand-name {
        font-size: 16px;
    }

    .brand-tag {
        font-size: 9px;
    }

    .logo-tooltip {
        display: none;
    }

    .search-section {
        order: 3;
        flex: 1 1 100%;
        max-width: 100%;
    }

    .search-category {
        display: none;
    }

    .search-input {
        font-size: 13px;
        padding: 12px;
    }

    .search-btn {
        padding: 12px 16px;
    }

    .user-actions {
        gap: 4px;
        margin-left: auto;
    }

    .action-link {
        min-width: 50px;
        padding: 8px;
    }

    .action-link i {
        font-size: 20px;
    }

    .category-nav {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .category-list {
        justify-content: flex-start;
        padding: 0 16px;
    }

    .category-item > a {
        padding: 12px 14px;
        font-size: 13px;
    }

    .mega-menu {
        min-width: 350px;
        left: -50px;
    }

    .mega-menu-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .mini-cart-dropdown {
        width: calc(100vw - 32px);
        right: 16px;
    }
}

@media (max-width: 480px) {
    .utility-link span, .utility-badge span {
        font-size: 11px;
    }

    .action-count {
        min-width: 18px;
        height: 18px;
        font-size: 10px;
    }

    .category-item > a {
        padding: 10px 12px;
        font-size: 12px;
    }

    .category-item > a i {
        font-size: 14px;
    }
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-light);
}

::-webkit-scrollbar-thumb {
    background: var(--text-light);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-color);
}
</style>

<script>
// Sticky Navigation Scroll Handler
let lastScrollY = window.scrollY;
let ticking = false;

function handleScroll() {
    const currentScrollY = window.scrollY;
    const scrollThreshold = 50; // Minimum scroll distance to trigger changes

    console.log('handleScroll called - current:', currentScrollY, 'last:', lastScrollY);

    const header = document.querySelector('.product-header');
    const topUtilityBar = document.querySelector('.top-utility-bar');
    const categoryNav = document.querySelector('.category-nav');

    console.log('Elements found - header:', !!header, 'utility:', !!topUtilityBar, 'category:', !!categoryNav);

    if (Math.abs(currentScrollY - lastScrollY) < scrollThreshold) {
        console.log('Scroll below threshold, ignoring');
        return; // Ignore small scrolls
    }

    if (currentScrollY > lastScrollY && currentScrollY > 100) {
        // Scrolling down - return to sticky position, fade out bars
        console.log('Scrolling down - sticky position, fading out bars');
        header?.classList.remove('fixed-nav');
        console.log('Removed fixed-nav class');
        topUtilityBar?.classList.add('fade-out');
        topUtilityBar?.setAttribute('aria-hidden', 'true');
        categoryNav?.classList.add('fade-out');
        categoryNav?.setAttribute('aria-hidden', 'true');
    } else if (currentScrollY < lastScrollY) {
        // Scrolling up - become fixed, fade in bars
        console.log('Scrolling up - fixed position, fading in bars');
        header?.classList.add('fixed-nav');
        console.log('Added fixed-nav class');
        topUtilityBar?.classList.remove('fade-out');
        topUtilityBar?.removeAttribute('aria-hidden');
        categoryNav?.classList.remove('fade-out');
        categoryNav?.removeAttribute('aria-hidden');
    }

    lastScrollY = currentScrollY;
}

function throttleScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            handleScroll();
            ticking = false;
        });
        ticking = true;
    }
}

// Cart Management
let cartData = {
    count: 0,
    total: 0,
    items: []
};

// Update cart count and total
async function updateHeaderCartCount() {
    try {
        const response = await fetch('{{ route("cart.summary") }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) throw new Error('Failed to fetch cart data');

        const data = await response.json();
        cartData = data;

        // Update cart count
        const countElement = document.getElementById('header-cart-count');
        const miniCountElement = document.getElementById('mini-cart-count');
        const count = data.count || 0;
        const displayCount = count > 99 ? '99+' : count;

        if (countElement) {
            countElement.textContent = displayCount;
            countElement.style.display = count > 0 ? 'flex' : 'none';
        }

        if (miniCountElement) {
            miniCountElement.textContent = count;
        }

        // Update cart total
        const totalElement = document.getElementById('cart-total');
        const miniTotalElement = document.getElementById('mini-cart-total');
        const total = data.total || 0;
        const formattedTotal = `KSh ${total.toLocaleString()}`;

        if (totalElement) totalElement.textContent = formattedTotal;
        if (miniTotalElement) miniTotalElement.textContent = formattedTotal;

        // Update wishlist count if available
        if (data.wishlist_count !== undefined) {
            const wishlistCount = document.getElementById('wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.wishlist_count;
                wishlistCount.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
            }
        }

        // Update mini cart items
        updateMiniCartItems(data.items || []);

    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

// Update mini cart items display
function updateMiniCartItems(items) {
    const miniCartItems = document.getElementById('mini-cart-items');
    if (!miniCartItems) return;

    if (items.length === 0) {
        miniCartItems.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Your cart is empty</p>';
        return;
    }

    miniCartItems.innerHTML = items.map(item => `
        <div class="mini-cart-item" style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
            <img src="${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            <div style="flex: 1;">
                <h6 style="margin: 0 0 4px 0; font-size: 14px;">${item.name}</h6>
                <p style="margin: 0; font-size: 12px; color: #666;">${item.quantity} × KSh ${item.price.toLocaleString()}</p>
            </div>
            <button onclick="removeFromCart(${item.id})" style="background: none; border: none; color: #ea1c4d; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
}

// Remove item from cart
async function removeFromCart(itemId) {
    try {
        const response = await fetch(`{{ route("cart.remove", ":id") }}`.replace(':id', itemId), {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            await updateHeaderCartCount();
            window.dispatchEvent(new Event('cartUpdated'));
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}

// Show installment modal
function showInstallmentModal(event) {
    if (event) event.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById('installmentModal'));
    modal.show();
}

// Toggle mobile menu
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileMenuOverlay');

    if (mobileMenu && overlay) {
        const isActive = mobileMenu.classList.toggle('active');
        overlay.style.display = isActive ? 'block' : 'none';
        document.body.style.overflow = isActive ? 'hidden' : '';
    }
}

// Search suggestions (debounced)
let searchTimeout;
const searchInput = document.querySelector('.search-input');
const searchSuggestions = document.getElementById('searchSuggestions');

if (searchInput && searchSuggestions) {
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();

        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetchSearchSuggestions(query);
        }, 300);
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
}

// Fetch search suggestions
async function fetchSearchSuggestions(query) {
    try {
        const response = await fetch(`{{ route("products.suggestions") }}?q=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) return;

        const suggestions = await response.json();
        displaySearchSuggestions(suggestions);
    } catch (error) {
        console.error('Error fetching suggestions:', error);
    }
}

// Display search suggestions
function displaySearchSuggestions(suggestions) {
    if (!searchSuggestions) return;

    if (suggestions.length === 0) {
        searchSuggestions.style.display = 'none';
        return;
    }

    searchSuggestions.innerHTML = suggestions.map(item => `
        <a href="${item.url}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; color: #333; transition: background 0.2s;">
            ${item.image ? `<img src="${item.image}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` : ''}
            <div style="flex: 1;"></div>
            <span style="font-size: 12px; color: #666;">${item.category || ''}</span>
        </a>
    `).join('');

    searchSuggestions.style.display = 'block';
}

// Mega Menu Touch Handling
let activeMegaMenu = null;
let isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

function initMegaMenuHandlers() {
    const megaTriggers = document.querySelectorAll('.mega-menu-trigger');
    console.log('Initializing mega menu handlers, found triggers:', megaTriggers.length);

    megaTriggers.forEach((trigger, index) => {
        const megaMenu = trigger.querySelector('.mega-menu');
        console.log(`Mega menu ${index + 1}:`, {
            trigger: !!trigger,
            menu: !!megaMenu,
            isTouchDevice: isTouchDevice
        });

        if (isTouchDevice) {
            // Touch device handling
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Touch device click - toggling mega menu');

                // Close any other open mega menu
                if (activeMegaMenu && activeMegaMenu !== megaMenu) {
                    activeMegaMenu.style.display = 'none';
                    activeMegaMenu.classList.remove('active');
                }

                // Toggle current menu
                const isVisible = megaMenu.style.display === 'block';
                console.log('Current visibility:', isVisible);
                megaMenu.style.display = isVisible ? 'none' : 'block';
                megaMenu.style.visibility = isVisible ? 'hidden' : 'visible';

                if (!isVisible) {
                    megaMenu.classList.add('active');
                    activeMegaMenu = megaMenu;
                    console.log('Mega menu activated');
                } else {
                    megaMenu.classList.remove('active');
                    activeMegaMenu = null;
                    console.log('Mega menu deactivated');
                }
            });

            // Prevent closing when scrolling inside the mega menu
            if (megaMenu) {
                megaMenu.addEventListener('touchstart', function(e) {
                    e.stopPropagation();
                });

                megaMenu.addEventListener('touchmove', function(e) {
                    e.stopPropagation();
                });

                // Close when clicking a link inside
                megaMenu.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A') {
                        megaMenu.style.display = 'none';
                        megaMenu.classList.remove('active');
                        activeMegaMenu = null;
                    }
                });
            }
        } else {
            // Desktop hover handling (keep existing)
            trigger.addEventListener('mouseenter', function() {
                console.log('Desktop hover enter - showing mega menu');
                megaMenu.style.display = 'block';
                megaMenu.style.visibility = 'visible';
            });

            trigger.addEventListener('mouseleave', function() {
                console.log('Desktop hover leave - hiding mega menu');
                megaMenu.style.display = 'none';
            });
        }
    });

    // Close mega menu when clicking outside
    document.addEventListener('click', function(e) {
        if (activeMegaMenu && !e.target.closest('.mega-menu-trigger')) {
            activeMegaMenu.style.display = 'none';
            activeMegaMenu.classList.remove('active');
            activeMegaMenu = null;
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && activeMegaMenu) {
            activeMegaMenu.style.display = 'none';
            activeMegaMenu.classList.remove('active');
            activeMegaMenu = null;
        }
    });
}

// Initialize mega menu handlers when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add touch class to body for CSS targeting
    if (isTouchDevice) {
        document.body.classList.add('touch-device');
    }

    initMegaMenuHandlers();
});
