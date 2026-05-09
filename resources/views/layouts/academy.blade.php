<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vipers Academy - Professional Football Training')</title>
    <meta name="description" content="@yield('meta_description', 'Join Vipers Academy for world-class football training')">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og:title', 'Vipers Academy - Professional Football Training')">
    <meta property="og:description" content="@yield('og:description', 'Join Vipers Academy for world-class football training')">
    <meta property="og:image" content="@yield('og:image', asset('assets/img/logo/vps.jpeg'))">
    <meta property="og:url" content="@yield('og:url', url()->current())">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:site_name" content="@yield('og:site_name', 'Vipers Academy')">
    <meta property="og:locale" content="@yield('og:locale', 'en_US')">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="@yield('twitter:card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter:title', 'Vipers Academy - Professional Football Training')">
    <meta name="twitter:description" content="@yield('twitter:description', 'Join Vipers Academy for world-class football training')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('assets/img/logo/vps.jpeg'))">

    <!-- Article Schema Meta Tags -->
    <meta property="article:published_time" content="@yield('article:published_time')">
    <meta property="article:author" content="@yield('article:author')">
    <meta property="article:section" content="@yield('article:section')">
    <meta property="article:tag" content="@yield('article:tag')">

    <!-- Fonts & Libraries -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
        /* ========================================
           CSS VARIABLES
           ======================================== */
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

            /* Effects */
            --glass: rgba(255, 255, 255, 0.95);
            --border-glass: rgba(255, 255, 255, 0.2);

            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.2);

            /* Spacing */
            --space-xs: 0.125rem;
            --space-sm: 0.25rem;
            --space-md: 0.5rem;
            --space-lg: 0.75rem;
            --space-xl: 1rem;
            --space-2xl: 1.5rem;

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

            /* Player System Colors - Consolidated */
            --primary-red: #ea1c4d;
            --primary-red-light: #f87171;
            --primary-red-dark: #dc2626;
            --secondary-green: #059669;
            --neutral-50: #fafafa;
            --neutral-100: #f5f5f5;
            --neutral-200: #e5e5e5;
            --neutral-300: #d4d4d4;
            --neutral-400: #a3a3a3;
            --neutral-500: #737373;
            --neutral-600: #525252;
            --neutral-700: #404040;
            --neutral-800: #262626;
            --neutral-900: #171717;

            /* Heights */
            --topbar-height: 40px;
            --navbar-height: 80px;
            --navbar-shrink: 64px;
        }

        /* ========================================
           GLOBAL STYLES
           ======================================== */
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
            padding-top: calc(var(--topbar-height) + var(--navbar-shrink));
            transition: padding-top var(--duration-normal) var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1 0 auto;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
            letter-spacing: -0.025em;
            margin-bottom: var(--space-md);
        }

        h1 { font-size: clamp(1.75rem, 4vw, 2.5rem); color: var(--primary); margin-bottom: var(--space-lg); }
        h2 { font-size: clamp(1.5rem, 3vw, 2rem); margin-bottom: var(--space-md); }
        h3 { font-size: clamp(1.25rem, 2.5vw, 1.5rem); margin-bottom: var(--space-sm); }
        h4 { font-size: clamp(1.125rem, 2vw, 1.25rem); margin-bottom: var(--space-sm); }
        h5 { font-size: clamp(1rem, 1.75vw, 1.125rem); margin-bottom: var(--space-xs); }
        h6 { font-size: clamp(0.875rem, 1.5vw, 1rem); margin-bottom: var(--space-xs); }

        .nav-links a, .top-bar-content span {
            letter-spacing: 0.02em;
            font-weight: 600;
        }

        /* ========================================
           TOP BAR
           ======================================== */
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

        /* ========================================
           MAIN NAVBAR
           ======================================== */
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
            width: 90px;
            height: 90px;
            object-fit: contain;
            transition: transform var(--duration-normal);
        }

        .navbar-brand:hover .logo-image {
            transform: scale(1.1);
        }

        .main-navbar.shrink .logo-image {
            width: 70px;
            height: 70px;
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
            height: 44px;
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 8px 12px;
            font-size: 0.8rem;
            outline: none;
        }

        .search-input::placeholder {
            color: var(--gray-600);
            font-size: 0.75rem;
        }

        .search-btn {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background var(--duration-fast);
            white-space: nowrap;
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
            gap: 2px;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-links a {
            padding: 6px 12px;
            color: var(--gray-900);
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: all var(--duration-fast);
            display: block;
            position: relative;
        }

        .nav-links a:hover {
            background: #fff5f0;
            color: var(--primary);
        }

        .nav-links a.active {
            color: var(--primary);
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Sign In Button */
        .nav-signin {
            background: var(--primary) !important;
            color: var(--white) !important;
            margin-left: 2px;
        }

        .nav-signin:hover {
            background: var(--primary-dark) !important;
        }

        /* More Dropdown */
        .nav-more {
            position: relative;
        }

        .nav-more-toggle {
            padding: 6px 12px !important;
            color: var(--gray-900) !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            border-radius: var(--radius-sm) !important;
            cursor: pointer;
            display: flex !important;
            align-items: center;
            gap: 4px;
            background: transparent !important;
            border: none !important;
        }

        .nav-more-toggle::after {
            display: none !important;
        }

        .nav-more-toggle:hover {
            background: #fff5f0 !important;
            color: var(--primary) !important;
        }

        .nav-more-toggle::before {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width var(--duration-fast);
            border-radius: 2px;
        }

        .nav-more:hover .nav-more-toggle::before {
            width: 18px;
        }

        .nav-more-toggle .more-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 14px;
            border-radius: 3px;
            background: var(--gray-200);
            font-size: 0.65rem;
            font-weight: 700;
            transition: all var(--duration-fast);
        }

        .nav-more:hover .nav-more-toggle .more-icon {
            background: var(--primary);
            color: var(--white);
        }

        /* Dropdown Menu */
        .nav-more-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-sm);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: all var(--duration-normal) var(--transition);
            z-index: 999;
            list-style: none;
            padding: 4px 0;
            margin: 4px 0 0 0;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .nav-more:hover .nav-more-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-section {
            padding: 0;
            margin: 0;
        }

        .dropdown-section-title {
            display: block;
            padding: 6px 14px 4px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 2px;
        }

        .dropdown-section:first-child .dropdown-section-title {
            padding-top: 2px;
        }

        .dropdown-section a {
            padding: 8px 14px 8px 18px !important;
            font-size: 0.8rem !important;
            display: block;
            width: 100%;
            color: var(--gray-900) !important;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .dropdown-section a::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--gray-300);
            transition: all var(--duration-fast);
        }

        .dropdown-section a:last-child {
            border-bottom: none;
        }

        .dropdown-section a:hover {
            background: rgba(234, 28, 77, 0.06);
            color: var(--primary) !important;
            padding-left: 22px !important;
        }

        .dropdown-section a:hover::before {
            background: var(--primary);
            width: 5px;
            height: 5px;
            left: 6px;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 4px 0;
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

        /* ========================================
           MOBILE MENU
           ======================================== */
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
            animation: slideDown 0.25s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mobile-nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-links > li {
            border-bottom: 1px solid var(--gray-200);
        }

        .mobile-nav-links a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 5%;
            min-height: 46px;
            color: var(--gray-900);
            transition: all var(--duration-fast);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .mobile-nav-links a:hover,
        .mobile-nav-links a.active {
            color: var(--primary);
        }

        .mobile-nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 5%;
            width: 3px;
            height: 3px;
            background: var(--primary);
            border-radius: 50%;
        }

        .mobile-nav-links > li > a {
            padding: 14px 5%;
            min-height: 48px;
            font-weight: 600;
            background: rgba(0, 0, 0, 0.02);
        }

        .mobile-nav-links > li > a:hover,
        .mobile-nav-links > li > a.active {
            background: rgba(234, 28, 77, 0.08);
        }

        /* Mobile Sign In - Proper specificity */
        .mobile-nav-links .mobile-signin {
            background: var(--primary);
            color: var(--white);
            margin: 8px 5%;
            padding: 12px 5%;
            border-radius: var(--radius-sm);
            justify-content: center;
            min-height: 44px;
        }

        .mobile-nav-links .mobile-signin:hover {
            background: var(--primary-dark);
        }

        .mobile-more-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }

        .mobile-more-toggle .more-indicator {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            background: var(--gray-200);
            font-size: 0.85rem;
            font-weight: 700;
            transition: all var(--duration-fast);
        }

        .mobile-more-toggle.active .more-indicator {
            background: var(--primary);
            color: var(--white);
            transform: rotate(45deg);
        }

        .mobile-more-menu {
            display: none;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 0;
            margin: 0;
            box-shadow: inset 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: expandIn 0.25s ease;
        }

        @keyframes expandIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mobile-more-menu.active {
            display: block;
        }

        .mobile-dropdown-section {
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .mobile-dropdown-section:last-child {
            border-bottom: none;
        }

        .mobile-dropdown-section-title {
            display: block;
            padding: 10px 5% 8px 8%;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gray-600);
        }

        /* Mobile More Menu Links - Proper specificity */
        .mobile-more-menu a {
            padding: 12px 5% 12px 8%;
            min-height: 44px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .mobile-more-menu a:hover {
            background: rgba(234, 28, 77, 0.08);
            color: var(--primary);
            padding-left: 10%;
        }

        .mobile-more-menu a:last-child {
            border-bottom: none;
        }

        /* ========================================
           FOOTER
           ======================================== */
        .footer {
            background: linear-gradient(135deg, var(--black) 0%, #1a1a1a 100%);
            color: var(--gray-600);
            padding: 0.3rem 0 0.2rem;
            position: relative;
            flex-shrink: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--highlight) 50%, var(--primary) 100%);
            box-shadow: 0 0 10px rgba(234, 28, 77, 0.3);
        }

        .footer-content {
            padding: 0;
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.1rem;
        }

        .footer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .footer-title {
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 0.125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-title::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--primary);
        }

        .footer-text {
            font-size: 0.7rem;
            line-height: 1.4;
            color: var(--gray-600);
            margin: 0 auto 0.25rem;
            max-width: 700px;
            text-align: center;
        }

        .footer-stats {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 0.1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 0.15rem 0.35rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.6rem;
            font-weight: 600;
            color: var(--gray-300);
        }

        .stat-value {
            color: var(--highlight);
            font-weight: 800;
        }

        .footer-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.1rem;
        }

        .play-store-badge,
        .app-store-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .play-store-badge {
            background: linear-gradient(135deg, #20c997, #198754);
            box-shadow: 0 4px 12px rgba(32, 201, 151, 0.3);
        }

        .play-store-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(32, 201, 151, 0.4);
            background: linear-gradient(135deg, #198754, #146c43);
        }

        .app-store-badge {
            background: linear-gradient(135deg, #0d6efd, #0b5fd6);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .app-store-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.4);
            background: linear-gradient(135deg, #0b5fd6, #0a58ca);
        }

        .store-icon {
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 800;
        }

        .play-store-badge .store-icon {
            color: #20c997;
        }

        .app-store-badge .store-icon {
            color: #0d6efd;
        }

        .qr-code {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--gray-300);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .qr-code:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .qr-icon {
            width: 16px;
            height: 16px;
            background: var(--primary);
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 800;
            color: white;
        }

        .footer-copyright {
            font-size: 0.6rem;
            color: var(--white);
            letter-spacing: 0.3px;
            margin-bottom: 0;
            text-align: center;
            opacity: 0.9;
            line-height: 1.2;
        }

        .footer-copyright a {
            color: var(--white);
            text-decoration: underline;
            opacity: 0.85;
            transition: opacity 0.2s ease;
        }

        .footer-copyright a:hover {
            opacity: 1;
            color: var(--white);
        }

        /* ========================================
           MOBILE FOOTER OPTIMIZATION
           ======================================== */
        @media (max-width: 767px) {
            .footer {
                padding: 0.2rem 0 0.3rem; /* 60% reduction from original 0.75rem/0.5rem */
                min-height: auto;
            }

            .footer-content {
                gap: 0.08rem; /* Proportional gap for mobile */
                padding: 0 0.25rem;
            }

            .footer-actions {
                margin: 0.08rem 0 0.08rem 0; /* Proportional margins */
                gap: 0.4rem; /* Appropriate spacing */
            }

            .footer-actions img {
                height: 32px; /* Smaller Google Play badge */
            }

            .footer-copyright {
                font-size: 0.55rem; /* Smaller font for mobile */
                line-height: 1.1; /* Tighter line height */
                margin-bottom: 0;
                padding: 0 0.25rem;
                letter-spacing: 0.2px;
            }

            .footer-stats {
                gap: 0.5rem; /* Appropriate gap between stats */
                margin-bottom: 0.08rem;
            }

            .stat-item {
                padding: 0.1rem 0.25rem; /* Smaller padding */
                font-size: 0.55rem; /* Smaller font */
                gap: 0.2rem; /* Tighter internal spacing */
            }

            .stat-value {
                font-weight: 700;
            }

            /* Remove footer pseudo-element on mobile for cleaner look */
            .footer::before {
                display: none;
            }
        }

        /* ========================================
           RESPONSIVE DESIGN
           ======================================== */
        @media (max-width: 1199px) {
            .search-wrapper { max-width: 350px; }
            .nav-links a { padding: 5px 8px; font-size: 0.75rem; }
        }

        @media (min-width: 1200px) {
            .nav-links a { padding: 6px 10px; font-size: 0.75rem; }
        }

        @media (min-width: 1024px) {
            .desktop-signin { display: block; }
        }

        @media (max-width: 1023px) {
            :root { --navbar-height: 64px; }
            .desktop-signin { display: none; }
            .navbar-content { grid-template-columns: auto 1fr; gap: var(--space-lg); }
            .search-wrapper { grid-column: 2; max-width: none; }
            .nav-links { display: none; }
            .mobile-toggle { display: flex; grid-column: 2; justify-self: end; }
            body { padding-top: var(--navbar-height); }
            .top-bar-left span:first-child { display: none; }
            .top-bar-right a:nth-child(3),
            .top-bar-right a:nth-child(4) { display: none; }
        }

        @media (max-width: 767px) {
            .brand-name { font-size: 1rem; }
            .brand-tagline { font-size: 0.5625rem; }
        }

        @media (max-width: 575px) {
            :root { --navbar-height: 60px; }
            .top-bar { display: none; }
            body { padding-top: var(--navbar-height); }
            .main-navbar { top: 0; }
            .navbar-content { grid-template-columns: auto auto; gap: var(--space-md); }
            .search-wrapper { display: none; }
            .mobile-toggle { grid-column: 2; }
            .brand-text { display: none; }
            .logo-image { width: 48px; height: 48px; }
            .mobile-menu { top: var(--navbar-height); max-height: calc(100vh - var(--navbar-height)); }
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

    <!-- Global Mobile Optimization Framework -->
    @include('website.includes.mobile-optimization')

    @stack('styles')
</head>
<body class="academy-layout">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <span><i class="fas fa-phone me-1"></i> +254 716 305 905</span>
                <span><i class="fas fa-envelope me-1"></i> info@vipersacademy.com</span>
            </div>
            <div class="top-bar-right">
                <a href="{{ route('careers.index') }}">Careers</a>
                <a href="{{ route('contact') }}">Contact</a>
                <a href="{{ route('login') }}">Log in</a>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="main-navbar">
        <div class="navbar-content">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logo/vps.jpeg') }}" alt="Vipers Academy" class="logo-image">
                <div class="brand-text">
                    <span class="brand-name">Mumias Vipers Academy</span>
                    <span class="brand-tagline">Excellence in Football</span>
                </div>
            </a>

            <!-- Search -->
            <div class="search-wrapper">
                <form class="search-form" action="{{ route('search') }}" method="GET">
                    <input type="search" name="q" class="search-input" placeholder="Search players, programs..." aria-label="Search">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <!-- Desktop Navigation -->
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('programs') }}" class="{{ request()->routeIs('programs*') ? 'active' : '' }}">Programs</a></li>
                <li><a href="{{ route('gamesuite') }}" class="{{ request()->routeIs('gamesuite*') ? 'active' : '' }}">Gamesuite</a></li>
                <li><a href="{{ route('players.index') }}" class="{{ request()->routeIs('players*') ? 'active' : '' }}">Players</a></li>
                <li class="nav-more">
                    <a href="#" class="nav-more-toggle" onclick="return false;">
                        More
                        <span class="more-icon">+</span>
                    </a>
                    <ul class="nav-more-menu">
                        <li class="dropdown-section">
                            <span class="dropdown-section-title">Community</span>
                            <a href="{{ route('contact') }}">Contact</a>
                            <a href="{{ route('donate') }}">Donate</a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-section">
                            <span class="dropdown-section-title">Resources</span>
                            <a href="{{ route('blog') }}">Blog</a>
                            <a href="{{ route('merchandise') }}">Merchandise</a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li class="dropdown-section">
                            <span class="dropdown-section-title">Careers</span>
                            <a href="{{ route('staff') }}">Staff</a>
                            <a href="{{ route('careers.index') }}">Careers</a>
                        </li>
                    </ul>
                </li>
                <li><a href="{{ route('login') }}" class="nav-signin desktop-signin {{ request()->routeIs('login*') ? 'active' : '' }}">Sign In</a></li>
            </ul>

            <!-- Mobile Toggle -->
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
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('programs') }}" class="{{ request()->routeIs('programs*') ? 'active' : '' }}">Programs</a></li>
            <li><a href="{{ route('gamesuite') }}" class="{{ request()->routeIs('gamesuite*') ? 'active' : '' }}">Gamesuite</a></li>
            <li><a href="{{ route('players.index') }}" class="{{ request()->routeIs('players*') ? 'active' : '' }}">Players</a></li>
            <li>
                <a href="#" class="mobile-more-toggle" onclick="toggleMobileMore(event)">
                    More
                    <span class="more-indicator">+</span>
                </a>
                 <div class="mobile-more-menu" id="mobileMoreMenu">
                     <div class="mobile-dropdown-section">
                         <span class="mobile-dropdown-section-title">Community</span>
                         <a href="{{ route('contact') }}">Contact</a>
                         <a href="{{ route('donate') }}">Donate</a>
                     </div>
                     <div class="mobile-dropdown-section">
                         <span class="mobile-dropdown-section-title">Resources</span>
                         <a href="{{ route('blog') }}">Blog</a>
                         <a href="{{ route('merchandise') }}">Merchandise</a>
                     </div>
                     <div class="mobile-dropdown-section">
                         <span class="mobile-dropdown-section-title">Careers</span>
                         <a href="{{ route('staff') }}">Staff</a>
                         <a href="{{ route('careers.index') }}">Careers</a>
                     </div>
                 </div>
            </li>
            <li><a href="{{ route('login') }}" class="mobile-signin {{ request()->routeIs('login*') ? 'active' : '' }}">Sign In</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">

                <!-- Google Play Badge -->
                <div class="footer-actions" style="justify-content: center; margin: 0.1rem 0;">
                    <a href="https://play.google.com/store/apps/details?id=GameSuite" target="_blank">
                        <img
                            src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"
                            alt="Get it on Google Play"
                            height="50">
                    </a>
                </div>

                <!-- Copyright -->
                <p class="footer-copyright">
                    2026 Vipers Academy. All rights reserved.
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('privacy') }}">Privacy</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({ duration: 800, once: true });

        // Mobile Menu Toggle
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

        // Mobile More Dropdown
        function toggleMobileMore(event) {
            event.preventDefault();
            const toggle = event.currentTarget;
            const menu = document.getElementById('mobileMoreMenu');
            toggle.classList.toggle('active');
            menu.classList.toggle('active');
        }

        // Close mobile menu when clicking links
        const mobileLinks = document.querySelectorAll('.mobile-nav-links a:not([href^="#"])');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileToggle.classList.remove('active');
            });
        });

        // Navbar Scroll Behavior
        const navbar = document.querySelector('.main-navbar');
        const topBar = document.querySelector('.top-bar');

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;

            if (navbar) {
                navbar.classList.toggle('sticky', scrollY > 100);
                navbar.classList.toggle('shrink', scrollY > 100);
            }

            if (topBar) {
                topBar.classList.toggle('shrink', scrollY > 10);
            }
        }, { passive: true });
    </script>
    @stack('scripts')
</body>
</html>
