@extends('layouts.academy')

@section('title', '{{ $league }} Goalkeeper Rankings - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    @include('standings.partials.goalkeepers')
</div>
@endsection
