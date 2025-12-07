<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vipers Academy - Professional Football Training')</title>
    <meta name="description" content="@yield('meta_description', 'Join Vipers Academy for world-class football training')">

    <!-- Fonts & Libraries -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
        /* ==================== CSS VARIABLES ==================== */
        :root {
            /* Brand Colors */
            --primary: #ea1c4d;
            --primary-light: #f05a7a;
            --primary-dark: #c0173f;
            --accent: #65c16e;
            --highlight: #fbc761;
            --highlight-light: #fdd56f;

            /* Neutrals */
            --white: #ffffff;
            --gray-50: #fafafa;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-900: #212529;
            --black: #1a1a1a;

            /* Glass Effects */
            --glass: rgba(255, 255, 255, 0.95);
            --border-glass: rgba(255, 255, 255, 0.2);

            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.2);

            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;

            /* Border Radius */
            --radius-sm: 6px;

            /* Transitions */
            --transition: cubic-bezier(0.4, 0, 0.2, 1);
            --duration-fast: 0.15s;
            --duration-normal: 0.3s;

            /* Z-Index */
            --z-navbar: 1021;
            --z-mobile: 1048;
            --z-topbar: 1050;

            /* Heights */
            --topbar-height: 40px;
            --navbar-height: 80px;
            --navbar-shrink: 64px;
        }

        /* ==================== GLOBAL STYLES ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 16px;
            color: var(--gray-900);
            line-height: 1.6;
            background: var(--white);
            padding-top: calc(var(--topbar-height) + var(--navbar-height));
            transition: padding-top var(--duration-normal) var(--transition);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
        }

        h1 { font-size: 30px; color: var(--primary); }
        h2 { font-size: 22px; }
        h3 { font-size: 16px; }

        /* ==================== TOP BAR ==================== */
        .top-bar {
            background: linear-gradient(135deg, var(--highlight), var(--highlight-light));
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: var(--z-topbar);
            transition: all var(--duration-normal) var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .top-bar.shrink {
            height: 0;
            opacity: 0;
            overflow: hidden;
        }

        .top-bar-content {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            font-size: 0.75rem;
        }

        .top-bar-left,
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }

        .top-bar-left span,
        .top-bar-right a {
            color: var(--gray-600);
            transition: color var(--duration-fast);
        }

        .top-bar-right a {
            padding: 0 var(--space-sm);
            border-right: 1px solid var(--gray-300);
        }

        .top-bar-right a:last-child {
            border-right: none;
        }

        .top-bar-left span:hover,
        .top-bar-right a:hover {
            color: var(--primary);
        }

        /* ==================== MAIN NAVBAR ==================== */
        .main-navbar {
            background: var(--glass);
            backdrop-filter: blur(20px);
            height: var(--navbar-height);
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            right: 0;
            z-index: var(--z-navbar);
            transition: all var(--duration-normal) var(--transition);
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid var(--border-glass);
        }

        .main-navbar.sticky {
            top: 0;
            box-shadow: var(--shadow-lg);
        }

        .main-navbar.shrink {
            height: var(--navbar-shrink);
        }

        .navbar-content {
            height: 100%;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: var(--space-2xl);
            padding: 0 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Logo */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            flex-shrink: 0;
        }

        .logo-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            transition: transform var(--duration-normal);
        }

        .navbar-brand:hover .logo-image {
            transform: scale(1.1);
        }

        .main-navbar.shrink .logo-image {
            width: 48px;
            height: 48px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 0.625rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Search */
        .search-wrapper {
            grid-column: 2;
            justify-self: center;
            max-width: 600px;
            width: 100%;
        }

        .search-form {
            display: flex;
            border: 2px solid var(--primary);
            border-radius: var(--radius-sm);
            overflow: hidden;
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 12px 16px;
            font-size: 0.875rem;
            outline: none;
        }

        .search-input::placeholder {
            color: var(--gray-600);
        }

        .search-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px 24px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background var(--duration-fast);
        }

        .search-btn:hover {
            background: var(--primary-dark);
        }

        /* Navigation Links */
        .nav-links {
            grid-column: 3;
            justify-self: end;
            display: flex;
            list-style: none;
            gap: var(--space-sm);
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            padding: 10px 18px;
            color: var(--gray-900);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: all var(--duration-fast);
            display: block;
        }

        .nav-links a:hover {
            background: #fff5f0;
            color: var(--primary);
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: var(--space-sm);
            flex-direction: column;
            gap: 4px;
            grid-column: 3;
            justify-self: end;
        }

        .mobile-toggle span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--gray-900);
            transition: all var(--duration-normal);
            border-radius: 2px;
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* ==================== MOBILE MENU ==================== */
        .mobile-menu {
            display: none;
            position: fixed;
            top: calc(var(--topbar-height) + var(--navbar-height));
            left: 0;
            right: 0;
            background: var(--white);
            box-shadow: var(--shadow-md);
            max-height: calc(100vh - var(--topbar-height) - var(--navbar-height));
            overflow-y: auto;
            z-index: var(--z-mobile);
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-links a {
            display: block;
            padding: 16px 5%;
            color: var(--gray-900);
            border-bottom: 1px solid var(--gray-200);
            transition: all var(--duration-fast);
        }

        .mobile-nav-links a:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        /* ==================== FOOTER ==================== */
        .footer {
            background: var(--black);
            color: var(--gray-600);
            padding: var(--space-xl) 0 var(--space-md);
        }

        .footer-section h5 {
            color: var(--primary);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: var(--space-md);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-section p {
            font-size: 0.875rem;
            line-height: 1.8;
            margin-bottom: var(--space-md);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: var(--space-xs);
        }

        .footer-links a {
            color: var(--gray-600);
            font-size: 0.875rem;
            transition: all var(--duration-fast);
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: var(--space-xs);
        }

        .footer-social {
            display: flex;
            gap: var(--space-md);
            margin-top: var(--space-md);
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: all var(--duration-normal);
        }

        .footer-social a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            padding: var(--space-md) 0 0;
            margin-top: var(--space-md);
            border-top: 1px solid var(--gray-700);
            text-align: center;
            font-size: 0.8125rem;
        }

        .footer-bottom a {
            color: var(--primary);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1199px) {
            .search-wrapper {
                max-width: 400px;
            }

            .nav-links a {
                padding: 10px 12px;
                font-size: 0.8125rem;
            }
        }

        @media (max-width: 1023px) {
            :root {
                --navbar-height: 64px;
            }

            .navbar-content {
                grid-template-columns: auto 1fr;
                gap: var(--space-lg);
            }

            .search-wrapper {
                grid-column: 2;
                max-width: none;
            }

            .nav-links,
            .mobile-toggle {
                display: none;
            }

            .mobile-toggle {
                display: flex;
                grid-column: 2;
                justify-self: end;
            }

            body {
                padding-top: calc(var(--topbar-height) + var(--navbar-height));
            }

            .top-bar-left span:first-child {
                display: none;
            }

            .top-bar-right a:nth-child(3),
            .top-bar-right a:nth-child(4) {
                display: none;
            }
        }

        @media (max-width: 767px) {
            .brand-name {
                font-size: 1rem;
            }

            .brand-tagline {
                font-size: 0.5625rem;
            }
        }

        @media (max-width: 575px) {
            :root {
                --navbar-height: 60px;
            }

            .top-bar {
                display: none;
            }

            body {
                padding-top: var(--navbar-height);
            }

            .main-navbar {
                top: 0;
            }

            .navbar-content {
                grid-template-columns: auto auto;
                gap: var(--space-md);
            }

            .search-wrapper {
                display: none;
            }

            .mobile-toggle {
                grid-column: 2;
            }

            .brand-text {
                display: none;
            }

            .logo-image {
                width: 48px;
                height: 48px;
            }

            .mobile-menu {
                top: var(--navbar-height);
                max-height: calc(100vh - var(--navbar-height));
            }

            .footer-section {
                margin-bottom: var(--space-lg);
            }
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <span><i class="fas fa-phone me-1"></i> +254 716 305 905</span>
                <span><i class="fas fa-envelope me-1"></i> info@vipersacademy.com</span>
            </div>
            <div class="top-bar-right">
                <a href="{{ route('products.index') }}">Merchandise</a>
                <a href="{{ route('careers.index') }}">Careers</a>
                <a href="{{ route('gallery') }}">Gallery</a>
                <a href="{{ route('contact') }}">Contact</a>
                <a href="{{ route('login') }}">Log in</a>
                <x-register-dropdown />
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="main-navbar">
        <div class="navbar-content">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}" alt="Vipers Academy" class="logo-image">
                <div class="brand-text">
                    <span class="brand-name">Mumias Vipers Academy</span>
                    <span class="brand-tagline">Excellence in Football</span>
                </div>
            </a>

            <div class="search-wrapper">
                <form class="search-form" action="{{ route('search') }}" method="GET">
                    <input type="search" name="q" class="search-input" placeholder="Search players, programs..." aria-label="Search">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <ul class="nav-links">
                 <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('players.index') }}">Players</a></li>
                <li><a href="{{ route('programs') }}">Programs</a></li>
                <li><a href="{{ route('news') }}">News</a></li>
                <li><a href="{{ route('staff') }}">Staff</a></li>
            </ul>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <ul class="mobile-nav-links">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('programs') }}">Programs</a></li>
            <li><a href="{{ route('players.index') }}">Players</a></li>
            <li><a href="{{ route('news') }}">News</a></li>
            <li><a href="{{ route('staff') }}">Staff</a></li>
            <li><a href="{{ route('gallery') }}">Gallery</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="{{ route('login') }}">Sign In</a></li>

        </ul>
    </div>

    <!-- Main Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5><i class="fas fa-futbol me-2"></i>Vipers Academy</h5>
                        <p>Building the future of African football through world-class training and commitment to excellence.</p>
                        <div class="footer-social">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h5>Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('programs') }}">Programs</a></li>
                            <li><a href="{{ route('news') }}">News</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-section">
                        <h5>Programs</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('programs') }}">Weekend football</a></li>
                            <li><a href="{{ route('programs') }}">Long Holiday Camp</a></li>
                            <li><a href="{{ route('programs') }}">Computer</a></li>

                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5>Contact Us</h5>
                        <p>
                            <i class="fas fa-phone me-2"></i> +254 716 305 905<br>
                            <i class="fas fa-envelope me-2"></i> info@vipersacademy.com<br>
                            <i class="fas fa-map-marker-alt me-2"></i> Mumias, Kenya
                        </p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                © {{ date('Y') }} <a href="{{ route('home') }}">Vipers Academy</a>. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({ duration: 800, once: true });

        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileMenu.classList.toggle('active');
                mobileToggle.classList.toggle('active');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.main-navbar') && !e.target.closest('.mobile-menu')) {
                    mobileMenu.classList.remove('active');
                    mobileToggle.classList.remove('active');
                }
            });
        }

        // Scroll behavior
        const navbar = document.querySelector('.main-navbar');
        const topBar = document.querySelector('.top-bar');

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (navbar) {
                if (currentScrollY > 100) {
                    navbar.classList.add('sticky', 'shrink');
                } else {
                    navbar.classList.remove('sticky', 'shrink');
                }
            }

            if (topBar) {
                topBar.classList.toggle('shrink', currentScrollY > 10);
            }
        }, { passive: true });
    </script>
    @stack('scripts')
</body>
</html>
