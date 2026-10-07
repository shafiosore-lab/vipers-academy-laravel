<div class="card tournament-card h-100 shadow-sm border-0">
    <div class="card-header bg-white border-0 py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-{{ $color ?? 'primary' }} bg-opacity-10 rounded p-2">
                    <i class="fas {{ $icon }} text-{{ $color ?? 'primary' }}"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold text-dark">{{ $title }}</h6>
                    <small class="text-muted">{{ $subtitle ?? '' }}</small>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#card-{{ $id }}" aria-expanded="false" aria-controls="card-{{ $id }}">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="display-6 fw-bold text-{{ $color ?? 'primary' }}">{{ $value }}</div>
                    @if(isset($subvalue))
                        <div>
                            <small class="text-muted">{{ $subvalue }}</small>
                            @if(isset($trend))
                                <div class="mt-1">
                                    <span class="badge bg-{{ $trend['color'] }} rounded-pill">
                                        <i class="fas fa-{{ $trend['icon'] }} me-1"></i>{{ $trend['text'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                @if(isset($summary))
                    <div class="text-end">
                        {{ $summary }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="collapse border-top" id="card-{{ $id }}">
        <div class="card-body p-3">
            {{ $content }}
        </div>
        @if(isset($footer))
            <div class="card-footer bg-light border-0 p-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
