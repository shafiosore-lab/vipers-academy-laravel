@props([
    'href' => null,
    'variant' => 'primary',   // primary | navy | ghost | ghost-light
    'size' => null,           // sm | lg (null = 44px default)
    'icon' => null,
    'iconPosition' => 'left', // left | right
    'block' => false,
    'type' => null,
])

@php
    $classes = ['v-btn', 'v-btn--' . $variant];
    if ($size) {
        $classes[] = 'v-btn--' . $size;
    }
    if ($block) {
        $classes[] = 'v-btn--block';
    }
    $class = implode(' ', $classes);

    /* The icon + label content is identical for links and buttons, so build
     * it once and only vary the wrapping element below. */
    $content = '<span>' . e($slot) . '</span>';
    if ($icon && $iconPosition === 'left') {
        $content = view('components.site.icon', ['name' => $icon, 'class' => 'v-btn__icon'])->render() . $content;
    }
    if ($icon && $iconPosition === 'right') {
        $content .= view('components.site.icon', ['name' => $icon, 'class' => 'v-btn__icon'])->render();
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{!! $content !!}</a>
@else
    <button type="{{ $type ?? 'submit' }}" {{ $attributes->merge(['class' => $class]) }}>{!! $content !!}</button>
@endif
