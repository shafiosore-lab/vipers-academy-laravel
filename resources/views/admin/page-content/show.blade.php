@extends('layouts.admin')

@section('title', 'Manage ' . ucfirst($page) . ' Page Content')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-capitalize">{{ $page }} Page Content</h1>
            <p class="text-muted">Manage sections for this page</p>
        </div>
        <a href="{{ route('admin.page-content.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Pages
        </a>
    </div>

    <!-- Sections Grid -->
    <div class="row">
        @forelse($sections as $section)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                @if($section === 'journey')
                                    <i class="fas fa-route text-success fs-4"></i>
                                @elseif($section === 'values')
                                    <i class="fas fa-heart text-success fs-4"></i>
                                @else
                                    <i class="fas fa-list-alt text-success fs-4"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-0 text-capitalize">{{ str_replace('_', ' ', $section) }}</h5>
                                <small class="text-muted">Section</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.page-content.edit', ['page' => $page, 'section' => $section]) }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-edit me-2"></i>Edit Section
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No sections found for this page.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
