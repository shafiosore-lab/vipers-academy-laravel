{{--
    Standardized Dashboard Stat Component
    ===================================
    A reusable stat component for consistent metric display across all dashboards.
    Designed for compact layout density and uniform visual hierarchy.

    Usage:
    @component('components.dashboard-stat', [
        'label' => 'Total Players',
        'value' => 1234,
        'icon' => 'fas fa-users',
        'change' => '+12%',
        'changeType' => 'positive',
        'variant' => 'default'
    ])
    @endcomponent

    Or with slots:
    <x-dashboard-stat label="Total Players" value="1234" icon="fas fa-users">
        <x-slot:change type="positive">+12%</x-slot:change>
    </x-dashboard-stat>
--}}

@props([
    'label' => null,
    'value' => null,
    'icon' => null,
    'iconColor' => 'primary',
    'change' => null,
    'changeType' => 'neutral', // positive, negative, neutral
    'variant' => 'default', // default, compact, minimal
    'size' => 'md', // sm, md, lg, xl
    'align' => 'left', // left, center, right
    'format' => 'number', // number, currency, percentage, text
    'currency' => 'KES',
    'prefix' => null,
    'suffix' => null,
])

@php
    // Determine CSS classes based on props
    $statClasses = [
        'dashboard-stat',
        'stat-variant-' . $variant,
        'stat-size-' . $size,
        'stat-align-' . $align,
    ];

    $iconClasses = [
        'stat-icon',
        'stat-icon-' . $iconColor,
        $icon ? '' : 'stat-icon-default',
    ];

    $valueClasses = [
        'stat-value',
        'stat-value-' . $size,
        $change ? 'stat-value-with-change' : '',
    ];

    $labelClasses = [
        'stat-label',
        'stat-label-' . $size,
    ];

    $changeClasses = [
        'stat-change',
        'stat-change-' . $changeType,
        $change ? '' : 'stat-change-hidden',
    ];

    // Format the value based on type
    $formattedValue = $value;
    if ($format === 'number' && is_numeric($value)) {
        $formattedValue = number_format($value);
    } elseif ($format === 'currency' && is_numeric($value)) {
        $formattedValue = $currency . ' ' . number_format($value);
    } elseif ($format === 'percentage' && is_numeric($value)) {
        $formattedValue = $value . '%';
    }

    // Add prefix/suffix
    if ($prefix) {
        $formattedValue = $prefix . $formattedValue;
    }
    if ($suffix) {
        $formattedValue = $formattedValue . $suffix;
    }
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $statClasses)]) }}>
    @if($icon)
    <div class="{{ implode(' ', $iconClasses) }}">
        <i class="{{ $icon }}" aria-hidden="true"></i>
    </div>
    @endif

    <div class="stat-content">
        <div class="{{ implode(' ', $valueClasses) }}">
            {{ $formattedValue }}
        </div>
        <div class="{{ implode(' ', $labelClasses) }}">
            {{ $label }}
        </div>
        @if($change)
        <div class="{{ implode(' ', $changeClasses) }}">
            <span class="stat-change-icon">
                @if($changeType === 'positive')
                    <i class="fas fa-arrow-up" aria-hidden="true"></i>
                @elseif($changeType === 'negative')
                    <i class="fas fa-arrow-down" aria-hidden="true"></i>
                @else
                    <i class="fas fa-minus" aria-hidden="true"></i>
                @endif
            </span>
            <span class="stat-change-text">{{ $change }}</span>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* ========================================
       STANDARDIZED DASHBOARD STAT COMPONENT
       ======================================== */

/* Base Stat Styles */
.dashboard-stat {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--card-padding-md);
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    min-height: 80px;
}

/* Variant Styles */
.stat-variant-default {
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-sm);
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
}

.stat-variant-compact {
    border: 1px solid var(--gray-300);
    box-shadow: var(--shadow-xs);
    background: var(--white);
    padding: var(--card-padding-sm);
    min-height: 60px;
}

.stat-variant-minimal {
    border: 1px solid var(--gray-200);
    box-shadow: none;
    background: var(--white);
    padding: var(--card-padding-sm);
    min-height: 60px;
}

/* Size Variants */
.stat-size-sm {
    min-height: 60px;
}

.stat-size-md {
    min-height: 80px;
}

.stat-size-lg {
    min-height: 100px;
}

.stat-size-xl {
    min-height: 120px;
}

/* Alignment Variants */
.stat-align-left {
    text-align: left;
}

.stat-align-center {
    text-align: center;
    justify-content: center;
}

.stat-align-right {
    text-align: right;
    flex-direction: row-reverse;
}

/* Icon Styles */
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--white);
    flex-shrink: 0;
    transition: var(--transition);
}

