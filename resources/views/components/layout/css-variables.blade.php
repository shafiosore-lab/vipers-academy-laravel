{{--
    Unified Dashboard CSS Variables
    ===============================
    This file contains all CSS variables used across dashboard layouts.
    Include this in your layout to ensure consistent styling.

    Usage:
    @include('components.layout.css-variables')
--}}

<style>
/* ========================================
       CSS VARIABLES (Unified Dashboard Design)
       ======================================== */
:root {
    /* Primary Colors */
    --primary: #ea1c4d;
    --primary-dark: #c0173f;
    --primary-light: #ff4d7a;

    /* Secondary Colors */
    --secondary: #65c16e;
    --secondary-dark: #4a9e54;
    --secondary-light: #8ed68d;

    /* Accent & Status */
    --accent: #fbc761;
    --danger: #dc2626;
    --danger-light: #fecaca;
    --info: #0891b2;
    --info-light: #cffafe;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --success: #10b981;
    --success-light: #d1fae5;

    /* Neutral Colors */
    --dark: #1a1a1a;
    --gray-900: #333;
    --gray-800: #4a4a4a;
    --gray-700: #5c5c5c;
    --gray-600: #666;
    --gray-500: #888;
    --gray-400: #aaa;
    --gray-300: #e8e8e8;
    --gray-200: #eee;
    --gray-100: #f5f5f5;
    --bg-light: #f7f7f7;
    --white: #fff;

    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);

    /* Spacing Scale */
    --spacing-xs: 0.25rem;    /* 4px */
    --spacing-sm: 0.5rem;     /* 8px */
    --spacing-md: 1rem;       /* 16px */
    --spacing-lg: 1.5rem;     /* 24px */
    --spacing-xl: 2rem;       /* 32px */
    --spacing-2xl: 3rem;      /* 48px */
    --spacing-3xl: 4rem;      /* 64px */
    --spacing-4xl: 6rem;      /* 96px */

    /* Component Spacing */
    --card-padding-xs: 0.5rem;    /* 8px */
    --card-padding-sm: 0.75rem;   /* 12px */
    --card-padding-md: 1rem;      /* 16px */
    --card-padding-lg: 1.25rem;   /* 20px */
    --card-padding-xl: 1.5rem;    /* 24px */
    --card-gap: 1rem;             /* 16px */
    --stat-gap: 1.5rem;           /* 24px */
    --grid-gap: 1.5rem;           /* 24px */

    /* Layout */
    --sidebar-width: 260px;
    --sidebar-collapsed-width: 70px;
    --header-height: 60px;
    --content-padding: 20px;

    /* Typography */
    --font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-size-xs: 0.75rem;
    --font-size-sm: 0.875rem;
    --font-size-base: 1rem;
    --font-size-lg: 1.125rem;
    --font-size-xl: 1.25rem;
    --font-size-2xl: 1.5rem;
    --font-size-3xl: 1.875rem;

    /* Border Radius */
    --radius-sm: 4px;
    --radius: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
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
    --z-popover: 1060;
    --z-tooltip: 1070;
}

/* ========================================
       BASE STYLES (Shared)
       ======================================== */
.dashboard-body {
    font-family: var(--font-family);
    font-size: var(--font-size-base);
    background: var(--gray-100);
    color: var(--gray-900);
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

.dashboard-body h1,
.dashboard-body h2,
.dashboard-body h3,
.dashboard-body h4,
.dashboard-body h5,
.dashboard-body h6 {
    font-weight: 700;
    line-height: 1.2;
    color: var(--gray-900);
    margin-bottom: var(--spacing-md);
}

.dashboard-body h1 {
    font-size: var(--font-size-3xl);
    color: var(--primary);
}

.dashboard-body h2 {
    font-size: var(--font-size-xl);
}

.dashboard-body h3 {
    font-size: var(--font-size-lg);
}

/* ========================================
       HEADER STYLES (Shared)
       ======================================== */
.dashboard-header {
    background: var(--white);
    border-bottom: 1px solid var(--gray-300);
    height: var(--header-height);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: var(--z-fixed);
    box-shadow: var(--shadow-sm);
}

.dashboard-header .container-fluid {
    height: 100%;
    display: flex;
    align-items: center;
    padding: 0 var(--spacing-xl);
}

.dashboard-brand {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
}

.dashboard-logo {
    width: 36px;
    height: 36px;
    object-fit: contain;
    border-radius: var(--radius);
}

.dashboard-brand-text h5 {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    line-height: 1.2;
}

.dashboard-brand-text small {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dashboard-header-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-left: auto;
}

.dashboard-action-btn {
    width: 38px;
    height: 38px;
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

.dashboard-action-btn:hover {
    background: var(--primary);
    color: var(--white);
}

.dashboard-notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: var(--danger);
    color: var(--white);
    border-radius: var(--radius-full);
    width: 18px;
    height: 18px;
    font-size: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--white);
}

