@extends('layouts.admin')

@section('title', 'Gallery Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-images me-2"></i>Gallery Management
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">Manage gallery items for the Vipers Academy website. Add, edit, or remove images and videos.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <!-- Gallery Items Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Gallery Items</h5>
                        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Gallery Item
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($galleries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Media Type</th>
                                        <th>Media URL</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($galleries as $gallery)
                                    <tr>
                                        <td>#{{ $gallery->id }}</td>
                                        <td>
                                            <strong>{{ $gallery->title }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $gallery->media_type === 'image' ? 'success' : 'info' }}">
                                                <i class="fas fa-{{ $gallery->media_type === 'image' ? 'image' : 'video' }} me-1"></i>
                                                {{ ucfirst($gallery->media_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ $gallery->media_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">
                                                {{ $gallery->media_url }}
                                                <i class="fas fa-external-link-alt ms-1 text-muted"></i>
                                            </a>
                                        </td>
                                        <td>{{ $gallery->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.gallery.destroy', $gallery) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this gallery item?')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $galleries->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-images fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No gallery items yet</h5>
                            <p class="text-muted mb-4">Start building your gallery by adding your first item.</p>
                            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Gallery Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid Preview -->
    @if($galleries->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Gallery Preview</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                        @foreach($galleries->take(12) as $gallery)
                        <div class="col">
                            <div class="card h-100">
                                @if($gallery->media_type === 'image')
                                    <div class="position-relative" style="height: 200px;">
                                        <img src="{{ $gallery->media_url }}"
                                             class="card-img-top h-100 w-100 object-cover"
                                             alt="{{ $gallery->title }}"
                                             onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-image"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="position-relative" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                            <i class="fas fa-video fa-3x"></i>
                                        </div>
                                        <span class="badge bg-info position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-video"></i>
                                        </span>
                                    </div>
                                @endif
                                <div class="card-body p-3">
                                    <h6 class="card-title text-truncate">{{ $gallery->title }}</h6>
                                    <small class="text-muted">{{ $gallery->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.object-cover {
    object-fit: cover;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-img-top {
    transition: transform 0.2s;
}

.card-img-top:hover {
    transform: scale(1.05);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
@endsection