.stat-icon-default {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

.stat-icon-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
}

.stat-icon-secondary {
    background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
}

.stat-icon-success {
    background: linear-gradient(135deg, var(--success), var(--success-light));
}

.stat-icon-warning {
    background: linear-gradient(135deg, var(--warning), var(--warning-light));
}

.stat-icon-danger {
    background: linear-gradient(135deg, var(--danger), var(--danger-light));
}

.stat-icon-info {
    background: linear-gradient(135deg, var(--info), var(--info-light));
}

.stat-icon-purple {
    background: linear-gradient(135deg, #6f42c1, #8e79d6);
}

.stat-icon-blue {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.stat-icon-green {
    background: linear-gradient(135deg, #10b981, #34d399);
}

.stat-icon-orange {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

/* Content Styles */
.stat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

/* Value Styles */
.stat-value {
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1.2;
    display: block;
}

.stat-value-sm {
    font-size: var(--font-size-lg);
}

.stat-value-md {
    font-size: var(--font-size-2xl);
}

.stat-value-lg {
    font-size: var(--font-size-3xl);
}

.stat-value-xl {
    font-size: 2rem;
}

.stat-value-with-change {
    margin-bottom: var(--spacing-xs);
}

/* Label Styles */
.stat-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
}

.stat-label-sm {
    font-size: var(--font-size-xs);
}

.stat-label-md {
    font-size: var(--font-size-sm);
}

.stat-label-lg {
    font-size: var(--font-size-base);
}

.stat-label-xl {
    font-size: var(--font-size-lg);
}

/* Change Indicator Styles */
.stat-change {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 2px 6px;
    border-radius: var(--radius);
    transition: var(--transition);
}

.stat-change-hidden {
    display: none !important;
}

.stat-change-icon {
    font-size: 10px;
}

.stat-change-text {
    font-size: var(--font-size-xs);
}

.stat-change-positive {
    color: var(--success-dark);
    background: var(--success-light);
    border: 1px solid var(--success);
}

.stat-change-negative {
    color: var(--danger-dark);
    background: var(--danger-light);
    border: 1px solid var(--danger);
}

.stat-change-neutral {
    color: var(--gray-600);
    background: var(--gray-100);
    border: 1px solid var(--gray-300);
}

/* Hover Effects */
.dashboard-stat:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-variant-compact:hover {
    box-shadow: var(--shadow-sm);
}

.stat-variant-minimal:hover {
    box-shadow: var(--shadow-xs);
    border-color: var(--gray-400);
}

.stat-icon:hover {
    transform: scale(1.05);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .dashboard-stat {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
        padding: var(--card-padding-sm);
    }

    .stat-align-right {
        align-items: flex-end;
    }

    .stat-value-md {
        font-size: var(--font-size-xl);
    }

    .stat-value-lg {
        font-size: var(--font-size-2xl);
    }

    .stat-value-xl {
        font-size: var(--font-size-3xl);
    }
}

@media (max-width: 576px) {
    .dashboard-stat {
        min-height: auto;
        padding: var(--card-padding-sm);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }

    .stat-value-md {
        font-size: var(--font-size-lg);
    }

    .stat-value-lg {
        font-size: var(--font-size-xl);
    }

    .stat-value-xl {
        font-size: var(--font-size-2xl);
    }
}

/* Utility Classes */
.stat-metric {
    display: inline-block;
    padding: 2px 8px;
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}

.stat-trend {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--font-size-xs);
    color: var(--gray-600);
}

.stat-trend.up {
    color: var(--success);
}

.stat-trend.down {
    color: var(--danger);
}

.stat-trend.stable {
    color: var(--gray-500);
}

/* Focus States for Accessibility */
.dashboard-stat:focus-within {
    outline: 2px solid var(--primary);
    outline-offset: -2px;
}

/* Loading State */
.stat-loading {
    opacity: 0.6;
    pointer-events: none;
}

.stat-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 10;
}

/* Animation for Value Changes */
@keyframes statPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.stat-value-updating {
    animation: statPulse 0.3s ease-in-out;
}

/* Gradient Text for Values */
.stat-value-gradient {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Compact Layout for Dense Dashboards */
.stat-compact-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-lg);
}

.stat-compact-row {
    display: flex;
    gap: var(--spacing-lg);
    flex-wrap: wrap;
}

.stat-compact-column {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

/* Status Indicators */
.stat-status {
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

.stat-status.active {
    background: var(--success-light);
    color: var(--success-dark);
    border: 1px solid var(--success);
}

.stat-status.inactive {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.stat-status.warning {
    background: var(--warning-light);
    color: var(--warning-dark);
    border: 1px solid var(--warning);
}
</style>
@endpush
