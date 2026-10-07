<div class="card tournament-card h-100 shadow-sm border-0">
    <div class="card-header bg-white border-0 py-2">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-{{ $color ?? 'primary' }} bg-opacity-10 rounded p-2">
                    <i class="fas {{ $icon }} text-{{ $color ?? 'primary' }}"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold text-dark">{{ $title }}</h6>
                    <small class="text-muted">{{ $subtitle ?? '' }}</small>
                </div>
            </div>
            @if(isset($badge))
                <span class="badge bg-{{ $badge['color'] }}">{{ $badge['text'] }}</span>
            @endif
        </div>
    </div>

    <div class="card-body p-3">
        @if(isset($description))
            <p class="text-muted mb-3">{{ $description }}</p>
        @endif

        <div class="row g-2">
            @foreach($actions as $action)
                <div class="col-12 col-sm-6 col-md-{{ 12 / count($actions) }}">
                    <a href="{{ $action['url'] }}" class="btn btn-{{ $action['style'] ?? 'primary' }} w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="fas {{ $action['icon'] }}"></i>
                        {{ $action['label'] }}
                    </a>
                </div>
            @endforeach
        </div>

        @if(isset($secondaryActions))
            <div class="row g-2 mt-2">
                @foreach($secondaryActions as $action)
                    <div class="col-12 col-sm-6 col-md-{{ 12 / count($secondaryActions) }}">
                        <a href="{{ $action['url'] }}" class="btn btn-outline-{{ $action['style'] ?? 'secondary' }} w-100">
                            {{ $action['label'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if(isset($footer))
        <div class="card-footer bg-light border-0 p-3">
            {{ $footer }}
        </div>
    @endif
</div>
