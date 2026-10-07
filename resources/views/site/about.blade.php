@extends('layouts.site')

@section('title', 'About Mumias Vipers CBO â€” Youth Development Organisation in Mumias, Kenya')
@section('meta_description', 'Mumias Vipers CBO is a community-based youth organisation in Mumias, Kenya. Our mission, vision and values, and the work we do from the pitch to the classroom to the community.')

@php
    $org = config('vipers.org', []);
@endphp


@section('content')
<x-site.about-hero
    title="A youth development organisation built around a"
    accent="football club"
    script="From the pitch to the classroom to the community."
    :lead="$org['who_we_are'] ?? ''"
    image="assets/img/home/hhh.jpeg"
    imageAlt="Mumias Vipers players together on the pitch"
    overlapImage="assets/img/home/under-13.jpeg"
    overlapAlt="Young Mumias Vipers players"
    :founded="$org['founded'] ?? null"
    :registration="$org['registration'] ?? null"
    audience="Ages 10â€“18" />

{{-- WHO WE ARE / WHY / HOW ---------------------------------------------- --}}
<section class="v-section" aria-labelledby="mv-heading">
    <div class="v-container">
        <x-site.section-heading
            id="mv-heading"
            title="Our mission and vision"
            align="split" />

        <div class="v-grid v-grid--2">
            <div class="v-panel v-reveal">
                <h3 class="v-panel__title">Our mission</h3>
                <p class="v-panel__body">{{ $org['mission'] ?? '' }}</p>
            </div>
            <div class="v-panel v-panel--gold v-reveal" data-delay="1">
                <h3 class="v-panel__title">Our vision</h3>
                <p class="v-panel__body">{{ $org['vision'] ?? '' }}</p>
            </div>
        </div>
    </div>
</section>

{{-- THE STORY: who we are, why we exist, how we work ------------------- --}}
<section class="v-section v-section--surface" aria-labelledby="story-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <span class="v-eyebrow">Who we are</span>
                <h2 class="v-h2" id="story-heading">A youth development organisation built around a football club</h2>
                <p class="v-body-text">{{ $org['summary'] ?? '' }}</p>
                <p class="v-body-text">{{ $org['why_we_exist'] ?? '' }}</p>
                <p class="v-body-text">{{ $org['how_we_work'] ?? '' }}</p>

                @if (empty($org['founded']) || empty($org['registration']))
                    <x-site.placeholder-note
                        note="Year founded and registration / NGO number are yet to be published. Add them in config/vipers.php under org." />
                @endif
            </div>

            <div class="v-reveal" data-delay="1">
                <div style="border-radius:var(--v-r-lg);overflow:hidden">
                    <x-site.image
                        src="assets/img/home/WhatsApp Image 2026-01-21 at 12.46.59.jpeg"
                        alt="Mumias Vipers community activity"
                        ratio="4 / 5"
                        placeholder-label="Community photograph" />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- WHERE WE WANT TO GO ----------------------------------------------- --}}
<section class="v-section" aria-labelledby="where-heading">
    <div class="v-container">
        <x-site.section-heading
            id="where-heading"
            title="Building on work already underway"
            :lead="$org['where_we_want_to_go'] ?? ''"
            align="split-group" />
    </div>
</section>
{{-- Values --}}
<section class="v-section" aria-labelledby="values-heading">
    <div class="v-container">
        <x-site.section-heading
            id="values-heading"
            title="Our values"
            lead="Six commitments that shape how we work with young people, families and partners." />

        <div class="v-values">
            @foreach ($org['values'] as $i => $value)
                <div class="v-value v-reveal" data-delay="{{ $i % 3 }}">
                    <h3 class="v-value__title">{{ $value['title'] }}</h3>
                    <p class="v-value__body">{{ $value['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Youth & community ownership --}}
<section class="v-section v-section--navy v-on-dark" aria-labelledby="ownership-heading">
    <div class="v-container">
        <div class="v-split">
            <div class="v-reveal">
                <span class="v-eyebrow">Community ownership</span>
                <h2 class="v-h2" id="ownership-heading">Youth first. Community owned.</h2>
                <p class="v-lead">
                    We do not arrive in Mumias to deliver something to the community. We work with it.
                    Young people shape the programmes, parents and schools are part of the design, and
                    local coaches and mentors carry the work forward.
                </p>
                <div class="v-btn-row v-btn-row--tight">
                    <x-site.button :href="route('site.get-involved')" variant="primary">Get involved</x-site.button>
                    <x-site.button :href="route('site.programs')" variant="ghost-light">See our programs</x-site.button>
                </div>
            </div>

            <div class="v-reveal" data-delay="1">
                <ul class="v-list">
                    <li><strong style="color:#fff">Young people lead.</strong> Committees, captains and youth facilitators hold real responsibility.</li>
                    <li><strong style="color:#fff">Parents and schools partner.</strong> Programmes are designed with the adults who support young people at home.</li>
                    <li><strong style="color:#fff">Local talent delivers.</strong> Coaches, mentors and facilitators are trained within the community.</li>
                    <li><strong style="color:#fff">Everyone is included.</strong> Girls and boys, younger and older players, with a real pathway for each.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<x-site.cta-section image="assets/img/gallery/team.jpeg" />

@endsection

