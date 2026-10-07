@extends('layouts.admin')

@section('title', 'Manage Leaders - Meet Our Leaders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Manage Leaders</h1>
            <p class="text-muted">Manage the "Meet Our Leaders" page content</p>
        </div>
        <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Leader
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Leaders</h6>
        </div>
        <div class="card-body">
            @if($leaders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Credentials</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaders as $leader)
                        <tr>
                            <td>{{ $leader->display_order }}</td>
                            <td>
                                @if($leader->photo_path)
                                <img src="{{ asset('storage/' . $leader->photo_path) }}"
                                     alt="{{ $leader->name }}"
                                     class="rounded-circle"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                @endif
                            </td>
                            <td>{{ $leader->name }}</td>
                            <td>{{ $leader->role }}</td>
                            <td>{{ $leader->credentials ?? '-' }}</td>
                            <td>
                                @if($leader->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.leaders.edit', $leader) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.leaders.toggle-status', $leader) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                                class="btn btn-sm {{ $leader->is_active ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $leader->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $leader->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.leaders.destroy', $leader) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this leader?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
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
            {{ $leaders->links() }}
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Leaders Found</h5>
                <p class="text-muted">Add your first leader to display on the "Meet Our Leaders" page.</p>
                <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Leader
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview on Website</h6>
        </div>
        <div class="card-body text-center">
            <p class="text-muted mb-3">See how this looks on the website</p>
            <a href="{{ route('staff') }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-external-link-alt"></i> View "Meet Our Leaders" Page
            </a>
        </div>
    </div>
</div>
@endsection

