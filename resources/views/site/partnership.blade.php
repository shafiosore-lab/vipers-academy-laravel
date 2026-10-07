@extends('layouts.site')

@section('title', 'Partner With Us — Mumias Vipers CBO')
@section('meta_description', 'Partner with Mumias Vipers CBO to support youth education, STEM, health education, peacebuilding, football development and scholarship pathways in Mumias, Kenya.')

@php
    $funding = config('vipers.funding', []);
    $partners = config('vipers.partnerships', []);
    $programs = config('vipers.programs', []);
@endphp

@section('content')
<x-site.stat-hero
    eyebrow="Partnerships"
    title="Partner with us"
    lead="For grant makers, foundations, corporates, government programmes and development partners working in youth development, education, health and football for development."
    image="assets/img/gallery/team.jpeg"
    imageAlt="Mumias Vipers team together"
    statValue="300+"
    statLabel="Students connected to sports scholarship opportunities through school partnerships"
    :actions="[
        ['label' => 'Start a conversation', 'href' => route('site.contact') . '?subject=partnership', 'variant' => 'primary'],
        ['label' => 'See our impact', 'href' => route('site.impact'), 'variant' => 'ghost-light'],
    ]" />

{{-- ================ WHY INVEST ================ --}}
<section class="v-section">
    <div class="v-container">
        <x-site.section-heading
            eyebrow="{{ $funding['eyebrow'] ?? 'Funding' }}"
            :title="$funding['title'] ?? 'Why invest in Mumias Vipers?'"
            :lead="$funding['body'] ?? ''"
            align="split" />

        <div class="v-values">
            @foreach ($funding['why_invest'] ?? [] as $i => $reason)
                <div class="v-value v-reveal" data-delay="{{ $i % 3 }}">
                    <h3 class="v-value__title">{{ $reason['title'] }}</h3>
                    <p class="v-value__body">{{ $reason['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================ THE PARTNERSHIP PRINCIPLE ================ --}}
<section class="v-section v-section--navy v-on-dark" aria-labelledby="principle-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <p class="v-eyebrow">The principle</p>
                <h2 class="v-h2" id="principle-heading">Partnership turns access into impact</h2>
                <p class="v-lead">{{ $partners['principle'] ?? '' }}</p>
            </div>
            <div class="v-reveal" data-delay="1">
                <ol class="v-list" style="list-style:none;padding:0;margin:0">
                    @foreach ($partners['how_it_works'] ?? [] as $i => $step)
                        <li style="display:flex;gap:0.85rem;align-items:flex-start">
                            <span aria-hidden="true" style="flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:var(--v-gold-400);color:var(--v-navy-900);font-family:var(--v-font-display);font-weight:800;font-size:0.78rem">{{ $i + 1 }}</span>
                            <span>{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ================ EXISTING PARTNERSHIPS ================ --}}
<section class="v-section v-section--surface" aria-labelledby="existing-heading">
    <div class="v-container">
        <x-site.section-heading
            id="existing-heading"
            eyebrow="What already exists"
            title="Partnerships in place today"
            lead="Only relationships we can describe accurately are listed. We do not display a logo without written permission, and we do not publish partnership terms." />

        <div class="v-grid v-grid--2">
            @foreach ($partners['existing'] ?? [] as $i => $partner)
                <div class="v-card v-accent-gold v-reveal" data-delay="{{ $i % 2 }}" style="padding:1.5rem">
                    <span class="v-badge v-badge--gold">{{ ucfirst($partner['status'] ?? 'partner') }}</span>
                    <h3 class="v-card__title v-h4" style="margin:0.85rem 0 0.5rem">{{ $partner['name'] }}</h3>
                    <p class="v-card__text">{{ $partner['note'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================ FUTURE PARTNER CATEGORIES ================ --}}
<section class="v-section" aria-labelledby="cats-heading">
    <div class="v-container">
        <x-site.section-heading
            id="cats-heading"
            eyebrow="Future opportunities"
            title="How your organisation could work with us"
            lead="These are the kinds of partners we are looking to build relationships with. None of these are claimed as existing." />

        <div class="v-grid v-grid--3">
            @foreach ($partners['categories'] ?? [] as $i => $cat)
                <div class="v-card v-accent-gold v-reveal" data-delay="{{ $i % 3 }}" style="padding:1.5rem">
                    <span class="v-icon-chip" aria-hidden="true"><x-site.icon :name="$cat['icon'] ?? 'handshake'" /></span>
                    <h3 class="v-card__title v-h4" style="margin:0.85rem 0 0.5rem">{{ $cat['title'] }}</h3>
                    <p class="v-card__text">{{ $cat['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================ PROGRAMME-SPECIFIC ASKS ================ --}}
<section class="v-section v-section--surface" aria-labelledby="asks-heading">
    <div class="v-container">
        <x-site.section-heading
            id="asks-heading"
            eyebrow="Where support is needed"
            title="Specific ways to partner"
            align="split" />

        <div class="v-grid v-grid--2">
            @foreach ($programs as $i => $program)
                <div class="v-card v-accent-{{ $program['accent'] }} v-reveal" data-delay="{{ $i % 2 }}"
                     style="padding:1.5rem">
                    <span class="v-icon-chip" aria-hidden="true" style="margin-bottom:1rem">
                        <x-site.icon :name="$program['icon']" />
                    </span>
                    <h3 class="v-card__title v-h4">{{ $program['name'] }}</h3>
                    <ul class="v-list" style="margin:0;font-size:var(--v-fs-sm)">
                        @foreach ($program['partnership_opportunities'] as $opportunity)
                            <li>{{ $opportunity }}</li>
                        @endforeach
                    </ul>
                    <div class="v-card__foot">
                        <a class="v-link-arrow" href="{{ route('site.programs.show', ['slug' => $program['slug']]) }}">
                            About {{ $program['name'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="v-btn-row" style="margin-top:2.5rem">
            <x-site.button :href="route('site.contact') . '?subject=partnership'" variant="primary" size="lg" icon="arrow">
                Start a conversation
            </x-site.button>
            <x-site.button :href="route('site.contact') . '?subject=general'" variant="ghost" size="lg">
                Get our organization profile
            </x-site.button>
        </div>
    </div>
</section>

@endsection