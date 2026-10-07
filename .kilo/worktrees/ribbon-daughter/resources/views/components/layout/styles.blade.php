{{--
    Component Styles
    ================
    This file contains additional styles needed for the unified layout components.
    These styles complement the CSS variables defined in css-variables.blade.php

    This file is automatically included in layouts.dashboard.blade.php
--}}

<style>
/* ========================================
       HEADER/TASKBAR SPECIFIC STYLES
       ======================================== */
.dashboard-header-container {
    height: 100%;
    display: flex;
    align-items: center;
    padding: 0 var(--spacing-xl);
    gap: var(--spacing-md);
}

.dashboard-breadcrumbs {
    display: flex;
    align-items: center;
    margin-left: var(--spacing-lg);
}

.dashboard-breadcrumbs .breadcrumb {
    margin: 0;
    padding: 0;
    background: transparent;
    font-size: var(--font-size-sm);
}

.dashboard-breadcrumbs .breadcrumb-item {
    color: var(--gray-600);
}

.dashboard-breadcrumbs .breadcrumb-item a {
    color: var(--gray-600);
    text-decoration: none;
}

.dashboard-breadcrumbs .breadcrumb-item a:hover {
    color: var(--primary);
}

.dashboard-breadcrumbs .breadcrumb-item.active {
    color: var(--gray-900);
}

.dashboard-breadcrumbs .breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: var(--gray-400);
    padding: 0 var(--spacing-sm);
}

.dashboard-header-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-left: auto;
}

.dashboard-user-name {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-900);
}

/* ========================================
       SIDEBAR SPECIFIC STYLES
       ======================================== */
.dashboard-sidebar-nav {
    padding: var(--spacing-lg) var(--spacing-md);
}

/* Accordion Settings */
.accordion-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    background: var(--gray-100);
    border-bottom: 1px solid var(--gray-300);
    border-radius: var(--radius);
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
}

.accordion-toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.accordion-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: var(--transition);
    border-radius: var(--radius-full);
}

