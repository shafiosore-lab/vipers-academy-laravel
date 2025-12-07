<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filter by category if provided
        if ($request->has('category') && in_array($request->category, ['new', 'old'])) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $totalProducts = $query->count();
        $products = $query->latest()->paginate(12);

        // Group products by category for display
        $newProducts = Product::where('is_active', true)->where('category', 'new')->latest()->take(8)->get();
        $oldProducts = Product::where('is_active', true)->where('category', 'old')->latest()->take(8)->get();

        return view('product.index', compact('products', 'newProducts', 'oldProducts', 'totalProducts'));
    }

    public function category($category)
    {
        // Validate category
        if (!in_array($category, ['training', 'jerseys', 'new', 'old', 'accessories'])) {
            abort(404);
        }

        $query = Product::where('is_active', true);

        // Filter by specific category
        $query->where('category', $category);

        $totalProducts = $query->count();
        $products = $query->latest()->paginate(12);

        // Get other categories for display
        $newProducts = Product::where('is_active', true)->where('category', 'new')->latest()->take(8)->get();
        $oldProducts = Product::where('is_active', true)->where('category', 'old')->latest()->take(8)->get();

        return view('product.index', compact('products', 'newProducts', 'oldProducts', 'totalProducts', 'category'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return redirect()->route('products.index');
        }

        $query = Product::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });

        $totalProducts = $query->count();
        $products = $query->latest()->paginate(12);

        // Get other categories for display
        $newProducts = Product::where('is_active', true)->where('category', 'new')->latest()->take(8)->get();
        $oldProducts = Product::where('is_active', true)->where('category', 'old')->latest()->take(8)->get();

        return view('product.index', compact('products', 'newProducts', 'oldProducts', 'totalProducts', 'search'));
    }

    public function suggestions(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $suggestions = Product::where('is_active', true)
            ->where('name', 'like', "%{$search}%")
            ->limit(5)
            ->get(['id', 'name', 'sku'])
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'url' => route('products.show', $product->id)
                ];
            });

        return response()->json($suggestions);
    }

    public function show($id)
    {
        $product = Product::where('is_active', true)->findOrFail($id);

        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('is_active', true)
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('product.show', compact('product', 'relatedProducts'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'optional|integer|min:1',
        ]);

        try {
            $cartItem = Cart::addItem(
                $request->product_id,
                $request->quantity ?? 1
            );

            $message = 'Product added to cart successfully!';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::getCartCount(),
                    'cart_total' => Cart::getCartTotal(),
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Show cart
     */
    public function cart()
    {
        $cartItems = Cart::getCurrentCart();
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartCount();

        return view('cart', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $cartItem = Cart::findOrFail($request->cart_id);

            // Check ownership
            if (auth()->check()) {
                if ($cartItem->user_id !== auth()->id()) {
                    throw new \Exception('Unauthorized access to cart item.');
                }
            } else {
                if ($cartItem->session_id !== session()->getId()) {
                    throw new \Exception('Unauthorized access to cart item.');
                }
            }

            if ($request->quantity == 0) {
                $cartItem->remove();
                $message = 'Item removed from cart.';
            } else {
                $cartItem->updateQuantity($request->quantity);
                $message = 'Cart updated successfully.';
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::getCartCount(),
                    'cart_total' => Cart::getCartTotal(),
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        try {
            $cartItem = Cart::findOrFail($request->cart_id);

            // Check ownership
            if (auth()->check()) {
                if ($cartItem->user_id !== auth()->id()) {
                    throw new \Exception('Unauthorized access to cart item.');
                }
            } else {
                if ($cartItem->session_id !== session()->getId()) {
                    throw new \Exception('Unauthorized access to cart item.');
                }
            }

            $cartItem->remove();
            $message = 'Item removed from cart.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::getCartCount(),
                    'cart_total' => Cart::getCartTotal(),
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        Cart::clearCart();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully.',
                'cart_count' => 0,
                'cart_total' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Cart cleared successfully.');
    }

    /**
     * Get cart summary (for AJAX calls)
     */
    public function getCartSummary()
    {
        return response()->json([
            'cart_count' => Cart::getCartCount(),
            'cart_total' => Cart::getCartTotal(),
            'cart_items' => Cart::getCurrentCart()->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            }),
        ]);
    }
}
