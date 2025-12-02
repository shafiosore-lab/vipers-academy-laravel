<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(): View
    {
        $coupons = Coupon::withTrashed()->latest()->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create(): View
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:starts_at',
            'first_time_only' => 'boolean',
            'auto_apply' => 'boolean',
        ]);

        // Set default values
        $validated['usage_count'] = 0;
        $validated['is_active'] = $request->has('is_active');
        $validated['first_time_only'] = $request->has('first_time_only');
        $validated['auto_apply'] = $request->has('auto_apply');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon): View
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'first_time_only' => 'boolean',
            'auto_apply' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['first_time_only'] = $request->has('first_time_only');
        $validated['auto_apply'] = $request->has('auto_apply');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon deleted successfully.');
    }

    /**
     * Toggle coupon active status.
     */
    public function toggle(Coupon $coupon): JsonResponse
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => 'Coupon status updated successfully.'
        ]);
    }

    /**
     * Duplicate coupon.
     */
    public function duplicate(Coupon $coupon): RedirectResponse
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '_COPY_' . time();
        $newCoupon->name = $coupon->name . ' (Copy)';
        $newCoupon->usage_count = 0;
        $newCoupon->starts_at = null;
        $newCoupon->expires_at = null;
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon)
                        ->with('success', 'Coupon duplicated successfully. Please update the code and settings.');
    }

    /**
     * Force delete a soft deleted coupon.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon permanently deleted.');
    }

    /**
     * Restore a soft deleted coupon.
     */
    public function restore(string $id): RedirectResponse
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon restored successfully.');
    }

    /**
     * Generate bulk coupons.
     */
    public function bulkCreate(Request $request): RedirectResponse
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'count' => 'required|integer|min:1|max:100',
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $coupons = [];
        for ($i = 0; $i < $request->count; $i++) {
            $code = strtoupper($request->prefix . '_' . str_pad($i + 1, 3, '0', STR_PAD_LEFT));

            $coupons[] = [
                'code' => $code,
                'name' => ucfirst($request->type) . ' Discount #' . ($i + 1),
                'type' => $request->type,
                'value' => $request->value,
                'is_active' => true,
                'expires_at' => $request->expires_at,
                'usage_limit' => 1,
                'usage_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Coupon::insert($coupons);

        return redirect()->route('admin.coupons.index')
                        ->with('success', $request->count . ' coupons generated successfully.');
    }
}
