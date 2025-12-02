@extends('layouts.academy')

@section('title', 'Gallery - Vipers Academy')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Photo Gallery</h1>

    @foreach($galleries as $gallery)
    <div class="mb-5">
        <h3>{{ $gallery->title }}</h3>
        <p>{{ $gallery->description }}</p>
        <div class="row">
            @if($gallery->images)
                @foreach(json_decode($gallery->images) as $image)
                <div class="col-md-3 mb-3">
                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Gallery image">
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
