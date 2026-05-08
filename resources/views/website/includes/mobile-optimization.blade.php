<style>
/* ========================================
   GLOBAL MOBILE OPTIMIZATION FRAMEWORK
   ======================================== */

/* Base Mobile Optimizations */
@media (max-width: 1024px) {
    /* Tablet optimizations */
    .container {
        max-width: 100%;
        padding-left: 20px;
        padding-right: 20px;
    }

    h1 { font-size: clamp(1.75rem, 4vw, 2.25rem); }
    h2 { font-size: clamp(1.5rem, 3vw, 1.875rem); }
    h3 { font-size: clamp(1.25rem, 2.5vw, 1.5rem); }
}

@media (max-width: 768px) {
    /* Mobile optimizations */
    .container {
        padding-left: 16px;
        padding-right: 16px;
    }

    /* Typography scaling */
    h1 { font-size: 1.75rem; }
    h2 { font-size: 1.5rem; }
    h3 { font-size: 1.25rem; }
    h4 { font-size: 1.125rem; }
    p { font-size: 0.95rem; line-height: 1.6; }

    /* Button optimizations */
    .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        min-height: 44px; /* Touch target minimum */
        border-radius: 8px;
    }

    /* Form optimizations */
    .form-control {
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
        border-radius: 8px;
        min-height: 44px;
    }

    /* Card optimizations */
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Grid optimizations */
    .row { margin-left: -8px; margin-right: -8px; }
    .col, [class*="col-"] { padding-left: 8px; padding-right: 8px; }

    /* Spacing optimizations */
    .section { padding: 3rem 0; }
    .py-4, .py-5 { padding-top: 2rem !important; padding-bottom: 2rem !important; }
}

@media (max-width: 576px) {
    /* Small mobile optimizations */
    .container {
        padding-left: 12px;
        padding-right: 12px;
    }

    /* Typography adjustments */
    h1 { font-size: 1.5rem; }
    h2 { font-size: 1.25rem; }
    h3 { font-size: 1.125rem; }

    /* Button adjustments */
    .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
        min-height: 40px;
        width: 100%;
        justify-content: center;
    }

    /* Form adjustments */
    .form-control {
        padding: 0.625rem;
        font-size: 16px;
        min-height: 40px;
    }

    /* Grid adjustments */
    .row { margin-left: -6px; margin-right: -6px; }
    .col, [class*="col-"] { padding-left: 6px; padding-right: 6px; }

    /* Section spacing */
    .section { padding: 2rem 0; }
}

@media (max-width: 480px) {
    /* Extra small mobile optimizations */
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }

    /* Typography for very small screens */
    h1 { font-size: 1.375rem; }
    h2 { font-size: 1.125rem; }
    h3 { font-size: 1rem; }

    /* Touch targets */
    .btn, .form-control {
        min-height: 44px; /* iOS minimum touch target */
    }

    /* Compact spacing */
    .section { padding: 1.5rem 0; }
    .mb-3, .mb-4 { margin-bottom: 1rem !important; }
    .mt-3, .mt-4 { margin-top: 1rem !important; }
}

/* Touch-friendly interactions */
@media (hover: none) and (pointer: coarse) {
    /* Remove hover effects on touch devices */
    .btn:hover,
    .card:hover,
    .nav-link:hover {
        transform: none !important;
    }

    /* Ensure touch targets are adequate */
    button, .btn, a, input, select, textarea {
        min-height: 44px;
        min-width: 44px;
    }
}

/* Fluid scaling utilities */
@media (max-width: 768px) {
    .fluid-text {
        font-size: clamp(0.875rem, 2.5vw, 1rem);
    }

    .fluid-heading {
        font-size: clamp(1.25rem, 5vw, 1.75rem);
    }

    .fluid-title {
        font-size: clamp(1.5rem, 6vw, 2rem);
    }
}

/* Mobile-first grid improvements */
@media (max-width: 768px) {
    .mobile-stack {
        flex-direction: column !important;
    }

    .mobile-center {
        text-align: center !important;
    }

    .mobile-full-width {
        width: 100% !important;
    }

    .mobile-hide {
        display: none !important;
    }
}

/* Image optimizations for mobile */
@media (max-width: 768px) {
    img {
        max-width: 100%;
        height: auto;
    }

    .mobile-responsive-img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
}

/* Navigation improvements */
@media (max-width: 768px) {
    .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }

    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
}

/* Modal optimizations */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100vw - 1rem);
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-header,
    .modal-footer {
        padding: 1rem 1.25rem;
    }

    .modal-body {
        padding: 1rem 1.25rem;
    }
}

/* Table responsiveness */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .table-responsive table {
        font-size: 0.875rem;
    }

    .table-responsive thead th {
        font-size: 0.8rem;
        font-weight: 600;
    }
}

/* Alert optimizations */
@media (max-width: 576px) {
    .alert {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
    }
}

/* Badge optimizations */
@media (max-width: 576px) {
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.625rem;
        border-radius: 4px;
    }
}

/* Progress bar optimizations */
@media (max-width: 576px) {
    .progress {
        height: 8px;
        border-radius: 4px;
    }
}

/* Carousel optimizations */
@media (max-width: 768px) {
    .carousel-indicators {
        bottom: -40px;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }
}

/* Accessibility improvements for mobile */
@media (max-width: 768px) {
    /* Ensure focus indicators are visible */
    button:focus,
    .btn:focus,
    input:focus,
    select:focus,
    textarea:focus {
        outline: 2px solid #007bff;
        outline-offset: 2px;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    /* Improve readability */
    body {
        font-size: 16px;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Better contrast for small screens */
    .text-muted {
        color: #6c757d !important;
    }

    /* Ensure sufficient color contrast */
    .text-secondary {
        color: #6c757d !important;
    }

    /* Improve tap targets */
    a, button, .btn, [role="button"] {
        touch-action: manipulation;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .btn-primary {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
    }

    .card {
        border: 2px solid #000 !important;
    }
}

/* Reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }

    .carousel {
        scroll-behavior: auto;
    }
}

/* Dark mode support (if implemented) */
@media (prefers-color-scheme: dark) {
    .card {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.6) !important;
    }
}
</style>