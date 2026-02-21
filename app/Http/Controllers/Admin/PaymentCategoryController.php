<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentCategory;
use App\Models\Payment;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of payment categories.
     */
    public function index()
    {
        $categories = PaymentCategory::orderBy('sort_order')->get();

        // Get statistics for each category
        $categories->transform(function ($category) {
            $category->player_count = Player::where('payment_category_id', $category->id)->count();
            $category->total_revenue = $category->getTotalRevenue();
            $category->pending_amount = $category->getPendingAmount();
            $category->overdue_amount = $category->getOverdueAmount();
            return $category;
        });

        return view('admin.payment-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.payment-categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_categories',
            'description' => 'nullable|string',
            'monthly_amount' => 'required|numeric|min:0',
            'joining_fee' => 'required|numeric|min:0',
            'payment_interval_days' => 'required|integer|min:1',
            'grace_period_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        PaymentCategory::create($validated);

        return redirect()->route('admin.payment-categories.index')
            ->with('success', 'Payment category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(PaymentCategory $paymentCategory)
    {
        $payments = Payment::where('payment_category_id', $paymentCategory->id)
            ->with('player')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $players = Player::where('payment_category_id', $paymentCategory->id)->get();

        return view('admin.payment-categories.show', compact('paymentCategory', 'payments', 'players'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(PaymentCategory $paymentCategory)
    {
        return view('admin.payment-categories.edit', compact('paymentCategory'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, PaymentCategory $paymentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('payment_categories')->ignore($paymentCategory->id)],
            'description' => 'nullable|string',
            'monthly_amount' => 'required|numeric|min:0',
            'joining_fee' => 'required|numeric|min:0',
            'payment_interval_days' => 'required|integer|min:1',
            'grace_period_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $paymentCategory->update($validated);

        return redirect()->route('admin.payment-categories.index')
            ->with('success', 'Payment category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(PaymentCategory $paymentCategory)
    {
        // Check if there are players in this category
        $playerCount = Player::where('payment_category_id', $paymentCategory->id)->count();

        if ($playerCount > 0) {
            return redirect()->route('admin.payment-categories.index')
                ->with('error', 'Cannot delete category with ' . $playerCount . ' players. Please reassign players first.');
        }

        $paymentCategory->delete();

        return redirect()->route('admin.payment-categories.index')
            ->with('success', 'Payment category deleted successfully.');
    }

    /**
     * Toggle category active status.
     */
    public function toggleStatus(PaymentCategory $paymentCategory)
    {
        $paymentCategory->update(['is_active' => !$paymentCategory->is_active]);

        $status = $paymentCategory->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.payment-categories.index')
            ->with('success', "Category {$status} successfully.");
    }

    /**
     * Assign players to a category in bulk.
     */
    public function assignPlayers(Request $request, PaymentCategory $paymentCategory)
    {
        $validated = $request->validate([
            'player_ids' => 'required|array',
            'player_ids.*' => 'exists:players,id',
        ]);

        Player::whereIn('id', $validated['player_ids'])->update([
            'payment_category_id' => $paymentCategory->id,
            'category_effective_date' => now(),
        ]);

        return redirect()->route('admin.payment-categories.show', $paymentCategory->id)
            ->with('success', count($validated['player_ids']) . ' players assigned to ' . $paymentCategory->name);
    }
}
