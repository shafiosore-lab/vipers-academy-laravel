@extends('layouts.admin')

@section('content')
<h1>Manage News</h1>
<a href="{{ route('admin.news.create') }}" class="btn btn-primary mb-3">Add New News</a>

<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($news as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->created_at->format('M d, Y') }}</td>
            <td>
                <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline">
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
