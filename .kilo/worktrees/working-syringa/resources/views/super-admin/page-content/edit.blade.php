@extends('layouts.admin')

@section('title', 'Edit Content - Website Sections')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-capitalize">{{ $page }} / {{ $section }}</h4>
            <p class="text-muted mb-0">Edit content items</p>
        </div>
        <div>
            <a href="{{ route('super-admin.page-content.show', $page) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Sections
            </a>
        </div>
    </div>

    <!-- Content Form -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Content Items</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.page-content.update', ['page' => $page, 'section' => $section]) }}">
                @csrf
                @method('PUT')

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="width: 20%">Key</th>
                            <th style="width: 50%">Value</th>
                            <th style="width: 15%">Type</th>
                            <th style="width: 15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contents as $content)
                            <tr>
                                <td>
                                    <strong>{{ $content->key }}</strong>
                                    @if($content->organization_id)
                                        <br><small class="text-info">Org: {{ $content->organization_id }}</small>
                                    @else
                                        <br><small class="text-success">Global</small>
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="contents[{{ $loop->index }}][id]" value="{{ $content->id }}">
                                    @if($content->type === 'textarea')
                                        <textarea name="contents[{{ $loop->index }}][value]" class="form-control form-control-sm" rows="3">{{ $content->value }}</textarea>
                                    @elseif($content->type === 'json')
                                        <textarea name="contents[{{ $loop->index }}][value]" class="form-control form-control-sm font-monospace" rows="3">{{ $content->value }}</textarea>
                                    @else
                                        <input type="text" name="contents[{{ $loop->index }}][value]" class="form-control form-control-sm" value="{{ $content->value }}">
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $content->type }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $content->is_active ? 'success' : 'secondary' }}">
                                        {{ $content->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
