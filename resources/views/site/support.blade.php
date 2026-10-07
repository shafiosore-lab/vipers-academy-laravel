@extends('layouts.site')

@section('title', 'Support Our Programs — Mumias Vipers CBO')
@section('meta_description', 'Support Mumias Vipers CBO — funding STEM sessions, youth health education, education pathways, peacebuilding and football development in Mumias, Kenya.')

@php
    $funding = config('vipers.funding', []);
@endphp

@section('content')
<x-site.split-hero
    eyebrow="Support us"
    title="Support our programs"
    script="Fund a term. Equip a team. Open a pathway."
    lead="Investment helps us reach more young people, run programmes for longer, and show clearly what difference they make."
    image="assets/img/gallery/kids.jpeg"
    imageAlt="Young players at a community football session"
    badge="Every gift counts"
    :actions="[
        ['label' => 'Support our programs', 'href' => route('site.contact') . '?subject=support', 'variant' => 'primary'],
        ['label' => 'Partner with us', 'href' => route('site.partnership'), 'variant' => 'ghost-light'],
    ]" />

{{-- ================ WHAT FUNDING ENABLES ================ --}}
<section class="v-section">
    <div class="v-container">
        <x-site.section-heading
            eyebrow="Where investment goes"
            title="What funding enables"
            lead="Each of these describes an outcome, not a package. We do not publish prices or budgets — any figure is agreed in writing with our partners first."
            align="split" />

        <div class="v-values">
            @foreach ($funding['what_enables'] ?? [] as $i => $item)
                <div class="v-value v-reveal" data-delay="{{ $i % 3 }}">
                    <span class="v-badge v-badge--gold">{{ $item['area'] ?? '' }}</span>
                    <h3 class="v-value__title" style="margin-top:0.75rem">{{ $item['title'] }}</h3>
                    <p class="v-value__body">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================ WAYS TO SUPPORT ================ --}}
<section class="v-section v-section--surface" aria-labelledby="ways-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <x-site.section-heading
                    id="ways-heading"
                    eyebrow="Ways to support"
                    title="Choose what you want to build"
                    :lead="$funding['body'] ?? ''" />

                <ul class="v-list">
                    @foreach ($funding['areas'] ?? [] as $area)
                        <li>{{ $area }}</li>
                    @endforeach
                </ul>

                @if (!empty($funding['note']))
                    <p class="v-text-muted" style="font-size:var(--v-fs-xs)">{{ $funding['note'] }}</p>
                @endif
            </div>

            <div class="v-reveal" data-delay="1">
                <div class="v-panel" style="margin-bottom:1rem">
                    <h3 class="v-panel__title v-h4">How you can help</h3>
                    <ul class="v-list" style="margin:0">
                        <li><strong>Fund a programme.</strong> Support a term of STEM, health education, peacebuilding or football development.</li>
                        <li><strong>Support a young person&rsquo;s pathway.</strong> Help connect a talented young person to a scholarship or continued education.</li>
                        <li><strong>Donate equipment.</strong> Football kit, coding devices, robotics kits, health education materials.</li>
                        <li><strong>Give your time.</strong> Volunteer as a coach, mentor or facilitator.</li>
                        <li><strong>Partner institutionally.</strong> Multi-programme funding and co-delivery.</li>
                    </ul>
                </div>

                <div class="v-btn-row v-btn-row--tight">
                    <x-site.button :href="route('site.contact') . '?subject=support'" variant="primary" icon="arrow">
                        Support our programs
                    </x-site.button>
                    <x-site.button :href="route('site.partnership')" variant="ghost" icon="handshake">
                        Partner with us
                    </x-site.button>
                </div>

                {{-- Payment rails are not connected yet; nothing is claimed or implied --}}
                <div style="margin-top:1.5rem">
                    <x-site.placeholder-note
                        eyebrow="Payment method to be confirmed"
                        note="No payment provider is connected to this site yet. Live payment details (mobile money, bank or card) will be published once an authorised account is in place. Until then, please contact us directly." />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================ PARTNERSHIP CTA ================ --}}
<x-site.cta-section
    eyebrow="Working together"
    title="Partnerships make impact possible"
    :lead="config('vipers.partnerships.principle') ?? ''"
    :areas="[]"
    primary-label="Partner with us"
    primary-route="site.partnership"
    secondary-label="See our impact"
    secondary-route="site.impact"
    image="assets/img/gallery/kids.jpeg" />

@endsection