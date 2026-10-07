@props([
    'eyebrow' => null,
    'title' => null,
    'accent' => null,
    'script' => null,
    'lead' => null,
    'image' => null,
    'imageAlt' => '',
    'overlapImage' => null,
    'overlapAlt' => '',
    'founded' => null,
    'registration' => null,
    'audience' => null,
    'badge' => 'Community-rooted',
])

{{-- About-only editorial hero: copy left, layered photo collage right.
     Carries the page <h1> so the page keeps exactly one. --}}
<section class="v-abouthero v-on-dark" aria-labelledby="about-hero-heading">
    <div class="v-container v-abouthero__grid">
        <div class="v-abouthero__copy v-reveal">
            @if ($eyebrow)
                <p class="v-eyebrow">{{ $eyebrow }}</p>
            @endif

            <h1 class="v-h1 v-abouthero__title" id="about-hero-heading">{!! $title !!}
                @if ($accent)
                    <em>{{ $accent }}</em>
                @endif
            </h1>

            @if ($script)
                <p class="v-script v-abouthero__script">{{ $script }}</p>
            @endif

            @if ($lead)
                <p class="v-lead v-abouthero__lead">{{ $lead }}</p>
            @endif

            <div class="v-btn-row">
                <x-site.button :href="route('site.programs')" variant="primary" icon="arrow">
                    See our programs
                </x-site.button>
                <x-site.button :href="route('site.get-involved')" variant="ghost-light" icon="handshake">
                    Get involved
                </x-site.button>
            </div>

            @if ($founded || $registration || $audience)
                <ul class="v-abouthero__trust" aria-label="Organisation at a glance">
                    @if ($founded)
                        <li>Est. {{ $founded }}</li>
                    @endif
                    @if ($audience)
                        <li>{{ $audience }}</li>
                    @endif
                    @if ($registration)
                        <li>{{ $registration }}</li>
                    @endif
                </ul>
            @endif
        </div>

        <div class="v-abouthero__media v-reveal" data-delay="1">
            <div class="v-abouthero__main">
                <x-site.image
                    :src="$image"
                    :alt="$imageAlt"
                    ratio="4 / 3"
                    loading="eager"
                    placeholder-label="About photograph" />
            </div>
            @if ($overlapImage)
                <div class="v-abouthero__overlap" aria-hidden="false">
                    <x-site.image
                        :src="$overlapImage"
                        :alt="$overlapAlt"
                        ratio="1 / 1"
                        placeholder-label="Team photograph" />
                </div>
            @endif
            <p class="v-abouthero__badge" aria-hidden="true">{{ $badge }}</p>
        </div>
    </div>
</section>
