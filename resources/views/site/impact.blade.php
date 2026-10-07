@extends('layouts.site')

@section('title', 'Our Impact — Mumias Vipers CBO')
@section('meta_description', 'How Mumias Vipers CBO measures impact — reach, engagement and opportunity across football, education, health education, STEM and peacebuilding in Mumias, Kenya.')

@php
    $framework = config('vipers.impact_framework', []);
    $why = config('vipers.why_football', []);
@endphp

@section('content')
<x-site.about-hero
    eyebrow="Impact"
    title="Evidence over promises."
    accent="People over numbers."
    script="Girls and boys. Pitch to classroom to community."
    lead="We report what we can evidence, and we say plainly what we are still building. Long-term outcome claims appear here only when we can support them."
    image="assets/img/home/WhatsApp Image 2026-05-05 at 12.48.23.jpeg"
    imageAlt="Mumias Vipers ladies and youth gathered outdoors after a community session"
    overlapImage="assets/img/home/WhatsApp Image 2026-05-05 at 12.48.21.jpeg"
    overlapAlt="Mumias Vipers girls and boys together outside a community venue"
    badge="Girls included"
    :founded="config('vipers.org.founded')"
    :registration="config('vipers.org.registration')"
    audience="Ages 10–18" />

{{-- ============ EVIDENCE: only the facts we can stand behind ============ --}}
<section class="v-section v-section--navy-deep v-on-dark">
    <div class="v-container">
        <x-site.section-heading
            eyebrow="Evidence today"
            title="What we can show right now"
            lead="Two facts are documented and verified. Everything else is still being counted before we publish it." />

        <x-site.impact-strip :items="config('vipers.evidence', [])" />

        <p class="v-cta__note" style="margin-top:1.5rem">
            {{ config('vipers.impact_outcomes_note') }}
        </p>
    </div>
</section>

{{-- ================= IMPACT FRAMEWORK: 3 LEVELS ================= --}}
<section class="v-section" aria-labelledby="framework-heading">
    <div class="v-container">
        <x-site.section-heading
            id="framework-heading"
            eyebrow="How we measure impact"
            title="Reach. Engagement. Opportunity."
            lead="Impact is reported at three levels, because a single number cannot show what actually changed." />

        <div class="v-grid v-grid--3">
            @foreach (array_values($framework) as $i => $level)
                <div class="v-card v-reveal v-accent-gold" data-delay="{{ $i % 3 }}" style="padding:1.5rem">
                    <p class="v-eyebrow">{{ $level['question'] ?? '' }}</p>
                    <h3 class="v-card__title v-h3" style="margin-bottom:0.6rem">{{ $level['heading'] ?? '' }}</h3>
                    <p class="v-card__text">{{ $level['body'] ?? '' }}</p>
                    <ul class="v-list" style="margin:1rem 0 0;font-size:var(--v-fs-sm)">
                        @foreach ($level['facts'] ?? [] as $fact)
                            <li>{{ $fact }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= WHY FOOTBALL — the model behind the numbers ================= --}}
<section class="v-section v-section--surface" aria-labelledby="how-heading">
    <div class="v-container">
        <x-site.section-heading
            id="how-heading"
            eyebrow="How impact happens"
            title="Play. Learn. Lead. Change."
            :lead="$why['statement'] ?? ''"
            align="split" />

        <x-site.journey-steps :steps="config('vipers.journey', [])" />
    </div>
</section>

<x-site.cta-section
    eyebrow="Why invest in Mumias Vipers?"
    :title="config('vipers.funding.title')"
    primary-label="Partner with us"
    primary-route="site.partnership"
    secondary-label="Support our programs"
    secondary-route="site.support"
    image="assets/img/gallery/team.jpeg" />

@endsection