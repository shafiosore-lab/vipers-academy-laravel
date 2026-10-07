@props(['eyebrow' => 'Note', 'note' => null])

{{--
    An honest, visible marker for content the organisation has not supplied yet.
    Shown to administrators and readers alike: a dashed, labelled slot is far
    better than a page that quietly invents registration numbers or figures.
--}}

@if (!empty($note))
    <div class="v-placeholder">
        <span class="v-placeholder__tag">{{ $eyebrow }}</span>
        <p class="v-placeholder__text">{{ $note }}</p>
    </div>
@endif
