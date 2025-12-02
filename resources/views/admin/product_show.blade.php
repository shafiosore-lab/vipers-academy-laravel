@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Product Details: {{ $product->name }}</h4>
                        <div>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Name:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $product->description }}</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>
                                        <span class="badge bg-{{ $product->category === 'new' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->category) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Stock:</th>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                                <tr>
                                    <th>SKU:</th>
                                    <td>{{ $product->sku ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @if($product->images && count($product->images) > 0)
                                <h5>Product Images</h5>
                                <div class="row">
                                    @foreach($product->images as $image)
                                        <div class="col-6 mb-3">
                                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Product Image">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No images uploaded for this product.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
