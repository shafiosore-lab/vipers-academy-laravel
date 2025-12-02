@extends('layouts.admin')

@section('content')
<h1>Manage Programs</h1>
<a href="{{ route('admin.programs.create') }}" class="btn btn-primary mb-3">Add New Program</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Age Group</th>
            <th>Duration</th>
            <th>Regular Fee (KSH)</th>
            <th>Mumias Fee (KSH)</th>
            <th>Discount (%)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programs as $program)
        <tr>
            <td>{{ $program->title }}</td>
            <td>{{ $program->age_group }}</td>
            <td>{{ $program->duration ?? 'N/A' }}</td>
            <td>{{ $program->regular_fee ? 'KSH ' . number_format($program->regular_fee, 0) : 'N/A' }}</td>
            <td>{{ $program->mumias_fee ? 'KSH ' . number_format($program->mumias_fee, 0) : 'N/A' }}</td>
            <td>{{ $program->mumias_discount_percentage }}%</td>
            <td>
                <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="d-inline">
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
