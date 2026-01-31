@extends('layouts.admin')
@section('title', 'Manage Blog - Admin Dashboard')

@section('content')
<h1>Manage Blog Posts</h1>
<a href="{{ route('admin.blog.create') }}" class="btn btn-primary mb-3">Add New Post</a>

<div class="card">
    <div class="card-body">
        @if($blogs->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ Str::limit($item->title, 50) }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->author }}</td>
                    <td>
                        @if($item->published_at)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.blog.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.blog.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this blog post?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-muted">No blog posts found. Create your first post!</p>
        @endif
    </div>
</div>
@endsection
