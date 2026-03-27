{{--
    Standardized Dashboard Grid System
    ================================
    A responsive grid system for consistent layout density across all dashboards.
    Provides standardized spacing, breakpoints, and responsive behavior.

    Usage:
    @component('components.dashboard-grid', ['columns' => 3, 'gap' => 'lg', 'variant' => 'default'])
        <div>Grid item 1</div>
        <div>Grid item 2</div>
        <div>Grid item 3</div>
    @endcomponent

    Or with slots:
    <x-dashboard-grid columns="3" gap="lg" variant="default">
        <div>Grid item 1</div>
        <div>Grid item 2</div>
        <div>Grid item 3</div>
    </x-dashboard-grid>
--}}

@props([
    'columns' => 2, // Number of columns (1-6)
    'gap' => 'lg', // Gap size: xs, sm, md, lg, xl
    'variant' => 'default', // default, compact, minimal
    'align' => 'start', // start, center, end, stretch
    'justify' => 'start', // start, center, end, between, around, evenly
    'responsive' => true, // Enable responsive breakpoints
    'breakpoints' => null, // Custom breakpoint configuration
])

@php
    // Determine CSS classes based on props
    $gridClasses = [
        'dashboard-grid',
        'grid-columns-' . $columns,
        'grid-gap-' . $gap,
        'grid-variant-' . $variant,
        'grid-align-' . $align,
        'grid-justify-' . $justify,
        $responsive ? 'grid-responsive' : 'grid-static',
    ];

    // Default breakpoints if none provided
    $defaultBreakpoints = [
        'lg' => $columns,
        'md' => min(2, $columns),
        'sm' => min(1, $columns),
        'xs' => 1,
    ];

    $breakpoints = $breakpoints ?? $defaultBreakpoints;
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $gridClasses)]) }}>
    {{ $slot }}
</div>

@push('styles')
<style>
/* ========================================
       STANDARDIZED DASHBOARD GRID SYSTEM
       ======================================== */

/* Base Grid Styles */
.dashboard-grid {
    display: grid;
    gap: var(--grid-gap);
    width: 100%;
}

/* Column Variants */
.grid-columns-1 {
    grid-template-columns: 1fr;
}

.grid-columns-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-columns-3 {
    grid-template-columns: repeat(3, 1fr);
}

.grid-columns-4 {
    grid-template-columns: repeat(4, 1fr);
}

.grid-columns-5 {
    grid-template-columns: repeat(5, 1fr);
}

.grid-columns-6 {
    grid-template-columns: repeat(6, 1fr);
}

/* Gap Variants */
.grid-gap-xs {
    gap: var(--spacing-xs);
}

.grid-gap-sm {
    gap: var(--spacing-sm);
}

.grid-gap-md {
    gap: var(--spacing-md);
}

.grid-gap-lg {
    gap: var(--spacing-lg);
}

.grid-gap-xl {
    gap: var(--spacing-xl);
}

/* Variant Styles */
.grid-variant-default {
    /* Default grid with standard spacing */
}

.grid-variant-compact {
    gap: var(--spacing-sm);
}

.grid-variant-minimal {
    gap: var(--spacing-xs);
}

/* Alignment Variants */
.grid-align-start {
    align-items: start;
}

.grid-align-center {
    align-items: center;
}

.grid-align-end {
    align-items: end;
}

.grid-align-stretch {
    align-items: stretch;
}

/* Justify Variants */
.grid-justify-start {
    justify-items: start;
}

.grid-justify-center {
    justify-items: center;
}

.grid-justify-end {
    justify-items: end;
}

.grid-justify-between {
    justify-items: stretch;
}

.grid-justify-around {
    justify-content: space-around;
}

.grid-justify-evenly {
    justify-content: space-evenly;
}

/* Responsive Breakpoints */
.grid-responsive.grid-columns-1 {
    /* Single column layout */
}

.grid-responsive.grid-columns-2 {
    /* Two column layout */
}

.grid-responsive.grid-columns-3 {
    /* Three column layout */
}

.grid-responsive.grid-columns-4 {
    /* Four column layout */
}

.grid-responsive.grid-columns-5 {
    /* Five column layout */
}

.grid-responsive.grid-columns-6 {
    /* Six column layout */
}

/* Standard Responsive Breakpoints */
@media (max-width: 1200px) {
    /* Extra large screens */
    .grid-responsive.grid-columns-6 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-responsive.grid-columns-5 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-responsive.grid-columns-4 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-responsive.grid-columns-3 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 992px) {
    /* Large screens */
    .grid-responsive.grid-columns-6 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-responsive.grid-columns-5 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-responsive.grid-columns-4 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-3 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    /* Medium screens */
    .grid-responsive.grid-columns-6 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-responsive.grid-columns-5 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-4 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-3 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    /* Small screens */
    .grid-responsive.grid-columns-6 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-5 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-responsive.grid-columns-4 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-3 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .grid-responsive.grid-columns-6 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-5 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-4 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-3 {
        grid-template-columns: 1fr;
    }

    .grid-responsive.grid-columns-2 {
        grid-template-columns: 1fr;
    }
}

/* Utility Grid Classes */
.grid-2-cols {
    grid-template-columns: repeat(2, 1fr);
}

.grid-3-cols {
    grid-template-columns: repeat(3, 1fr);
}

.grid-4-cols {
    grid-template-columns: repeat(4, 1fr);
}

.grid-5-cols {
    grid-template-columns: repeat(5, 1fr);
}

.grid-6-cols {
    grid-template-columns: repeat(6, 1fr);
}

.grid-auto-cols {
    grid-auto-columns: 1fr;
}

.grid-auto-rows {
    grid-auto-rows: 1fr;
}

