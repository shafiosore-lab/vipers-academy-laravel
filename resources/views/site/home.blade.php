@extends('layouts.site')

@section('title', 'Mumias Vipers CBO — Football for a Brighter Future')
@section('meta_description', 'Mumias Vipers CBO is a community-rooted youth development organisation in Mumias, Kenya. We use football as a platform to connect young people with education, health information, STEM skills, scholarships and pathways into opportunity.')

@php
    $org = config('vipers.org', []);
    $programs = array_slice(config('vipers.programs', []), 0, 4);
    $heroImage = 'assets/img/home/hhh.jpeg';
    $why = config('vipers.why_football', []);
    $how = config('vipers.how_we_work', []);
    $pathway = config('vipers.scholarship_pathway', []);

    // The four programme themes, taken from the programmes themselves so the
    // hero never advertises something the organisation does not run.
    $themes = [];
    foreach ($programs as $p) {
        $themes[] = ['icon' => $p['icon'] ?? 'target', 'label' => $p['name'] ?? ''];
    }
@endphp

@section('content')
{{-- ======================== 1. HERO ======================== --}}
<section class="v-hero v-hero--home" style="background-image: url('{{ asset($heroImage) }}');">
    <span class="v-hero__scrim" aria-hidden="true"></span>

    <div class="v-container">
        <div class="v-hero__inner">
            <p class="v-hero__org">{{ $org['name'] ?? 'Mumias Vipers CBO' }}</p>

            <h1 class="v-display v-hero__title">
                Football for a<br> <em>brighter future</em>
            </h1>

            <p class="v-script">We don&rsquo;t just develop footballers. We use football to develop young people.</p>

            {{-- The full organisation summary lives in the About section.
                 Repeating it here pushed the band well past its designed height,
                 so the hero carries one short sentence instead. --}}
            <p class="v-hero__text">
                Mumias Vipers CBO uses football as a platform to connect young people with education,
                health information, STEM opportunities, scholarships, peacebuilding and pathways for
                personal development.
            </p>

            {{-- Theme row: 4 across on desktop, 2x2 on mobile --}}
            <ul class="v-hero__themes">
                @foreach ($themes as $theme)
                    <li class="v-hero__theme">
                        <x-site.icon :name="$theme['icon']" />
                        <span>{{ $theme['label'] }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="v-btn-row">
                <x-site.button :href="route('site.programs')" variant="primary" size="lg" icon="arrow">
                    Explore our programs
                </x-site.button>
                <x-site.button :href="route('site.partnership')" variant="ghost-light" size="lg" icon="handshake">
                    Partner with us
                </x-site.button>
            </div>
        </div>
    </div>
</section>

{{-- ====================== 2. WHY FOOTBALL? ====================== --}}
<div class="v-section-stack">
    <section class="v-section" aria-labelledby="why-heading">
        <div class="v-container">
            <div class="v-split v-about2">
                <div class="v-reveal">
                    <p class="v-eyebrow">{{ $why['eyebrow'] ?? 'Our operating model' }}</p>
                    <h2 class="v-h2" id="why-heading">{{ $why['title'] ?? 'Why football?' }}</h2>
                    <p class="v-script" style="color:var(--v-gold-600)">{{ $why['statement'] ?? '' }}</p>
                    <p class="v-body-text">{{ $why['body'] ?? '' }}</p>
                </div>

                <div class="v-reveal" data-delay="1">
                    <p class="v-eyebrow">A football session can become a doorway to</p>
                    <ul class="v-about2__values">
                        @foreach ($why['openings'] ?? [] as $opening)
                            <li><x-site.icon name="check" />{{ $opening }}</li>
                        @endforeach
                    </ul>
                    <div class="v-btn-row v-btn-row--tight">
                        <x-site.button :href="route('site.programs')" variant="ghost" size="sm" icon="arrow">
                            See how this works
                        </x-site.button>
                    </div>
                </div>
            </div>
        </div>
    </section>{{-- ====================== 2. OUR PROGRAMS (25 / 75) ====================== --}}
    <section class="v-section" aria-labelledby="home-programs-heading">
        <div class="v-container">
            <div class="v-progsplit">
                {{-- Left quarter: heading and link only --}}
                <div class="v-progsplit__intro v-reveal">
                    <p class="v-eyebrow">What we do</p>
                    <h2 class="v-h2" id="home-programs-heading">Our <em class="v-text-navy">Programs</em></h2>
                    <p class="v-lead" style="margin-bottom:1.25rem">
                        Four pathways, one goal: turn talent on the pitch into confidence,
                        skills and opportunity off it.
                    </p>
                    <x-site.button :href="route('site.programs')" variant="ghost" size="sm" icon="arrow">
                        View all programs
                    </x-site.button>
                </div>

                {{-- Right three quarters: four compact cards --}}
                <div class="v-progsplit__cards">
                    @foreach ($programs as $i => $program)
                        @php
                            $hasImage = !empty($program['image']) && @file_exists(public_path($program['image']));
                        @endphp
                        <article class="v-progcard v-reveal v-accent-{{ $program['accent'] ?? 'gold' }}"
                                 data-delay="{{ $i % 4 }}">
                            <div class="v-progcard__body">
                                <span class="v-progcard__icon">
                                    <x-site.icon :name="$program['icon'] ?? 'target'" />
                                </span>
                                <h3 class="v-progcard__title">{{ $program['name'] ?? '' }}</h3>
                                <p class="v-progcard__text">
                                    {{ \Illuminate\Support\Str::limit($program['tagline'] ?? ($program['summary'] ?? ''), 78) }}
                                </p>
                                <p class="v-progcard__cta">Learn More</p>
                            </div>

                            <div class="v-progcard__media">
                                @if ($hasImage)
                                    <img src="{{ asset($program['image']) }}" alt="{{ $program['image_alt'] ?? '' }}"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="v-img-placeholder" role="img" aria-label="Programme photo to be supplied">
                                        <span>Programme photo</span>
                                    </div>
                                @endif
                            </div>

                            <a class="v-card__overlay" href="{{ route('site.programs.show', $program['slug'] ?? '') }}">
                                <span class="v-visually-hidden">{{ $program['name'] ?? 'Programme' }} — learn more</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

{{-- ========== 3. EVIDENCE — the two facts we can stand behind ========== --}}
<section class="v-impactbar" aria-labelledby="home-evidence-heading">
    <div class="v-container">
        <div class="v-impactbar__inner">
            <div>
                <h2 class="v-impactbar__title" id="home-evidence-heading">
                    <em>Evidence</em> so far
                </h2>
            </div>

            <div class="v-impactbar__stats">
                @foreach (config('vipers.evidence', []) as $fact)
                    <div class="v-impactbar__stat">
                        <x-site.icon :name="$fact['type'] === 'number' ? 'target' : 'shield'" />
                        <div>
                            @if (($fact['type'] ?? '') === 'number')
                                <span class="v-impactbar__num">{{ $fact['value'] }}{{ $fact['suffix'] ?? '' }}</span>
                            @else
                                <span class="v-impactbar__num v-impactbar__num--text">{{ $fact['display'] ?? '' }}</span>
                            @endif
                            <span class="v-impactbar__label">{{ $fact['label'] ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <x-site.button :href="route('site.impact')" variant="primary" size="sm" icon="arrow">
                    See our impact
                </x-site.button>
            </div>
        </div>
    </div>
</section>

{{-- ===== 4. EDUCATION & SCHOLARSHIPS — our strongest evidenced pathway ===== --}}
<div class="v-section-stack">
    <section class="v-section" aria-labelledby="home-pathway-heading">
        <div class="v-container">
            <div class="v-split v-about2">
                <div class="v-reveal">
                    <p class="v-eyebrow">{{ $pathway['eyebrow'] ?? '' }}</p>
                    <h2 class="v-h2" id="home-pathway-heading">{{ $pathway['title'] ?? '' }}</h2>
                    <p class="v-body-text">{{ $pathway['lead'] ?? '' }}</p>
                    <p class="v-body-text">{{ $pathway['fact'] ?? '' }}</p>
                    <x-site.placeholder-note
                        eyebrow="How we describe this accurately"
                        :note="$pathway['precision'] ?? null" />
                </div>

                <div class="v-reveal" data-delay="1">
                    <ol class="v-pathway">
                        @foreach ($pathway['stages'] ?? [] as $i => $stage)
                            <li class="v-pathway__step">
                                <span class="v-pathway__num" aria-hidden="true">{{ $i + 1 }}</span>
                                <span class="v-pathway__body">
                                    <strong>{{ $stage['stage'] }}</strong>
                                    <span>{{ $stage['body'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- ============== 5. HOW WE WORK — the signature section ============== --}}
    <section class="v-section v-section--navy-deep v-on-dark" aria-labelledby="home-how-heading">
        <div class="v-container">
            <x-site.section-heading
                id="home-how-heading"
                :eyebrow="$how['eyebrow'] ?? 'How we work'"
                :title="$how['title'] ?? ''"
                :lead="$how['lead'] ?? ''"
                align="split" />

            <x-site.journey-steps :steps="$how['steps'] ?? []" />
        </div>
    </section>

    {{-- ================== 6. ABOUT US (organisational story) ================== --}}
    <section class="v-section v-section--surface" aria-labelledby="home-about-heading">
        <div class="v-container">
            <div class="v-split v-about2">
                <div class="v-about2__media v-reveal">
                    @php
                        $aboutImage = 'assets/img/home/teamb.jpg';
                    @endphp
                    @if (@file_exists(public_path($aboutImage)))
                        <img src="{{ asset($aboutImage) }}"
                             alt="Mumias Vipers team — young players and coaches at a community session"
                             loading="lazy" decoding="async">
                    @else
                        <div class="v-img-placeholder" role="img" aria-label="Community photograph to be supplied">
                            <x-site.icon name="quote" />
                            <span>Community photograph</span>
                        </div>
                    @endif
                </div>

                <div class="v-reveal" data-delay="1">
                    <p class="v-eyebrow">Who we are</p>
                    <h2 class="v-h2" id="home-about-heading">Dream. Learn. Play. Achieve.</h2>
                    <p class="v-script" style="color:var(--v-gold-600)">Football is our platform. Youth development is our purpose.</p>

                    <p class="v-body-text">{{ $org['summary'] ?? '' }}</p>
                    <p class="v-body-text">{{ $org['why_we_exist'] ?? '' }}</p>

                    <div class="v-btn-row">
                        <x-site.button :href="route('site.about')" variant="navy" icon="arrow">
                            More about us
                        </x-site.button>
                        <x-site.button :href="route('site.get-involved')" variant="ghost">
                            Work with us
                        </x-site.button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== 7. PARTNERSHIPS ==================== --}}
    <section class="v-section v-section--surface" aria-labelledby="home-partners-heading">
        <div class="v-container">
            <x-site.section-heading
                id="home-partners-heading"
                eyebrow="Partnerships"
                title="Partnerships make impact possible"
                :lead="config('vipers.partnerships.principle') ?? ''"
                align="split-group" />

            {{-- Only relationships we can describe accurately. No logos are shown
                 without written permission, so these are named, not branded. --}}
            <div class="v-grid v-grid--2">
                @foreach (config('vipers.partnerships.existing', []) as $i => $partner)
                    <div class="v-card v-card--compact v-accent-gold v-reveal" data-delay="{{ $i % 2 }}">
                        <span class="v-badge v-badge--gold">{{ ucfirst($partner['status'] ?? 'partner') }}</span>
                        <h3 class="v-card__title v-h4">{{ $partner['name'] }}</span>
                        <p class="v-card__text">{{ $partner['note'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="v-btn-row v-btn-row--section">
                <x-site.button :href="route('site.partnership')" variant="ghost" size="sm" icon="arrow">
                    How to partner with us
                </x-site.button>
            </div>
        </div>
    </section>
</div>

@endsection