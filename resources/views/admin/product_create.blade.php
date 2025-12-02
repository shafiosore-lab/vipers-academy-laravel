@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Product</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                                       id="price" name="price" value="{{ old('price') }}" required>
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock Quantity *</label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                                   id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category *</label>
                                            <select class="form-control @error('category') is-invalid @enderror"
                                                    id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="new" {{ old('category') === 'new' ? 'selected' : '' }}>New</option>
                                                <option value="old" {{ old('category') === 'old' ? 'selected' : '' }}>Old</option>
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                                   id="sku" name="sku" value="{{ old('sku') }}">
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (Product will be visible to customers)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Images</label>
                                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                                   id="images" name="images[]" multiple accept="image/*">
                                            @error('images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Optional: Upload multiple product images (JPG, PNG, GIF, max 2MB each)</div>
                                        </div>

                                        <div class="alert alert-primary">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Image Guidelines:</strong><br>
                                                • Recommended size: 800x800px<br>
                                                • File formats: JPG, PNG, GIF<br>
                                                • Maximum file size: 2MB per image<br>
                                                • You can upload multiple images
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
