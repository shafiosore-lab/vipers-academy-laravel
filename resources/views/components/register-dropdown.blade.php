<div class="dropdown">
    <button class="btn btn-danger btn-lg px-3 py-1 fw-semibold dropdown-toggle" type="button"
        id="registerDropdownBottom" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
        <i class="fas fa-user-plus"></i> Register
    </button>
    <ul class="dropdown-menu" aria-labelledby="registerDropdownBottom">
        <li><a class="dropdown-item" href="{{ route('enrol') }}">
                <i class="fas fa-graduation-cap"></i> Enroll in Program
                <small class="text-muted d-block">Join our training programs</small>
            </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="{{ route('contact') }}">
                <i class="fas fa-handshake"></i> Become a Partner
                <small class="text-muted d-block">Contact us for partnership</small>
            </a></li>
    </ul>
</div>
