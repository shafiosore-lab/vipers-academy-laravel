@extends('layouts.admin')

@section('title', 'View Gallery Item')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>Gallery Item Details
                    </h4>
                </div>
            </div>

            <!-- Content -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <!-- Media Display -->
                    <div class="text-center mb-4">
                        @if($gallery->media_type === 'image')
                            <img src="{{ $gallery->media_url }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 400px;"
                                 alt="{{ $gallery->title }}">
                        @elseif($gallery->media_type === 'video')
                            <video controls class="img-fluid rounded shadow" style="max-height: 400px;">
                                <source src="{{ $gallery->media_url }}" type="video/mp4">
                                <source src="{{ $gallery->media_url }}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 120px;">ID:</td>
                                    <td>#{{ $gallery->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Title:</td>
                                    <td>{{ $gallery->title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Media Type:</td>
                                    <td>
                                        <span class="badge bg-{{ $gallery->media_type === 'image' ? 'success' : 'info' }}">
                                            <i class="fas fa-{{ $gallery->media_type === 'image' ? 'image' : 'video' }} me-1"></i>
                                            {{ ucfirst($gallery->media_type) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Technical Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 140px;">Created:</td>
                                    <td>{{ $gallery->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Updated:</td>
                                    <td>{{ $gallery->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">URL:</td>
                                    <td>
                                        <a href="{{ $gallery->media_url }}" target="_blank" class="text-truncate">
                                            {{ $gallery->media_url }}
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <small class="text-muted">
                            Gallery items are automatically displayed on the public website.
                        </small>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.img-fluid {
    width: 100%;
    height: auto;
}

.table td {
    vertical-align: top;
}
</style>
@endsection
