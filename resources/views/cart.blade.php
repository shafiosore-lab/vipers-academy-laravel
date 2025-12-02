@extends('layouts.academy')

@section('title', 'Shopping Cart - Vipers Academy')

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-shopping-cart me-3 text-primary"></i>
                        Shopping Cart
                    </h1>
                    <p class="lead text-muted">{{ $cartCount }} item{{ $cartCount !== 1 ? 's' : '' }} in your cart</p>

                    <!-- Session Persistence Indicator -->
                    @if(!auth()->check())
                        <div class="alert alert-info mt-3" role="alert">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Your cart is saved for this session.
                                <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#authModal">
                                    Sign in to save your cart permanently
                                </a>
                            </small>
                        </div>
                    @endif
                </div>

                @if($cartItems->count() > 0)
                    <div class="row">
                        <!-- Cart Items -->
                        <div class="col-lg-8" data-aos="fade-right">
                            <div class="cart-items">
                                @foreach($cartItems as $item)
                                <div class="cart-item card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <!-- Product Image -->
                                            <div class="col-md-2">
                                                <div class="product-image">
                                                    @if($item->product_data && isset($item->product_data['images']) && count($item->product_data['images']) > 0)
                                                        <img src="{{ asset('storage/' . $item->product_data['images'][0]) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="img-fluid rounded"
                                                             style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                             style="width: 80px; height: 80px;">
                                                            <i class="fas fa-tshirt fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Product Details -->
                                            <div class="col-md-4">
                                                <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                                <p class="text-muted small mb-1">{{ $item->product->category }}</p>
                                                @if($item->product_data && isset($item->product_data['sku']))
                                                    <small class="text-muted">SKU: {{ $item->product_data['sku'] }}</small>
                                                @endif
                                            </div>

                                            <!-- Quantity -->
                                            <div class="col-md-2">
                                                <div class="quantity-controls d-flex align-items-center">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number"
                                                           class="form-control form-control-sm mx-2 text-center"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           max="{{ $item->product->stock }}"
                                                           onchange="updateQuantity({{ $item->id }}, this.value)"
                                                           style="width: 60px;">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Price -->
                                            <div class="col-md-2 text-center">
                                                <div class="fw-bold text-primary">KSH {{ number_format($item->price) }}</div>
                                                <small class="text-muted">each</small>
                                            </div>

                                            <!-- Total & Actions -->
                                            <div class="col-md-2 text-end">
                                                <div class="fw-bold text-success mb-2">KSH {{ number_format($item->total) }}</div>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="removeItem({{ $item->id }})">
                                                    <i class="fas fa-trash me-1"></i>Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Cart Actions -->
                            <div class="cart-actions d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('products') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                </a>
                                <button class="btn btn-outline-secondary" onclick="clearCart()">
                                    <i class="fas fa-trash me-2"></i>Clear Cart
                                </button>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="col-lg-4" data-aos="fade-left">
                            <div class="cart-summary card border-0 shadow-lg sticky-top" style="top: 20px;">
                                <div class="card-header bg-primary text-white text-center py-3">
                                    <h5 class="mb-0">
                                        <i class="fas fa-receipt me-2"></i>
                                        Order Summary
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Subtotal ({{ $cartCount }} item{{ $cartCount !== 1 ? 's' : '' }})</span>
                                        <strong>KSH {{ number_format($cartTotal) }}</strong>
                                    </div>

                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Shipping</span>
                                        <span class="text-success">Free</span>
                                    </div>

                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Tax</span>
                                        <span class="text-success">Calculated at checkout</span>
                                    </div>

                                    <hr>

                                    <div class="summary-total d-flex justify-content-between mb-4">
                                        <span class="fw-bold">Total</span>
                                        <span class="fw-bold text-primary h5">KSH {{ number_format($cartTotal) }}</span>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success btn-lg" onclick="proceedToCheckout()">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Proceed to Checkout
                                        </button>

                                        @auth
                                            <small class="text-muted text-center">
                                                <i class="fas fa-user-check me-1"></i>
                                                Signed in as {{ auth()->user()->name }}
                                                @if(auth()->user()->isPlayer())
                                                    <span class="badge bg-success ms-1">Player</span>
                                                @elseif(auth()->user()->isPartner())
                                                    <span class="badge bg-info ms-1">Partner</span>
                                                @elseif(auth()->user()->isVisitor())
                                                    <span class="badge bg-warning ms-1">Visitor</span>
                                                @elseif(auth()->user()->isAdmin())
                                                    <span class="badge bg-danger ms-1">Admin</span>
                                                @endif
                                            </small>
                                        @else
                                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#authModal">
                                                <i class="fas fa-sign-in-alt me-2"></i>
                                                Sign In or Register
                                            </button>
                                        @endauth
                                    </div>

                                    <!-- Security Badges -->
                                    <div class="security-badges text-center mt-4 pt-3 border-top">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <i class="fas fa-shield-alt fa-2x text-success"></i>
                                                <br><small class="text-muted">Secure</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-truck fa-2x text-primary"></i>
                                                <br><small class="text-muted">Fast Delivery</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-undo fa-2x text-info"></i>
                                                <br><small class="text-muted">Easy Returns</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-5" data-aos="fade-up">
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                            <h2 class="mb-3">Your cart is empty</h2>
                            <p class="lead text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('products') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-store me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Authentication Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Quick Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-pills nav-justified mb-3" id="authTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="player-tab" data-bs-toggle="pill" data-bs-target="#player-tab-pane" type="button" role="tab">
                            <i class="fas fa-user-graduate me-2"></i>Player
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="partner-tab" data-bs-toggle="pill" data-bs-target="#partner-tab-pane" type="button" role="tab">
                            <i class="fas fa-handshake me-2"></i>Partner
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="visitor-tab" data-bs-toggle="pill" data-bs-target="#visitor-tab-pane" type="button" role="tab">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="authTabsContent">
                    <!-- Player Login -->
                    <div class="tab-pane fade show active" id="player-tab-pane" role="tabpanel">
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
                            <button type="submit" class="btn btn-success w-100">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="playerSpinner"></span>
                                <i class="fas fa-sign-in-alt me-2"></i>Player Login
                            </button>
                        </form>
                    </div>

                    <!-- Partner Login -->
                    <div class="tab-pane fade" id="partner-tab-pane" role="tabpanel">
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
                            <button type="submit" class="btn btn-info w-100">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="partnerSpinner"></span>
                                <i class="fas fa-handshake me-2"></i>Partner Login
                            </button>
                        </form>
                    </div>

                    <!-- Visitor Registration -->
                    <div class="tab-pane fade" id="visitor-tab-pane" role="tabpanel">
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
                            <button type="submit" class="btn btn-warning w-100">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="visitorSpinner"></span>
                                <i class="fas fa-user-plus me-2"></i>Register & Continue
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Enhanced Cart Functions with Session Persistence
let sessionTimeout;
const SESSION_WARNING_TIME = 25 * 60 * 1000; // 25 minutes
const SESSION_TIMEOUT_TIME = 30 * 60 * 1000; // 30 minutes

