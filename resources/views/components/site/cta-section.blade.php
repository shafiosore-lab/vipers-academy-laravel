@props([
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'areas' => null,
    'note' => null,
    'image' => null,
    'primaryLabel' => 'Support our mission',
    'primaryRoute' => 'site.support',
    'secondaryLabel' => 'Get involved',
    'secondaryRoute' => 'site.get-involved',
])

{{-- Closing call to action. Copy comes from config so it can be corrected
     without touching markup; the background photo is only applied when the
     file actually exists. Funding copy is the default so call sites only
     pass what differs (the background image or an override). --}}

@php
    $funding = config('vipers.funding', []);

    $eyebrow = $eyebrow ?? 'Work with us';
    $title = $title ?? 'Help us build the next generation';
    $lead = $lead ?? ($funding['body'] ?? '');
    $areas = $areas ?? ($funding['areas'] ?? []);
    $note = $note ?? ($funding['note'] ?? null);
@endphp

<section class="v-cta v-on-dark"
         @if ($image && @file_exists(public_path($image)))
             style="background-image: url('{{ asset($image) }}')"
         @endif>
    <div class="v-cta__scrim" aria-hidden="true"></div>

    <div class="v-container">
        <div class="v-section-head">
            @if ($eyebrow)
                <p class="v-eyebrow">{{ $eyebrow }}</p>
            @endif

            @if ($title)
                <h2 class="v-h2">{{ $title }}</h2>
            @endif

            @if ($lead)
                <p class="v-lead">{{ $lead }}</p>
            @endif
        </div>

        @if (!empty($areas))
            <ul class="v-cta__areas">
                @foreach ($areas as $area)
                    <li>{{ $area }}</li>
                @endforeach
            </ul>
        @endif

        <div class="v-btn-row">
            <x-site.button :href="route($primaryRoute)" variant="primary" size="lg" icon="heart">
                {{ $primaryLabel }}
            </x-site.button>
            <x-site.button :href="route($secondaryRoute)" variant="ghost-light" size="lg" icon="arrow">
                {{ $secondaryLabel }}
            </x-site.button>
        </div>

        @if (!empty($note))
            <p class="v-cta__note" style="margin-top:1.5rem">{{ $note }}</p>
        @endif
    </div>
</section>
