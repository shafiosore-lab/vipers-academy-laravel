<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Enhanced Cart View with Multi-Tiered Authentication Flow
     */
    public function index(Request $request)
    {
        $cartItems = Cart::getCurrentCart();
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartCount();

        // Check for session timeout
        if ($request->has('session_timeout')) {
            return view('cart_auth_required', [
                'cartItems' => $cartItems,
                'cartTotal' => $cartTotal,
                'cartCount' => $cartCount,
                'sessionTimeout' => true
            ]);
        }

        // If user is authenticated, redirect to appropriate dashboard based on user type
        if (auth()->check()) {
            $user = auth()->user();

            if (!$user->isActive()) {
                $message = 'Your account is not active. Please contact support.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'redirect' => route('login'),
                    ], 403);
                }
                return view('cart_auth_required', [
                    'cartItems' => $cartItems,
                    'cartTotal' => $cartTotal,
                    'cartCount' => $cartCount,
                    'accountInactive' => true,
                    'message' => $message
                ]);
            }

            // Redirect authenticated users based on type
            $redirectRoute = $this->getUserDashboardRoute($user);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User authenticated',
                    'redirect' => $redirectRoute,
                ]);
            }

            return redirect($redirectRoute);
        }

        // Show cart with authentication options for guests
        return view('product.cart', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * Get appropriate dashboard route based on user type
     */
    private function getUserDashboardRoute($user)
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard');
        } elseif ($user->isPlayer()) {
            return route('player.portal.dashboard');
        } elseif ($user->isPartner()) {
            return route('partner.dashboard');
        }

        // For visitors and other user types, redirect to home
        return route('home');
    }

    /**
     * Player Login with Cart Preservation
     */
    public function playerLogin(Request $request)
    {
        // Store cart state before login attempt
        $cartState = $this->captureCartState();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->returnAuthError($validator->errors(), 'player');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Attempt authentication
        $attemptResult = Auth::attempt($credentials, $remember);

        if (!$attemptResult) {
            return $this->returnAuthError(['password' => ['Invalid credentials']], 'player');
        }

        $user = Auth::user();

        // Validate user type and status
        if (!$user->isPlayer()) {
            Auth::logout();
            return $this->returnAuthError(['email' => ['This account is not registered as a player']], 'player');
        }

        if (!$user->isActive()) {
            Auth::logout();
            $message = 'Your account is pending approval. Please contact support.';
            return $this->returnAuthError(['general' => [$message]], 'player');
        }

        // Transfer cart from session to user
        $this->transferCartToUser($user->id);

        // Regenerate session for security
        $request->session()->regenerate();

        return $this->returnAuthSuccess('player', 'Welcome back to Vipers Academy!');
    }

    /**
     * Partner Login with Cart Preservation
     */
    public function partnerLogin(Request $request)
    {
        $cartState = $this->captureCartState();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->returnAuthError($validator->errors(), 'partner');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        $attemptResult = Auth::attempt($credentials, $remember);

        if (!$attemptResult) {
            return $this->returnAuthError(['password' => ['Invalid credentials']], 'partner');
        }

        $user = Auth::user();

        if (!$user->isPartner()) {
            Auth::logout();
            return $this->returnAuthError(['email' => ['This account is not registered as a business partner']], 'partner');
        }

        if (!$user->isActive()) {
            Auth::logout();
            $message = 'Your partner account is pending approval. Please contact support.';
            return $this->returnAuthError(['general' => [$message]], 'partner');
        }

        $this->transferCartToUser($user->id);
        $request->session()->regenerate();

        return $this->returnAuthSuccess('partner', 'Welcome back to the Partner Portal!');
    }

    /**
     * Visitor Registration (Limited Access)
     */
    public function visitorRegister(Request $request)
    {
        $cartState = $this->captureCartState();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^(\+?254|0)[17]\d{8}$/',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'name' => 'required|string|max:255',
        ], [
            'email.unique' => 'This email is already registered.',
            'phone.regex' => 'Please enter a valid Kenyan phone number.',
            'password.letters' => 'Password must contain letters.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain numbers.',
            'password.symbols' => 'Password must contain special characters.',
        ]);

        if ($validator->fails()) {
            return $this->returnAuthError($validator->errors(), 'visitor');
        }

        try {
            DB::beginTransaction();

            // Create user with visitor privileges (limited access)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'visitor', // Limited access user type
                'status' => 'active',
            ]);

            // Create visitor profile
            $user->visitorProfile()->create([
                'phone' => $request->phone,
                'registration_method' => 'cart_registration',
            ]);

            DB::commit();

            // Auto-login the user
            Auth::login($user);
            $this->transferCartToUser($user->id);
            $request->session()->regenerate();

            return $this->returnAuthSuccess('visitor', 'Registration successful! Welcome to Vipers Academy.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Visitor registration failed: ' . $e->getMessage());

            return $this->returnAuthError(['general' => ['Registration failed. Please try again.']], 'visitor');
        }
    }

    /**
     * Enhanced Add to Cart with Real-time Validation
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ], [
            'product_id.exists' => 'Product not found.',
            'quantity.max' => 'Maximum 10 items allowed per product.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $cartItem = Cart::addItem(
                $request->product_id,
                $request->quantity
            );

            $message = 'Product added to cart successfully!';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => Cart::getCartCount(),
                    'cart_total' => Cart::getCartTotal(),
                    'cart_item' => [
                        'id' => $cartItem->id,
                        'product_name' => $cartItem->product->name,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'total' => $cartItem->total,
                    ],
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Add to cart failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update Cart with Enhanced Security
     */
    public function updateCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return $this->returnCartError($validator->errors());
        }

        try {
            $cartItem = Cart::findOrFail($request->cart_id);

            // Enhanced security check
            if (!$this->canModifyCartItem($cartItem)) {
                throw new \Exception('Unauthorized access to cart item.');
            }

            if ($request->quantity == 0) {
                $cartItem->remove();
                $message = 'Item removed from cart.';
            } else {
                $cartItem->updateQuantity($request->quantity);
                $message = 'Cart updated successfully.';
            }

            return $this->returnCartSuccess($message);

        } catch (\Exception $e) {
            Log::error('Cart update failed: ' . $e->getMessage());
            return $this->returnCartError(['general' => [$e->getMessage()]]);
        }
    }

    /**
     * Remove Item from Cart
     */
    public function remove(Request $request, $id)
    {
        try {
            $cartItem = Cart::findOrFail($id);

            if (!$this->canModifyCartItem($cartItem)) {
                throw new \Exception('Unauthorized access to cart item.');
            }

            $cartItem->remove();
            return $this->returnCartSuccess('Item removed from cart.');

        } catch (\Exception $e) {
            Log::error('Cart removal failed: ' . $e->getMessage());
            return $this->returnCartError(['general' => [$e->getMessage()]]);
        }
    }

    /**
     * Clear Cart
     */
    public function clearCart(Request $request)
    {
        try {
            Cart::clearCart();
            return $this->returnCartSuccess('Cart cleared successfully.');
        } catch (\Exception $e) {
            Log::error('Cart clear failed: ' . $e->getMessage());
            return $this->returnCartError(['general' => ['Failed to clear cart']]);
        }
    }

    /**
     * Get Cart Summary for Real-time Updates
     */
    public function getCartSummary(Request $request)
    {
        try {
            $cartItems = Cart::getCurrentCart();

            return response()->json([
                'success' => true,
                'cart_count' => Cart::getCartCount(),
                'cart_total' => Cart::getCartTotal(),
                'cart_items' => $cartItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product->name,
                        'product_image' => $item->product->images ? asset('storage/' . $item->product->images[0]) : null,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                        'total' => (float) $item->total,
                        'stock' => $item->product->stock,
                    ];
                }),
                'is_authenticated' => auth()->check(),
                'user_type' => auth()->check() ? auth()->user()->user_type : 'guest',
            ]);
        } catch (\Exception $e) {
            Log::error('Cart summary failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart summary',
            ], 500);
        }
    }

    /**
     * Check Authentication Status
     */
    public function checkAuthStatus(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'is_active' => $user->isActive(),
                ],
                'cart_preserved' => true,
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'cart_items' => Cart::getCurrentCart()->count(),
        ]);
    }

    /**
     * Validate Email Availability (Real-time)
     */
    public function validateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid email format',
            ]);
        }

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'valid' => true,
            'available' => !$exists,
            'message' => $exists ? 'Email already registered' : 'Email available',
        ]);
    }

    /**
     * Validate Phone Number (Real-time)
     */
    public function validatePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^(\+?254|0)[17]\d{8}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid phone number format',
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Phone number valid',
        ]);
    }

    /**
     * Validate Password Strength (Real-time)
     */
    public function validatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        $isValid = !$validator->fails();
        $errors = $validator->errors();

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Strong password' : 'Password does not meet requirements',
            'requirements' => [
                'min_length' => strlen($request->password) >= 8,
                'has_letters' => preg_match('/[a-zA-Z]/', $request->password),
                'has_mixed_case' => preg_match('/[a-z]/', $request->password) && preg_match('/[A-Z]/', $request->password),
                'has_numbers' => preg_match('/\d/', $request->password),
                'has_symbols' => preg_match('/[^a-zA-Z\d]/', $request->password),
            ],
        ]);
    }

    // Private helper methods

    /**
     * Capture current cart state for preservation
     */
    private function captureCartState()
    {
        return [
            'cart_count' => Cart::getCartCount(),
            'cart_total' => Cart::getCartTotal(),
            'timestamp' => now(),
            'session_id' => session()->getId(),
        ];
    }

    /**
     * Transfer guest cart to authenticated user
     */
    private function transferCartToUser($userId)
    {
        try {
            $transferred = Cart::transferGuestCartToUser($userId);
            Log::info("Cart transferred to user {$userId}: {$transferred} items");
            return $transferred;
        } catch (\Exception $e) {
            Log::error("Cart transfer failed for user {$userId}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if user can modify cart item
     */
    private function canModifyCartItem($cartItem)
    {
        if (auth()->check()) {
            return $cartItem->user_id === auth()->id();
        } else {
            return $cartItem->session_id === session()->getId();
        }
    }

    /**
     * Return authentication error response
     */
    private function returnAuthError($errors, $type)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed',
                'errors' => $errors,
                'login_type' => $type,
            ], 422);
        }

        return redirect()->back()
            ->withErrors($errors)
            ->with('login_type', $type)
            ->withInput();
    }

    /**
     * Return authentication success response
     */
    private function returnAuthSuccess($type, $message)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $redirectRoute = $this->getUserDashboardRoute(auth()->user());

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => $redirectRoute,
                'login_type' => $type,
                'user' => [
                    'name' => auth()->user()->name,
                    'type' => auth()->user()->user_type,
                ],
            ]);
        }

        return redirect()->intended($this->getUserDashboardRoute(auth()->user()))
            ->with('success', $message);
    }

    /**
     * Return cart operation error response
     */
    private function returnCartError($errors)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart operation failed',
                'errors' => $errors,
            ], 422);
        }

        return redirect()->back()->withErrors($errors);
    }

    /**
     * Return cart operation success response
     */
    private function returnCartSuccess($message)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => Cart::getCartCount(),
                'cart_total' => Cart::getCartTotal(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
