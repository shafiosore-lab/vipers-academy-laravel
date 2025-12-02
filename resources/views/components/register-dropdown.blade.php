<div class="dropdown">
    <button class="btn btn-danger btn-lg px-3 py-1 fw-semibold dropdown-toggle" type="button"
        id="registerDropdownBottom" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
        <i class="fas fa-user-plus"></i> Register
    </button>
    <ul class="dropdown-menu" aria-labelledby="registerDropdownBottom">
        <li><a class="dropdown-item" href="{{ route('register.player') }}">
                <i class="fas fa-user"></i> Join as Player
                <small class="text-muted d-block">Become an academy player</small>
            </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="{{ route('register.partner') }}">
                <i class="fas fa-handshake"></i> Join as Partner
                <small class="text-muted d-block">Partner with our academy</small>
            </a></li>
    </ul>
</div>
