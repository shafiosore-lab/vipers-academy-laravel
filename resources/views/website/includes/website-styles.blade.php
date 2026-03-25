<?php
/**
 * Unified Website Styles
 *
 * This file provides consistent CSS variables, typography, and utility classes
 * for all website views. Include this in pages that extend layouts.academy.
 *
 * Usage: @include('website::includes.website-styles')
 */
?>

<style>
/* ==================== UNIFIED CSS VARIABLES ==================== */
:root {
    /* Brand Colors */
    --primary: #ea1c4d;
    --primary-light: #f05a7a;
    --primary-dark: #c0173f;
    --secondary: #65c16e;
    --secondary-light: #8fd98f;
    --accent: #fbc761;
    --accent-light: #fdd56f;

    /* Semantic Colors */
    --success: #22c55e;
    --info: #0891b2;
    --warning: #f59e0b;
    --danger: #dc2626;

    /* Neutrals */
    --white: #ffffff;
    --gray-50: #fafafa;
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --gray-400: #ced4da;
    --gray-500: #adb5bd;
    --gray-600: #6c757d;
    --gray-700: #495057;
    --gray-800: #343a40;
    --gray-900: #212529;
    --black: #1a1a1a;

    /* Background Colors */
    --bg-primary: var(--white);
    --bg-secondary: var(--gray-100);
    --bg-light: var(--gray-50);
    --bg-dark: var(--gray-900);

    /* Shadows */
    --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
    --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);

    /* Spacing */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    --space-3xl: 4rem;

    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --radius-full: 9999px;

    /* Transitions */
    --transition-fast: 0.15s ease;
    --transition-normal: 0.3s ease;
    --transition-slow: 0.5s ease;

    /* Typography Scale */
    --font-xs: 0.75rem;
    --font-sm: 0.875rem;
    --font-base: 1rem;
    --font-lg: 1.125rem;
    --font-xl: 1.25rem;
    --font-2xl: 1.5rem;
    --font-3xl: 1.875rem;
    --font-4xl: 2.25rem;
    --font-5xl: 3rem;

    /* Line Heights */
    --leading-tight: 1.25;
    --leading-normal: 1.5;
    --leading-relaxed: 1.625;
}

/* ==================== TYPOGRAPHY ==================== */
.website-text {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: var(--font-base);
    line-height: var(--leading-normal);
    color: var(--gray-900);
}

/* Headings */
.website-h1, .website-h2, .website-h3, .website-h4, .website-h5, .website-h6 {
    font-weight: 700;
    line-height: var(--leading-tight);
    color: var(--gray-900);
    margin-bottom: var(--space-md);
}

.website-h1 { font-size: var(--font-4xl); }
.website-h2 { font-size: var(--font-3xl); }
.website-h3 { font-size: var(--font-2xl); }
.website-h4 { font-size: var(--font-xl); }
.website-h5 { font-size: var(--font-lg); }
.website-h6 { font-size: var(--font-base); }

/* Section Titles */
.section-title {
    font-size: var(--font-2xl);
    font-weight: 700;
    line-height: 1.3;
    color: var(--gray-900);
    margin-bottom: var(--space-sm);
}

.section-subtitle {
    font-size: var(--font-base);
    color: var(--gray-600);
    line-height: var(--leading-relaxed);
}

/* ==================== HERO SECTION ==================== */
.hero-section {
    position: relative;
    min-height: 70vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    color: var(--white);
}

.hero-title {
    font-size: var(--font-4xl);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--space-md);
    color: var(--white);
}

.hero-subtitle {
    font-size: var(--font-lg);
    line-height: var(--leading-relaxed);
    opacity: 0.95;
    margin-bottom: var(--space-lg);
    max-width: 700px;
}

/* ==================== CARDS ==================== */
.website-card {
    background: var(--white);
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-normal);
}

.website-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.website-card-body {
    padding: var(--space-lg);
}

.website-card-title {
    font-size: var(--font-lg);
    font-weight: 700;
    margin-bottom: var(--space-sm);
    color: var(--gray-900);
}

.website-card-text {
    font-size: var(--font-sm);
    color: var(--gray-600);
    line-height: var(--leading-relaxed);
}

/* ==================== BUTTONS ==================== */
.website-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-sm) var(--space-lg);
    font-size: var(--font-sm);
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}

.website-btn-primary {
    background: var(--primary);
    color: var(--white);
}

.website-btn-primary:hover {
    background: var(--primary-dark);
    color: var(--white);
}

.website-btn-secondary {
    background: var(--secondary);
    color: var(--white);
}

.website-btn-secondary:hover {
    background: #1a7f42;
    color: var(--white);
}

.website-btn-outline {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.website-btn-outline:hover {
    background: var(--primary);
    color: var(--white);
}

.website-btn-light {
    background: var(--white);
    color: var(--gray-900);
}

.website-btn-light:hover {
    background: var(--gray-100);
}

/* ==================== BADGES ==================== */
.website-badge {
    display: inline-block;
    padding: var(--space-xs) var(--space-sm);
    font-size: var(--font-xs);
    font-weight: 600;
    border-radius: var(--radius-full);
}

.website-badge-primary {
    background: var(--primary);
    color: var(--white);
}

.website-badge-secondary {
    background: var(--secondary);
    color: var(--white);
}

.website-badge-success {
    background: rgba(34, 197, 94, 0.15);
    color: var(--success);
}

.website-badge-warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning);
}

.website-badge-info {
    background: rgba(8, 145, 178, 0.15);
    color: var(--info);
}

/* ==================== FORM ELEMENTS ==================== */
.website-form-group {
    margin-bottom: var(--space-md);
}

.website-form-label {
    display: block;
    font-size: var(--font-sm);
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: var(--space-xs);
}

