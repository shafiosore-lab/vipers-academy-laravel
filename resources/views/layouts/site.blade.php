<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('vipers.seo.title'))</title>
    <meta name="description" content="@yield('meta_description', config('vipers.seo.description'))">
    <meta name="keywords" content="{{ implode(', ', config('vipers.seo.keywords', [])) }}">
    <meta name="author" content="{{ config('vipers.org.name') }}">
    <meta name="theme-color" content="#062B57">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('vipers.org.name') }}">
    <meta property="og:title" content="@yield('title', config('vipers.seo.title'))">
    <meta property="og:description" content="@yield('meta_description', config('vipers.seo.description'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(config('vipers.seo.og_image')) }}">
    <meta property="og:image:alt" content="{{ config('vipers.org.name') }}">
    <meta property="og:locale" content="en_KE">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('vipers.seo.title'))">
    <meta name="twitter:description" content="@yield('meta_description', config('vipers.seo.description'))">
    <meta name="twitter:image" content="{{ asset(config('vipers.seo.og_image')) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700&display=swap">

    <link rel="icon" href="{{ asset('assets/img/logo/vps.jpeg') }}">

    @vite(['resources/css/site.css', 'resources/js/site.js'])

    @stack('head')
</head>
<body class="v-body">
    <a class="v-skip-link" href="#main">Skip to main content</a>

    @php
        $nav = config('vipers.nav', []);
        $cta = config('vipers.cta', []);
        $orgName = config('vipers.org.name', 'Mumias Vipers CBO');

        /* The header button and the drawer button share these values; resolve
         * them once so both stay in sync when the CTA config changes. */
        $ctaRoute = $cta['route'] ?? 'site.partnership';
        $ctaIcon = $cta['icon'] ?? 'handshake';
        $ctaLabel = $cta['label'] ?? 'Support Us';

        /*
         * Nav entries may carry an optional `slug` (e.g. "STEM Education" ->
         * site.programs.show). Build the URL from whichever parameters the
         * item actually supplies so route() never receives a missing argument.
         */
        $navUrl = function (array $item) {
            $params = array_filter([
                'slug' => $item['slug'] ?? null,
            ], fn ($v) => $v !== null && $v !== '');

            $url = route($item['route'], $params);

            return !empty($item['anchor']) ? $url . '#' . $item['anchor'] : $url;
        };
    @endphp

    {{-- ============================ NAVIGATION ============================ --}}
    <header class="v-nav v-nav--light">
        <div class="v-container v-nav__inner">
            <x-site.brand :org-name="$orgName" />

            <nav class="v-nav__links" aria-label="Primary">
                <ul>
                    @foreach ($nav as $item)
                        <li class="v-nav__item" @if (!empty($item['children'])) data-dropdown @endif>
                            <a class="v-nav__link"
                               href="{{ $navUrl($item) }}"
                               @if (request()->routeIs($item['route'])) aria-current="page" @endif
                               @if (!empty($item['children']))
                                   data-dropdown-trigger aria-expanded="false" aria-haspopup="true"
                               @endif>
                                {{ $item['label'] }}
                                @if (!empty($item['children']))
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                        <path d="M6 9l6 6 6-6"/>
                                    </svg>
                                @endif
                            </a>

                            @if (!empty($item['children']))
                                <ul class="v-nav__menu" data-dropdown-menu>
                                    @foreach ($item['children'] as $child)
                                        <li>
                                            <a href="{{ $navUrl($child) }}">
                                                {{ $child['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="v-nav__actions">
                <a class="v-nav__search" href="{{ route('site.gallery') }}" aria-label="Search the site">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>
                    </svg>
                </a>

                <x-site.button :href="route($ctaRoute)" variant="primary" size="sm" :icon="$ctaIcon">
                    {{ $ctaLabel }}
                </x-site.button>
            </div>

            {{-- The drawer toggle sits OUTSIDE .v-nav__actions on purpose: that
                 wrapper is display:none below 1080px, so nesting the button
                 inside it made the hamburger unreachable at every width. It is
                 its own flex child here and hides itself from 1080px up. --}}
            <button class="v-nav__toggle" type="button"
                    aria-label="Open menu" aria-expanded="false" aria-controls="v-mobile-menu">
                <span aria-hidden="true"></span>
            </button>
        </div>
    </header>


    {{-- ===================== MOBILE OFF-CANVAS MENU ===================== --}}
    <div class="v-mobile" id="v-mobile-menu" role="dialog" aria-modal="true" aria-label="Site menu">
        <div class="v-mobile__scrim" aria-hidden="true"></div>

        <div class="v-mobile__panel">
            <!-- Glassmorphism header -->
            <header class="v-mobile__head">
                <span class="v-mobile__wordmark">Venom</span>
                <button class="v-mobile__close" type="button" aria-label="Close menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </header>

            <!-- Navigation body -->
            <div class="v-mobile__body">
                <nav class="v-mobile__nav" aria-label="Main navigation">
                    <ul class="v-mobile__list">
                        @foreach ($nav as $item)
                            <li>
                                @if (!empty($item['children']))
                                    <button class="v-mobile__link v-mobile__link--parent" type="button"
                                            data-submenu-toggle aria-expanded="false"
                                            aria-controls="m-sub-{{ $item['label'] }}">
                                        {{ $item['label'] }}
                                        <svg class="v-mobile__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                            <path d="M6 9l6 6 6-6"/>
                                        </svg>
                                    </button>
                                    <div class="v-mobile__sub" id="m-sub-{{ $item['label'] }}" role="region" aria-label="{{ $item['label'] }} submenu">
                                        <div class="v-mobile__sub-inner">
                                            @foreach ($item['children'] as $child)
                                                <a href="{{ $navUrl($child) }}" class="v-mobile__sub-link">{{ $child['label'] }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a class="v-mobile__link" href="{{ $navUrl($item) }}"
                                       @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                                        {{ $item['label'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            <!-- Footer with CTA -->
            <footer class="v-mobile__foot">
                <x-site.button :href="route($ctaRoute)" variant="primary" :icon="$ctaIcon" block>
                    {{ $ctaLabel }}
                </x-site.button>
            </footer>
        </div>
    </div>

    <main id="main">
        @yield('content')
    </main>

    {{-- ============================== FOOTER ============================== --}}
    {{-- Deliberately minimal: logo, one wrapping row of primary links, and a
         single legal line. The organisation summary, the three column headings,
         the duplicate Get Involved list and the contact column were all removed
         — every link they carried is still reachable from the header and the
         mobile drawer, so nothing became unreachable. --}}
    <footer class="v-footer">
        <div class="v-container">
            <div class="v-footer__top">
                {{-- One wrapping row, identical on desktop and mobile. --}}
                <nav class="v-footer__nav" aria-label="Footer">
                    <a href="{{ route('site.about') }}">About</a>
                    <a href="{{ route('site.programs') }}">Programs</a>
                    <a href="{{ route('site.impact') }}">Impact</a>
                    <a href="{{ route('site.stories') }}">Stories</a>
                    <a href="{{ route('site.gallery') }}">Gallery</a>
                    <a href="{{ route('site.contact') }}">Contact</a>
                </nav>
            </div>

            <div class="v-footer__bar">
                <p>&copy; {{ date('Y') }} {{ $orgName }}. {{ config('vipers.footer.note') }}</p>
                <p><a href="{{ route('site.sitemap') }}">Sitemap</a></p>
            </div>
        </div>
    </footer>

    {{-- ============================ LIGHTBOX ============================ --}}
    <div class="v-lightbox" role="dialog" aria-modal="true" aria-label="Photo viewer">
        <div class="v-lightbox__bar">
            <span class="v-lightbox__count" aria-live="polite"></span>
            <button class="v-lightbox__btn v-lightbox__close" type="button" aria-label="Close photo viewer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>
        <div class="v-lightbox__stage">
            <button class="v-lightbox__btn v-lightbox__nav v-lightbox__nav--prev" type="button" aria-label="Previous photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M15 5l-7 7 7 7"/>
                </svg>
            </button>
            <img class="v-lightbox__img" src="" alt="">
            <button class="v-lightbox__btn v-lightbox__nav v-lightbox__nav--next" type="button" aria-label="Next photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <p class="v-lightbox__caption"></p>
    </div>

    @stack('scripts')
</body>
</html>
