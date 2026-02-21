@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Company Branding Settings</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.company.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Company Information</h5>

                                <div class="form-group mb-3">
                                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name"
                                           name="company_name"
                                           value="{{ old('company_name', $settings->company_name ?? 'Mumias Vipers Academy') }}"
                                           required>
                                    @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="company_email">Official Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('company_email') is-invalid @enderror"
                                           id="company_email"
                                           name="company_email"
                                           value="{{ old('company_email', $settings->company_email ?? '') }}"
                                           required>
                                    <small class="form-text text-muted">This email will be used for report sharing and must be registered in the system.</small>
                                    @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="company_phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('company_phone') is-invalid @enderror"
                                           id="company_phone"
                                           name="company_phone"
                                           value="{{ old('company_phone', $settings->company_phone ?? '') }}"
                                           placeholder="+254 700 000 000"
                                           required>
                                    @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="company_website">Website URL</label>
                                    <input type="url"
                                           class="form-control @error('company_website') is-invalid @enderror"
                                           id="company_website"
                                           name="company_website"
                                           value="{{ old('company_website', $settings->company_website ?? '') }}"
                                           placeholder="https://www.example.com">
                                    @error('company_website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="company_address">Physical Address</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror"
                                              id="company_address"
                                              name="company_address"
                                              rows="3">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                                    @error('company_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Logo & Branding -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Logo & Branding</h5>

                                <div class="form-group mb-3">
                                    <label for="logo">Company Logo</label>
                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('logo') is-invalid @enderror"
                                               id="logo"
                                               name="logo"
                                               accept="image/*">
                                        <label class="custom-file-label" for="logo">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Recommended: 200x80px, Max 2MB (PNG, JPG, SVG)</small>
                                    @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Current Logo</label>
                                    <div class="mt-2">
                                        @if($settings && $settings->logo_path && file_exists(public_path($settings->logo_path)))
                                        <img src="{{ asset($settings->logo_path) }}" alt="Company Logo" style="max-width: 200px; max-height: 100px;" class="img-thumbnail">
                                        @else
                                        <div class="alert alert-info">
                                            <i class="icon fas fa-info"></i>
                                            No logo uploaded. Using default.
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Preview</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong style="font-size: 18px; color: #1a5f7a;">{{ $settings->company_name ?? 'Company Name' }}</strong>
                                                @if($settings && $settings->company_address)
                                                <div style="font-size: 11px; color: #666;">{{ $settings->company_address }}</div>
                                                @endif
                                            </div>
                                            @if($settings && $settings->logo_path && file_exists(public_path($settings->logo_path)))
                                            <img src="{{ asset($settings->logo_path) }}" alt="Logo" style="max-width: 80px; max-height: 40px;">
                                            @else
                                            <div style="width: 80px; height: 40px; background: #ddd; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">Logo</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- PDF Settings -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">PDF Document Settings</h5>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="pdf_footer_enabled"
                                               name="pdf_footer_enabled"
                                               value="1"
                                               {{ old('pdf_footer_enabled', $settings->pdf_footer_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="pdf_footer_enabled">Enable PDF Footer</label>
                                    </div>
                                    <small class="form-text text-muted">Display company website, email, and phone in PDF footers</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="pdf_footer_text">Custom Footer Text</label>
                                    <textarea class="form-control @error('pdf_footer_text') is-invalid @enderror"
                                              id="pdf_footer_text"
                                              name="pdf_footer_text"
                                              rows="2">{{ old('pdf_footer_text', $settings->pdf_footer_text ?? '') }}</textarea>
                                    <small class="form-text text-muted">Optional custom text to display in PDF footer</small>
                                    @error('pdf_footer_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.settings.company.preview') }}" class="btn btn-info ml-2">
                                <i class="fas fa-eye"></i> Preview PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
