@extends('layouts.academy')

@section('title', '{{ $league }} Top Scorers - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    @include('standings.partials.top-scorers')
</div>
@endsection
