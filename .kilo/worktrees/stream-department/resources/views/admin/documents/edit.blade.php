@extends('layouts.admin')

@section('title', __('Edit Document - Vipers Academy Admin'))

@push('styles')
<style>
    .document-form-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 0;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-bottom: 1px solid #e5e7eb;
        padding: 24px 32px;
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 4px;
    }

    .form-subtitle {
        color: #a16207;
        font-size: 14px;
        margin: 0;
    }

    .form-body {
        padding: 32px;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #f59e0b;
    }

    .form-group-custom {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .form-control-modern, .form-select-modern {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .file-upload-area {
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        cursor: pointer;
        position: relative;
    }

    .file-upload-area:hover, .file-upload-area.dragover {
        border-color: #f59e0b;
        background: #fef3c7;
    }

    .file-upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 16px;
        transition: color 0.3s ease;
    }

    .file-upload-area:hover .file-upload-icon {
        color: #f59e0b;
    }

    .file-upload-text {
        font-size: 16px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 4px;
    }

    .file-upload-hint {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .target-roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .role-option {
        display: flex;
        align-items: center;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .role-option:hover {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .role-option.selected {
        border-color: #f59e0b;
        background: #fef3c7;
    }

    .role-option input[type="checkbox"] {
        display: none;
    }

    .role-option .checkbox-indicator {
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .role-option.selected .checkbox-indicator {
        background: #f59e0b;
        border-color: #f59e0b;
    }

    .role-option.selected .checkbox-indicator::before {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .role-name {
        font-weight: 500;
        color: #374151;
    }

    .switch-container {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #f59e0b;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    .switch-label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .category-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }

    .category-option {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        text-align: center;
        position: relative;
    }

    .category-option:hover {
        border-color: #f59e0b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .category-option.selected {
        border-color: #f59e0b;
        background: #fef3c7;
    }

    .category-icon {
        font-size: 32px;
        margin-bottom: 12px;
        display: block;
    }

    .category-option.selected .category-icon {
        color: #f59e0b;
    }

    .category-name {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .category-description {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.4;
    }

    input[type="radio"] {
        display: none;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 32px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }

    .btn-group-custom {
        display: flex;
        gap: 12px;
    }

    .btn-modern {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-modern {
        background: #f59e0b;
        color: white;
    }

    .btn-primary-modern:hover {
        background: #d97706;
        transform: translateY(-1px);
    }

    .btn-secondary-modern {
        background: #f8fafc;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary-modern:hover {
        background: #f1f5f9;
        border-color: #d1d5db;
    }

    .current-file {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #fef3c7;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        margin-top: 12px;
    }

    .file-icon-current {
        font-size: 20px;
        color: #d97706;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-weight: 500;
        color: #92400e;
    }

    .file-size {
        font-size: 12px;
        color: #a16207;
    }

    .change-file {
        color: #f59e0b;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .change-file:hover {
        background: #fde68a;
    }

    .required-indicator {
        color: #dc2626;
        font-weight: bold;
        margin-left: 4px;
    }

    .help-text {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .document-stats {
        background: #fef5e7;
        border: 1px solid #fed7aa;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .stat-item:last-child {
        margin-bottom: 0;
    }

    .stat-label {
        font-weight: 500;
        color: #92400e;
        font-size: 14px;
    }

    .stat-value {
        font-weight: 600;
        color: #7c2d12;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .form-header, .form-body {
            padding: 20px;
        }

        .document-form-container {
            margin: -16px;
        }

        .target-roles-grid {
            grid-template-columns: 1fr;
        }

        .category-cards {
            grid-template-columns: 1fr;
        }

        .form-actions {
            padding: 16px 20px;
            flex-direction: column;
            gap: 12px;
        }

        .btn-group-custom {
            width: 100%;
        }

        .btn-modern {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="document-form-container">
        <!-- Form Header -->
        <div class="form-header">
            <h1 class="form-title">{{ __('Edit Document') }}</h1>
            <p class="form-subtitle">{{ __('Update document details, metadata, and access control') }}</p>
        </div>

        <!-- Document Stats -->
        <div class="form-body">
            <div class="document-stats">
                <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>{{ __('Document Statistics') }}</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-label">{{ __('Created') }}</span>
                            <span class="stat-value">{{ $document->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-label">{{ __('Last Updated') }}</span>
                            <span class="stat-value">{{ $document->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-label">{{ __('Views/Signatures') }}</span>
                            <span class="stat-value">{{ $document->userDocuments->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form id="documentForm" action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-body">
                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>{{ __('Basic Information') }}
                    </h3>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group-custom">
                                <label class="form-label">
                                    {{ __('Document Title') }}<span class="required-indicator">*</span>
                                </label>
                                <input type="text"
                                       class="form-control-modern"
                                       name="title"
                                       value="{{ old('title', $document->title) }}"
                                       placeholder="{{ __('e.g., Player Code of Conduct 2024') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group-custom">
                                <label class="form-label">
                                    {{ __('Document ID') }}
                                </label>
                                <input type="text"
                                       class="form-control-modern"
                                       value="{{ $document->document_id }}"
                                       readonly>
                                <div class="help-text">{{ __('Generated automatically based on category') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group-custom">
                                <label class="form-label">
                                    {{ __('Language') }}<span class="required-indicator">*</span>
                                </label>
                                <select class="form-select-modern" name="language" required>
                                    <option value="en" {{ old('language', $document->language) == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                                    <option value="sw" {{ old('language', $document->language) == 'sw' ? 'selected' : '' }}>{{ __('Swahili') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group-custom">
                                <label class="form-label">
                                    {{ __('Version') }}
                                </label>
                                <input type="text"
                                       class="form-control-modern"
                                       name="version"
                                       value="{{ old('version', $document->version) }}"
                                       placeholder="1.0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Description') }}
                        </label>
                        <textarea class="form-control-modern form-textarea"
                                  name="description"
                                  placeholder="{{ __('Describe the document\'s purpose and contents...') }}">{{ old('description', $document->description) }}</textarea>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-file-alt"></i>{{ __('Document File') }}
                    </h3>

                    <!-- Current File Display -->
                    <div class="current-file">
                        <i class="fas fa-file-alt file-icon-current"></i>
                        <div class="file-details">
                            <div class="file-name">{{ $document->file_name }}</div>
                            <div class="file-size">{{ number_format($document->file_size / 1024, 1) }} KB - {{ $document->mime_type }}</div>
                        </div>
                        <a href="#" class="change-file" onclick="toggleFileUpload()">
                            <i class="fas fa-exchange-alt me-1"></i>{{ __('Change File') }}
                        </a>
                        <a href="{{ route('admin.documents.download', $document) }}" class="change-file">
                            <i class="fas fa-download me-1"></i>{{ __('Download') }}
                        </a>
                    </div>

                    <!-- File Upload Area (Hidden by default) -->
                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Replace Document File (Optional)') }}
                        </label>
                        <div class="file-upload-area" id="fileUploadArea" style="display: none;">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">{{ __('Drop your new document here or click to browse') }}</div>
                            <div class="file-upload-hint">
                                {{ __('Supported formats: PDF, DOC, DOCX, TXT, RTF. Maximum size: 20MB') }}
                            </div>
                            <input type="file"
                                   class="file-input"
                                   name="file"
                                   accept=".pdf,.doc,.docx,.txt,.rtf">
                        </div>

                        <!-- Selected file display -->
                        <div class="selected-file" id="selectedFile" style="display: none;">
                            <i class="fas fa-file-alt file-icon-selected"></i>
                            <div class="file-details">
                                <div class="file-name" id="selectedFileName"></div>
                                <div class="file-size" id="selectedFileSize"></div>
                            </div>
                            <span class="remove-file" onclick="removeSelectedFile()">
                                <i class="fas fa-times"></i>
                            </span>
                        </div>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Category and Organization -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-tags"></i>{{ __('Category & Organization') }}
                    </h3>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Document Category') }}<span class="required-indicator">*</span>
                        </label>
                        <div class="category-cards">
                            @foreach($categories as $key => $label)
                                <label class="category-option" for="category{{ $key }}">
                                    <input type="radio"
                                           name="category"
                                           value="{{ $key }}"
                                           id="category{{ $key }}"
                                           {{ old('category', $document->category) == $key ? 'checked' : '' }}>
                                    <span class="category-icon">
                                        @php
                                            $icons = [
                                                'codes_of_conduct' => 'fa-gavel',
                                                'safety_protection' => 'fa-shield-alt',
                                                'academy_policies' => 'fa-university',
                                                'contracts_agreements' => 'fa-file-contract',
                                                'academy_information' => 'fa-info-circle',
                                                'administrative' => 'fa-clipboard',
                                                'training' => 'fa-graduation-cap',
                                                'medical' => 'fa-plus-circle',
                                                'financial' => 'fa-dollar-sign',
                                                'legal' => 'fa-balance-scale'
                                            ];
                                        @endphp
                                        <i class="fas {{ $icons[$key] ?? 'fa-file' }}"></i>
                                    </span>
                                    <div class="category-name">{{ $label }}</div>
                                    <div class="category-description">
                                        @php
                                            $descriptions = [
                                                'codes_of_conduct' => 'Ethical standards and behavioral guidelines',
                                                'safety_protection' => 'Safety procedures and protection policies',
                                                'academy_policies' => 'Academy rules and operational policies',
                                                'contracts_agreements' => 'Legal agreements and contracts',
                                                'academy_information' => 'General academy information and notices',
                                                'administrative' => 'Administrative documents and forms',
                                                'training' => 'Training materials and educational content',
                                                'medical' => 'Medical forms and health documents',
                                                'financial' => 'Financial policies and documentation',
                                                'legal' => 'Legal documents and regulatory compliance'
                                            ];
                                        @endphp
                                        {{ $descriptions[$key] ?? 'Document category' }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Subcategory (Optional)') }}
                        </label>
                        <input type="text"
                               class="form-control-modern"
                               name="subcategory"
                               value="{{ old('subcategory', $document->subcategory) }}"
                               placeholder="{{ __('e.g., Youth Team, Junior Academy, etc.') }}">
                        <div class="help-text">{{ __('Optional sub-organization within the main category') }}</div>
                    </div>
                </div>

                <!-- Access Control -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-lock"></i>{{ __('Access Control') }}
                    </h3>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Target Roles') }}<span class="required-indicator">*</span>
                        </label>
                        <div class="target-roles-grid">
                            @foreach($targetRoles as $key => $label)
                                <label class="role-option {{ in_array($key, old('target_roles', $document->target_roles ?? [])) ? 'selected' : '' }}">
                                    <input type="checkbox"
                                           name="target_roles[]"
                                           value="{{ $key }}"
                                           {{ in_array($key, old('target_roles', $document->target_roles ?? [])) ? 'checked' : '' }}>
                                    <span class="checkbox-indicator"></span>
                                    <div class="role-name">{{ $label }}</div>
                                </label>
                            @endforeach
                        </div>
                        <div class="help-text">{{ __('Select which user types can access this document') }}</div>
                        @error('target_roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Additional Settings') }}
                        </label>

                        <div class="switch-container">
                            <div class="switch">
                                <input type="checkbox"
                                       id="is_mandatory"
                                       name="is_mandatory"
                                       value="1"
                                       {{ old('is_mandatory', $document->is_mandatory) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <div>
                                <label class="switch-label" for="is_mandatory">
                                    <strong>{{ __('Mandatory Document') }}</strong>
                                </label>
                                <div class="help-text">{{ __('Users must acknowledge this document to continue using the portal') }}</div>
                            </div>
                        </div>

                        <div class="switch-container">
                            <div class="switch">
                                <input type="checkbox"
                                       id="requires_signature"
                                       name="requires_signature"
                                       value="1"
                                       {{ old('requires_signature', $document->requires_signature) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <div>
                                <label class="switch-label" for="requires_signature">
                                    <strong>{{ __('Requires Signature') }}</strong>
                                </label>
                                <div class="help-text">{{ __('Users must provide electronic signature upon acceptance') }}</div>
                            </div>
                        </div>

                        <div class="switch-container">
                            <div class="switch">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $document->is_active) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </div>
                            <div>
                                <label class="switch-label" for="is_active">
                                    <strong>{{ __('Active Document') }}</strong>
                                </label>
                                <div class="help-text">{{ __('Document is visible and accessible to target roles') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label">
                            {{ __('Expiry Period (Days)') }}
                        </label>
                        <input type="number"
                               class="form-control-modern"
                               name="expiry_days"
                               value="{{ old('expiry_days', $document->expiry_days) }}"
                               placeholder="{{ __('e.g., 365 for 1 year') }}"
                               min="1"
                               max="3650">
                        <div class="help-text">{{ __('Leave empty for documents with no expiry') }}</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div>
                    <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-secondary-modern me-2">
                        <i class="fas fa-eye"></i>
                        {{ __('View Document') }}
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary-modern">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('Back to Documents') }}
                    </a>
                </div>

                <div class="btn-group-custom">
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save"></i>
                        {{ __('Update Document') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const fileInput = document.querySelector('.file-input');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const selectedFile = document.getElementById('selectedFile');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');

    // Toggle file upload visibility
    window.toggleFileUpload = function() {
        if (fileUploadArea.style.display === 'none') {
            fileUploadArea.style.display = 'block';
        } else {
            fileUploadArea.style.display = 'none';
            removeSelectedFile();
        }
    };

    fileInput.addEventListener('change', handleFileSelect);
    fileUploadArea.addEventListener('click', () => fileInput.click());

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        fileUploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        fileUploadArea.classList.remove('dragover');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect({ target: fileInput });
    }

    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            selectedFileName.textContent = file.name;
            selectedFileSize.textContent = formatFileSize(file.size);
            selectedFile.style.display = 'flex';
        }
    }

    function removeSelectedFile() {
        fileInput.value = '';
        selectedFile.style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    window.removeSelectedFile = removeSelectedFile;

    // Category selection handling
    const categoryOptions = document.querySelectorAll('.category-option');
    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {
            categoryOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });

    // Initialize category selection
    categoryOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio.checked) {
            option.classList.add('selected');
        }
    });

    // Role selection handling
    const roleOptions = document.querySelectorAll('.role-option');
    roleOptions.forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        if (checkbox.checked) {
            option.classList.add('selected');
        }
        option.addEventListener('click', function() {
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('selected', checkbox.checked);
        });
    });

    // Form validation
    const form = document.getElementById('documentForm');
    form.addEventListener('submit', function(e) {
        const targetRoles = document.querySelectorAll('input[name="target_roles[]"]:checked');
        if (targetRoles.length === 0) {
            e.preventDefault();
            alert('Please select at least one target role for document access.');
            return;
        }
    });
});
</script>
@endsection
