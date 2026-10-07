<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Dashboard - GameSuite'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ========================================
           CSS VARIABLES (Enhanced for Responsive)
           ======================================== */
        :root {
            /* Primary Colors */
            --primary:        #ea1c4d;
            --primary-dark:   #c0173f;
            --secondary:      #65c16e;
            --accent:         #fbc761;
            --danger:         #dc2626;
            --success:        #10b981;
            --warning:        #f59e0b;
            --info:           #0891b2;
            --dark:           #1a1a1a;

            /* Neutrals */
            --gray-900:       #333;
            --gray-800:       #4a4a4a;
            --gray-700:       #5c5c5c;
            --gray-600:       #666;
            --gray-500:       #888;
            --gray-400:       #aaa;
            --gray-300:       #e8e8e8;
            --gray-200:       #eee;
            --gray-100:       #f5f5f5;
            --white:          #fff;

            /* Shadows */
            --shadow-sm:      0 1px 2px rgba(0,0,0,0.05);
            --shadow:         0 1px 3px rgba(0,0,0,0.1);
            --shadow-md:      0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg:      0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl:      0 20px 25px rgba(0,0,0,0.15);

            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;

            /* Breakpoints */
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --breakpoint-xxl: 1400px;

            /* Layout */
            --header-height:  60px;
            --sidebar-width:  260px;

            /* Border Radius */
            --radius-sm: 4px;
            --radius: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;

            /* Transitions */
            --transition: 0.2s ease;
            --transition-fast: 0.15s ease;
            --transition-slow: 0.3s ease;

            /* Z-Index */
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
        }

        /* ========================================
           GLOBAL STYLES
           ======================================== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family);
            font-size: var(--font-size-base);
            background: var(--gray-100);
            color: var(--gray-900);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========================================
           TYPOGRAPHY - FLUID
           ======================================== */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
            margin-bottom: var(--spacing-md);
        }

        h1 { font-size: clamp(1.5rem, 4vw, var(--font-size-3xl)); color: var(--primary); }
        h2 { font-size: clamp(1.25rem, 3vw, var(--font-size-2xl)); }
        h3 { font-size: clamp(1.125rem, 2.5vw, var(--font-size-xl)); }
        h4 { font-size: clamp(1rem, 2vw, var(--font-size-lg)); }
        h5 { font-size: clamp(0.875rem, 1.5vw, var(--font-size-base)); }
        h6 { font-size: clamp(0.75rem, 1.2vw, var(--font-size-sm)); }

        /* ========================================
           TOP HEADER
           ======================================== */
        .top-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-300);
            height: var(--header-height);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: var(--z-fixed);
            box-shadow: var(--shadow-sm);
            transition: height var(--transition-slow);
        }

        .top-header .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 var(--spacing-lg);
            gap: var(--spacing-md);
        }

        /* Mobile sidebar toggle */
        .sidebar-toggle-mobile {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--gray-600);
            cursor: pointer;
            padding: var(--spacing-sm);
            border-radius: var(--radius);
            transition: var(--transition);
            min-width: 44px;
            min-height: 44px;
        }

        .sidebar-toggle-mobile:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .sidebar-toggle-mobile:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Brand */
        .admin-brand {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            text-decoration: none;
            flex-shrink: 0;
        }

        .admin-brand-text {
            font-weight: 700;
            font-size: clamp(1.1rem, 2vw, 1.5rem);
            color: var(--primary);
            line-height: 1.2;
        }

        /* Push user menu to far right */
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-left: auto;
        }

        /* Icon action buttons */
        .header-action-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            background: var(--gray-100);
            border: none;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        @media (max-width: 768px) {
            .header-action-btn {
                width: 40px;
                height: 40px;
                min-width: 44px;
                min-height: 44px;
            }
        }

        .header-action-btn:hover {
            background: var(--primary);
            color: var(--white);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger);
            color: var(--white);
            border-radius: var(--radius-full);
            min-width: 16px;
            height: 16px;
            font-size: 9px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
            padding: 0 3px;
        }

        /* User menu button */
        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-xs) var(--spacing-sm);
            background: var(--gray-100);
            border-radius: var(--radius-md);
            cursor: pointer;
            border: none;
            transition: var(--transition);
            min-height: 44px;
        }

        @media (max-width: 768px) {
            .admin-user-menu {
                padding: var(--spacing-sm);
            }

            .admin-user-name {
                display: none;
            }
        }

        .admin-user-menu:hover { background: var(--gray-200); }

        .admin-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: var(--font-size-sm);
            font-weight: 600;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .admin-user-avatar {
                width: 36px;
                height: 36px;
                font-size: var(--font-size-base);
            }
        }

        .admin-user-name {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--gray-900);
        }

        .admin-user-menu i {
            font-size: var(--font-size-xs);
            color: var(--gray-500);
        }

        /* ========================================
           DROPDOWN MENUS
           ======================================== */
        .dropdown-menu {
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-300);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-sm);
            animation: dropdownSlide 0.2s ease;
        }

        @keyframes dropdownSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            border-radius: var(--radius);
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: var(--font-size-sm);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .dropdown-header {
            font-size: var(--font-size-xs);
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
        }

        /* User dropdown */
        .user-dropdown .dropdown-menu {
            right: 0 !important;
            left: auto !important;
        }

        /* ========================================
           LEFT SIDEBAR
           ======================================== */
        .admin-sidebar {
            background: var(--white);
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            border-right: 1px solid var(--gray-300);
            overflow-y: auto;
            z-index: calc(var(--z-fixed) - 10);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            transition: transform var(--transition-slow);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        .sidebar-nav {
            padding: var(--spacing-md) 0;
        }

        /* Accordion settings */
        .accordion-settings {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--spacing-md);
            margin-bottom: var(--spacing-sm);
            background: var(--gray-100);
            border-bottom: 1px solid var(--gray-200);
        }

        .accordion-settings-label {
            font-size: var(--font-size-xs);
            color: var(--gray-500);
            font-weight: 500;
        }

        .accordion-toggle-switch {
            position: relative;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
        }

        .accordion-toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .accordion-toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #ccc;
            transition: var(--transition);
            border-radius: var(--radius-full);
        }

        .accordion-toggle-slider::before {
            content: "";
            position: absolute;
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: var(--transition);
            border-radius: 50%;
        }

        .accordion-toggle-switch input:checked + .accordion-toggle-slider {
            background-color: var(--primary);
        }

        .accordion-toggle-switch input:checked + .accordion-toggle-slider::before {
            transform: translateX(16px);
        }

        /* Sidebar accordion */
        .sidebar-accordion {
            margin-bottom: 2px;
        }

        .sidebar-accordion-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--spacing-sm) var(--spacing-md);
            color: var(--gray-900);
            font-size: var(--font-size-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            border-radius: var(--radius);
            min-height: 44px;
        }

        @media (max-width: 768px) {
            .sidebar-accordion-header {
                padding: var(--spacing-md);
                font-size: var(--font-size-base);
            }
        }

        .sidebar-accordion-header:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .sidebar-accordion-header.active {
            background: #fff5f0;
            color: var(--primary);
        }

        .sidebar-accordion-header i {
            width: 18px;
            font-size: var(--font-size-sm);
            margin-right: var(--spacing-sm);
        }

        .sidebar-accordion-icon {
            font-size: 11px;
            color: var(--gray-600);
            transition: var(--transition-slow);
            font-weight: bold;
        }

        .sidebar-accordion-header.active .sidebar-accordion-icon {
            color: var(--primary);
        }

        .sidebar-accordion-header.active .sidebar-accordion-icon::before {
            content: "\2212";
        }

        .sidebar-accordion:not(.open) .sidebar-accordion-icon::before {
            content: "\002B";
        }

        .sidebar-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height var(--transition-slow);
        }

        .sidebar-accordion.open .sidebar-accordion-content {
            max-height: 500px;
        }

        .sidebar-accordion-content .sidebar-link {
            padding-left: 2.5rem;
        }

        /* Sidebar links */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            color: var(--gray-600);
            text-decoration: none;
            font-size: var(--font-size-sm);
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
            min-height: 44px;
        }

        @media (max-width: 768px) {
            .sidebar-link {
                font-size: var(--font-size-base);
                padding: var(--spacing-md);
            }
        }

        .sidebar-link:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .sidebar-link.active {
            background: #fff5f0;
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }

        .sidebar-link i {
            width: 16px;
            font-size: var(--font-size-sm);
            text-align: center;
        }

        .sidebar-badge {
            margin-left: auto;
            background: var(--danger);
            color: var(--white);
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: var(--radius-full);
            white-space: nowrap;
        }

        .sidebar-divider {
            height: 1px;
            background: var(--gray-200);
            margin: var(--spacing-sm) var(--spacing-md);
        }

        .sidebar-section-label {
            padding: var(--spacing-xs) var(--spacing-md);
            font-size: var(--font-size-xs);
            color: var(--gray-500);
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Mobile close button */
        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: var(--spacing-md);
            right: var(--spacing-md);
            background: var(--gray-100);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            z-index: 10;
            min-width: 44px;
            min-height: 44px;
        }

        .sidebar-close-btn:hover {
            background: var(--primary);
            color: var(--white);
        }

        @media (max-width: 992px) {
            .sidebar-close-btn {
                display: flex;
            }
        }

        /* ========================================
           MOBILE SIDEBAR OVERLAY
           ======================================== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: calc(var(--z-fixed) - 20);
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition-slow), visibility var(--transition-slow);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ========================================
           MAIN CONTENT
           ======================================== */
        .admin-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: var(--spacing-lg);
            min-height: calc(100vh - var(--header-height));
            transition: margin-left var(--transition-slow);
        }

        /* ========================================
           ALERTS
           ======================================== */
        .alert-custom {
            border-radius: var(--radius-md);
            padding: var(--spacing-md) var(--spacing-lg);
            border: 1px solid transparent;
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-custom.success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-custom.error {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }

        /* ========================================
           FOOTER
           ======================================== */
        .admin-footer {
            margin-top: var(--spacing-2xl);
            padding: var(--spacing-lg) 0;
            border-top: 1px solid var(--gray-300);
        }

        .admin-footer-content {
            display: flex;
            justify-content: space-between;
            font-size: var(--font-size-sm);
            color: var(--gray-500);
        }

        /* ========================================
           RESPONSIVE STYLES
           ======================================== */

        /* Extra Small Devices (Phones) */
        @media (max-width: 575.98px) {
            .top-header .container-fluid {
                padding: 0 var(--spacing-sm);
                gap: var(--spacing-sm);
            }

            .admin-brand-text {
                font-size: 1.1rem;
            }

            .sidebar-toggle-mobile {
                display: flex;
                margin-right: var(--spacing-xs);
            }

            .header-action-btn {
                width: 32px;
                height: 32px;
            }

            .admin-content {
                padding: var(--spacing-md);
            }

            /* Hide user name on very small screens */
            .admin-user-name {
                display: none;
            }
        }

        /* Small Devices (Landscape Phones) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .top-header .container-fluid {
                padding: 0 var(--spacing-md);
            }

            .admin-brand-text {
                font-size: 1.2rem;
            }
        }

        /* Medium Devices (Tablets) */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: var(--header-height);
                left: 0;
                z-index: calc(var(--z-fixed) + 100);
                height: calc(100vh - var(--header-height));
                box-shadow: var(--shadow-xl);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-sidebar.show ~ .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }

            .admin-content {
                margin-left: 0;
                padding: var(--spacing-md);
            }

            .sidebar-toggle-mobile {
                display: flex !important;
            }

            /* Ensure proper z-index stacking */
            .top-header {
                z-index: calc(var(--z-fixed) + 101);
            }
        }

        /* Large Devices (Desktops) */
        @media (min-width: 992px) {
            .admin-sidebar {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: var(--sidebar-width);
            }

            .sidebar-toggle-mobile {
                display: none !important;
            }

            .sidebar-close-btn {
                display: none !important;
            }
        }

        /* Extra Large Devices */
        @media (min-width: 1200px) {
            :root {
                --header-height: 64px;
            }

            .admin-content {
                padding: var(--spacing-xl);
            }
        }

        /* ========================================
           TABLE RESPONSIVENESS
           ======================================== */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: var(--spacing-md);
            border-radius: var(--radius-md);
        }

        .table-responsive-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        .dashboard-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            font-size: var(--font-size-sm);
        }

        @media (max-width: 768px) {
            .dashboard-table {
                font-size: 0.8125rem;
            }

            .dashboard-table th,
            .dashboard-table td {
                padding: 0.5rem;
                white-space: nowrap;
            }
        }

        /* ========================================
           CARD GRID RESPONSIVENESS
           ======================================== */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--spacing-md);
        }

        @media (max-width: 768px) {
            .card-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-sm);
            }
        }

        @media (min-width: 576px) and (max-width: 991px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ========================================
           FORM RESPONSIVENESS
           ======================================== */
        .form-control {
            width: 100%;
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: var(--font-size-sm);
            line-height: 1.5;
            color: var(--gray-900);
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            transition: border-color var(--transition), box-shadow var(--transition);
            min-height: 40px;
        }

        @media (max-width: 768px) {
            .form-control {
                min-height: 44px;
                font-size: 16px; /* Prevents iOS zoom */
                padding: var(--spacing-md);
            }
        }

        .form-control:focus {
            outline: 0;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(234, 28, 77, 0.25);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-xs);
            font-weight: 500;
            color: var(--gray-700);
            font-size: var(--font-size-sm);
        }

        /* ========================================
           BUTTON RESPONSIVENESS
           ======================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-lg);
            font-size: var(--font-size-sm);
            font-weight: 500;
            line-height: 1.5;
            border-radius: var(--radius);
            transition: all var(--transition);
            min-height: 38px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
        }

        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                font-size: var(--font-size-base);
                padding: var(--spacing-md) var(--spacing-lg);
            }

            .btn-sm {
                min-height: 38px;
                padding: var(--spacing-xs) var(--spacing-md);
                font-size: 0.875rem;
            }
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ========================================
           SAFE AREA INSETS
           ======================================== */
        @supports (padding: max(0px)) {
            .top-header .container-fluid {
                padding-left: max(var(--spacing-lg), env(safe-area-inset-left));
                padding-right: max(var(--spacing-lg), env(safe-area-inset-right));
            }

            .admin-sidebar {
                padding-left: max(var(--spacing-md), env(safe-area-inset-left));
                padding-right: max(var(--spacing-md), env(safe-area-inset-right));
            }

            .admin-content {
                padding-left: max(var(--spacing-lg), env(safe-area-inset-left));
                padding-right: max(var(--spacing-lg), env(safe-area-inset-right));
            }
        }

        /* ========================================
           REDUCED MOTION
           ======================================== */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ========================================
           HIGH CONTRAST
           ======================================== */
        @media (prefers-contrast: high) {
            .top-header {
                border-bottom: 2px solid var(--gray-900);
            }

            .admin-sidebar {
                border-right: 2px solid var(--gray-900);
            }

            .btn-primary {
                border: 2px solid var(--white);
            }
        }

        /* ========================================
           TOUCH DEVICES
           ======================================== */
        @media (hover: none) and (pointer: coarse) {
            .admin-user-menu,
            .header-action-btn,
            .sidebar-link,
            .btn,
            .sidebar-accordion-header {
                min-height: 44px;
                min-width: 44px;
            }

            /* Remove hover transforms */
            .btn:hover {
                transform: none;
            }
        }

        /* ========================================
           PRINT
           ======================================== */
        @media print {
            .top-header,
            .admin-sidebar,
            .sidebar-overlay,
            .sidebar-toggle-mobile {
                display: none !important;
            }

            .admin-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white;
            }
        }

        /* ========================================
           LANDSCAPE ORIENTATION
           ======================================== */
        @media (max-width: 767px) and (orientation: landscape) {
            .top-header .container-fluid {
                padding: 0 var(--spacing-sm);
            }

            .admin-brand-text {
                font-size: 1rem;
            }
        }

        /* ========================================
           FOCUS VISIBLE
           ======================================== */
        .sidebar-toggle-mobile:focus-visible,
        .sidebar-close-btn:focus-visible,
        .sidebar-link:focus-visible,
        .btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px rgba(234, 28, 77, 0.2);
        }

        /* ========================================
           UTILITY CLASSES
           ======================================== */
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

        .text-primary { color: var(--primary) !important; }
        .text-secondary { color: var(--secondary) !important; }
        .text-success { color: var(--success) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-muted { color: var(--gray-500) !important; }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ===================== TOP HEADER ===================== --}}
    <header class="top-header" role="banner">
        <div class="container-fluid">

            {{-- Mobile sidebar toggle (left) --}}
            <button class="sidebar-toggle-mobile"
                    id="mobileSidebarToggle"
                    type="button"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    aria-expanded="false"
                    aria-controls="adminSidebar"
                    onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Brand --}}
            @php
                $dashboardRoute = route('admin.dashboard');
                $user = auth()->user();
                if ($user->hasAnyRole(['coach', 'head-coach', 'assistant-coach'])) {
                    $dashboardRoute = route('coach.dashboard');
                } elseif ($user->hasRole('team-manager')) {
                    $dashboardRoute = route('manager.dashboard');
                } elseif ($user->hasRole('finance-officer')) {
                    $dashboardRoute = route($user->hasRole('super-admin') ? 'super-admin.finance.dashboard' : 'finance.dashboard');
                }
            @endphp

            <a href="{{ $dashboardRoute }}" class="admin-brand">
                <span class="admin-brand-text">{{ __('GameSuite') }}</span>
            </a>

            {{-- Right-side actions (pushed to far right via margin-left: auto) --}}
            <nav class="header-right" role="navigation" aria-label="{{ __('Header actions') }}">

                {{-- Website link --}}
                <a href="{{ route('players.index') }}"
                   target="_blank"
                   class="header-action-btn"
                   aria-label="{{ __('View Website') }}"
                   title="{{ __('View Website') }}">
                    <i class="fas fa-external-link-alt"></i>
                </a>

                {{-- Notifications --}}
                @php
                    $pendingPlayers    = \App\Models\Player::where('registration_status', 'Pending')->count();
                    $pendingPartners   = \App\Models\User::where('user_type', 'partner')->where('approval_status', 'pending')->count();
                    $totalNotifications = $pendingPlayers + $pendingPartners;
                @endphp
                <div class="dropdown">
                    <button class="header-action-btn" data-bs-toggle="dropdown" aria-label="{{ __('Notifications') }}">
                        <i class="fas fa-bell"></i>
                        @if($totalNotifications > 0)
                            <span class="notification-badge">{{ $totalNotifications }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                        @if($pendingPlayers > 0)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.players.index') }}">
                                    <i class="fas fa-user-plus text-success me-2"></i>
                                    {{ $pendingPlayers }} {{ __('pending players') }}
                                </a>
                            </li>
                        @endif
                        @if($totalNotifications === 0)
                            <li><span class="dropdown-item text-muted">{{ __('No notifications') }}</span></li>
                        @endif
                    </ul>
                </div>

                {{-- User menu (far right) --}}
                <div class="dropdown user-dropdown">
                    <button class="admin-user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="admin-user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="admin-user-name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>{{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </nav>
        </div>
    </header>

     {{-- ===================== LEFT SIDEBAR ===================== --}}
     @include('components.layout.sidebar-unified', [
         'mode' => 'accordion',
         'sidebarId' => 'adminSidebar',
         'baseClass' => 'admin'
     ])

     {{-- Mobile Sidebar Overlay --}}
     <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true" onclick="toggleSidebar()"></div>

     {{-- ===================== MAIN CONTENT ===================== --}}
     <main class="admin-content">

        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom error">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        </div>
        @endif

        @auth
            @include('components.trial-notification')
        @endauth

        @yield('content')

        <footer class="admin-footer">
            <div class="admin-footer-content">
                <div>&copy; {{ date('Y') }} {{ __('GameSuite') }}. {{ __('All rights reserved.') }}</div>
            </div>
        </footer>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Accordion ─────────────────────────────────────────────
        let oneSectionOpen = localStorage.getItem('sidebarOneSectionOpen') !== 'false';
        document.getElementById('accordionMode').checked = oneSectionOpen;

        function toggleAccordionMode() {
            oneSectionOpen = document.getElementById('accordionMode').checked;
            localStorage.setItem('sidebarOneSectionOpen', oneSectionOpen);
        }

        function toggleAccordion(name) {
            const accordion = document.querySelector(`[data-accordion="${name}"]`);
            const isOpen = accordion.classList.contains('open');

            if (oneSectionOpen && !isOpen) {
                document.querySelectorAll('.sidebar-accordion.open')
                    .forEach(el => el.classList.remove('open'));
            }

            accordion.classList.toggle('open');
            saveAccordionState();
        }

        function saveAccordionState() {
            const open = [...document.querySelectorAll('.sidebar-accordion.open')]
                .map(el => el.dataset.accordion);
            localStorage.setItem('sidebarOpenAccordions', JSON.stringify(open));
        }

        function loadAccordionState() {
            const saved = localStorage.getItem('sidebarOpenAccordions');
            if (saved) {
                JSON.parse(saved).forEach(name => {
                    const el = document.querySelector(`[data-accordion="${name}"]`);
                    if (el) el.classList.add('open');
                });
            } else {
                const activeLink = document.querySelector('.sidebar-link.active');
                if (activeLink) {
                    const content = activeLink.closest('.sidebar-accordion-content');
                    if (content) content.parentElement.classList.add('open');
                }
            }
        }

        // ── Mobile sidebar ─────────────────────────────────────────
        function toggleSidebar() {
            const sidebar   = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('mobileSidebarToggle');
            const overlay   = document.getElementById('sidebarOverlay');
            const isExpanded = sidebar.classList.contains('show');

            sidebar.classList.toggle('show');

            if (overlay) {
                overlay.classList.toggle('active');
            }

            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', !isExpanded);
            }
        }

        // ── Init ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            loadAccordionState();

            const toggleBtn = document.getElementById('mobileSidebarToggle');
            const sidebar   = document.getElementById('adminSidebar');
            const overlay   = document.getElementById('sidebarOverlay');

            if (toggleBtn && sidebar) {
                // Close on overlay click
                if (overlay) {
                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('active');
                        toggleBtn.setAttribute('aria-expanded', 'false');
                    });
                }

                document.addEventListener('click', function (e) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && !overlay?.contains(e.target)) {
                        sidebar.classList.remove('show');
                        if (overlay) overlay.classList.remove('active');
                        toggleBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        if (overlay) overlay.classList.remove('active');
                        toggleBtn.setAttribute('aria-expanded', 'false');
                        toggleBtn.focus();
                    }
                });
            }

            // Auto-dismiss alerts after 5 s
            document.querySelectorAll('.alert-custom').forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
