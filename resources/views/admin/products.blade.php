@extends('layouts.admin')

@section('content')
<h1>Manage Products</h1>
<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Add New Product</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>
                <span class="badge bg-{{ $product->category === 'new' ? 'success' : 'secondary' }}">
                    {{ ucfirst($product->category) }}
                </span>
            </td>
            <td>${{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