// Session Management
function startSessionMonitoring() {
    sessionTimeout = setTimeout(() => {
        showSessionWarning();
    }, SESSION_WARNING_TIME);
}

function showSessionWarning() {
    const remaining = SESSION_TIMEOUT_TIME - SESSION_WARNING_TIME;

    if (confirm('Your session will expire in 5 minutes. Sign in now to keep your cart?')) {
        // Redirect to authentication
        window.location.href = '{{ route("cart.index") }}?session_timeout=1';
    } else {
        setTimeout(() => {
            clearTimeout(sessionTimeout);
            handleSessionTimeout();
        }, remaining);
    }
}

function handleSessionTimeout() {
    // Clear cart and redirect
    fetch('{{ route("cart.clear.legacy") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        window.location.href = '{{ route("cart.index") }}?session_timeout=1';
    });
}

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

// Enhanced cart functions with security and session management
function updateQuantity(cartId, quantity) {
    if (quantity < 1) {
        if (confirm('Remove this item from cart?')) {
            removeItem(cartId);
        }
        return;
    }

    showLoading('Updating quantity...');

    fetch('{{ route("cart.update.legacy") }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
            // Dispatch custom event for navbar cart count update
            window.dispatchEvent(new CustomEvent('cartUpdated'));
        } else {
            showError(data.message || 'Error updating cart');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showError('Network error. Please try again.');
    });
}

