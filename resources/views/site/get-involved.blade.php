@extends('layouts.site')

@section('title', 'Get Involved — Mumias Vipers CBO')
@section('meta_description', 'Partner with us, volunteer, sponsor a young person, or support our programmes. There is more than one way to help Mumias Vipers CBO.')

@php
    $involved = config('vipers.get_involved', []);
@endphp


@section('content')
<x-site.cards-hero
    eyebrow="Get involved"
    title="Get involved"
    lead="Partner, volunteer, sponsor or simply start a conversation. Every one of these helps a young person in Mumias."
    :cards="[
        ['icon' => 'handshake', 'title' => 'Partner', 'body' => 'Institutions & funders'],
        ['icon' => 'heart', 'title' => 'Support', 'body' => 'Fund programmes'],
        ['icon' => 'users', 'title' => 'Volunteer', 'body' => 'Give your time'],
    ]" />

<section class="v-section v-section--tight">
    <div class="v-container">
        <div class="v-grid v-grid--3 v-grid--cozy">
            @foreach ($involved as $i => $item)
                <div class="v-card v-card--gold-top v-reveal" data-delay="{{ $i % 3 }}">
                    <span class="v-icon-chip" aria-hidden="true">
                        <x-site.icon :name="$item['icon']" />
                    </span>
                    <h2 class="v-card__title v-h3">{{ $item['title'] }}</h2>
                    <p class="v-card__text">{{ $item['body'] }}</p>
                    <div class="v-card__foot">
                        <a class="v-link-arrow"
                           href="{{ route($item['route']) }}@if (!empty($item['anchor']))#{{ $item['anchor'] }}@endif">
                            {{ $item['cta'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Volunteer --}}
<section class="v-section v-section--surface v-section--tight" id="volunteer" aria-labelledby="volunteer-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <span class="v-eyebrow">Volunteer</span>
                <h2 class="v-h2" id="volunteer-heading">Offer your time</h2>
                <p class="v-body-text">
                    Volunteers make the difference between a programme that runs and a programme that
                    stops. We are always looking for people willing to give a few hours a week or a
                    few days a month.
                </p>

                <ul class="v-list">
                    <li><strong>Coaches</strong> — football sessions, girls’ teams, age-group training.</li>
                    <li><strong>Mentors</strong> — STEM, academics, careers and life skills.</li>
                    <li><strong>Health professionals</strong> — health talks and wellbeing sessions.</li>
                    <li><strong>Designers and engineers</strong> — robotics, electronics, mentoring.</li>
                    <li><strong>Administrators</strong> — programme coordination, events, communications.</li>
                </ul>

                <div class="v-btn-row v-btn-row--tight">
                    <x-site.button :href="route('site.contact') . '?subject=volunteer'" variant="primary">
                        Offer your time
                    </x-site.button>
                </div>
            </div>

            <div class="v-reveal" data-delay="1">
                <div class="v-panel v-panel--navy">
                    <h3 class="v-panel__title v-h4">What volunteers receive</h3>
                    <ul class="v-list" style="margin:0">
                        <li>A clear role, so you always know what you are signing up for.</li>
                        <li>Training and materials for the work you do with young people.</li>
                        <li>A named contact at the organisation.</li>
                        <li>Recognition for your contribution, on request.</li>
                    </ul>
                </div>

                <div style="margin-top:0.75rem">
                    <x-site.placeholder-note
                        eyebrow="Volunteer sign-up form"
                        note="An online volunteer application form is yet to be connected. For now, enquiries are routed through the contact page." />
                </div>
            </div>
        </div>
    </div>
</section>

<x-site.cta-section image="assets/img/gallery/academics.jpg" />

@endsection

