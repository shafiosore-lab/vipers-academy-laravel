@props(['name' => 'arrow', 'class' => ''])

{{--
    Inline SVG icon set. Stroke-based and currentColor-driven so icons inherit
    the colour of whatever they sit inside (navy panels, gold accents, cards).
    Every glyph is decorative by default and hidden from assistive tech; pass a
    label only when an icon is the sole carrier of meaning.
--}}

@php
    $paths = [
        'arrow' => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
        'arrow-left' => '<path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/>',
        'heart' => '<path d="M12 20.5l-1.6-1.5C5.4 14.4 2.5 11.8 2.5 8.6 2.5 6 4.5 4 7.1 4c1.6 0 3.1.7 4 1.9l.9 1.2.9-1.2c.9-1.2 2.4-1.9 4-1.9 2.6 0 4.6 2 4.6 4.6 0 3.2-2.9 5.8-7.9 10.4L12 20.5z"/>',
        'users' => '<path d="M16 20v-1.5A3.5 3.5 0 0 0 12.5 15h-5A3.5 3.5 0 0 0 4 18.5V20"/><circle cx="10" cy="8" r="3.4"/><path d="M20 20v-1.4a3.5 3.5 0 0 0-2.6-3.4"/><path d="M15.5 4.8a3.4 3.4 0 0 1 0 6.4"/>',
        'dove' => '<path d="M3 13.5c3.5 0 5-2 5-4.5 0-1.2.6-2.2 1.6-2.7.4 2 1.7 3.2 3.4 3.2h4.5L21 6l-1.2 3.4L21 13h-5.5c-1 2.4-3 4-5.5 4-1.9 0-3.6-.6-5-1.6"/><path d="M6.5 16.5L5 21l3-1.6"/>',
        'cpu' => '<rect x="6.5" y="6.5" width="11" height="11" rx="2"/><rect x="10" y="10" width="4" height="4" rx="1"/><path d="M9.5 3v3.5M14.5 3v3.5M9.5 17.5V21M14.5 17.5V21M3 9.5h3.5M3 14.5h3.5M17.5 9.5H21M17.5 14.5H21"/>',
        'ball' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.2l3.6 2.6-1.4 4.2H9.8L8.4 9.8 12 7.2z"/><path d="M12 3v4.2M4.6 9.8l3.8 0M7.2 19.8l2.6-3.8M16.8 19.8l-2.6-3.8M19.4 9.8l-3.8 0"/>',
        'handshake' => '<path d="M11 6.5L8.5 9 6 7.5 2.5 11l3.5 3.5 1.4-1.4"/><path d="M13 6.5L15.5 9 18 7.5 21.5 11 18 14.5l-1.4-1.4"/><path d="M7 12.5l2 2 1.5-1 2 2 1.5-1 2 2"/>',
        'briefcase' => '<rect x="3" y="7.5" width="18" height="12.5" rx="2"/><path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5"/><path d="M3 12.5h18"/>',
        'star' => '<path d="M12 3.5l2.7 5.5 6 .9-4.35 4.2 1.03 6L12 17.3l-5.38 2.8 1.03-6L3.3 9.9l6-.9L12 3.5z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 6.5l8.5 6 8.5-6"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a12 12 0 0 0 5 5L15 13l5 2v4a1.5 1.5 0 0 1-1.6 1.5A16.5 16.5 0 0 1 3.5 5.6 1.5 1.5 0 0 1 5 4z"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.3l3.4 2"/>',
        'pin' => '<path d="M12 21.5s7-5.7 7-11a7 7 0 1 0-14 0c0 5.3 7 11 7 11z"/><circle cx="12" cy="10.3" r="2.6"/>',
        'shield' => '<path d="M12 3l7 3v6c0 4.4-3 7.7-7 9-4-1.3-7-4.6-7-9V6l7-3z"/><path d="M9 12l2.2 2.2L15.5 10"/>',
        'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15.5H6.5A2.5 2.5 0 0 0 4 21V5.5z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"/>',
        'check' => '<path d="M4.5 12.5l5 5 10-11"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
        'quote' => '<path d="M9.5 6.5C6.5 8 5 10.5 5 14v3.5h5.5V12H7.8c0-2 .8-3.5 2.4-4.4l-.7-1.1z"/><path d="M18.5 6.5C15.5 8 14 10.5 14 14v3.5h5.5V12h-2.7c0-2 .8-3.5 2.4-4.4l-.7-1.1z"/>',
        'target' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/>',
    ];

    $glyph = $paths[$name] ?? $paths['arrow'];
@endphp

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
     stroke-linecap="round" stroke-linejoin="round"
     class="v-icon {{ $class }}" aria-hidden="true" focusable="false">
    {!! $glyph !!}
</svg>
