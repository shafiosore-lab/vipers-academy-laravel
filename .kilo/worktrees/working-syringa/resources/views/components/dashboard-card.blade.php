{{--
    Standardized Dashboard Card Component
    ====================================
    A reusable card component for consistent layout density and compactness
    across all dashboard views.

    Usage:
    @component('components.dashboard-card', ['title' => 'Card Title', 'actions' => 'Optional Actions'])
        Card content goes here
    @endcomponent

    Or with slots:
    <x-dashboard-card title="Card Title" actions="Optional Actions">
        Card content goes here
    </x-dashboard-card>
--}}

@props([
    'title' => null,
    'actions' => null,
    'variant' => 'default', // default, compact, minimal
    'spacing' => 'md', // xs, sm, md, lg, xl
    'shadow' => 'default', // none, sm, default, lg, xl
    'border' => true,
    'radius' => 'md', // sm, md, lg, xl
])

@php
    // Determine CSS classes based on props
    $cardClasses = [
        'dashboard-card',
        'card-spacing-' . $spacing,
        'card-variant-' . $variant,
        'card-shadow-' . $shadow,
        'card-radius-' . $radius,
        $border ? 'card-bordered' : '',
    ];

    $headerClasses = [
        'card-header',
        $title ? '' : 'card-header-empty',
    ];

    $bodyClasses = [
        'card-body',
        'card-body-spacing-' . $spacing,
    ];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $cardClasses)]) }}>
    @if($title || $actions)
    <div class="{{ implode(' ', $headerClasses) }}">
        @if($title)
        <h3 class="card-title">{{ $title }}</h3>
        @endif
        @if($actions)
        <div class="card-actions">
            {{ $actions }}
        </div>
        @endif
    </div>
    @endif

    <div class="{{ implode(' ', $bodyClasses) }}">
        {{ $slot }}
    </div>
</div>

@push('styles')
<style>
/* ========================================
       STANDARDIZED DASHBOARD CARD COMPONENT
       ======================================== */

/* Base Card Styles */
.dashboard-card {
    background: var(--white);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

/* Spacing Variants */
.card-spacing-xs {
    margin-bottom: var(--spacing-xs);
}

.card-spacing-sm {
    margin-bottom: var(--spacing-sm);
}

.card-spacing-md {
    margin-bottom: var(--spacing-md);
}

.card-spacing-lg {
    margin-bottom: var(--spacing-lg);
}

.card-spacing-xl {
    margin-bottom: var(--spacing-xl);
}

/* Body Padding Variants */
.card-body-spacing-xs {
    padding: var(--card-padding-xs);
}

.card-body-spacing-sm {
    padding: var(--card-padding-sm);
}

.card-body-spacing-md {
    padding: var(--card-padding-md);
}

.card-body-spacing-lg {
    padding: var(--card-padding-lg);
}

.card-body-spacing-xl {
    padding: var(--card-padding-xl);
}

/* Variant Styles */
.card-variant-default {
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow);
}

.card-variant-compact {
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
}

.card-variant-minimal {
    border: 1px solid var(--gray-200);
    box-shadow: none;
    background: var(--white);
}

/* Shadow Variants */
.card-shadow-none {
    box-shadow: none;
}

.card-shadow-sm {
    box-shadow: var(--shadow-sm);
}

.card-shadow-default {
    box-shadow: var(--shadow);
}

.card-shadow-lg {
    box-shadow: var(--shadow-lg);
}

.card-shadow-xl {
    box-shadow: var(--shadow-xl);
}

/* Border Radius Variants */
.card-radius-sm {
    border-radius: var(--radius-sm);
}

.card-radius-md {
    border-radius: var(--radius-md);
}

.card-radius-lg {
    border-radius: var(--radius-lg);
}

.card-radius-xl {
    border-radius: var(--radius-xl);
}

/* Bordered/Non-bordered */
.card-bordered {
    border: 1px solid var(--gray-300);
}

/* Header Styles */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-sm) var(--card-padding-md);
    border-bottom: 1px solid var(--gray-300);
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
    position: relative;
}

.card-header-empty {
    border-bottom: none;
    padding: 0;
}

.card-title {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
    line-height: 1.3;
}

.card-actions {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.card-actions a,
.card-actions button {
    font-size: var(--font-size-sm);
    padding: 4px 8px;
    border-radius: var(--radius);
    border: 1px solid var(--gray-300);
    background: var(--white);
    color: var(--gray-700);
    transition: var(--transition);
    text-decoration: none;
}

.card-actions a:hover,
.card-actions button:hover {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

/* Body Styles */
.card-body {
    position: relative;
}

/* Hover Effects */
.dashboard-card:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.card-variant-compact:hover {
    box-shadow: var(--shadow-md);
}

.card-variant-minimal:hover {
    box-shadow: var(--shadow-sm);
    border-color: var(--gray-400);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }

    .card-actions {
        align-self: flex-end;
    }

    .card-body-spacing-md {
        padding: var(--card-padding-sm);
    }

    .card-body-spacing-lg {
        padding: var(--card-padding-md);
    }
}

/* Utility Classes for Content */
.card-content-grid {
    display: grid;
    gap: var(--spacing-md);
}

.card-content-flex {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.card-content-row {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.card-stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--gray-200);
}

.card-stat-row:last-child {
    border-bottom: none;
}

.card-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    font-weight: 500;
}

.card-value {
    font-size: var(--font-size-lg);
    font-weight: 700;
    color: var(--gray-900);
}

.card-meta {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    margin-top: var(--spacing-xs);
}

/* Status Indicators */
.card-status {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: 2px 8px;
    border-radius: var(--radius);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-status.success {
    background: var(--success-light);
    color: var(--success-dark);
    border: 1px solid var(--success);
}

.card-status.warning {
    background: var(--warning-light);
    color: var(--warning-dark);
    border: 1px solid var(--warning);
}

.card-status.danger {
    background: var(--danger-light);
    color: var(--danger-dark);
    border: 1px solid var(--danger);
}

.card-status.info {
    background: var(--info-light);
    color: var(--info-dark);
    border: 1px solid var(--info);
}

/* List Styles */
.card-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.card-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--gray-200);
    transition: var(--transition);
}

.card-list-item:hover {
    background: var(--gray-50);
}

.card-list-item:last-child {
    border-bottom: none;
}

.card-list-item .item-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
}

.card-list-item .item-value {
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: var(--gray-900);
}

/* Badge Styles */
.card-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-badge.primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
}

.card-badge.secondary {
    background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
    color: var(--white);
}

.card-badge.warning {
    background: linear-gradient(135deg, var(--warning), var(--warning-light));
    color: var(--white);
}

.card-badge.danger {
    background: linear-gradient(135deg, var(--danger), var(--danger-light));
    color: var(--white);
}

/* Empty State */
.card-empty {
    text-align: center;
    color: var(--gray-600);
    font-style: italic;
    padding: var(--spacing-lg);
}

.card-empty i {
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: var(--spacing-sm);
}

/* Loading State */
.card-loading {
    opacity: 0.6;
    pointer-events: none;
}

.card-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 10;
}

/* Focus States for Accessibility */
.dashboard-card:focus-within {
    outline: 2px solid var(--primary);
    outline-offset: -2px;
}

.card-actions a:focus,
.card-actions button:focus {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}
</style>
@endpush