.dashboard-user-menu {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 6px 12px;
    background: var(--gray-100);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    margin-left: var(--spacing-md);
}

.dashboard-user-menu:hover {
    background: var(--gray-200);
}

.dashboard-user-avatar {
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
}

/* ========================================
       SIDEBAR STYLES (Shared)
       ======================================== */
.dashboard-sidebar {
    background: var(--white);
    width: var(--sidebar-width);
    position: fixed;
    top: var(--header-height);
    left: 0;
    bottom: 0;
    border-right: 1px solid var(--gray-300);
    overflow-y: auto;
    z-index: var(--z-fixed);
    transition: var(--transition-slow);
}

.dashboard-sidebar::-webkit-scrollbar {
    width: 6px;
}

.dashboard-sidebar::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 3px;
}

.dashboard-sidebar-nav {
    padding: var(--spacing-lg) var(--spacing-md);
}

.dashboard-nav-section-title {
    font-size: var(--font-size-xs);
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 var(--spacing-md);
    margin-bottom: var(--spacing-sm);
    margin-top: var(--spacing-lg);
}

.dashboard-nav-section-title:first-child {
    margin-top: 0;
}

.dashboard-sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    color: var(--gray-600);
    text-decoration: none;
    border-radius: var(--radius);
    margin-bottom: 4px;
    font-size: var(--font-size-sm);
    font-weight: 500;
    transition: var(--transition);
    position: relative;
}

.dashboard-sidebar-link:hover {
    background: var(--gray-100);
    color: var(--primary);
}

.dashboard-sidebar-link.active {
    background: #fff5f0;
    color: var(--primary);
    font-weight: 600;
}

.dashboard-sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: var(--primary);
    border-radius: 0 3px 3px 0;
}

.dashboard-sidebar-link i {
    width: 20px;
    margin-right: 12px;
    font-size: 16px;
}

.dashboard-sidebar-badge {
    margin-left: auto;
    background: var(--danger);
    color: var(--white);
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: var(--radius-full);
}

/* ========================================
       MAIN CONTENT AREA (Shared)
       ======================================== */
.dashboard-content {
    margin-top: var(--header-height);
    margin-left: var(--sidebar-width);
    padding: var(--content-padding);
    min-height: calc(100vh - var(--header-height));
}

/* ========================================
       ALERTS (Shared)
       ======================================== */
.dashboard-alert {
    border-radius: var(--radius-md);
    padding: var(--spacing-md) var(--spacing-lg);
    border: 1px solid transparent;
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.dashboard-alert i {
    font-size: 18px;
}

.dashboard-alert.success {
    background: var(--success-light);
    border-color: var(--success);
    color: var(--success-dark, #065f46);
}

.dashboard-alert.error {
    background: #fef2f2;
    border-color: var(--danger-light);
    color: var(--danger);
}

/* ========================================
       FOOTER (Shared)
       ======================================== */
.dashboard-footer {
    margin-top: var(--spacing-2xl);
    padding: var(--spacing-lg) 0;
    border-top: 1px solid var(--gray-300);
}

.dashboard-footer-content {
    display: flex;
    justify-content: space-between;
    font-size: var(--font-size-sm);
    color: var(--gray-500);
}

/* ========================================
       DROPDOWN (Shared)
       ======================================== */
.dashboard-dropdown-menu {
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-lg);
    padding: var(--spacing-sm);
}

.dashboard-dropdown-item {
    border-radius: var(--radius);
    padding: 8px 12px;
    font-size: var(--font-size-sm);
    transition: var(--transition);
}

.dashboard-dropdown-item:hover {
    background: var(--gray-100);
    color: var(--primary);
}

/* ========================================
       RESPONSIVE (Shared)
       ======================================== */
@media (max-width: 992px) {
    .dashboard-sidebar {
        left: calc(-1 * var(--sidebar-width));
    }

    .dashboard-sidebar.show {
        left: 0;
        box-shadow: var(--shadow-lg);
    }

    .dashboard-content {
        margin-left: 0;
    }

    .dashboard-mobile-toggle {
        display: block !important;
    }
}

.dashboard-mobile-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--gray-600);
    font-size: 20px;
    cursor: pointer;
    margin-right: var(--spacing-md);
}

/* ========================================
       UTILITY CLASSES (Shared)
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
    border-width: 0;
}
</style>

