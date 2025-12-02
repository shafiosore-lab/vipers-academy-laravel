<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        // Calculate summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'order_items' => 'required|array',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:mpesa,card,bank_transfer,cash_on_delivery',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
            'order_items' => $request->order_items,
            'subtotal' => $request->subtotal,
            'tax_amount' => $request->tax_amount ?? 0,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $updateData = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ];

        // Set timestamps based on status changes
        if ($request->order_status === 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = now();
        }

        if ($request->order_status === 'delivered' && !$order->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        if ($request->payment_status === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->canBeCancelled()) {
            $order->delete();
            return redirect()->route('admin.orders.index')
                            ->with('success', 'Order deleted successfully.');
        }

        return redirect()->route('admin.orders.show', $order)
                        ->with('error', 'Cannot delete order that is already processed.');
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'type' => 'required|in:order,payment',
        ]);

        if ($request->type === 'order') {
            $order->update(['order_status' => $request->status]);

            if ($request->status === 'shipped' && !$order->shipped_at) {
                $order->update(['shipped_at' => now()]);
            } elseif ($request->status === 'delivered' && !$order->delivered_at) {
                $order->update(['delivered_at' => now()]);
            }
        } else {
            $order->update(['payment_status' => $request->status]);

            if ($request->status === 'paid' && !$order->paid_at) {
                $order->update(['paid_at' => now()]);
            }
        }

        return response()->json(['success' => true]);
    }
}
