@extends('layouts.admin')
@section('title', 'Manage Blog - Admin Dashboard')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Blog Posts</h1>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add
        </a>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-2">
            @if($blogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">ID</th>
                            <th class="py-1 px-2">Title</th>
                            <th class="py-1 px-2">Category</th>
                            <th class="py-1 px-2">Author</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2">Date</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $item)
                        <tr>
                            <td class="py-1 align-middle small">{{ $item->id }}</td>
                            <td class="py-1 align-middle small">{{ Str::limit($item->title, 40) }}</td>
                            <td class="py-1 align-middle small">{{ $item->category }}</td>
                            <td class="py-1 align-middle small">{{ $item->author }}</td>
                            <td class="py-1 align-middle">
                                @if($item->published_at)
                                    <span class="badge bg-success-subtle text-success" style="font-size: 10px;">Published</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning" style="font-size: 10px;">Draft</span>
                                @endif
                            </td>
                            <td class="py-1 align-middle small">{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.blog.edit', $item) }}" class="btn btn-sm btn-warning py-0 px-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Delete this post?')">
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
            @else
            <div class="text-center py-4">
                <i class="fas fa-newspaper fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">No blog posts found</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.btn-group .btn {
    margin-right: 2px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
