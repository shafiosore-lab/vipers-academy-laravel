@extends('layouts.staff')

@section('title', 'Player Details - Partner Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Player Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('partner.players') }}">Players</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        {{ substr($player->first_name ?? 'P', 0, 1) }}{{ substr($player->last_name ?? '', 0, 1) }}
                    </div>
                    <h4>{{ $player->full_name ?? 'N/A' }}</h4>
                    <p class="text-muted">{{ $player->position ?? 'N/A' }}</p>
                    <span class="badge bg-{{ $player->registration_status === 'Active' ? 'success' : 'warning' }}">
                        {{ $player->registration_status ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Player Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">First Name</strong>
                            <span>{{ $player->first_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Last Name</strong>
                            <span>{{ $player->last_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Date of Birth</strong>
                            <span>{{ $player->date_of_birth ? \Carbon\Carbon::parse($player->date_of_birth)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Age</strong>
                            <span>{{ $player->age ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Gender</strong>
                            <span>{{ $player->gender ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Program</strong>
                            <span>{{ $player->program->title ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Email</strong>
                            <span>{{ $player->email ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong class="text-muted d-block">Phone</strong>
                            <span>{{ $player->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('partner.player.edit', $player->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('partner.players') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
