@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Football Matches</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Match
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-2">Opponent</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Match Date</th>
                                    <th class="py-2">Venue</th>
                                    <th class="py-2">Score</th>
                                    <th class="py-2">Images</th>
                                    <th class="py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($matches as $match)
                                <tr>
                                    <td class="py-1 align-middle small">
                                        <strong>{{ $match->opponent }}</strong>
                                        @if($match->tournament_name)
                                        <br><small class="text-muted">{{ $match->tournament_name }}</small>
                                        @endif
                                    </td>
                                    <td class="py-1 align-middle">
                                        <span class="badge bg-{{ $match->type === 'friendly' ? 'success' : 'info' }}">
                                            {{ ucfirst($match->type) }}
                                        </span>
                                    </td>
                                    <td class="py-1 align-middle">
                                        <span class="badge bg-{{ $match->status === 'completed' ? 'success' : ($match->status === 'upcoming' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($match->status) }}
                                        </span>
                                    </td>
                                    <td class="py-1 align-middle small">{{ $match->match_date->format('M d, Y H:i') }}</td>
                                    <td class="py-1 align-middle small">{{ $match->venue }}</td>
                                    <td class="py-1 align-middle small">
                                        @if($match->status === 'completed')
                                            <strong>{{ $match->vipers_score ?? 0 }} - {{ $match->opponent_score ?? 0 }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="py-1 align-middle">
                                        @if($match->images && count($match->images) > 0)
                                            <span class="badge bg-success">{{ count($match->images) }}</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="py-1 align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.matches.show', $match) }}" class="btn btn-sm btn-info py-0 px-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.matches.edit', $match) }}" class="btn btn-sm btn-warning py-0 px-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger py-0 px-1"
                                                        onclick="return confirm('Are you sure you want to delete this match?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No matches found</h5>
                                        <p class="text-muted">Start by adding your first football match.</p>
                                        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add First Match
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($matches->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $matches->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
