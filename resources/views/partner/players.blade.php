@extends('layouts.staff')

@section('title', 'Players - Partner Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Managed Players</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Players</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('partner.player.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Register New Player
            </a>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('partner.export') }}" class="btn btn-outline-secondary">
                <i class="fas fa-download me-1"></i> Export Data
            </a>
        </div>
    </div>

    <!-- Players Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($players->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Age</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            {{ substr($player->first_name ?? 'P', 0, 1) }}{{ substr($player->last_name ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $player->full_name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $player->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $player->position ?? 'N/A' }}</td>
                                <td>{{ $player->age ?? 'N/A' }}</td>
                                <td>{{ $player->program->title ?? 'N/A' }}</td>
                                <td>
                                    @if($player->registration_status === 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($player->registration_status === 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $player->registration_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('partner.player.show', $player->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('partner.player.edit', $player->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($players instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-3">
                        {{ $players->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted fa-4x mb-3"></i>
                    <h5 class="text-muted">No Players Found</h5>
                    <p class="text-muted">You haven't registered any players yet.</p>
                    <a href="{{ route('partner.player.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Register Your First Player
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
