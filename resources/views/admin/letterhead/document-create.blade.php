@extends('layouts.admin')

@section('title', 'Create Document')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit"></i> Create Document</h1>
        <a href="{{ route('admin.letterhead.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Document Editor</h5>
                    <div>
                        <span class="badge bg-info" id="pageCountBadge">~1 page</span>
                        <span class="badge bg-warning">Max 20 pages</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.letterhead.document.store') }}" method="POST" id="documentForm">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Document Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter document title" required>
                        </div>

                        <div class="mb-3">
                            <label for="letterhead_id" class="form-label">Letterhead Template</label>
                            <select class="form-select" id="letterhead_id" name="letterhead_id">
                                <option value="">-- Select Letterhead --</option>
                                @foreach($letterheads as $lh)
                                <option value="{{ $lh->id }}" {{ ($defaultLetterhead && $lh->id == $defaultLetterhead->id) ? 'selected' : '' }}>
                                    {{ $lh->name }} {{ $lh->is_default ? '(Default)' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <!-- Toolbar -->
                            <div class="btn-toolbar mb-2" role="toolbar">
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('bold')" title="Bold">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('italic')" title="Italic">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('underline')" title="Underline">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('justifyLeft')" title="Align Left">
                                        <i class="fas fa-align-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('justifyCenter')" title="Align Center">
                                        <i class="fas fa-align-center"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('justifyRight')" title="Align Right">
                                        <i class="fas fa-align-right"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('insertUnorderedList')" title="Bullet List">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatDoc('insertOrderedList')" title="Numbered List">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                </div>
                                <div class="btn-group me-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="insertTable()" title="Insert Table">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Editor -->
                            <div id="editor" class="form-control" style="min-height: 500px; overflow-y: auto;" contenteditable="true">
                                {!! old('content', '<p>Start typing your document here...</p>') !!}
                            </div>

                            <!-- Hidden input to store content -->
                            <input type="hidden" name="content" id="content">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-info me-2" onclick="saveDraft()">
                                <i class="fas fa-save"></i> Save Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Create Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small text-muted">
                        <li>Use the toolbar to format text</li>
                        <li>Insert tables for structured data</li>
                        <li>Maximum 20 pages (~60,000 characters)</li>
                        <li>Save as draft to edit later</li>
                        <li>Use PDF download for printing</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Preview</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="previewDocument()">
                        <i class="fas fa-eye"></i> Preview PDF
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="downloadDocument()">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update page count on content change
    const editor = document.getElementById('editor');
    const pageCountBadge = document.getElementById('pageCountBadge');

    editor.addEventListener('input', function() {
        const text = editor.innerText || '';
        const charCount = text.length;
        const pageCount = Math.max(1, Math.ceil(charCount / 3000));

        if (pageCount > 20) {
            pageCountBadge.className = 'badge bg-danger';
            pageCountBadge.textContent = 'Exceeds limit!';
        } else {
            pageCountBadge.className = 'badge bg-info';
            pageCountBadge.textContent = '~' + pageCount + ' page' + (pageCount > 1 ? 's' : '');
        }
    });

    // Format document
    function formatDoc(cmd, value = null) {
        document.execCommand(cmd, false, value);
        editor.focus();
    }

    // Insert table
    function insertTable() {
        const html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;"><tr><th>Header 1</th><th>Header 2</th></tr><tr><td>Cell 1</td><td>Cell 2</td></tr></table><p></p>';
        editor.focus();
        document.execCommand('insertHTML', false, html);
    }

    // Save draft
    function saveDraft() {
        document.getElementById('content').value = editor.innerHTML;
        document.getElementById('status').value = 'draft';
        document.getElementById('documentForm').submit();
    }

    // Preview document
    function previewDocument() {
        document.getElementById('content').value = editor.innerHTML;
        // Show preview in modal or new window
        alert('Preview will be available after saving the document.');
    }

    // Download document
    function downloadDocument() {
        document.getElementById('content').value = editor.innerHTML;
        alert('Download will be available after saving the document.');
    }

    // Form submission
    document.getElementById('documentForm').addEventListener('submit', function(e) {
        document.getElementById('content').value = editor.innerHTML;

        // Validate content
        if (editor.innerText.trim().length < 10) {
            e.preventDefault();
            alert('Please add some content to the document.');
            return false;
        }

        // Check page limit
        const charCount = editor.innerText.length;
        if (charCount > 60000) {
            e.preventDefault();
            alert('Document exceeds maximum length of 20 pages. Please reduce content.');
            return false;
        }
    });
</script>
@endpush

<style>
#editor {
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    line-height: 1.6;
    padding: 15px;
    background: #fff;
}

#editor:focus {
    outline: 2px solid #ea1c4d;
    outline-offset: -1px;
}

#editor p {
    margin-bottom: 10px;
}

#editor table {
    margin: 10px 0;
}
</style>
@endsection
