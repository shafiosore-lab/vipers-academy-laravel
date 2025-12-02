@extends('layouts.app')

@section('title', 'Order Confirmed - Vipers Academy')

@section('content')
<section class="py-12 bg-green-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Success Animation -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <i class="fas fa-check text-white text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Order Confirmed!</h1>
                <p class="text-xl text-gray-600">Thank you for your purchase. Your order has been successfully placed.</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8 text-left">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $order->getStatusBadgeClass() }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                        <p class="text-sm text-gray-600 mt-2">Payment: {{ ucfirst($order->payment_status) }}</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t border-b py-6 mb-6">
                    <h3 class="text-lg font-medium mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->order_items as $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['product_name'] }}" class="w-16 h-16 object-cover rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item['product_name'] }}</h4>
                                        <p class="text-sm text-gray-500">SKU: {{ $item['sku'] }}</p>
                                        @if(isset($item['coupon_code']) && $item['coupon_code'])
                                            <p class="text-xs text-green-600">Discount Code: {{ $item['coupon_code'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}</p>
                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($item['total'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="grid md:grid-cols-2 gap-8 mb-6">
                    <!-- Addresses -->
                    <div>
                        <h4 class="font-medium mb-3">Shipping Address</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="font-medium">{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                            <p>{{ $order->shipping_address['address'] }}</p>
                            <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['region'] }}</p>
                            <p>{{ $order->shipping_address['postal_code'] ? $order->shipping_address['postal_code'] . ', ' : '' }}{{ $order->shipping_address['country'] }}</p>
                            <p class="text-sm text-gray-600">{{ $order->shipping_address['phone'] }}</p>
                        </div>

                        <h4 class="font-medium mb-3 mt-6">Billing Address</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="font-medium">{{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}</p>
                            <p>{{ $order->billing_address['address'] }}</p>
                            <p>{{ $order->billing_address['city'] }}, {{ $order->billing_address['region'] }}</p>
                            <p>{{ $order->billing_address['postal_code'] ? $order->billing_address['postal_code'] . ', ' : '' }}{{ $order->billing_address['country'] }}</p>
                            <p class="text-sm text-gray-600">{{ $order->billing_address['phone'] }}</p>
                        </div>
                    </div>

                    <!-- Payment & Totals -->
                    <div>
                        <h4 class="font-medium mb-3">Payment Method</h4>
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            @if($order->payment_method === 'mpesa')
                                <div class="flex items-center">
                                    <i class="fas fa-mobile-alt text-green-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-medium">M-Pesa Mobile Money</p>
                                        <p class="text-sm text-gray-600">Payment confirmed</p>
                                    </div>
                                </div>
                            @elseif($order->payment_method === 'card')
                                <div class="flex items-center">
                                    <i class="fas fa-credit-card text-blue-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-medium">Credit/Debit Card</p>
                                        <p class="text-sm text-gray-600">Payment confirmed</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <i class="fas fa-university text-orange-600 text-xl mr-3"></i>
                                    <div>
                                        <p class="font-medium">Bank Transfer</p>
                                        <p class="text-sm text-gray-600">Payment pending confirmation</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h4 class="font-medium mb-3">Order Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping:</span>
                                <span>${{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax:</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount:</span>
                                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-green-600">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Order Notes</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-medium text-blue-900 mb-4">What's Next?</h3>
                <div class="grid md:grid-cols-3 gap-6 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <h4 class="font-medium text-blue-900">Order Confirmation</h4>
                        <p class="text-sm text-blue-700">We'll send a confirmation email to {{ $order->customer_email }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-truck text-green-600"></i>
                        </div>
                        <h4 class="font-medium text-blue-900">Processing</h4>
                        <p class="text-sm text-blue-700">We'll process and ship your order within 2-3 business days</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-box-open text-purple-600"></i>
                        </div>
                        <h4 class="font-medium text-blue-900">Delivery</h4>
                        <p class="text-sm text-blue-700">Track your delivery with updates via email and SMS</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products') }}" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 font-medium transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
                </a>
                @auth
                    <a href="{{ route('player.portal.dashboard') }}" class="bg-gray-600 text-white px-8 py-3 rounded-md hover:bg-gray-700 font-medium transition-colors">
                        <i class="fas fa-user mr-2"></i>Back to Dashboard
                    </a>
                @endauth
                <button onclick="window.print()" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-md hover:bg-gray-200 font-medium transition-colors">
                    <i class="fas fa-print mr-2"></i>Print Receipt
                </button>
            </div>

            <!-- Support Info -->
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-2">Need help with your order?</p>
                <p class="text-sm text-gray-500">
                    Contact our support team at
                    <a href="mailto:orders@vipersacademy.com" class="text-blue-600 hover:underline">orders@vipersacademy.com</a>
                    or call <span class="font-medium">+254 XXX XXX XXX</span>
                </p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes checkmark {
    0% {
        transform: scale(0) rotate(45deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(45deg);
        opacity: 1;
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

.animate-checkmark {
    animation: checkmark 0.8s ease-in-out;
}

@media print {
    .no-print {
        display: none !important;
    }

    body {
        background-color: white !important;
    }

    .container {
        max-width: none !important;
        padding: 0 !important;
    }
}
</style>
@endsection
