@extends('layouts.admin')

@section('content')
<h1>Manage Jobs</h1>
<a href="{{ route('admin.jobs.create') }}" class="btn btn-primary mb-3">Add New Job</a>

<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Location</th>
            <th>Type</th>
            <th>Status</th>
            <th>Applications</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jobs as $job)
        <tr>
            <td>{{ $job->title }}</td>
            <td>{{ $job->location }}</td>
            <td>{{ ucfirst($job->type) }}</td>
            <td>
                <span class="badge bg-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($job->status) }}
                </span>
            </td>
            <td>{{ $job->applications->count() }}</td>
            <td>{{ $job->created_at->format('M d, Y') }}</td>
            <td>
                <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline">
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