.website-form-control {
    width: 100%;
    padding: var(--space-sm) var(--space-md);
    font-size: var(--font-base);
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-md);
    transition: border-color var(--transition-fast);
    background: var(--white);
}

.website-form-control:focus {
    outline: none;
    border-color: var(--primary);
}

.website-form-control::placeholder {
    color: var(--gray-500);
}

/* ==================== ICONS ==================== */
.website-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.website-icon-primary {
    color: var(--primary);
}

.website-icon-secondary {
    color: var(--secondary);
}

.website-icon-success {
    color: var(--success);
}

.website-icon-warning {
    color: var(--warning);
}

.website-icon-info {
    color: var(--info);
}

/* ==================== BACKGROUND UTILITIES ==================== */
.bg-primary { background-color: var(--primary) !important; }
.bg-secondary { background-color: var(--secondary) !important; }
.bg-success { background-color: var(--success) !important; }
.bg-info { background-color: var(--info) !important; }
.bg-warning { background-color: var(--warning) !important; }
.bg-danger { background-color: var(--danger) !important; }
.bg-white { background-color: var(--white) !important; }
.bg-light { background-color: var(--gray-100) !important; }
.bg-dark { background-color: var(--gray-900) !important; }

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, var(--secondary) 0%, #198754 100%);
}

/* ==================== TEXT COLORS ==================== */
.text-primary { color: var(--primary) !important; }
.text-secondary { color: var(--secondary) !important; }
.text-success { color: var(--success) !important; }
.text-info { color: var(--info) !important; }
.text-warning { color: var(--warning) !important; }
.text-danger { color: var(--danger) !important; }
.text-white { color: var(--white) !important; }
.text-muted { color: var(--gray-600) !important; }
.text-dark { color: var(--gray-900) !important; }

/* ==================== SPACING UTILITIES ==================== */
.m-0 { margin: 0 !important; }
.mt-0 { margin-top: 0 !important; }
.mb-0 { margin-bottom: 0 !important; }
.my-0 { margin-top: 0 !important; margin-bottom: 0 !important; }

.mt-1 { margin-top: var(--space-xs) !important; }
.mt-2 { margin-top: var(--space-sm) !important; }
.mt-3 { margin-top: var(--space-md) !important; }
.mt-4 { margin-top: var(--space-lg) !important; }
.mt-5 { margin-top: var(--space-xl) !important; }

.mb-1 { margin-bottom: var(--space-xs) !important; }
.mb-2 { margin-bottom: var(--space-sm) !important; }
.mb-3 { margin-bottom: var(--space-md) !important; }
.mb-4 { margin-bottom: var(--space-lg) !important; }
.mb-5 { margin-bottom: var(--space-xl) !important; }

.p-0 { padding: 0 !important; }
.p-1 { padding: var(--space-xs) !important; }
.p-2 { padding: var(--space-sm) !important; }
.p-3 { padding: var(--space-md) !important; }
.p-4 { padding: var(--space-lg) !important; }
.p-5 { padding: var(--space-xl) !important; }

.py-5 { padding-top: var(--space-2xl) !important; padding-bottom: var(--space-2xl) !important; }
.px-4 { padding-left: var(--space-lg) !important; padding-right: var(--space-lg) !important; }

/* ==================== BORDER RADIUS ==================== */
.rounded-sm { border-radius: var(--radius-sm) !important; }
.rounded-md { border-radius: var(--radius-md) !important; }
.rounded-lg { border-radius: var(--radius-lg) !important; }
.rounded-xl { border-radius: var(--radius-xl) !important; }
.rounded-full { border-radius: var(--radius-full) !important; }

/* ==================== SHADOWS ==================== */
.shadow-sm { box-shadow: var(--shadow-sm) !important; }
.shadow-md { box-shadow: var(--shadow-md) !important; }
.shadow-lg { box-shadow: var(--shadow-lg) !important; }
.shadow-xl { box-shadow: var(--shadow-xl) !important; }

/* ==================== TRANSITIONS ==================== */
.transition-all {
    transition: all var(--transition-normal);
}

.hover-lift:hover {
    transform: translateY(-5px);
}

/* ==================== RESPONSIVE STYLES ==================== */

/* Mobile (< 576px) */
@media (max-width: 575.98px) {
    .hero-section {
        min-height: 60vh;
        padding: var(--space-2xl) 0;
    }

    .hero-title {
        font-size: var(--font-2xl);
    }

    .hero-subtitle {
        font-size: var(--font-base);
    }

    .section-title {
        font-size: var(--font-xl);
    }

    .section-subtitle {
        font-size: var(--font-sm);
    }

    .website-card-body {
        padding: var(--space-md);
    }

    .py-5 {
        padding-top: var(--space-xl) !important;
        padding-bottom: var(--space-xl) !important;
    }
}

/* Tablet (576px - 991px) */
@media (min-width: 576px) and (max-width: 991.98px) {
    .hero-section {
        min-height: 65vh;
    }

    .hero-title {
        font-size: var(--font-3xl);
    }

    .section-title {
        font-size: var(--font-2xl);
    }
}

/* Desktop (>= 992px) */
@media (min-width: 992px) {
    .hero-section {
        min-height: 70vh;
    }

    .hero-title {
        font-size: var(--font-4xl);
    }

    .section-title {
        font-size: var(--font-3xl);
    }
}

/* Large Desktop (>= 1200px) */
@media (min-width: 1200px) {
    .hero-title {
        font-size: var(--font-5xl);
    }
}

/* ==================== ANIMATIONS ==================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
}

/* ==================== CUSTOM SCROLLBAR ==================== */
.website-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.website-scrollbar::-webkit-scrollbar-track {
    background: var(--gray-200);
}

.website-scrollbar::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: var(--radius-full);
}

.website-scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
}
</style>
