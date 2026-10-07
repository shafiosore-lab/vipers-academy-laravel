@extends('layouts.admin')

@section('title', 'Edit Leader - Meet Our Leaders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Leader</h1>
            <p class="text-muted">Update leader information</p>
        </div>
        <a href="{{ route('admin.leaders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.leaders.update', $leader) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Leader Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $leader->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role/Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('role') is-invalid @enderror"
                                   id="role" name="role" value="{{ old('role', $leader->role) }}"
                                   placeholder="e.g., Academy Director, Technical Director" required>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="credentials" class="form-label">Credentials</label>
                            <input type="text" class="form-control @error('credentials') is-invalid @enderror"
                                   id="credentials" name="credentials" value="{{ old('credentials', $leader->credentials) }}"
                                   placeholder="e.g., PhD Sports Management, UEFA Pro License">
                            @error('credentials')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Qualifications, certifications, or licenses</small>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biography</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="5"
                                      placeholder="Brief biography or description">{{ old('bio', $leader->bio) }}</textarea>
                            @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                            <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                   id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $leader->linkedin_url) }}"
                                   placeholder="https://linkedin.com/in/...">
                            @error('linkedin_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Photo & Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            @if($leader->photo_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $leader->photo_path) }}"
                                     alt="{{ $leader->name }}"
                                     class="rounded"
                                     style="max-width: 100%; max-height: 200px;">
                            </div>
                            @endif
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                   id="photo" name="photo" accept="image/*">
                            @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended: Square image, max 2MB (JPEG, PNG, JPG, GIF)</small>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                   id="display_order" name="display_order" value="{{ old('display_order', $leader->display_order) }}" min="0">
                            @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input"
                                   id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $leader->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible on website)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Leader
                    </button>
                    <a href="{{ route('admin.leaders.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

