@extends('layouts.site')

@section('title', $program['name'] . ' — Mumias Vipers CBO')
@section('meta_description', $program['tagline'] ?? '')
@section('og_image', $program['image'] ?? null)

@php
    $others = collect(config('vipers.programs', []))
        ->reject(fn ($p) => $p['slug'] === $program['slug'])
        ->values();

    $programCategory = match ($program['slug']) {
        'stem-education' => 'STEM',
        'health-education' => 'Health',
        'peace-justice' => 'Peace & Justice',
        'football-development' => 'Football',
        default => '__none__',
    };

    $gallery = array_values(array_filter(
        config('vipers.gallery', []),
        fn ($g) => ($g['category'] ?? '') === $programCategory
    ));
@endphp


@section('content')
<x-site.page-hero
    :title="$program['name']"
    :lead="$program['tagline'] ?? ''"
    :image="$program['image'] ?? null"
    :eyebrow="$program['eyebrow'] ?? 'Program'" />

{{-- Overview + focus --}}
<section class="v-section" aria-labelledby="overview-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <span class="v-eyebrow">Program overview</span>
                <h2 class="v-h2" id="overview-heading">{{ $program['name'] }}</h2>
                <p class="v-lead">{{ $program['summary'] ?? '' }}</p>

                @if (!empty($program['description_placeholder']))
                    <x-site.placeholder-note
                        eyebrow="Programme write-up to be supplied"
                        note="The full programme description, curriculum and delivery model are to be confirmed by the programme team. Add approved copy in config/vipers.php for this programme." />
                @endif
            </div>

            <div class="v-reveal" data-delay="1">
                <div class="v-panel" style="margin-bottom:1rem">
                    <h3 class="v-panel__title v-h4">What we focus on</h3>
                    <ul class="v-list" style="margin:0">
                        @foreach ($program['focus'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="v-panel v-panel--navy">
                    <h3 class="v-panel__title v-h4">Why it matters</h3>
                    <p class="v-panel__body">{{ $program['why_it_matters'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What we do / Who we serve --}}
<section class="v-section v-section--surface" aria-labelledby="doing-heading">
    <div class="v-container">
            <div class="v-reveal">
                <h2 class="v-h3" id="doing-heading">What we do</h2>
                <ul class="v-list">
                    @foreach ($program['what_we_do'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                {{-- Programme-specific status and partner facts, shown only when
                     the config actually carries them (Health / Peace). These use
                     the careful wordings: collaboration rather than sponsorship,
                     pursuing engagement rather than membership. --}}
                @if (!empty($program['role_note']))
                    <x-site.placeholder-note
                        eyebrow="Our role"
                        :note="$program['role_note']" />
                @endif

                @if (!empty($program['partner']))
                    <div class="v-panel v-accent-gold" style="margin-top:1rem">
                        <h3 class="v-panel__title v-h4">
                            Partnership: {{ $program['partner']['name'] }}
                        </h3>
                        <p class="v-panel__body">{{ $program['partner']['note'] }}</p>
                    </div>
                @endif

                @if (!empty($program['status_note']))
                    <x-site.placeholder-note
                        eyebrow="Programme status"
                        :note="$program['status_note']" />
                @endif
            </div>
            <div class="v-reveal" data-delay="1">
                <h2 class="v-h3">Who we serve</h2>
                <ul class="v-list">
                    @foreach ($program['who_we_serve'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Activities + impact --}}
<section class="v-section" aria-labelledby="activities-heading">
    <div class="v-container">
        <x-site.section-heading
            id="activities-heading"
            eyebrow="Delivery"
            title="Activities"
            lead="How the programme shows up week to week." />

        <div class="v-grid v-grid--4" style="margin-bottom:2.5rem">
            @foreach (array_keys($program['activities']) as $i => $activity)
                <div class="v-card v-reveal" data-delay="{{ $i % 4 }}"
                     style="padding:1.35rem;border-top:4px solid var(--v-gold-400)">
                    <h3 class="v-card__title v-h4" style="margin-bottom:.4rem">{{ $program['activities'][$activity] }}</h3>
                    <p class="v-card__text" style="font-size:var(--v-fs-xs);color:var(--v-ink-3)">
                        Schedule and frequency to be confirmed.
                    </p>
                </div>
            @endforeach
        </div>

        <x-site.section-heading eyebrow="Impact &amp; results" title="What we can show so far" />
        <x-site.impact-strip :items="config('vipers.impact_stats', [])" light />
    </div>
</section>

{{-- Gallery --}}
@if (count($gallery))
    <section class="v-section v-section--surface" aria-labelledby="program-gallery-heading">
        <div class="v-container">
            <x-site.section-heading id="program-gallery-heading" eyebrow="Gallery" title="Photos from this programme" />
            <x-site.gallery-grid :items="$gallery" :show-filters="false" />
        </div>
    </section>
@endif

{{-- Partnership --}}
<section class="v-section" aria-labelledby="partner-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <span class="v-eyebrow">Partnership opportunities</span>
                <h2 class="v-h2" id="partner-heading">Support {{ $program['name'] }}</h2>
                <ul class="v-list">
                    @foreach ($program['partnership_opportunities'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <div class="v-btn-row v-btn-row--tight">
                    <x-site.button :href="route('site.partnership')" variant="primary">Partner with us</x-site.button>
                    <x-site.button :href="route('site.contact')" variant="ghost">Ask a question</x-site.button>
                </div>
            </div>

            <div class="v-reveal" data-delay="1">
                <div style="border-radius:var(--v-r-lg);overflow:hidden">
                    <x-site.image :src="$program['image']" :alt="$program['image_alt']"
                                  ratio="4 / 3" placeholder-label="Programme photo" />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Other programs --}}
<section class="v-section v-section--surface" aria-labelledby="other-heading">
    <div class="v-container">
        <x-site.section-heading id="other-heading" eyebrow="Explore more" title="Other programmes" />
        <div class="v-grid v-grid--3">
            @foreach ($others as $i => $other)
                <x-site.program-card :program="$other" :delay="$i" />
            @endforeach
        </div>
    </div>
</section>

@endsection

