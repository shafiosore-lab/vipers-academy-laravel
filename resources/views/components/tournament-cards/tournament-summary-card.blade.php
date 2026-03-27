<div class="card tournament-card h-100 shadow-sm border-0">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-{{ $color ?? 'primary' }} bg-opacity-10 rounded p-2">
                    <i class="fas {{ $icon }} text-{{ $color ?? 'primary' }}"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold text-dark">{{ $title }}</h6>
                    <small class="text-muted">{{ $subtitle ?? '' }}</small>
                </div>
            </div>
            @if(isset($actions))
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        {{ $actions }}
                    </ul>
                </div>
            @endif
        </div>

        <div class="row align-items-end">
            <div class="col">
                <div class="display-6 fw-bold text-{{ $color ?? 'primary' }}">{{ $value }}</div>
                @if(isset($subvalue))
                    <small class="text-muted">{{ $subvalue }}</small>
                @endif
            </div>
            @if(isset($trend))
                <div class="col-auto">
                    <span class="badge bg-{{ $trend['color'] }} rounded-pill">
                        <i class="fas fa-{{ $trend['icon'] }} me-1"></i>{{ $trend['text'] }}
                    </span>
                </div>
            @endif
        </div>

        @if(isset($footer))
            <div class="mt-3 pt-3 border-top">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
