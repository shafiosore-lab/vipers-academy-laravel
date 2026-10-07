@extends('layouts.admin')

@section('title', 'Edit Letterhead - Super Admin')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Letterhead</h5>
        <a href="{{ route('super-admin.letterhead.index', ['organization_id' => $letterhead->organization_id]) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.letterhead.update', $letterhead->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Letterhead Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ $letterhead->name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Style</label>
                            <select name="style" class="form-select" required>
                                @foreach($styles as $style)
                                <option value="{{ $style }}" {{ $letterhead->style == $style ? 'selected' : '' }}>{{ ucfirst($style) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Header Alignment</label>
                            <select name="header_alignment" class="form-select" required>
                                @foreach($alignments as $alignment)
                                <option value="{{ $alignment }}" {{ $letterhead->header_alignment == $alignment ? 'selected' : '' }}>{{ ucfirst($alignment) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Primary Color</label>
                            <input type="text" name="primary_color" class="form-control" required value="{{ $letterhead->primary_color }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Custom Footer Note</label>
                    <textarea name="custom_footer_note" class="form-control" rows="2">{{ $letterhead->custom_footer_note }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Signature Name</label>
                            <input type="text" name="signature_name" class="form-control" value="{{ $letterhead->signature_name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Signature Title</label>
                            <input type="text" name="signature_title" class="form-control" value="{{ $letterhead->signature_title }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Signature Image</label>
                    @if($letterhead->signature_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $letterhead->signature_image) }}" alt="Signature" style="max-height: 60px;">
                        <span class="text-muted ms-2">Current image</span>
                    </div>
                    @endif
                    <input type="file" name="signature_image" class="form-control" accept="image/*">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="show_watermark" class="form-check-input" id="show_watermark" {{ $letterhead->show_watermark ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_watermark">Show Watermark</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="show_page_numbers" class="form-check-input" id="show_page_numbers" {{ $letterhead->show_page_numbers ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_page_numbers">Show Page Numbers</label>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="is_default" class="form-check-input" id="is_default" {{ $letterhead->is_default ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">Set as Default Letterhead</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Letterhead
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
