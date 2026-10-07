@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0">Manage Jobs</h1>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i>Add
        </a>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-1 px-2">Title</th>
                            <th class="py-1 px-2">Location</th>
                            <th class="py-1 px-2">Type</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2">Apps</th>
                            <th class="py-1 px-2">Date</th>
                            <th class="py-1 px-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td class="py-1 align-middle small">{{ $job->title }}</td>
                            <td class="py-1 align-middle small">{{ $job->location }}</td>
                            <td class="py-1 align-middle small">{{ ucfirst($job->type) }}</td>
                            <td class="py-1 align-middle">
                                <span class="badge bg-{{ $job->status === 'open' ? 'success-subtle' : ($job->status === 'closed' ? 'danger-subtle' : 'secondary-subtle') }} text-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'danger' : 'secondary') }}" style="font-size: 10px;">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="py-1 align-middle small">{{ $job->applications->count() }}</td>
                            <td class="py-1 align-middle small">{{ $job->created_at->format('M d, Y') }}</td>
                            <td class="py-1 align-middle">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-info py-0 px-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning py-0 px-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Are you sure?')">
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
