@extends('layouts.staff')

@section('title', 'Registrations - Team Manager')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Player Registrations</h2>
                <a href="{{ route('manager.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($registrations->isEmpty())
                        <div class="alert alert-info">
                            No registrations found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Registered Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->id }}</td>
                                        <td>{{ $registration->full_name ?? $registration->name }}</td>
                                        <td>{{ $registration->category ?? 'N/A' }}</td>
                                        <td>{{ $registration->position ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $registration->registration_status === 'Active' ? 'success' : 'warning' }}">
                                                {{ $registration->registration_status }}
                                            </span>
                                        </td>
                                        <td>{{ $registration->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.players.show', $registration->id) }}" class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $registrations->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