.accordion-toggle-slider:before {
    position: absolute;
    content: "";
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

.accordion-toggle-switch input:checked + .accordion-toggle-slider:before {
    transform: translateX(16px);
}

/* Accordion Styles */
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

.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 6px var(--spacing-md);
    color: var(--gray-600);
    text-decoration: none;
    font-size: var(--font-size-sm);
    font-weight: 500;
    transition: var(--transition);
    border-left: 3px solid transparent;
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

/* Simple Sidebar Styles */
.sidebar-nav-link {
    display: flex;
    align-items: center;
    padding: 10px var(--spacing-md);
    color: var(--gray-600);
    text-decoration: none;
    border-radius: var(--radius);
    margin-bottom: 4px;
    font-size: var(--font-size-sm);
    font-weight: 500;
    transition: var(--transition);
    position: relative;
}

.sidebar-nav-link:hover {
    background: var(--gray-100);
    color: var(--primary);
}

.sidebar-nav-link.active {
    background: #fff5f0;
    color: var(--primary);
    font-weight: 600;
}

.sidebar-nav-link.active::before {
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

.sidebar-nav-link i {
    width: 20px;
    margin-right: var(--spacing-md);
    font-size: 16px;
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

/* ========================================
       MAIN CONTENT AREA
       ======================================== */
#mainContent {
    padding: var(--content-padding);
}

/* ========================================
   RESPONSIVE STYLES
   ======================================== */

/* Extra Small Devices (Phones) */
@media (max-width: 575.98px) {
    .dashboard-header-container {
        padding: 0 var(--spacing-sm);
        gap: var(--spacing-sm);
    }

    .dashboard-brand {
        min-width: 0;
    }

    .dashboard-logo-text {
        font-size: 1.1rem;
    }

    .dashboard-mobile-toggle {
        padding: var(--spacing-xs);
        font-size: 18px;
        margin-right: var(--spacing-sm);
    }

    .dashboard-user-name {
        display: none;
    }

    .dashboard-header-actions {
        gap: var(--spacing-xs);
    }

    .dashboard-action-btn {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }

    .dashboard-user-menu {
        padding: 4px 8px;
    }

    .dashboard-user-avatar {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }

    /* Breadcrumbs hidden on extra small screens */
    .dashboard-breadcrumbs {
        display: none !important;
    }

    /* Adjust dropdown menu for mobile */
    .dashboard-dropdown-menu {
        min-width: 180px;
        padding: var(--spacing-xs);
    }

    .dashboard-dropdown-item {
        padding: 6px var(--spacing-sm);
        font-size: 0.8rem;
    }
}

/* Small Devices (Landscape Phones) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .dashboard-header-container {
        padding: 0 var(--spacing-md);
    }

    .dashboard-logo-text {
        font-size: 1.15rem;
    }
}

/* Medium Devices (Tablets) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .dashboard-header-container {
        padding: 0 var(--spacing-lg);
    }

    .dashboard-breadcrumbs {
        display: none;
    }
}

/* Large Devices (Desktops) */
@media (min-width: 992px) {
    .dashboard-sidebar {
        left: 0;
    }

    .dashboard-content {
        margin-left: var(--sidebar-width);
    }

    .dashboard-mobile-toggle {
        display: none;
    }

    .dashboard-breadcrumbs {
        display: flex;
    }
}

/* Extra Large Devices (Large Desktops) */
@media (min-width: 1200px) {
    :root {
        --header-height: 64px;
    }
}

/* XXL screens */
@media (min-width: 1400px) {
    .dashboard-header-container {
        padding: 0 var(--spacing-2xl);
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
    z-index: calc(var(--z-fixed) - 1);
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
   MOBILE SIDEBAR ENHANCEMENTS
   ======================================== */
@media (max-width: 992px) {
    .dashboard-sidebar {
        left: calc(-1 * var(--sidebar-width));
        box-shadow: none;
        z-index: calc(var(--z-fixed) + 100);
        transition: left var(--transition-slow), box-shadow var(--transition-slow);
    }

    .dashboard-sidebar.show {
        left: 0;
        box-shadow: var(--shadow-xl);
    }

    .dashboard-sidebar.show + .sidebar-overlay {
        opacity: 1;
        visibility: visible;
    }

    .dashboard-content {
        margin-left: 0;
    }

    .dashboard-mobile-toggle {
        display: block !important;
    }

    /* Ensure sidebar has close button on mobile */
    .dashboard-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: transparent;
        z-index: -1;
    }
}

/* Sidebar close button (visible only on mobile) */
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

    .dashboard-table th {
        font-weight: 600;
        background-color: var(--gray-100);
        border-bottom: 2px solid var(--gray-300);
    }
}

/* ========================================
   CARD RESPONSIVENESS
   ======================================== */
.dashboard-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--spacing-md);
}

@media (max-width: 768px) {
    .dashboard-card-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
}

@media (min-width: 576px) and (max-width: 991px) {
    .dashboard-card-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.dashboard-card {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
    padding: var(--spacing-lg);
    transition: transform var(--transition), box-shadow var(--transition);
    height: 100%;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

@media (max-width: 768px) {
    .dashboard-card {
        padding: var(--spacing-md);
    }
}

.dashboard-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--gray-200);
}

