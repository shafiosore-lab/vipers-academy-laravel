@extends('layouts.academy')

@section('title', '{{ $league }} Clean Sheets - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    @include('standings.partials.clean-sheets')
</div>
@endsection
