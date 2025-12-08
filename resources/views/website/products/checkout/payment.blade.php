@extends('layouts.product')

@section('title', 'Complete Payment - Vipers Academy')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Complete Your Payment</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Order #{{ $order->order_number }}</h2>

                <!-- Order Details -->
                <div class="mb-6">
                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <strong>Order Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <strong>Status:</strong>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusBadgeClass() }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t pt-4 mb-4">
                    <h3 class="font-medium mb-3">Order Items</h3>
                    <div class="space-y-3">
                        @foreach($order->order_items as $item)
                            <div class="flex items-center justify-between border-b pb-3">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['product_name'] }}" class="w-12 h-12 object-cover rounded">
                                    <div>
                                        <p class="font-medium">{{ $item['product_name'] }}</p>
                                        <p class="text-sm text-gray-500">SKU: {{ $item['sku'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}</p>
                                    <p class="text-sm text-gray-500">${{ number_format($item['total'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Totals -->
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between items-center mb-2 text-green-600">
                            <span>Discount:</span>
                            <span>-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-2">
                    <div class="flex justify-between items-center font-bold text-lg">
                        <span>Total:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            @if($order->payment_status === 'pending')
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- M-Pesa Payment -->
                    @if($order->payment_method === 'mpesa')
                        <div class="md:col-span-2">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <h3 class="text-lg font-semibold text-green-800 mb-2">Pay with M-Pesa</h3>
                                    <div class="text-sm text-green-700 space-y-1">
                                        <p>• A payment prompt will be sent to your phone</p>
                                        <p>• Enter your M-Pesa PIN when prompted</p>
                                        <p>• Transaction is processed instantly</p>
                                    </div>
                                </div>

                                <form id="mpesa-form" method="POST" action="{{ route('checkout.process-payment', $order) }}">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Phone Number *</label>
                                            <input type="tel" name="phone" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="2547xxxxxxxx">
                                            <small class="text-gray-500">Format: 2547xxxxxxxx (without +)</small>
                                        </div>

                                        <button type="submit" id="pay-mpesa-btn" class="w-full bg-green-600 text-white py-3 px-6 rounded-md hover:bg-green-700 font-medium text-lg">
                                            Pay KSH {{ number_format($order->total_amount, 2) }} with M-Pesa
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Card Payment -->
                    @if($order->payment_method === 'card')
                        <div class="md:col-span-2">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Pay with Credit/Debit Card</h3>
                                    <div class="text-sm text-blue-700 space-y-1">
                                        <p>• Secure payment powered by Stripe</p>
                                        <p>• All major cards accepted</p>
                                        <p>• Your payment information is encrypted</p>
                                    </div>
                                </div>

                                <form id="card-form" method="POST" action="{{ route('checkout.process-payment', $order) }}">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Card Number *</label>
                                            <input type="text" name="card_number" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="1234 5678 9012 3456">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium mb-1">Expiry Date *</label>
                                                <input type="text" name="expiry_date" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="MM/YY">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium mb-1">CVV *</label>
                                                <input type="text" name="cvv" required maxlength="3" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="123">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Cardholder Name *</label>
                                            <input type="text" name="cardholder_name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" placeholder="John Doe">
                                        </div>

                                        <button type="submit" id="pay-card-btn" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 font-medium text-lg">
                                            Pay KSH {{ number_format($order->total_amount, 2) }} with Card
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Bank Transfer -->
                    @if($order->payment_method === 'bank_transfer')
                        <div class="md:col-span-2">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                                    <h3 class="text-lg font-semibold text-orange-800 mb-2">Bank Transfer Instructions</h3>
                                    <div class="text-sm text-orange-700 space-y-2">
                                        <p>• Transfer the exact amount to our account</p>
                                        <p>• Use your Order Number as reference</p>
                                        <p>• Payment confirmation may take 1-2 business days</p>
                                        <p>• We'll email you once payment is confirmed</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <h4 class="font-medium mb-2">Bank Details</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Bank Name:</span>
                                            <span>KCB Bank Kenya</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Account Name:</span>
                                            <span>Vipers Academy</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Account Number:</span>
                                            <span>1234567890</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Swift Code:</span>
                                            <span>KCBKENX</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Reference:</span>
                                            <span>{{ $order->order_number }}</span>
                                        </div>
                                        <div class="flex justify-between font-medium">
                                            <span>Amount:</span>
                                            <span>KSH {{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <p class="text-sm text-gray-600 mb-4">After making the transfer, please send the receipt to <strong>payments@vipersacademy.com</strong></p>

                                    <form id="bank-transfer-form" method="POST" action="{{ route('checkout.process-payment', $order) }}">
                                        @csrf
                                        <button type="submit" class="bg-orange-600 text-white py-3 px-6 rounded-md hover:bg-orange-700 font-medium">
                                            I Have Completed the Bank Transfer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Order Summary Sidebar -->
                    <div class="md:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-4 sticky top-4">
                            <h3 class="font-semibold mb-4">Order Summary</h3>

                            <!-- Shipping Info -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium mb-2">Shipping Address</h4>
                                <div class="text-sm text-gray-600">
                                    <p>{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                                    <p>{{ $order->shipping_address['address'] }}</p>
                                    <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['region'] }}</p>
                                    <p>{{ $order->shipping_address['country'] }}</p>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium mb-2">Payment Method</h4>
                                <div class="flex items-center text-sm">
                                    @if($order->payment_method === 'mpesa')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">M-Pesa</span>
                                    @elseif($order->payment_method === 'card')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Card</span>
                                    @else
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">Bank Transfer</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="border-t pt-4">
                                <div class="flex justify-between font-bold">
                                    <span>Total</span>
                                    <span>${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Payment Already Processed -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Payment Completed</h3>
                    <p class="text-gray-600 mb-4">Your payment has already been processed successfully.</p>
                    <a href="{{ route('checkout.success', $order) }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                        View Order Confirmation
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
// Form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const button = form.querySelector('button[type="submit"]');
        if (button) {
            button.disabled = true;
            button.textContent = 'Processing Payment...';
        }
    });
});

// Card number formatting
const cardInput = document.querySelector('input[name="card_number"]');
if (cardInput) {
    cardInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });
}

// Expiry date formatting
const expiryInput = document.querySelector('input[name="expiry_date"]');
if (expiryInput) {
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });
}
</script>
@endsection