.grid-auto-flow-row {
    grid-auto-flow: row;
}

.grid-auto-flow-column {
    grid-auto-flow: column;
}

.grid-auto-flow-dense {
    grid-auto-flow: dense;
}

/* Gap Utility Classes */
.grid-gap-0 {
    gap: 0;
}

.grid-gap-1 {
    gap: var(--spacing-xs);
}

.grid-gap-2 {
    gap: var(--spacing-sm);
}

.grid-gap-3 {
    gap: var(--spacing-md);
}

.grid-gap-4 {
    gap: var(--spacing-lg);
}

.grid-gap-5 {
    gap: var(--spacing-xl);
}

.grid-gap-6 {
    gap: var(--spacing-2xl);
}

.grid-gap-8 {
    gap: var(--spacing-3xl);
}

.grid-gap-10 {
    gap: var(--spacing-4xl);
}

/* Column Span Classes */
.col-span-1 {
    grid-column: span 1;
}

.col-span-2 {
    grid-column: span 2;
}

.col-span-3 {
    grid-column: span 3;
}

.col-span-4 {
    grid-column: span 4;
}

.col-span-5 {
    grid-column: span 5;
}

.col-span-6 {
    grid-column: span 6;
}

.col-span-7 {
    grid-column: span 7;
}

.col-span-8 {
    grid-column: span 8;
}

.col-span-9 {
    grid-column: span 9;
}

.col-span-10 {
    grid-column: span 10;
}

.col-span-11 {
    grid-column: span 11;
}

.col-span-12 {
    grid-column: span 12;
}

/* Row Span Classes */
.row-span-1 {
    grid-row: span 1;
}

.row-span-2 {
    grid-row: span 2;
}

.row-span-3 {
    grid-row: span 3;
}

.row-span-4 {
    grid-row: span 4;
}

.row-span-5 {
    grid-row: span 5;
}

.row-span-6 {
    grid-row: span 6;
}

/* Alignment Utility Classes */
.grid-items-start {
    align-items: start;
}

.grid-items-center {
    align-items: center;
}

.grid-items-end {
    align-items: end;
}

.grid-items-stretch {
    align-items: stretch;
}

.grid-justify-start {
    justify-items: start;
}

.grid-justify-center {
    justify-items: center;
}

.grid-justify-end {
    justify-items: end;
}

.grid-justify-stretch {
    justify-items: stretch;
}

.grid-content-start {
    align-content: start;
}

.grid-content-center {
    align-content: center;
}

.grid-content-end {
    align-content: end;
}

.grid-content-between {
    align-content: space-between;
}

.grid-content-around {
    align-content: space-around;
}

.grid-content-evenly {
    align-content: space-evenly;
}

.grid-justify-items-start {
    justify-items: start;
}

.grid-justify-items-center {
    justify-items: center;
}

.grid-justify-items-end {
    justify-items: end;
}

.grid-justify-items-stretch {
    justify-items: stretch;
}

.grid-justify-self-start {
    justify-self: start;
}

.grid-justify-self-center {
    justify-self: center;
}

.grid-justify-self-end {
    justify-self: end;
}

.grid-justify-self-stretch {
    justify-self: stretch;
}

.grid-align-self-start {
    align-self: start;
}

.grid-align-self-center {
    align-self: center;
}

.grid-align-self-end {
    align-self: end;
}

.grid-align-self-stretch {
    align-self: stretch;
}

/* Responsive Grid Classes */
@media (min-width: 576px) {
    .grid-sm-1 {
        grid-template-columns: repeat(1, 1fr);
    }

    .grid-sm-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-sm-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-sm-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-sm-5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-sm-6 {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (min-width: 768px) {
    .grid-md-1 {
        grid-template-columns: repeat(1, 1fr);
    }

    .grid-md-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-md-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-md-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-md-5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-md-6 {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (min-width: 992px) {
    .grid-lg-1 {
        grid-template-columns: repeat(1, 1fr);
    }

    .grid-lg-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-lg-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-lg-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-lg-5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-lg-6 {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (min-width: 1200px) {
    .grid-xl-1 {
        grid-template-columns: repeat(1, 1fr);
    }

    .grid-xl-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .grid-xl-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .grid-xl-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .grid-xl-5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-xl-6 {
        grid-template-columns: repeat(6, 1fr);
    }
}

/* Special Grid Layouts */
.grid-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-lg);
}

.grid-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
}

.grid-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-lg);
}

.grid-dashboard-triple {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: var(--spacing-lg);
}

.grid-dashboard-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

/* Hover Effects for Grid Items */
.dashboard-grid > * {
    transition: var(--transition);
}

.dashboard-grid > *:hover {
    transform: translateY(-2px);
    z-index: 1;
}

/* Focus States for Accessibility */
.dashboard-grid > *:focus-within {
    outline: 2px solid var(--primary);
    outline-offset: -2px;
}

/* Loading State */
.grid-loading {
    opacity: 0.6;
    pointer-events: none;
}

.grid-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 10;
}

/* Print Styles */
@media print {
    .dashboard-grid {
        display: block;
    }

    .dashboard-grid > * {
        break-inside: avoid;
        margin-bottom: var(--spacing-lg);
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .dashboard-grid {
        border: 1px solid var(--gray-900);
    }

    .dashboard-grid > * {
        border: 1px solid var(--gray-900);
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .dashboard-grid > * {
        transition: none;
    }

    .dashboard-grid > *:hover {
        transform: none;
    }
}

/* Dark Mode Support (if needed) */
@media (prefers-color-scheme: dark) {
    .dashboard-grid {
        color-scheme: dark;
    }
}
</style>
@endpush
