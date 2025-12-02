@extends('layouts.product')

@section('title', $product->name . ' - Vipers Merchandise')

@section('content')
@include('product.product_detail_sections')
@endsection
