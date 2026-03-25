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
@media (max-width: 992px) {
    .dashboard-sidebar {
        left: calc(-1 * var(--sidebar-width));
        box-shadow: none;
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

    .dashboard-breadcrumbs {
        display: none;
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
    padding: var(--spacing-sm);
}

.dashboard-mobile-toggle:hover {
    color: var(--primary);
}

@media (max-width: 768px) {
    .dashboard-header-container {
        padding: 0 var(--spacing-md);
    }

    .dashboard-brand-text small {
        display: none;
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

