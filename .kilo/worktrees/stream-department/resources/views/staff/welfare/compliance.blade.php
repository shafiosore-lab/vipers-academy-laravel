@extends('layouts.staff')

@section('title', 'Document Compliance - Welfare Officer - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Document Compliance</h2>
                            <p class="mb-0">Track player document status and required submissions</p>
                        </div>
                        <a href="{{ route('welfare.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="filterStatus">
                                <option value="">All Statuses</option>
                                <option value="signed">Signed</option>
                                <option value="pending">Pending</option>
                                <option value="pending_review">Pending Review</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterAgeGroup">
                                <option value="">All Age Groups</option>
                                <option value="U8">U8</option>
                                <option value="U10">U10</option>
                                <option value="U12">U12</option>
                                <option value="U14">U14</option>
                                <option value="U16">U16</option>
                                <option value="U18">U18</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search by player name..." id="searchPlayer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players with Documents -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($players) && $players->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Category</th>
                                        <th>Registration Status</th>
                                        <th>Documents Completed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td>
                                                <strong>{{ $player->full_name }}</strong>
                                            </td>
                                            <td>{{ $player->category ?? 'N/A' }}</td>
                                            <td>
                                                @if($player->registration_status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($player->registration_status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $player->registration_status ?? 'Unknown' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($player->documents_completed)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Complete
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Incomplete
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('players.show', $player->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View Profile
                                                </a>
                                                <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $players->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open text-muted display-1"></i>
                            <h3 class="mt-3">No Player Data</h3>
                            <p class="text-muted">No player records found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
