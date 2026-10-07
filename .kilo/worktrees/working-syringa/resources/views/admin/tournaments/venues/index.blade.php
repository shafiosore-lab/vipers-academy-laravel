@extends('layouts.admin')

@section('title', 'Tournament Venues - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Tournament Venues</h1>
                    <p class="text-muted">{{ $tournament->name }}</p>
                </div>
                <div>
                    <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Back to Tournament
                    </a>
                    <a href="{{ route('tournaments.venues.create', $tournament) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Venue
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($venues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>Capacity</th>
                                        <th>Surface</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($venues as $venue)
                                        <tr>
                                            <td>
                                                <strong>{{ $venue->name }}</strong>
                                            </td>
                                            <td>{{ $venue->address ?? '-' }}</td>
                                            <td>{{ $venue->city ?? '-' }}</td>
                                            <td>{{ $venue->capacity ? number_format($venue->capacity) : '-' }}</td>
                                            <td>{{ $venue->surface_type ?? '-' }}</td>
                                            <td>
                                                @if($venue->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tournaments.venues.edit', [$tournament, $venue]) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('tournaments.venues.destroy', [$tournament, $venue]) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this venue?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
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
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt display-1 text-muted"></i>
                            <h4 class="mt-3">No Venues Yet</h4>
                            <p class="text-muted">Add venues to this tournament for match scheduling.</p>
                            <a href="{{ route('tournaments.venues.create', $tournament) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add First Venue
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
