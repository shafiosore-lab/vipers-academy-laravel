@extends('layouts.academy')

@section('title', '{{ $league }} League Table - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    @include('standings.partials.league-table')
</div>
@endsection
