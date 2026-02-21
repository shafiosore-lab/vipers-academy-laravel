@extends('layouts.staff')

@section('title', 'Players - Coach Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Players Management</h2>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('coach.players') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Search by name or email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="age_group" class="form-label">Age Group</label>
                        <select class="form-select" id="age_group" name="age_group">
                            <option value="">All Age Groups</option>
                            @foreach($ageGroups as $ageGroup)
                                <option value="{{ $ageGroup }}" {{ request('age_group') == $ageGroup ? 'selected' : '' }}>
                                    {{ $ageGroup }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Players Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Players ({{ $players->total() }})</h5>
            </div>
            <div class="card-body">
                @if($players->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Age Group</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players as $player)
                                    <tr>
                                        <td>{{ $player->id }}</td>
                                        <td>
                                            <strong>{{ $player->first_name }} {{ $player->last_name }}</strong>
                                        </td>
                                        <td>{{ $player->email ?? 'N/A' }}</td>
                                        <td>{{ $player->age_group ?? 'N/A' }}</td>
                                        <td>
                                            @if($player->registration_status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($player->registration_status == 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $player->registration_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $player->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <a href="{{ route('coach.player.progress', $player->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
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
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No players found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
