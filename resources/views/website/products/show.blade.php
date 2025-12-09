@extends('layouts.product')

@section('title', $product->name . ' - Vipers Merchandise')

@section('content')
@include('website.products.product_detail_sections')
@endsection
