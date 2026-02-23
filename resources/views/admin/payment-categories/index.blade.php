@extends('layouts.admin')

@section('title', 'Payment Categories - Mumias Vipers Academy')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Payment Categories</h1>
        <a href="{{ route('admin.payment-categories.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add
        </a>
    </div>

    <!-- Categories Grid -->
    <div class="row g-2">
        @foreach($categories as $category)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header py-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            {{ $category->name }}
                            @if(!$category->is_active)
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Inactive</span>
                            @endif
                        </h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link py-0 px-1" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item small" href="{{ route('admin.payment-categories.show', $category->id) }}">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                                <a class="dropdown-item small" href="{{ route('admin.payment-categories.edit', $category->id) }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <a class="dropdown-item small" href="{{ route('admin.payment-categories.toggle-status', $category->id) }}">
                                    <i class="fas fa-toggle-on me-1"></i> {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.payment-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item small text-danger">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                    <p class="text-muted small mb-2">{{ $category->description }}</p>

                    <div class="row text-center mb-2">
                        <div class="col-6">
                            <div class="small text-muted">Monthly</div>
                            <div class="fw-bold">KSh {{ number_format($category->monthly_amount, 0) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Joining Fee</div>
                            <div class="fw-bold">KSh {{ number_format($category->joining_fee, 0) }}</div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="row text-center">
                        <div class="col-3">
                            <div class="h6 mb-0">{{ $category->player_count }}</div>
                            <small class="text-muted">Players</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0 text-success">KSh {{ number_format($category->total_revenue, 0) }}</div>
                            <small class="text-muted">Revenue</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0 text-warning">KSh {{ number_format($category->pending_amount, 0) }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                        <div class="col-3">
                            <div class="h6 mb-0 text-danger">KSh {{ number_format($category->overdue_amount, 0) }}</div>
                            <small class="text-muted">Overdue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($categories->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <i class="fas fa-tags fa-2x text-muted mb-2"></i>
            <h6 class="text-muted">No Payment Categories</h6>
            <p class="text-muted small mb-2">Create your first payment category</p>
            <a href="{{ route('admin.payment-categories.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i>Create
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
