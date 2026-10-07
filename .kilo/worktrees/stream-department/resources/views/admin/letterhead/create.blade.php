@extends('layouts.admin')

@section('title', 'Create Letterhead')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-plus-circle"></i> Create Letterhead</h1>
        <a href="{{ route('admin.letterhead.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Letterhead Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.letterhead.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Letterhead Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', 'Default Letterhead') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="style" class="form-label">Style</label>
                                <select class="form-select" id="style" name="style" required>
                                    @foreach($styles as $value => $label)
                                    <option value="{{ $value }}" {{ old('style') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="header_alignment" class="form-label">Header Alignment</label>
                                <select class="form-select" id="header_alignment" name="header_alignment" required>
                                    @foreach($alignments as $value => $label)
                                    <option value="{{ $value }}" {{ old('header_alignment', 'left') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="primary_color" class="form-label">Primary Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ old('primary_color', '#ea1c4d') }}">
                                <input type="text" class="form-control" id="primary_color_text" value="{{ old('primary_color', '#ea1c4d') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_watermark" name="show_watermark" value="1" {{ old('show_watermark', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_watermark">
                                    Show Watermark
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_page_numbers" name="show_page_numbers" value="1" {{ old('show_page_numbers', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_page_numbers">
                                    Show Page Numbers
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label" for="is_default">
                                    Set as Default
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="custom_footer_note" class="form-label">Custom Footer Note (Optional)</label>
                            <input type="text" class="form-control" id="custom_footer_note" name="custom_footer_note" value="{{ old('custom_footer_note') }}" placeholder="e.g., Excellence in Sports">
                        </div>

                        <hr>

                        <h6>Signature (Optional)</h6>

                        <div class="mb-3">
                            <label for="signature_image" class="form-label">Signature Image</label>
                            <input type="file" class="form-control" id="signature_image" name="signature_image" accept="image/*">
                            <small class="text-muted">Upload a transparent PNG or JPG signature (max 2MB)</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="signature_name" class="form-label">Signatory Name</label>
                                <input type="text" class="form-control" id="signature_name" name="signature_name" value="{{ old('signature_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="signature_title" class="form-label">Signatory Title</label>
                                <input type="text" class="form-control" id="signature_title" name="signature_title" value="{{ old('signature_title') }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Letterhead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Preview</h5>
                </div>
                <div class="card-body">
                    <div class="border p-3 bg-white" style="min-height: 200px;">
                        <div id="preview-header">
                            <table width="100%">
                                <tr>
                                    <td width="60" valign="top">
                                        <div style="width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    </td>
                                    <td valign="top">
                                        <h6 style="margin: 0; color: #ea1c4d;">{{ $organization->name }}</h6>
                                        <small class="text-muted">Contact info will appear here</small>
                                    </td>
                                </tr>
                            </table>
                            <hr style="border-top: 2px solid #ea1c4d; margin: 10px 0;">
                        </div>
                        <div style="padding: 20px 0; color: #ccc;">
                            Document content will appear here...
                        </div>
                        <hr style="border-top: 1px solid #ddd; margin: 10px 0;">
                        <small class="text-muted">Page 1 of 1</small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small text-muted">
                        <li>Use your organization's primary brand color</li>
                        <li>Keep the logo clear and high resolution</li>
                        <li>Add a signature for official documents</li>
                        <li>Set one letterhead as default for auto-selection</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Sync color picker with text input
    document.getElementById('primary_color').addEventListener('input', function(e) {
        document.getElementById('primary_color_text').value = e.target.value;
        updatePreview();
    });

    document.getElementById('primary_color_text').addEventListener('input', function(e) {
        if(/^#[0-9A-F]{6}$/i.test(e.target.value)) {
            document.getElementById('primary_color').value = e.target.value;
            updatePreview();
        }
    });

    document.getElementById('header_alignment').addEventListener('change', updatePreview);

    function updatePreview() {
        const color = document.getElementById('primary_color').value;
        const alignment = document.getElementById('header_alignment').value;

        // Update preview colors
        document.querySelectorAll('#preview-header hr').forEach(function(el) {
            el.style.borderTopColor = color;
        });
        document.querySelector('#preview-header h6').style.color = color;
    }
</script>
@endpush
@endsection