.dashboard-card-title {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

@media (max-width: 768px) {
    .dashboard-card-title {
        font-size: var(--font-size-base);
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

.btn-outline-primary {
    color: var(--primary);
    border-color: var(--primary);
    background: transparent;
}

.btn-outline-primary:hover {
    background-color: var(--primary);
    color: var(--white);
}

.btn-lg {
    padding: var(--spacing-md) var(--spacing-xl);
    font-size: var(--font-size-base);
}

@media (max-width: 768px) {
    .btn-lg {
        width: 100%;
        max-width: 100%;
    }
}

/* ========================================
   GRID SYSTEM ENHANCEMENTS
   ======================================== */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-left: calc(var(--spacing-sm) * -1);
    margin-right: calc(var(--spacing-sm) * -1);
}

.col, [class*="col-"] {
    flex: 1 0 0%;
    padding-left: var(--spacing-sm);
    padding-right: var(--spacing-sm);
}

@media (max-width: 768px) {
    .row {
        margin-left: calc(var(--spacing-xs) * -1);
        margin-right: calc(var(--spacing-xs) * -1);
    }

    .col, [class*="col-"] {
        padding-left: var(--spacing-xs);
        padding-right: var(--spacing-xs);
    }

    .col-12.mb-3,
    .col-12.mb-4,
    .col-md-6.mb-3,
    .col-lg-4.mb-3 {
        margin-bottom: var(--spacing-md) !important;
    }
}

/* ========================================
   UTILITY CLASSES FOR RESPONSIVE DESIGN
   ======================================== */

/* Mobile-only utilities */
@media (max-width: 767.98px) {
    .mobile-hide { display: none !important; }
    .mobile-show { display: block !important; }
    .mobile-flex { display: flex !important; }
    .mobile-block { display: block !important; }
}

/* Desktop-only utilities */
@media (min-width: 768px) {
    .desktop-hide { display: none !important; }
    .desktop-show { display: block !important; }
}

/* Text alignment */
.text-center-mobile { text-align: center; }
@media (min-width: 768px) {
    .text-center-mobile { text-align: left; }
}

/* Spacing utilities */
.mb-mobile-0 { margin-bottom: 0 !important; }
.mb-mobile-2 { margin-bottom: var(--spacing-sm) !important; }
.mb-mobile-3 { margin-bottom: var(--spacing-md) !important; }
.mb-mobile-4 { margin-bottom: var(--spacing-lg) !important; }

/* Flex direction */
.flex-column-mobile {
    flex-direction: column;
}
@media (min-width: 768px) {
    .flex-column-mobile { flex-direction: row; }
}

/* Full width on mobile */
.w-100-mobile { width: 100%; }
@media (min-width: 768px) {
    .w-100-mobile { width: auto; }
}

/* ========================================
   SAFE AREA INSETS (For notched devices)
   ======================================== */
@supports (padding: max(0px)) {
    .dashboard-header {
        padding-left: max(var(--spacing-xl), env(safe-area-inset-left));
        padding-right: max(var(--spacing-xl), env(safe-area-inset-right));
    }

    .dashboard-sidebar {
        padding-left: max(var(--spacing-md), env(safe-area-inset-left));
        padding-right: max(var(--spacing-md), env(safe-area-inset-right));
    }

    .dashboard-content {
        padding-left: max(var(--content-padding), env(safe-area-inset-left));
        padding-right: max(var(--content-padding), env(safe-area-inset-right));
    }

    .dashboard-mobile-toggle {
        margin-left: env(safe-area-inset-left);
        margin-right: env(safe-area-inset-right);
    }
}

/* ========================================
   REDUCED MOTION PREFERENCES
   ======================================== */
@media (prefers-reduced-motion: reduce) {
    .dashboard-sidebar,
    .dashboard-mobile-toggle,
    .sidebar-overlay,
    .dropdown-menu,
    .dashboard-card,
    .btn {
        transition: none !important;
        animation: none !important;
    }

    .sidebar-accordion-content {
        transition: none !important;
    }
}

/* ========================================
   HIGH CONTRAST MODE
   ======================================== */
@media (prefers-contrast: high) {
    .dashboard-header {
        border-bottom: 2px solid var(--gray-900);
    }

    .dashboard-sidebar {
        border-right: 2px solid var(--gray-900);
    }

    .dashboard-card {
        border: 2px solid var(--gray-900);
    }

    .btn-primary {
        border: 2px solid var(--white);
    }
}

/* ========================================
   TOUCH DEVICE OPTIMIZATIONS
   ======================================== */
@media (hover: none) and (pointer: coarse) {
    /* Increase touch target sizes */
    .dashboard-action-btn {
        min-width: 44px;
        min-height: 44px;
    }

    .dashboard-user-menu {
        min-height: 44px;
        align-items: center;
    }

    .sidebar-nav-link,
    .sidebar-link {
        min-height: 44px;
        padding: var(--spacing-sm) var(--spacing-md);
    }

    .btn {
        min-height: 44px;
    }

    /* Remove hover effects on touch devices */
    .dashboard-card:hover {
        transform: none;
    }

    .sidebar-link:hover,
    .sidebar-nav-link:hover {
        background: transparent;
    }

    /* Improve tap highlight color */
    a, button {
        -webkit-tap-highlight-color: rgba(234, 28, 77, 0.2);
    }
}

/* ========================================
   PRINT STYLES
   ======================================== */
@media print {
    .dashboard-header,
    .dashboard-sidebar,
    .sidebar-overlay,
    .dashboard-mobile-toggle {
        display: none !important;
    }

    .dashboard-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }

    body {
        background: white;
    }
}

/* ========================================
   LANDSCAPE ORIENTATION FIXES
   ======================================== */
@media (max-width: 767px) and (orientation: landscape) {
    .dashboard-header-container {
        padding: 0 var(--spacing-sm);
    }

    .dashboard-logo-text {
        font-size: 1rem;
    }

    .dashboard-card-title {
        font-size: var(--font-size-sm);
    }

    /* Prevent overflow on small landscape screens */
    .hero-buttons {
        flex-wrap: wrap;
    }
}

/* ========================================
   FOCUS VISIBLE FOR ACCESSIBILITY
   ======================================== */
.dashboard-mobile-toggle:focus-visible,
.sidebar-close-btn:focus-visible,
.sidebar-nav-link:focus-visible,
.btn:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(234, 28, 77, 0.2);
}

