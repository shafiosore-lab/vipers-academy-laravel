@extends('layouts.admin')

@section('title', 'Payment Categories - Mumias Vipers Academy')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Payment Categories</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('admin.payment-categories.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-6">
                <div class="card {{ $category->is_active ? '' : 'card-secondary' }}">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $category->name }}
                            @if(!$category->is_active)
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.payment-categories.show', $category->id) }}">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.payment-categories.edit', $category->id) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.payment-categories.toggle-status', $category->id) }}">
                                        <i class="fas fa-toggle-on"></i> {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.payment-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $category->description }}</p>

                        <div class="row mt-3">
                            <div class="col-6">
                                <dl>
                                    <dt>Monthly Amount</dt>
                                    <dd>KSh {{ number_format($category->monthly_amount, 2) }}</dd>
                                </dl>
                            </div>
                            <div class="col-6">
                                <dl>
                                    <dt>Joining Fee</dt>
                                    <dd>KSh {{ number_format($category->joining_fee, 2) }}</dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <div class="row text-center">
                            <div class="col-3">
                                <div class="h4 mb-0">{{ $category->player_count }}</div>
                                <small class="text-muted">Players</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 mb-0 text-success">KSh {{ number_format($category->total_revenue, 0) }}</div>
                                <small class="text-muted">Revenue</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 mb-0 text-warning">KSh {{ number_format($category->pending_amount, 0) }}</div>
                                <small class="text-muted">Pending</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 mb-0 text-danger">KSh {{ number_format($category->overdue_amount, 0) }}</div>
                                <small class="text-muted">Overdue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($categories->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4>No Payment Categories</h4>
                <p class="text-muted">Create your first payment category to get started.</p>
                <a href="{{ route('admin.payment-categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Category
                </a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
