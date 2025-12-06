<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with cart items
     */
    public function index(): View
    {
        $cartItems = Cart::getCurrentCart();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum('total');
        $shipping = $this->calculateShipping($cartItems);
        $tax = $this->calculateTax($subtotal, $shipping);

        // Get applicable auto-apply coupons
        $autoCoupons = Coupon::autoApply()->available()->get();
        $appliedCoupons = [];

        foreach ($autoCoupons as $coupon) {
            // Check if coupon can be applied
            $errors = $coupon->canBeUsedBy(Auth::id(), $subtotal);
            if (empty($errors)) {
                $appliedCoupons[] = $coupon;
            }
        }

        // Calculate discounts
        $discount = 0;
        if (!empty($appliedCoupons)) {
            foreach ($appliedCoupons as $coupon) {
                $discountCalculation = $coupon->calculateDiscount($this->cartItemsToArray($cartItems), $subtotal);
                $discount += $discountCalculation['discount_amount'];
            }
        }

        $total = $subtotal + $shipping + $tax - $discount;

        return view('product.checkout', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'tax',
            'discount',
            'total',
            'appliedCoupons'
        ));
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $cartItems = Cart::getCurrentCart();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ]);
        }

        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::findByCode($code);

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        $subtotal = $cartItems->sum('total');
        $errors = $coupon->canBeUsedBy(Auth::id(), $subtotal);

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $errors)
            ]);
        }

        // Calculate discount
        $cartArray = $this->cartItemsToArray($cartItems);
        $discountCalculation = $coupon->calculateDiscount($cartArray, $subtotal);

        // Store coupon in session for checkout
        session(['applied_coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'name' => $coupon->name,
            'discount_amount' => $discountCalculation['discount_amount'],
            'type' => $coupon->type
        ]]);

        // Recalculate totals
        $shipping = $this->calculateShipping($cartItems);
        $tax = $this->calculateTax($subtotal, $shipping);
        $total = $subtotal + $shipping + $tax - $discountCalculation['discount_amount'];

        return response()->json([
            'success' => true,
            'message' => "Coupon '{$coupon->name}' applied!",
            'coupon' => [
                'name' => $coupon->name,
                'description' => $coupon->getDiscountDescription(),
                'discount' => number_format($discountCalculation['discount_amount'], 2)
            ],
            'totals' => [
                'subtotal' => number_format($subtotal, 2),
                'shipping' => number_format($shipping, 2),
                'tax' => number_format($tax, 2),
                'discount' => number_format($discountCalculation['discount_amount'], 2),
                'total' => number_format($total, 2)
            ]
        ]);
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(): JsonResponse
    {
        session()->forget('applied_coupon');

        $cartItems = Cart::getCurrentCart();
        $subtotal = $cartItems->sum('total');
        $shipping = $this->calculateShipping($cartItems);
        $tax = $this->calculateTax($subtotal, $shipping);
        $total = $subtotal + $shipping + $tax;

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'totals' => [
                'subtotal' => number_format($subtotal, 2),
                'shipping' => number_format($shipping, 2),
                'tax' => number_format($tax, 2),
                'discount' => '0.00',
                'total' => number_format($total, 2)
            ]
        ]);
    }

    /**
     * Process the checkout
     */
    public function process(Request $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $cartItems = Cart::getCurrentCart();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Your cart is empty.');
            }

            // Validate stock availability
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->product->name}. Available: {$item->product->stock}");
                }
            }

            $request->validate([
                'shipping_first_name' => 'required|string|max:255',
                'shipping_last_name' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'required|string|max:255',
                'shipping_region' => 'required|string|max:255',
                'shipping_postal_code' => 'nullable|string|max:20',
                'shipping_country' => 'required|string|max:255',
                'shipping_phone' => 'required|string|max:20',
                'billing_first_name' => 'required|string|max:255',
                'billing_last_name' => 'required|string|max:255',
                'billing_address' => 'required|string|max:500',
                'billing_city' => 'required|string|max:255',
                'billing_region' => 'required|string|max:255',
                'billing_postal_code' => 'nullable|string|max:20',
                'billing_country' => 'required|string|max:255',
                'billing_phone' => 'required|string|max:20',
                'payment_method' => 'required|in:mpesa,card,bank_transfer',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Check email if user not logged in
            if (!Auth::check()) {
                $request->validate([
                    'guest_email' => 'required|email|unique:users,email',
                ]);
            }

            // Calculate order totals
            $subtotal = $cartItems->sum('total');
            $shipping = $this->calculateShipping($cartItems);
            $tax = $this->calculateTax($subtotal, $shipping);

            // Handle coupon discount
            $appliedCoupon = null;
            $discount = 0;

            if (session()->has('applied_coupon')) {
                $couponData = session('applied_coupon');
                $appliedCoupon = Coupon::find($couponData['id']);

                if ($appliedCoupon && $appliedCoupon->isValid()) {
                    $cartArray = $this->cartItemsToArray($cartItems);
                    $discountCalculation = $appliedCoupon->calculateDiscount($cartArray, $subtotal);
                    $discount = $discountCalculation['discount_amount'];
                    $appliedCoupon->incrementUsage();
                }
            }

            $total = $subtotal + $shipping + $tax - $discount;

            // Create order data
            $orderData = [
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $request->shipping_first_name . ' ' . $request->shipping_last_name,
                'customer_email' => Auth::check() ? Auth::user()->email : $request->guest_email,
                'customer_phone' => $request->shipping_phone,
                'shipping_address' => [
                    'first_name' => $request->shipping_first_name,
                    'last_name' => $request->shipping_last_name,
                    'address' => $request->shipping_address,
                    'city' => $request->shipping_city,
                    'region' => $request->shipping_region,
                    'postal_code' => $request->shipping_postal_code,
                    'country' => $request->shipping_country,
                    'phone' => $request->shipping_phone,
                ],
                'billing_address' => [
                    'first_name' => $request->billing_first_name,
                    'last_name' => $request->billing_last_name,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'region' => $request->billing_region,
                    'postal_code' => $request->billing_postal_code,
                    'country' => $request->billing_country,
                    'phone' => $request->billing_phone,
                ],
                'order_items' => $cartItems->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'sku' => $item->product->sku,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                    ];
                })->toArray(),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_cost' => $shipping,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'currency' => 'KES',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ];

            // Add coupon info to order if applied
            if ($appliedCoupon) {
                $orderData['order_items'] = array_map(function($item) use ($appliedCoupon) {
                    $item['coupon_code'] = $appliedCoupon->code;
                    return $item;
                }, $orderData['order_items']);
            }

            // Create order
            $order = Order::create($orderData);

            // Reduce product stock
            foreach ($cartItems as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Create payment record
            $payment = Payment::create([
                'payment_reference' => Payment::generatePaymentReference(),
                'transaction_id' => null,
                'user_id' => Auth::id(),
                'payment_type' => 'merchandise',
                'description' => "Order #{$order->order_number}",
                'amount' => $total,
                'currency' => 'KES',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'payment_gateway' => $this->getPaymentGateway($request->payment_method),
                'notes' => "Order ID: {$order->id}",
            ]);

            // Link payment to order (assuming we'll add a payment_id column to orders table later)
            // For now, we'll update the order with payment info

            DB::commit();

            // Clear cart
            Cart::clearCart();

            // Clear coupon from session
            session()->forget('applied_coupon');

            // Redirect to payment processing
            return redirect()->route('checkout.payment', ['order' => $order->id])
                           ->with('success', 'Order created successfully. Please complete payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show payment page for order
     */
    public function payment(Order $order): View
    {
        // Ensure user can only view their own order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        // For guest orders, we'll need to verify via session or token
        // (This is a simplified version - in production, implement secure guest order access)

        return view('product.checkout.payment', compact('order'));
    }

    /**
     * Process payment for order
     */
    public function processPayment(Request $request, Order $order): RedirectResponse
    {
        // Ensure user can only pay for their own order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        // Here we would integrate with actual payment gateways
        // For demonstration, we'll simulate successful payment

        DB::beginTransaction();

        try {
            switch ($order->payment_method) {
                case 'mpesa':
                    // M-Pesa STK Push integration would go here
                    $this->processMpesaPayment($order);
                    break;

                case 'card':
                    // Stripe/card payment integration would go here
                    $this->processCardPayment($order, $request);
                    break;

                case 'bank_transfer':
                    // Bank transfer instructions
                    $this->processBankTransfer($order);
                    break;
            }

            DB::commit();

            return redirect()->route('checkout.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show order confirmation/success page
     */
    public function success(Order $order): View
    {
        // Ensure user can only view their own order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('product.checkout.success', compact('order'));
    }

    // Private helper methods

    private function calculateShipping($cartItems): float
    {
        $totalWeight = 0;
        $totalValue = $cartItems->sum('total');

        // Basic shipping calculation
        if ($totalValue >= 5000) { // Free shipping over KSH 5,000
            return 0;
        } elseif ($totalValue >= 2000) {
            return 200; // KSH 200 for orders over KSH 2,000
        } else {
            return 350; // Standard shipping
        }
    }

    private function calculateTax(float $subtotal, float $shipping): float
    {
        // Kenya VAT is 16%
        $taxableAmount = $subtotal + $shipping; // Some items may exclude shipping from tax
        return $taxableAmount * 0.16; // 16% VAT
    }

    private function cartItemsToArray($cartItems): array
    {
        return $cartItems->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
            ];
        })->toArray();
    }

    private function getPaymentGateway(string $paymentMethod): string
    {
        return match($paymentMethod) {
            'mpesa' => 'mpesa_daraja',
            'card' => 'stripe',
            'bank_transfer' => 'manual',
            default => 'unknown'
        };
    }

    private function processMpesaPayment(Order $order): void
    {
        // In a real implementation, integrate with M-Pesa STK Push API
        // This is a placeholder for the actual implementation

        // Simulate successful payment after API integration
        $order->update(['payment_status' => 'paid', 'paid_at' => now()]);

        // Update payment record
        $payment = Payment::where('description', "Order #{$order->order_number}")->first();
        if ($payment) {
            $payment->update([
                'payment_status' => 'completed',
                'transaction_id' => 'MPESA_' . time() . rand(1000, 9999),
                'paid_at' => now()
            ]);
        }
    }

    private function processCardPayment(Order $order, Request $request): void
    {
        // In a real implementation, integrate with Stripe API
        // This is a placeholder for the actual implementation

        // Validate payment details
        $request->validate([
            'card_number' => 'required|string',
            'expiry_month' => 'required|integer|min:1|max:12',
            'expiry_year' => 'required|integer|min:' . date('Y'),
            'cvv' => 'required|string|size:3',
        ]);

        // Simulate successful payment after Stripe processing
        $order->update(['payment_status' => 'paid', 'paid_at' => now()]);

        // Update payment record
        $payment = Payment::where('description', "Order #{$order->order_number}")->first();
        if ($payment) {
            $payment->update([
                'payment_status' => 'completed',
                'transaction_id' => 'STRIPE_' . time() . rand(1000, 9999),
                'paid_at' => now()
            ]);
        }
    }

    private function processBankTransfer(Order $order): void
    {
        // Bank transfer instructions - no immediate payment processing
        // Order status remains pending payment

        // Payment instructions will be shown to user
        // Admin will manually mark as paid when transfer is confirmed
    }
}
