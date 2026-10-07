@extends('layouts.admin')

@section('title', 'Branding Management - ' . $organization->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Branding Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.organizations.documents.index', $organization) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark-text me-2"></i>Documents
                        </a>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal">
                            <i class="bi bi-eye me-2"></i>Preview
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('super-admin.organizations.branding.update', $organization) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Color Scheme -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Color Scheme</h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="primary_color" class="form-label">Primary Color</label>
                                        <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror" id="primary_color" name="primary_color" value="{{ old('primary_color', $branding->primary_color ?? $defaultColors['primary']) }}">
                                        <input type="text" class="form-control mt-2" value="{{ old('primary_color', $branding->primary_color ?? $defaultColors['primary']) }}" id="primary_color_text" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="secondary_color" class="form-label">Secondary Color</label>
                                        <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $branding->secondary_color ?? $defaultColors['secondary']) }}">
                                        <input type="text" class="form-control mt-2" value="{{ old('secondary_color', $branding->secondary_color ?? $defaultColors['secondary']) }}" id="secondary_color_text" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="accent_color" class="form-label">Accent Color</label>
                                        <input type="color" class="form-control form-control-color @error('accent_color') is-invalid @enderror" id="accent_color" name="accent_color" value="{{ old('accent_color', $branding->accent_color ?? $defaultColors['accent']) }}">
                                        <input type="text" class="form-control mt-2" value="{{ old('accent_color', $branding->accent_color ?? $defaultColors['accent']) }}" id="accent_color_text" readonly>
                                    </div>
                                </div>

                                <!-- Font Family -->
                                <div class="mb-3">
                                    <label for="font_family" class="form-label">Font Family</label>
                                    <select class="form-select @error('font_family') is-invalid @enderror" id="font_family" name="font_family">
                                        @foreach($fontFamilies as $value => $label)
                                            <option value="{{ $value }}" {{ (old('font_family', $branding->font_family ?? $defaultColors['font_family']) == $value) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('font_family')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Logo Uploads -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Main Logo</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                            @if($branding && $branding->logo_path)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::disk('public')->url($branding->logo_path) }}" alt="Current Logo" style="max-height: 100px;">
                                                    <small class="text-muted d-block mt-1">Current logo</small>
                                                </div>
                                            @endif
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="favicon" class="form-label">Favicon</label>
                                            <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" accept="image/*,.ico">
                                            @if($branding && $branding->favicon_path)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::disk('public')->url($branding->favicon_path) }}" alt="Current Favicon" style="max-height: 32px;">
                                                    <small class="text-muted d-block mt-1">Current favicon</small>
                                                </div>
                                            @endif
                                            @error('favicon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="header_logo" class="form-label">Header Logo</label>
                                            <input type="file" class="form-control @error('header_logo') is-invalid @enderror" id="header_logo" name="header_logo" accept="image/*">
                                            @if($branding && $branding->header_logo_path)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::disk('public')->url($branding->header_logo_path) }}" alt="Current Header Logo" style="max-height: 60px;">
                                                    <small class="text-muted d-block mt-1">Current header logo</small>
                                                </div>
                                            @endif
                                            @error('header_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="footer_logo" class="form-label">Footer Logo</label>
                                            <input type="file" class="form-control @error('footer_logo') is-invalid @enderror" id="footer_logo" name="footer_logo" accept="image/*">
                                            @if($branding && $branding->footer_logo_path)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::disk('public')->url($branding->footer_logo_path) }}" alt="Current Footer Logo" style="max-height: 60px;">
                                                    <small class="text-muted d-block mt-1">Current footer logo</small>
                                                </div>
                                            @endif
                                            @error('footer_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Templates and Settings -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Templates & Settings</h6>

                                <!-- Letterhead Template -->
                                <div class="mb-4">
                                    <label class="form-label">Letterhead Template</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_show_logo" name="letterhead_template[header][show_logo]" value="1" {{ (old('letterhead_template.header.show_logo', $branding->letterhead_template['header']['show_logo'] ?? $defaultLetterhead['header']['show_logo'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_show_logo">Show Logo</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_show_address" name="letterhead_template[header][show_address]" value="1" {{ (old('letterhead_template.header.show_address', $branding->letterhead_template['header']['show_address'] ?? $defaultLetterhead['header']['show_address'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_show_address">Show Address</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_show_contact" name="letterhead_template[header][show_contact]" value="1" {{ (old('letterhead_template.header.show_contact', $branding->letterhead_template['header']['show_contact'] ?? $defaultLetterhead['header']['show_contact'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_show_contact">Show Contact</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_show_date" name="letterhead_template[header][show_date]" value="1" {{ (old('letterhead_template.header.show_date', $branding->letterhead_template['header']['show_date'] ?? $defaultLetterhead['header']['show_date'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_show_date">Show Date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_footer_show_logo" name="letterhead_template[footer][show_logo]" value="1" {{ (old('letterhead_template.footer.show_logo', $branding->letterhead_template['footer']['show_logo'] ?? $defaultLetterhead['footer']['show_logo'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_footer_show_logo">Footer Show Logo</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_footer_show_address" name="letterhead_template[footer][show_address]" value="1" {{ (old('letterhead_template.footer.show_address', $branding->letterhead_template['footer']['show_address'] ?? $defaultLetterhead['footer']['show_address'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_footer_show_address">Footer Show Address</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_footer_show_contact" name="letterhead_template[footer][show_contact]" value="1" {{ (old('letterhead_template.footer.show_contact', $branding->letterhead_template['footer']['show_contact'] ?? $defaultLetterhead['footer']['show_contact'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_footer_show_contact">Footer Show Contact</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_footer_show_page_number" name="letterhead_template[footer][show_page_number]" value="1" {{ (old('letterhead_template.footer.show_page_number', $branding->letterhead_template['footer']['show_page_number'] ?? $defaultLetterhead['footer']['show_page_number'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_footer_show_page_number">Footer Show Page Number</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="letterhead_footer_show_disclaimer" name="letterhead_template[footer][show_disclaimer]" value="1" {{ (old('letterhead_template.footer.show_disclaimer', $branding->letterhead_template['footer']['show_disclaimer'] ?? $defaultLetterhead['footer']['show_disclaimer'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="letterhead_footer_show_disclaimer">Footer Show Disclaimer</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Template -->
                                <div class="mb-4">
                                    <label class="form-label">Email Template</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_show_logo" name="email_template[header][show_logo]" value="1" {{ (old('email_template.header.show_logo', $branding->email_template['header']['show_logo'] ?? $defaultEmail['header']['show_logo'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_show_logo">Header Show Logo</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_show_contact" name="email_template[footer][show_contact]" value="1" {{ (old('email_template.footer.show_contact', $branding->email_template['footer']['show_contact'] ?? $defaultEmail['footer']['show_contact'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_show_contact">Footer Show Contact</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_show_social" name="email_template[footer][show_social_media]" value="1" {{ (old('email_template.footer.show_social_media', $branding->email_template['footer']['show_social_media'] ?? $defaultEmail['footer']['show_social_media'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_show_social">Footer Show Social Media</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email_header_bg" class="form-label">Header Background Color</label>
                                                <input type="color" class="form-control form-control-color" id="email_header_bg" name="email_template[header][background_color]" value="{{ old('email_template.header.background_color', $branding->email_template['header']['background_color'] ?? $defaultEmail['header']['background_color']) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email_footer_bg" class="form-label">Footer Background Color</label>
                                                <input type="color" class="form-control form-control-color" id="email_footer_bg" name="email_template[footer][background_color]" value="{{ old('email_template.footer.background_color', $branding->email_template['footer']['background_color'] ?? $defaultEmail['footer']['background_color']) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Media Links -->
                                <div class="mb-3">
                                    <label for="social_media_links" class="form-label">Social Media Links</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="url" class="form-control mb-2" name="social_media_links[facebook]" placeholder="Facebook URL" value="{{ old('social_media_links.facebook', $branding->social_media_links['facebook'] ?? '') }}">
                                            <input type="url" class="form-control mb-2" name="social_media_links[twitter]" placeholder="Twitter URL" value="{{ old('social_media_links.twitter', $branding->social_media_links['twitter'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="url" class="form-control mb-2" name="social_media_links[instagram]" placeholder="Instagram URL" value="{{ old('social_media_links.instagram', $branding->social_media_links['instagram'] ?? '') }}">
                                            <input type="url" class="form-control mb-2" name="social_media_links[linkedin]" placeholder="LinkedIn URL" value="{{ old('social_media_links.linkedin', $branding->social_media_links['linkedin'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Brand Guidelines -->
                                <div class="mb-3">
                                    <label for="brand_guidelines" class="form-label">Brand Guidelines</label>
                                    <textarea class="form-control" name="brand_guidelines[guidelines]" rows="4" placeholder="Enter brand guidelines...">{{ old('brand_guidelines.guidelines', $branding->brand_guidelines['guidelines'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between border-top pt-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Save Branding
                                </button>
                                <form action="{{ route('super-admin.organizations.branding.reset', $organization) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset branding to defaults?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset to Defaults
                                    </button>
                                </form>
                                <a href="{{ route('super-admin.organizations.branding.export', $organization) }}" class="btn btn-outline-info">
                                    <i class="bi bi-download me-2"></i>Export Branding
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="bi bi-upload me-2"></i>Import Branding
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Branding Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="previewForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="preview_primary_color" class="form-label">Primary Color</label>
                                <input type="color" class="form-control form-control-color" id="preview_primary_color" name="primary_color" value="{{ old('primary_color', $branding->primary_color ?? $defaultColors['primary']) }}">
                            </div>
                            <div class="mb-3">
                                <label for="preview_secondary_color" class="form-label">Secondary Color</label>
                                <input type="color" class="form-control form-control-color" id="preview_secondary_color" name="secondary_color" value="{{ old('secondary_color', $branding->secondary_color ?? $defaultColors['secondary']) }}">
                            </div>
                            <div class="mb-3">
                                <label for="preview_accent_color" class="form-label">Accent Color</label>
                                <input type="color" class="form-control form-control-color" id="preview_accent_color" name="accent_color" value="{{ old('accent_color', $branding->accent_color ?? $defaultColors['accent']) }}">
                            </div>
                            <div class="mb-3">
                                <label for="preview_font_family" class="form-label">Font Family</label>
                                <select class="form-select" id="preview_font_family" name="font_family">
                                    @foreach($fontFamilies as $value => $label)
                                        <option value="{{ $value }}" {{ (old('font_family', $branding->font_family ?? $defaultColors['font_family']) == $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" id="previewCard" style="font-family: {{ old('font_family', $branding->font_family ?? $defaultColors['font_family']) }};">
                                <div class="card-header" style="background-color: {{ old('primary_color', $branding->primary_color ?? $defaultColors['primary']) }}; color: white;">
                                    <h6 class="mb-0">Preview Card</h6>
                                </div>
                                <div class="card-body" style="border: 2px solid {{ old('secondary_color', $branding->secondary_color ?? $defaultColors['secondary']) }};">
                                    <h5 style="color: {{ old('accent_color', $branding->accent_color ?? $defaultColors['accent']) }};">This is a preview</h5>
                                    <p class="text-muted">See how your branding looks in practice.</p>
                                    <button class="btn" style="background-color: {{ old('accent_color', $branding->accent_color ?? $defaultColors['accent']) }}; color: white;">Preview Button</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Branding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super-admin.organizations.branding.import', $organization) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Select Branding File</label>
                        <input type="file" class="form-control" id="import_file" name="import_file" accept=".json" required>
                        <div class="form-text">Upload a JSON file containing branding configuration.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Branding</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color input sync
    const colorInputs = ['primary_color', 'secondary_color', 'accent_color'];

    colorInputs.forEach(colorId => {
        const colorInput = document.getElementById(colorId);
        const textInput = document.getElementById(colorId + '_text');

        if (colorInput && textInput) {
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
                updatePreview(colorId, this.value);
            });
        }
    });

    // Font family preview
    const fontFamilySelect = document.getElementById('font_family');
    const previewCard = document.getElementById('previewCard');

    if (fontFamilySelect && previewCard) {
        fontFamilySelect.addEventListener('change', function() {
            previewCard.style.fontFamily = this.value;
        });
    }

    // Preview form updates
    const previewForm = document.getElementById('previewForm');
    if (previewForm) {
        previewForm.addEventListener('input', function(e) {
            if (e.target.name === 'primary_color') {
                const header = previewCard.querySelector('.card-header');
                if (header) header.style.backgroundColor = e.target.value;
            } else if (e.target.name === 'secondary_color') {
                const body = previewCard.querySelector('.card-body');
                if (body) body.style.borderColor = e.target.value;
            } else if (e.target.name === 'accent_color') {
                const h5 = previewCard.querySelector('h5');
                const btn = previewCard.querySelector('button');
                if (h5) h5.style.color = e.target.value;
                if (btn) btn.style.backgroundColor = e.target.value;
            } else if (e.target.name === 'font_family') {
                previewCard.style.fontFamily = e.target.value;
            }
        });
    }

    function updatePreview(colorId, value) {
        if (colorId === 'primary_color') {
            const header = previewCard.querySelector('.card-header');
            if (header) header.style.backgroundColor = value;
        } else if (colorId === 'secondary_color') {
            const body = previewCard.querySelector('.card-body');
            if (body) body.style.borderColor = value;
        } else if (colorId === 'accent_color') {
            const h5 = previewCard.querySelector('h5');
            const btn = previewCard.querySelector('button');
            if (h5) h5.style.color = value;
            if (btn) btn.style.backgroundColor = value;
        }
    }
});
</script>
@endpush
@endsection
