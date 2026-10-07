@props(['groups' => []])

{{--
    Partners and sponsors.

    No logos are fabricated. A partner without a confirmed logo file renders as
    a labelled placeholder slot, and the group note explains what is still
    needed — so the page is honest rather than full of invented brands.
--}}

@if (!empty($groups))
    <div class="v-partners">
        @foreach ($groups as $group)
            <div>
                <h3 class="v-partner-group__title">{{ $group['title'] ?? '' }}</h3>

                <div class="v-partner-grid">
                    @foreach ($group['partners'] ?? [] as $partner)
                        @php
                            $hasLogo = !empty($partner['logo'])
                                && ($partner['confirmed'] ?? false)
                                && @file_exists(public_path($partner['logo']));
                        @endphp

                        @if ($hasLogo)
                            <div class="v-partner">
                                <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] ?? '' }}"
                                     loading="lazy" decoding="async">
                            </div>
                        @else
                            <div class="v-partner v-partner--placeholder">
                                <span>{{ $partner['name'] ?? 'Partner slot' }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if (!empty($group['note']))
                    <p class="v-partner__note">{{ $group['note'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