function removeItem(cartId) {
    showLoading('Removing item...');

    fetch('{{ route("cart.remove.legacy") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            cart_id: cartId,
            _token: '{{ csrf_token() }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
        } else {
            showError(data.message || 'Error removing item');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showError('Network error. Please try again.');
    });
}

function clearCart() {
    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }

    showLoading('Clearing cart...');

    fetch('{{ route("cart.clear.legacy") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
        } else {
            showError(data.message || 'Error clearing cart');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showError('Network error. Please try again.');
    });
}

function proceedToCheckout() {
    // Check authentication first
    fetch('{{ route("cart.status") }}')
    .then(response => response.json())
    .then(data => {
        if (data.authenticated) {
            window.location.href = '{{ route("checkout") }}';
        } else {
            // Show authentication modal
            const modal = new bootstrap.Modal(document.getElementById('authModal'));
            modal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Please try again.');
    });
}

// Real-time validation functions
let validationTimeouts = {};

function debounceValidation(func, delay) {
    return function(...args) {
        clearTimeout(validationTimeouts[func]);
        validationTimeouts[func] = setTimeout(() => func.apply(this, args), delay);
    };
}

const validateEmail = debounceValidation((email, type) => {
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

// Loading and error handling functions
function showLoading(message = 'Loading...') {
    // Implement loading indicator
    console.log(message);
}

function hideLoading() {
    // Hide loading indicator
}

function showError(message) {
    // Implement error display (could be a toast notification)
    alert(message);
}

// Form submission handlers with enhanced security
document.querySelectorAll('.auth-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const type = this.dataset.type;
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Clear previous errors
        this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        this.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

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
                // Success - show message and redirect
                this.reset();

                // Close modal if it's open
                const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
                if (modal) modal.hide();

                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }

                showSuccess(data.message || 'Success!');
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(type + field.charAt(0).toUpperCase() + field.slice(1) + 'Error');
                        const inputElement = document.getElementById(type + field.charAt(0).toUpperCase() + field.slice(1));

                        if (errorElement && inputElement) {
                            errorElement.textContent = data.errors[field][0];
                            inputElement.classList.add('is-invalid');
                        }
                    });
                }

                showError(data.message || 'Authentication failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Network error. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
});

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    // Start session monitoring for guests
    @if(!auth()->check())
        startSessionMonitoring();
    @endif

    // Add validation listeners for visitor form
    const visitorEmail = document.getElementById('visitorEmail');
    if (visitorEmail) {
        visitorEmail.addEventListener('input', function() {
            validateEmail(this.value, 'visitor');
        });
    }

    // Check authentication status
    fetch('{{ route("cart.status") }}')
    .then(response => response.json())
    .then(data => {
        if (data.authenticated) {
            // Update UI to show authenticated state
            console.log('User authenticated:', data.user);
        }
    })
    .catch(error => {
        console.error('Auth status check failed:', error);
    });
});

function showSuccess(message) {
    // Implement success notification
    console.log('Success:', message);
}
</script>
@endpush

@push('styles')
<style>
.cart-item {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.quantity-controls input {
    border-radius: 4px;
}

.summary-item {
    padding: 0.5rem 0;
}

.summary-total {
    border-top: 2px solid #dee2e6;
    padding-top: 1rem;
    font-size: 1.1rem;
}

.security-badges .col-4 {
    padding: 0.5rem;
}

.empty-cart {
    max-width: 500px;
    margin: 0 auto;
}

.sticky-top {
    z-index: 100;
}

.nav-pills .nav-link {
    border-radius: 25px;
    margin: 0 2px;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
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
