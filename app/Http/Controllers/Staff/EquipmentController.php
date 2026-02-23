<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentDistribution;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    /**
     * Display equipment categories page
     */
    public function categories()
    {
        $categories = EquipmentCategory::withCount('equipment')
            ->orderBy('name')
            ->paginate(10);

        return view('staff.manager.equipment.categories', compact('categories'));
    }

    /**
     * Store new equipment category
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        EquipmentCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'box',
            'organization_id' => auth()->user()->organization_id,
        ]);

        return redirect()->route('manager.equipment.categories')
            ->with('success', 'Equipment category created successfully.');
    }

    /**
     * Update equipment category
     */
    public function updateCategory(Request $request, EquipmentCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'box',
        ]);

        return redirect()->route('manager.equipment.categories')
            ->with('success', 'Equipment category updated successfully.');
    }

    /**
     * Delete equipment category
     */
    public function destroyCategory(EquipmentCategory $category)
    {
        if ($category->equipment()->count() > 0) {
            return redirect()->route('manager.equipment.categories')
                ->with('error', 'Cannot delete category with associated equipment.');
        }

        $category->delete();

        return redirect()->route('manager.equipment.categories')
            ->with('success', 'Equipment category deleted successfully.');
    }

    /**
     * Display inventory counts page
     */
    public function inventory()
    {
        $equipment = Equipment::with('category')
            ->orderBy('name')
            ->paginate(15);

        $totalItems = Equipment::sum('quantity');
        $lowStock = Equipment::whereRaw('quantity <= min_quantity')->count();
        $available = Equipment::where('status', 'available')->sum('quantity');
        $distributed = Equipment::where('status', 'distributed')->sum('quantity');

        return view('staff.manager.equipment.inventory', compact(
            'equipment',
            'totalItems',
            'lowStock',
            'available',
            'distributed'
        ));
    }

    /**
     * Store new equipment item
     */
    public function storeEquipment(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|unique:equipment,sku',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'sponsor' => 'nullable|string|max:255',
            'sponsor_compliant' => 'boolean',
            'expiry_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Equipment::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'sku' => $request->sku,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'condition' => $request->condition,
            'purchase_date' => $request->purchase_date,
            'purchase_price' => $request->purchase_price,
            'sponsor' => $request->sponsor,
            'sponsor_compliant' => $request->sponsor_compliant ?? false,
            'expiry_date' => $request->expiry_date,
            'location' => $request->location,
            'status' => $request->quantity > 0 ? 'available' : 'retired',
            'notes' => $request->notes,
            'organization_id' => auth()->user()->organization_id,
        ]);

        return redirect()->route('manager.equipment.inventory')
            ->with('success', 'Equipment added successfully.');
    }

    /**
     * Update equipment item
     */
    public function updateEquipment(Request $request, Equipment $equipment)
    {
        $request->validate([
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|unique:equipment,sku,' . $equipment->id,
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'sponsor' => 'nullable|string|max:255',
            'sponsor_compliant' => 'boolean',
            'expiry_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $equipment->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'sku' => $request->sku,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'condition' => $request->condition,
            'purchase_date' => $request->purchase_date,
            'purchase_price' => $request->purchase_price,
            'sponsor' => $request->sponsor,
            'sponsor_compliant' => $request->sponsor_compliant ?? false,
            'expiry_date' => $request->expiry_date,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        return redirect()->route('manager.equipment.inventory')
            ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Delete equipment item
     */
    public function destroyEquipment(Equipment $equipment)
    {
        if ($equipment->distributions()->where('status', 'active')->count() > 0) {
            return redirect()->route('manager.equipment.inventory')
                ->with('error', 'Cannot delete equipment with active distributions.');
        }

        $equipment->delete();

        return redirect()->route('manager.equipment.inventory')
            ->with('success', 'Equipment deleted successfully.');
    }

    /**
     * Display distribution records page
     */
    public function distribution()
    {
        $distributions = EquipmentDistribution::with(['equipment', 'player', 'staff'])
            ->orderBy('assigned_date', 'desc')
            ->paginate(15);

        $activeDistributions = EquipmentDistribution::where('status', 'active')->count();
        $totalDistributed = EquipmentDistribution::count();

        return view('staff.manager.equipment.distribution', compact(
            'distributions',
            'activeDistributions',
            'totalDistributed'
        ));
    }

    /**
     * Store new equipment distribution
     */
    public function storeDistribution(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'player_id' => 'nullable|exists:players,id',
            'staff_id' => 'nullable|exists:staff,id',
            'team_name' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'assigned_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        if ($equipment->getAvailableQuantity() < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Insufficient equipment available. Only ' . $equipment->getAvailableQuantity() . ' available.');
        }

        EquipmentDistribution::create([
            'equipment_id' => $request->equipment_id,
            'player_id' => $request->player_id,
            'staff_id' => $request->staff_id,
            'team_id' => null,
            'quantity' => $request->quantity,
            'condition_when_assigned' => $request->condition,
            'assigned_date' => $request->assigned_date,
            'assigned_by' => Auth::id(),
            'status' => 'active',
            'notes' => $request->notes,
            'organization_id' => auth()->user()->organization_id,
        ]);

        // Update equipment status
        $equipment->update(['status' => 'distributed']);

        return redirect()->route('manager.equipment.distribution')
            ->with('success', 'Equipment distributed successfully.');
    }

    /**
     * Return equipment
     */
    public function returnEquipment(Request $request, EquipmentDistribution $distribution)
    {
        $request->validate([
            'condition_when_returned' => 'required|in:new,good,fair,poor,damaged',
            'notes' => 'nullable|string',
        ]);

        $distribution->update([
            'status' => 'returned',
            'returned_date' => now(),
            'condition_when_returned' => $request->condition_when_returned,
            'notes' => $distribution->notes . "\n" . $request->notes,
        ]);

        // Update equipment quantity and status
        $equipment = $distribution->equipment;
        $availableQty = $equipment->getAvailableQuantity();

        if ($availableQty > 0) {
            $equipment->update(['status' => 'available']);
        } else {
            $equipment->update(['status' => 'available']);
        }

        return redirect()->route('manager.equipment.distribution')
            ->with('success', 'Equipment returned successfully.');
    }

    /**
     * Display sponsor compliance report
     */
    public function compliance()
    {
        $sponsoredItems = Equipment::whereNotNull('sponsor')
            ->where('sponsor_compliant', true)
            ->with('category')
            ->orderBy('sponsor')
            ->paginate(15);

        $totalSponsored = Equipment::whereNotNull('sponsor')->count();
        $compliant = Equipment::where('sponsor_compliant', true)->count();
        $nonCompliant = Equipment::whereNotNull('sponsor')
            ->where('sponsor_compliant', false)
            ->count();

        $sponsors = Equipment::select('sponsor')
            ->whereNotNull('sponsor')
            ->distinct()
            ->pluck('sponsor');

        return view('staff.manager.equipment.compliance', compact(
            'sponsoredItems',
            'totalSponsored',
            'compliant',
            'nonCompliant',
            'sponsors'
        ));
    }

    /**
     * Generate sponsor compliance report
     */
    public function generateComplianceReport(Request $request)
    {
        $request->validate([
            'sponsor' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $query = Equipment::with('category', 'distributions')
            ->whereNotNull('sponsor')
            ->where('sponsor_compliant', true);

        if ($request->sponsor) {
            $query->where('sponsor', $request->sponsor);
        }

        if ($request->start_date) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $report = $query->get();

        return view('staff.manager.equipment.compliance-report', compact('report'));
    }
}
