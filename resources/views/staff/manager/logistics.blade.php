@extends('layouts.staff')

@section('title', 'Logistics - Team Manager')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Logistics Management</h2>
                <a href="{{ route('manager.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Programs</h5>
                </div>
                <div class="card-body">
                    @if($upcomingPrograms->isEmpty())
                        <div class="alert alert-info">
                            No upcoming programs found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingPrograms as $program)
                                    <tr>
                                        <td>{{ $program->id }}</td>
                                        <td>{{ $program->name }}</td>
                                        <td>{{ $program->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $program->end_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $program->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ $program->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.programs.show', $program->id) }}" class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
