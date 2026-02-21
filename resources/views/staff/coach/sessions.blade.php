@extends('layouts.staff')

@section('title', 'Training Sessions - Coach Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Training Sessions</h2>
                            <p class="mb-0">Manage and view all training sessions</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Training Sessions</h5>
                </div>
                <div class="card-body">
                    @if(isset($sessions) && $sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td>
                                                <strong>{{ $session->title }}</strong>
                                                @if($session->description)
                                                <br><small class="text-muted">{{ Str::limit($session->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($session->scheduled_start_time)->format('M j, Y') }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($session->scheduled_start_time)->format('g:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($session->scheduled_end_time)->format('g:i A') }}
                                            </td>
                                            <td>{{ $session->location ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $session->status === 'active' ? 'success' : ($session->status === 'completed' ? 'secondary' : 'warning') }}">
                                                    {{ $session->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $sessions->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No training sessions found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
