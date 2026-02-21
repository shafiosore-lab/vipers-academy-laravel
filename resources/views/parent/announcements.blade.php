@extends('layouts.staff')

@section('title', 'Announcements')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Announcements</h2>
        <p class="text-muted mb-0">Important updates from the academy</p>
    </div>
</div>

<!-- Filter by Type -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('parent.announcements') }}"
               class="btn btn-sm {{ !request('type') ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>
            <a href="{{ route('parent.announcements', ['type' => 'general']) }}"
               class="btn btn-sm {{ request('type') == 'general' ? 'btn-primary' : 'btn-outline-primary' }}">
                General
            </a>
            <a href="{{ route('parent.announcements', ['type' => 'training']) }}"
               class="btn btn-sm {{ request('type') == 'training' ? 'btn-primary' : 'btn-outline-primary' }}">
                Training
            </a>
            <a href="{{ route('parent.announcements', ['type' => 'match']) }}"
               class="btn btn-sm {{ request('type') == 'match' ? 'btn-primary' : 'btn-outline-primary' }}">
                Match
            </a>
            <a href="{{ route('parent.announcements', ['type' => 'payment']) }}"
               class="btn btn-sm {{ request('type') == 'payment' ? 'btn-primary' : 'btn-outline-primary' }}">
                Payment
            </a>
            <a href="{{ route('parent.announcements', ['type' => 'event']) }}"
               class="btn btn-sm {{ request('type') == 'event' ? 'btn-primary' : 'btn-outline-primary' }}">
                Events
            </a>
        </div>
    </div>
</div>

<!-- Announcements List -->
@if($announcements && $announcements->count() > 0)
<div class="row g-4">
    @foreach($announcements as $announcement)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $announcement->type == 'urgent' ? 'danger' : ($announcement->type == 'important' ? 'warning' : 'primary') }}">
                            {{ ucfirst($announcement->type ?? 'general') }}
                        </span>
                        @if($announcement->is_pinned)
                        <span class="badge bg-dark">
                            <i class="fas fa-thumbtack me-1"></i>Pinned
                        </span>
                        @endif
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $announcement->created_at->diffForHumans() }}
                    </small>
                </div>

                <h4 class="mb-3">{{ $announcement->title }}</h4>

                <div class="announcement-content">
                    {!! nl2br(e($announcement->content)) !!}
                </div>

                @if($announcement->attachment)
                <div class="mt-3 pt-3 border-top">
                    <a href="{{ asset('storage/' . $announcement->attachment) }}"
                       class="btn btn-sm btn-outline-primary"
                       target="_blank">
                        <i class="fas fa-paperclip me-1"></i>View Attachment
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $announcements->links() }}
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-bullhorn text-muted fs-1 mb-3 d-block opacity-25"></i>
        <h5>No Announcements</h5>
        <p class="text-muted mb-0">There are no announcements at this time.</p>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .announcement-content {
        color: #4a5568;
        line-height: 1.7;
    }
</style>
@endpush
