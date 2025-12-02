@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Checkout</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Cart Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between border-b pb-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/80' }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                <div>
                                    <h3 class="font-medium">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                    <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">{{ $item->formatted_total }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Coupon Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div id="coupon-section">
                        <h3 class="font-medium mb-3">Have a coupon code?</h3>
                        <div class="flex space-x-3 mb-4">
                            <input type="text" id="coupon_code" placeholder="Enter coupon code" class="flex-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            <button id="apply-coupon" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Apply</button>
                        </div>
                    </div>

                    <!-- Applied Coupons -->
                    <div id="applied-coupons" class="space-y-2">
                        @if(isset($appliedCoupons) && count($appliedCoupons) > 0)
                            @foreach($appliedCoupons as $coupon)
                                <div class="flex justify-between items-center bg-green-50 p-3 rounded">
                                    <span class="text-green-800">{{ $coupon->name }}: {{ $coupon->getDiscountDescription() }}</span>
                                    <button class="text-red-500 hover:text-red-700 remove-coupon" data-coupon-id="">×</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Order Totals -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (16% VAT):</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount:</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <hr class="my-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" class="grid md:grid-cols-3 gap-6">
                @csrf

                <!-- Left Column: Shipping & Billing Info -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">First Name *</label>
                                <input type="text" name="shipping_first_name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Last Name *</label>
                                <input type="text" name="shipping_last_name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Address *</label>
                            <input type="text" name="shipping_address" placeholder="Street address" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">City *</label>
                                <input type="text" name="shipping_city" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Region/State *</label>
                                <input type="text" name="shipping_region" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Postal Code</label>
                                <input type="text" name="shipping_postal_code" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Country *</label>
                                <select name="shipping_country" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Country</option>
                                    <option value="Kenya" selected>Kenya</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Burundi">Burundi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Phone *</label>
                                <input type="tel" name="shipping_phone" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Billing Information</h2>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="same-as-shipping" class="mr-2">
                                <span>Same as shipping address</span>
                            </label>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">First Name *</label>
                                <input type="text" name="billing_first_name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Last Name *</label>
                                <input type="text" name="billing_last_name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Address *</label>
                            <input type="text" name="billing_address" placeholder="Street address" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">City *</label>
                                <input type="text" name="billing_city" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Region/State *</label>
                                <input type="text" name="billing_region" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Postal Code</label>
                                <input type="text" name="billing_postal_code" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Country *</label>
                                <select name="billing_country" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Country</option>
                                    <option value="Kenya" selected>Kenya</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Burundi">Burundi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Phone *</label>
                                <input type="tel" name="billing_phone" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment & Submit -->
                <div class="space-y-6">
                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Payment Method</h2>

                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="mpesa" checked class="mr-3">
                                <div class="flex items-center">
                                    <img src="/images/mpesa-logo.png" alt="M-Pesa" class="w-8 h-8 mr-2" onerror="this.src='https://via.placeholder.com/32?text=MP'">
                                    <span class="font-medium">M-Pesa (Mobile Money)</span>
                                </div>
                            </label>

                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="card" class="mr-3">
                                <div class="flex items-center">
                                    <span class="font-medium">Credit/Debit Card</span>
                                </div>
                            </label>

                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                                <div class="flex items-center">
                                    <span class="font-medium">Bank Transfer</span>
                                </div>
                            </label>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Order Notes (Optional)</label>
                            <textarea name="notes" rows="3" placeholder="Special delivery instructions..." class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <!-- Guest Email (if not logged in) -->
                    @if(!Auth::check())
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email Address *</label>
                                <input type="email" name="guest_email" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="your@email.com">
                            </div>
                        </div>
                    @endif

                    <!-- Complete Order Button -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <button type="submit" id="place-order-btn" class="w-full bg-green-600 text-white py-3 px-6 rounded-md hover:bg-green-700 font-medium text-lg">
                            Complete Order
                        </button>

                        <div class="mt-4 text-sm text-gray-600">
                            <p>By placing your order, you agree to our Terms of Service and Privacy Policy.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Copy shipping address to billing address
document.getElementById('same-as-shipping').addEventListener('change', function() {
    const shippingFields = [
        'shipping_first_name', 'shipping_last_name', 'shipping_address',
        'shipping_city', 'shipping_region', 'shipping_postal_code', 'shipping_country', 'shipping_phone'
    ];

    const billingFields = [
        'billing_first_name', 'billing_last_name', 'billing_address',
        'billing_city', 'billing_region', 'billing_postal_code', 'billing_country', 'billing_phone'
    ];

    shippingFields.forEach((field, index) => {
        const shippingInput = document.querySelector(`[name="${field}"]`);
        const billingInput = document.querySelector(`[name="${billingFields[index]}"]`);

        if (this.checked) {
            billingInput.value = shippingInput.value;
        } else {
            billingInput.value = '';
        }
    });
});

// Coupon functionality
document.getElementById('apply-coupon').addEventListener('click', function() {
    const couponCode = document.getElementById('coupon_code').value.trim();

    if (!couponCode) {
        alert('Please enter a coupon code.');
        return;
    }

    // Disable button and show loading
    this.disabled = true;
    this.textContent = 'Applying...';

    fetch('/checkout/apply-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_code: couponCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message, 'success');

            // Update totals
            updateTotals(data.totals);

            // Reload page to show applied coupon
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred while applying the coupon.', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        // Re-enable button
        document.getElementById('apply-coupon').disabled = false;
        document.getElementById('apply-coupon').textContent = 'Apply';
    });
});

// Remove coupon
document.querySelectorAll('.remove-coupon').forEach(button => {
    button.addEventListener('click', function() {
        fetch('/checkout/remove-coupon', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to reflect changes
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error removing coupon:', error);
        });
    });
});

function updateTotals(totals) {
    // Update the displayed totals
    const subtotalElement = document.querySelector('.order-summary .subtotal');
    const shippingElement = document.querySelector('.order-summary .shipping');
    const taxElement = document.querySelector('.order-summary .tax');
    const discountElement = document.querySelector('.order-summary .discount');
    const totalElement = document.querySelector('.order-summary .total');

    if (subtotalElement) subtotalElement.textContent = `$${totals.subtotal}`;
    if (shippingElement) shippingElement.textContent = `$${totals.shipping}`;
    if (taxElement) taxElement.textContent = `$${totals.tax}`;
    if (discountElement) discountElement.textContent = `$${totals.discount}`;
    if (totalElement) totalElement.textContent = `$${totals.total}`;
}

function showNotification(message, type) {
    // Simple notification - you can enhance this with a proper notification system
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: white;
        background-color: ${type === 'success' ? '#10b981' : '#ef4444'};
        z-index: 1000;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Form submission
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const button = document.getElementById('place-order-btn');
    button.disabled = true;
    button.textContent = 'Processing...';
});
</script>
@endsection
