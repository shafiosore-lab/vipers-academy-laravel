{{-- Documents Table Partial --}}
<div class="documents-table">
    {{-- Filters Row --}}
    <div class="filters-row">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="search-container">
                    <input type="text"
                           class="search-input form-control form-control-sm"
                           placeholder="Search documents..."
                           id="search{{ $tab ?? 'all' }}"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm category-filter" id="category{{ $tab ?? 'all' }}">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['value'] }}"
                                {{ request('category') == $category['value'] ? 'selected' : '' }}>
                            {{ $category['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="language{{ $tab ?? 'all' }}">
                    <option value="">{{ __('All Languages') }}</option>
                    <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                    <option value="sw" {{ request('language') == 'sw' ? 'selected' : '' }}>{{ __('Swahili') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="status{{ $tab ?? 'all' }}">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    <option value="mandatory" {{ request('status') == 'mandatory' ? 'selected' : '' }}>{{ __('Mandatory') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="clearFilters('{{ $tab ?? 'all' }}')">
                        <i class="fas fa-times"></i>
                    </button>
                    <select class="form-select form-select-sm" id="sortBy{{ $tab ?? 'all' }}">
                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Newest</option>
                        <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Recently Updated</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                        <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="file_size" {{ request('sort_by') == 'file_size' ? 'selected' : '' }}>File Size</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-sm"
                            onclick="toggleSortOrder('{{ $tab ?? 'all' }}')">
                        <i class="fas fa-sort-{{ request('sort_direction', 'desc') == 'desc' ? 'down' : 'up' }}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Documents Table --}}
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" class="form-check-input" id="selectAll{{ $tab ?? 'all' }}">
                    </th>
                    <th>{{ __('Document') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Target Roles') }}</th>
                    <th>{{ __('File Size') }}</th>
                    <th>{{ __('Downloads') }}</th>
                    <th>{{ __('Signatures') }}</th>
                    <th>{{ __('Created') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                    <tr class="document-row" data-document-id="{{ $document->id }}">
                        <td>
                            <input type="checkbox"
                                   class="form-check-input document-checkbox"
                                   value="{{ $document->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="file-icon {{ strtolower($document->mime_type ?? 'pdf') == 'application/pdf' ? 'pdf' :
                                                          (strtolower($document->mime_type ?? 'doc') == 'application/msword' || str_contains(strtolower($document->mime_type ?? ''), 'word') ? 'doc' :
                                                          (strtolower($document->mime_type ?? 'txt') == 'text/plain' ? 'txt' : 'rtf')) }}">
                                    @php
                                        $extension = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                                        $icon = match(true) {
                                            str_contains($extension, 'pdf') => 'fa-file-pdf',
                                            str_contains($extension, 'doc') => 'fa-file-word',
                                            str_contains($extension, 'xls') => 'fa-file-excel',
                                            str_contains($extension, 'txt') => 'fa-file-alt',
                                            default => 'fa-file'
                                        };
                                    @endphp
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="fw-semibold">{{ Str::limit($document->title, 30) }}</div>
                                    <small class="text-muted">{{ $document->document_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $document->getCategoryDisplayNameAttribute() }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($document->is_active)
                                    <span class="badge badge-active">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-inactive">{{ __('Inactive') }}</span>
                                @endif
                                @if($document->is_mandatory)
                                    <span class="badge badge-mandatory">{{ __('Mandatory') }}</span>
                                @endif
                                @if($document->requires_signature)
                                    <i class="fas fa-signature text-info" title="Requires Signature"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($document->target_roles ?? [] as $role)
                                    <small class="badge bg-light text-dark">{{ ucfirst($role) }}</small>
                                @endforeach
                            </div>
                        </td>
                        <td>{{ $document->getHumanFileSizeAttribute() }}</td>
                        <td>{{ $document->getDownloadCountAttribute() }}</td>
                        <td>{{ $document->getSignatureCountAttribute() }}</td>
                        <td>
                            <small class="text-muted">{{ $document->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="document-actions">
                                <button class="btn btn-sm btn-outline-info"
                                        onclick="previewDocument({{ $document->id }})"
                                        title="Preview">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.documents.download', $document) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('admin.documents.edit', $document) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteDocument({{ $document->id }}, '{{ $document->title }}')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <h5>{{ __('No documents found') }}</h5>
                                <p>{{ __('There are no documents matching your criteria.') }}</p>
                                <a href="{{ route('admin.documents.create') }}" class="btn btn-primary btn-modern">
                                    <i class="fas fa-plus me-2"></i>{{ __('Create First Document') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($documents->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $documents->appends(request()->query())->links() }}
        </div>
    @endif
</div>
