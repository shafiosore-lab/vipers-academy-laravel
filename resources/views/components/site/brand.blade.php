@props(['orgName' => 'Mumias Vipers CBO'])

 {{-- Shared logo lockup used by the header and the footer so the brand block
     is defined once. The visible name/sub are static parts of the logo; the
     image alt text uses the configured organisation name. --}}

<a class="v-brand" href="{{ route('site.home') }}">
    <img class="v-brand__mark" src="{{ asset('assets/img/logo/vps.jpeg') }}"
         alt="{{ $orgName }} logo" width="59" height="42">
    <span class="v-brand__text">
        <span class="v-brand__name">Mumias Vipers</span>
        <span class="v-brand__sub">CBO</span>
    </span>
</a>