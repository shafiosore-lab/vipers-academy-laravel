@extends('layouts.admin')

@section('title', 'Page Content Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Page Content Management</h1>
            <p class="text-muted">Manage content for website pages</p>
        </div>
    </div>

    <!-- Pages Grid -->
    <div class="row">
        @forelse($pages as $page)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                <i class="fas fa-file-alt text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 text-capitalize">{{ $page }}</h5>
                                <small class="text-muted">Page Content</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.page-content.show', $page) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-edit me-2"></i>Manage Content
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No page content found. Please run migrations and seeders.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
