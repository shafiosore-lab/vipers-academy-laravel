@extends('layouts.admin')

@section('title', 'Website Players - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Website Players Management</h3>
                    <a href="{{ route('admin.website-players.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Add Player
                    </a>
                </div>
                <div class="card-body">
                    @if($players->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Position</th>
                                        <th>Age</th>
                                        <th>Jersey #</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td>
                                                @if($player->image_url)
                                                    <img src="{{ $player->image_url }}" alt="{{ $player->full_name }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                                        {{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $player->full_name }}</td>
                                            <td>{{ $player->formatted_category }}</td>
                                            <td>{{ $player->formatted_position }}</td>
                                            <td>{{ $player->age }}</td>
                                            <td>{{ $player->jersey_number ?: '-' }}</td>
                                            <td>
                                                <a href="{{ route('admin.website-players.show', $player) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.website-players.edit', $player) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.website-players.destroy', $player) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4>No Players Added Yet</h4>
                            <p class="text-muted">Start by adding players to display on the website.</p>
                            <a href="{{ route('admin.website-players.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Player
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
