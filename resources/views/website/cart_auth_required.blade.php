@extends('layouts.academy')

@section('title', 'Authentication Required - Vipers Academy')

@section('content')
<section class="cart-auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-lock me-3 text-warning"></i>
                        Authentication Required
                    </h1>
                    <p class="lead text-muted">Please sign in to continue with your cart items</p>
                </div>

                <!-- Cart Summary -->
                <div class="row mb-5">
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Cart Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Items:</span>
                                    <strong>{{ $cartCount }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong>KSH {{ number_format($cartTotal) }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fw-bold text-primary h5">KSH {{ number_format($cartTotal) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    <div class="col-lg-6" data-aos="fade-left">
                        @if(isset($sessionTimeout) && $sessionTimeout)
                            <div class="alert alert-warning" role="alert">
                                <h6><i class="fas fa-clock me-2"></i>Session Timeout</h6>
                                <p class="mb-0">Your session has expired. Please sign in to continue with your cart.</p>
                            </div>
                        @endif

                        @if(isset($accountInactive) && $accountInactive)
                            <div class="alert alert-danger" role="alert">
                                <h6><i class="fas fa-user-slash me-2"></i>Account Inactive</h6>
                                <p class="mb-0">{{ $message ?? 'Your account is not active. Please contact support.' }}</p>
                            </div>
                        @endif

                        @if(isset($message) && !isset($accountInactive))
                            <div class="alert alert-info" role="alert">
                                <h6>Information</h6>
                                <p class="mb-0">{{ $message }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Authentication Options -->
                <div class="row">
                    <!-- Player Login -->
                    <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                            <div class="card-header bg-success text-white text-center">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <h5 class="mb-0">Player Login</h5>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-3">Sign in as a registered player to access your account.</p>
                                <form id="playerLoginForm" class="auth-form" data-type="player">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="playerEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="playerEmail" name="email" required>
                                        <div class="invalid-feedback" id="playerEmailError"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="playerPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="playerPassword" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('playerPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="playerPasswordError"></div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="playerRemember" name="remember">
                                        <label class="form-check-label" for="playerRemember">Remember me</label>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100" id="playerLoginBtn">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="playerSpinner"></span>
                                        <i class="fas fa-sign-in-alt me-2"></i>Player Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Partner Login -->
                    <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                            <div class="card-header bg-info text-white text-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <h5 class="mb-0">Partner Login</h5>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-3">Access the partner portal for business collaborators.</p>
                                <form id="partnerLoginForm" class="auth-form" data-type="partner">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="partnerEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="partnerEmail" name="email" required>
                                        <div class="invalid-feedback" id="partnerEmailError"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="partnerPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="partnerPassword" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('partnerPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="partnerPasswordError"></div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="partnerRemember" name="remember">
                                        <label class="form-check-label" for="partnerRemember">Remember me</label>
                                    </div>
                                    <button type="submit" class="btn btn-info w-100" id="partnerLoginBtn">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="partnerSpinner"></span>
                                        <i class="fas fa-handshake me-2"></i>Partner Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Visitor Registration -->
                    <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                            <div class="card-header bg-warning text-dark text-center">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <h5 class="mb-0">Quick Registration</h5>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-muted mb-3">Register as a visitor with limited access to continue shopping.</p>
                                <form id="visitorRegisterForm" class="auth-form" data-type="visitor">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="visitorName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="visitorName" name="name" required>
                                        <div class="invalid-feedback" id="visitorNameError"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="visitorEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="visitorEmail" name="email" required>
                                        <div class="invalid-feedback" id="visitorEmailError"></div>
                                        <div class="form-text" id="emailValidationText"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="visitorPhone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="visitorPhone" name="phone"
                                               placeholder="+254 7XX XXX XXX" required>
                                        <div class="invalid-feedback" id="visitorPhoneError"></div>
                                        <div class="form-text" id="phoneValidationText"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="visitorPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="visitorPassword" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('visitorPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="visitorPasswordError"></div>
                                        <div class="form-text" id="passwordValidationText"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="visitorPasswordConfirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="visitorPasswordConfirmation"
                                               name="password_confirmation" required>
                                        <div class="invalid-feedback" id="visitorPasswordConfirmationError"></div>
                                    </div>
                                    <button type="submit" class="btn btn-warning w-100" id="visitorRegisterBtn">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="visitorSpinner"></span>
                                        <i class="fas fa-user-plus me-2"></i>Register & Continue
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Actions -->
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('products') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-store me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Password toggle function
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');

    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Real-time validation
let emailValidationTimeout;
let phoneValidationTimeout;
let passwordValidationTimeout;

function validateEmail(email, type) {
    clearTimeout(emailValidationTimeout);
    emailValidationTimeout = setTimeout(() => {
        if (email.length > 3) {
            fetch('{{ route("cart.validate.email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                const textElement = document.getElementById(type + 'ValidationText');
                const inputElement = document.getElementById(type + 'Email');
                const errorElement = document.getElementById(type + 'EmailError');

                if (data.valid) {
                    inputElement.classList.remove('is-invalid');
                    if (type === 'visitor') {
                        textElement.textContent = data.message;
                        textElement.className = 'form-text ' + (data.available ? 'text-success' : 'text-danger');
                    }
                } else {
                    inputElement.classList.add('is-invalid');
                    errorElement.textContent = data.message;
                    textElement.textContent = '';
                }
            });
        }
    }, 500);
}

function validatePhone(phone, type) {
    clearTimeout(phoneValidationTimeout);
    phoneValidationTimeout = setTimeout(() => {
        if (phone.length >= 10) {
            fetch('{{ route("cart.validate.phone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                const textElement = document.getElementById('phoneValidationText');
                const inputElement = document.getElementById(type + 'Phone');
                const errorElement = document.getElementById(type + 'PhoneError');

                if (data.valid) {
                    inputElement.classList.remove('is-invalid');
                    textElement.textContent = data.message;
                    textElement.className = 'form-text text-success';
                } else {
                    inputElement.classList.add('is-invalid');
                    errorElement.textContent = data.message;
                    textElement.textContent = '';
                }
            });
        }
    }, 500);
}

function validatePassword(password, type) {
    clearTimeout(passwordValidationTimeout);
    passwordValidationTimeout = setTimeout(() => {
        if (password.length >= 6) {
            fetch('{{ route("cart.validate.password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => response.json())
            .then(data => {
                const textElement = document.getElementById('passwordValidationText');
                const inputElement = document.getElementById(type + 'Password');
                const errorElement = document.getElementById(type + 'PasswordError');

                if (data.valid) {
                    inputElement.classList.remove('is-invalid');
                    textElement.textContent = 'Strong password';
                    textElement.className = 'form-text text-success';
                } else {
                    inputElement.classList.add('is-invalid');
                    errorElement.textContent = 'Password does not meet requirements';
                    textElement.textContent = '';
                }
            });
        }
    }, 500);
}

// Form submission handlers
document.querySelectorAll('.auth-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const type = this.dataset.type;
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const endpoint = type === 'player' ? '{{ route("cart.login.player") }}' :
                        type === 'partner' ? '{{ route("cart.login.partner") }}' :
                        '{{ route("cart.register.visitor") }}';

        fetch(endpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and redirect
                this.reset();

                if (data.redirect) {
                    // Show success message briefly then redirect
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }

                // You could show a success toast here
                console.log(data.message);
            } else {
                // Handle errors
                Object.keys(data.errors || {}).forEach(field => {
                    const errorElement = document.getElementById(type + field.charAt(0).toUpperCase() + field.slice(1) + 'Error');
                    const inputElement = document.getElementById(type + field.charAt(0).toUpperCase() + field.slice(1));

                    if (errorElement && inputElement) {
                        errorElement.textContent = data.errors[field][0];
                        inputElement.classList.add('is-invalid');
                    }
                });

                if (data.message) {
                    console.error(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
});

// Add event listeners for real-time validation
document.getElementById('visitorEmail')?.addEventListener('input', function() {
    validateEmail(this.value, 'visitor');
});

document.getElementById('visitorPhone')?.addEventListener('input', function() {
    validatePhone(this.value, 'visitor');
});

document.getElementById('visitorPassword')?.addEventListener('input', function() {
    validatePassword(this.value, 'visitor');
});

// Check authentication status on page load
fetch('{{ route("cart.status") }}')
.then(response => response.json())
.then(data => {
    if (data.authenticated) {
        // User is authenticated, redirect to appropriate page
        const redirectRoute = data.user.user_type === 'admin' ? '{{ route("admin.dashboard") }}' :
                             data.user.user_type === 'player' ? '{{ route("player.portal.dashboard") }}' :
                             data.user.user_type === 'partner' ? '{{ route("partner.dashboard") }}' :
                             '{{ route("dashboard") }}';

        setTimeout(() => {
            window.location.href = redirectRoute;
        }, 2000);
    }
});
</script>
@endpush

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.auth-form .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.invalid-feedback {
    display: block;
    font-size: 0.875rem;
    color: #dc3545;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
@endpush
