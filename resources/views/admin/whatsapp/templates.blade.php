@extends('layouts.admin')

@section('title', 'WhatsApp Templates')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0"><i class="fab fa-whatsapp"></i> WhatsApp Templates</h5>
            <small class="text-muted">Manage message templates for quick sending</small>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Available Templates</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $key => $template)
                        <tr>
                            <td><strong>{{ $template['name'] }}</strong></td>
                            <td><small class="text-muted">{{ $template['content'] }}</small></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="useTemplate('{{ $key }}')">
                                    <i class="fas fa-plus"></i> Use
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Create Custom Template</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.whatsapp.templates.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message Content</label>
                    <textarea name="content" class="form-control" rows="4" required placeholder="Enter your WhatsApp message..."></textarea>
                    <small class="text-muted">Use {'{message}}' as placeholder for dynamic content</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Template
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function useTemplate(key) {
    const templates = @json($templates);
    const template = templates[key];
    if (template) {
        window.location.href = '{{ route("admin.whatsapp.index") }}?template=' + encodeURIComponent(template.content);
    }
}
</script>
@endsection