/* ========================================
   SCROLLBAR STYLING FOR WEBKIT
   ======================================== */
@media (max-width: 992px) {
    .dashboard-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .dashboard-sidebar::-webkit-scrollbar-track {
        background: var(--gray-100);
    }

    .dashboard-sidebar::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 3px;
    }

    .dashboard-sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }
}

/* ========================================
       DROPDOWN MENU STYLES
       ======================================== */
.dashboard-dropdown-menu {
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-lg);
    padding: var(--spacing-sm);
    min-width: 200px;
}

.dashboard-dropdown-item {
    border-radius: var(--radius);
    padding: 8px var(--spacing-md);
    font-size: var(--font-size-sm);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.dashboard-dropdown-item:hover {
    background: var(--gray-100);
    color: var(--primary);
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
.text-info { color: var(--info) !important; }
.text-muted { color: var(--gray-500) !important; }

.bg-primary { background-color: var(--primary) !important; }
.bg-secondary { background-color: var(--secondary) !important; }
.bg-success { background-color: var(--success) !important; }
.bg-danger { background-color: var(--danger) !important; }
.bg-warning { background-color: var(--warning) !important; }
.bg-info { background-color: var(--info) !important; }
.bg-light { background-color: var(--gray-100) !important; }

/* ========================================
       TABLE STYLES
       ======================================== */
.dashboard-table {
    table-layout: fixed;
    width: 100%;
    margin-bottom: 1rem;
}

.dashboard-table th,
.dashboard-table td {
    padding: 0.75rem;
    vertical-align: middle;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dashboard-table th {
    font-weight: 600;
    background-color: var(--gray-100);
    border-bottom: 2px solid var(--gray-300);
}

.dashboard-table td.wrap,
.dashboard-table th.wrap {
    white-space: normal;
    word-wrap: break-word;
}

/* ========================================
       CARD STYLES
       ======================================== */
.dashboard-card {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
    padding: var(--spacing-lg);
}

.dashboard-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--gray-200);
}

.dashboard-card-title {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

/* ========================================
       BUTTON STYLES
       ======================================== */
.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
}

.btn-outline-primary {
    color: var(--primary);
    border-color: var(--primary);
}

.btn-outline-primary:hover {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>

