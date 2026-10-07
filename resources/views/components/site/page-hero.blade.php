@props([
    'title' => null,
    'lead' => null,
    'eyebrow' => null,
    'image' => null,
    'imageAlt' => 'Football training session',
])

{{-- Interior page banner. Carries the page <h1> so each page has exactly one. --}}

<section class="v-pagehero"
         @if ($image && @file_exists(public_path($image)))
             style="background-image: url('{{ asset($image) }}')"
         @endif>
    <div class="v-pagehero__scrim" aria-hidden="true"></div>

    <div class="v-container v-pagehero__text-wrap">
        <div class="v-pagehero__text">
            @if ($eyebrow)
                <p class="v-eyebrow">{{ $eyebrow }}</p>
            @endif

            <h1 class="v-h1 v-pagehero__title">{{ $title }}</h1>

            @if ($lead)
                <p class="v-lead">{{ $lead }}</p>
            @endif
        </div>
    </div>
</section>
