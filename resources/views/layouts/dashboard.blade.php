{{--
    Unified Dashboard Layout
    =======================
    This is the master layout for all dashboard types (admin, staff, super-admin).
    It uses shared components for consistent styling across all dashboards.

    Features:
    - Unified CSS variables (single source of truth)
    - Shared header/taskbar component
    - Shared sidebar component with role-based menu
    - Consistent responsive behavior
    - Shared JavaScript for sidebar toggle

    Usage:
    @extends('layouts.dashboard')

    Role-specific menus are automatically generated based on user permissions.
--}}

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Dashboard - GameSuite'))</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Include CSS Variables (Single Source of Truth) --}}
    @include('components.layout.css-variables')

    {{-- Include Component Styles --}}
    @include('components.layout.styles')

    @stack('styles')
</head>

<body class="dashboard-body">
    {{-- Header / Taskbar --}}
    @include('components.layout.header')

    {{-- Sidebar --}}
    @include('components.layout.sidebar-unified', [
        'mode' => auth()->user()->hasAnyRole(['super-admin', 'admin', 'operations-admin']) ? 'accordion' : 'simple',
        'sidebarId' => 'dashboardSidebar'
    ])

    {{-- Mobile Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <main class="dashboard-content" id="mainContent">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="dashboard-alert success">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="dashboard-alert error">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="dashboard-alert error">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <div>
                <strong>{{ __('Please fix the following errors:') }}</strong>
                <ul class="mb-0 mt-2" style="padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="{{ __('Close') }}"></button>
        </div>
        @endif

        {{-- Trial Notification --}}
        @auth
            @include('components.trial-notification')
        @endauth

        {{-- Page Content --}}
        @yield('content')

        {{-- Footer --}}
        <footer class="dashboard-footer">
            <div class="dashboard-footer-content">
                <div>&copy; {{ date('Y') }} {{ __('GameSuite') }}. {{ __('All rights reserved.') }}</div>
            </div>
        </footer>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Shared JavaScript --}}
    <script>
    'use strict';

    // =====================
    // Sidebar Toggle
    // =====================
    function toggleSidebar() {
        const sidebar = document.getElementById('dashboardSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.querySelector('.dashboard-mobile-toggle');

        if (sidebar) {
            sidebar.classList.toggle('show');
            if (overlay) {
                overlay.classList.toggle('show');
                overlay.classList.toggle('active');
            }
            if (toggleBtn) {
                const isExpanded = sidebar.classList.contains('show');
                toggleBtn.setAttribute('aria-expanded', isExpanded);
            }
        }
    }

    // =====================
    // Accordion Functionality (Admin mode)
    // =====================
    let oneSectionOpen = localStorage.getItem('sidebarOneSectionOpen') !== 'false';

    function toggleAccordionMode() {
        const checkbox = document.getElementById('accordionMode');
        if (checkbox) {
            oneSectionOpen = checkbox.checked;
            localStorage.setItem('sidebarOneSectionOpen', oneSectionOpen);
        }
    }

    function toggleAccordion(accordionName) {
        const accordion = document.querySelector(`[data-accordion="${accordionName}"]`);
        if (!accordion) return;

        const isOpen = accordion.classList.contains('open');

        // If one-section-open mode is enabled, close all others first
        if (oneSectionOpen && !isOpen) {
            document.querySelectorAll('.sidebar-accordion.open').forEach(openAccordion => {
                openAccordion.classList.remove('open');
            });
        }

        // Toggle current accordion
        accordion.classList.toggle('open');

        // Save state to localStorage
        saveAccordionState();
    }

    function saveAccordionState() {
        const openAccordions = [];
        document.querySelectorAll('.sidebar-accordion.open').forEach(acc => {
            openAccordions.push(acc.dataset.accordion);
        });
        localStorage.setItem('sidebarOpenAccordions', JSON.stringify(openAccordions));
    }

    function loadAccordionState() {
        const savedState = localStorage.getItem('sidebarOpenAccordions');
        if (savedState) {
            const openAccordions = JSON.parse(savedState);
            openAccordions.forEach(name => {
                const accordion = document.querySelector(`[data-accordion="${name}"]`);
                if (accordion) {
                    accordion.classList.add('open');
                }
            });
        } else {
            // Default: open section that contains active link
            const activeLink = document.querySelector('.sidebar-link.active, .sidebar-nav-link.active');
            if (activeLink) {
                const content = activeLink.closest('.sidebar-accordion-content');
                if (content) {
                    content.parentElement.classList.add('open');
                }
            }
        }
    }

    // =====================
    // Auto-hide Alerts
    // =====================
    function initAlerts() {
        const alerts = document.querySelectorAll('.dashboard-alert');
        alerts.forEach(function(alert, index) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            }, 5000 + (index * 1000));
        });
    }

    // =====================
    // Initialize on DOM Ready
    // =====================
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize accordion state
        loadAccordionState();

        // Initialize alerts auto-hide
        initAlerts();

        // Mobile sidebar toggle
        const mobileToggle = document.querySelector('.dashboard-mobile-toggle');
        const sidebar = document.getElementById('dashboardSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function() {
                toggleSidebar();
            });

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show', 'active');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                });
            }

            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target) && !overlay?.contains(e.target)) {
                    sidebar.classList.remove('show');
                    if (overlay) {
                        overlay.classList.remove('show', 'active');
                    }
                    mobileToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close sidebar when pressing Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    if (overlay) {
                        overlay.classList.remove('show', 'active');
                    }
                    mobileToggle.setAttribute('aria-expanded', 'false');
                    mobileToggle.focus();
                }
            });
        }

        // Initialize accordion mode checkbox
        const accordionModeCheckbox = document.getElementById('accordionMode');
        if (accordionModeCheckbox) {
            accordionModeCheckbox.checked = oneSectionOpen;
        }
    });
    </script>

    @stack('scripts')
</body>

</html>

