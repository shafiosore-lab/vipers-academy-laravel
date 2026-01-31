@extends('layouts.admin')

@section('content')
<h1>Manage Gallery</h1>
<a href="{{ route('admin.gallery.create') }}" class="btn btn-primary mb-3">Add New Gallery</a>

<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($galleries as $gallery)
        <tr>
            <td>{{ $gallery->title }}</td>
            <td>{{ Str::limit($gallery->description, 50) }}</td>
            <td>
                <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
