@extends('layouts.admin')

@section('content')
<h1>Manage Programs</h1>
<a href="{{ route('admin.programs.create') }}" class="btn btn-primary mb-3">Add New Program</a>

<table class="table table-sm table-bordered" width="100%" cellspacing="0">
    <thead class="table-light">
        <tr class="small">
            <th class="py-2">Title</th>
            <th class="py-2">Age Group</th>
            <th class="py-2">Duration</th>
            <th class="py-2">Regular Fee</th>
            <th class="py-2">Mumias Fee</th>
            <th class="py-2">Discount</th>
            <th class="py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programs as $program)
        <tr>
            <td class="py-1 align-middle small">{{ $program->title }}</td>
            <td class="py-1 align-middle small">{{ $program->age_group }}</td>
            <td class="py-1 align-middle small">{{ $program->duration ?? 'N/A' }}</td>
            <td class="py-1 align-middle small">{{ $program->regular_fee ? 'KSH ' . number_format($program->regular_fee, 0) : 'N/A' }}</td>
            <td class="py-1 align-middle small">{{ $program->mumias_fee ? 'KSH ' . number_format($program->mumias_fee, 0) : 'N/A' }}</td>
            <td class="py-1 align-middle small">{{ $program->mumias_discount_percentage }}%</td>
            <td class="py-1 align-middle">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-sm btn-info py-0 px-1">View</a>
                    <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-warning py-0 px-1">Edit</a>
                    <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
