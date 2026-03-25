@extends('layouts.admin')

@section('title', 'Create Document - ' . $organization->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Document</h5>
                    <p class="text-muted mb-0">Create a new document for {{ $organization->name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('super-admin.organizations.documents.store', $organization) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Document Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('document_type') is-invalid @enderror" id="document_type" name="document_type" required>
                                        <option value="">Select Document Type</option>
                                        @foreach($documentTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('document_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $category)
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        @foreach($statuses as $key => $status)
                                            <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_template" name="is_template" value="1" {{ old('is_template') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_template">
                                            Mark as Template
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content (JSON)</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" placeholder='{"key": "value"}'>{{ old('content') }}</textarea>
                                    <div class="form-text">Optional JSON content for the document</div>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="file" class="form-label">Upload File</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx,.txt">
                                    <div class="form-text">Maximum file size: 10MB</div>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">Document Information</h6>
                                        <ul class="mb-0">
                                            <li>Version will be automatically set to 1.0</li>
                                            <li>Created by will be set to current user</li>
                                            <li>File uploads are optional</li>
                                            <li>Templates can be used as document bases</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('super-admin.organizations.documents.index', $organization) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Documents
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
