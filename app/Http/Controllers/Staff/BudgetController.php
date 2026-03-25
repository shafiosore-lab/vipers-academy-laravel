<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BudgetPlan;
use App\Models\BudgetItem;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    // Middleware is handled at the route level via route groups

    // ==================== BUDGET PLANS ====================

    /**
     * List all budget plans
     */
    public function budgets(Request $request)
    {
        $query = BudgetPlan::with(['items.category', 'createdBy']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by year
        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $budgets = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(15);

        $years = BudgetPlan::getAvailableYears();
        $types = ['monthly' => 'Monthly', 'yearly' => 'Yearly'];
        $statuses = ['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed', 'cancelled' => 'Cancelled'];

        return view('staff.finance.budgets.index', compact('budgets', 'years', 'types', 'statuses'));
    }

    /**
     * Show create budget form
     */
    public function createBudget()
    {
        $years = BudgetPlan::getAvailableYears();
        $months = BudgetPlan::getAvailableMonths();
        $categories = ExpenseCategory::active()->get();

        return view('staff.finance.budgets.create', compact('years', 'months', 'categories'));
    }

    /**
     * Store a new budget plan
     */
    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:monthly,yearly',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'nullable|integer|min:1|max:12',
            'total_budget' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'objectives' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.expense_category_id' => 'nullable|exists:expense_categories,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.budgeted_amount' => 'required_with:items|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        // Validate monthly budget has month
        if ($validated['type'] === 'monthly' && empty($validated['month'])) {
            return back()->withErrors(['month' => 'Month is required for monthly budget'])->withInput();
        }

        // Check for duplicate
        $exists = BudgetPlan::where('type', $validated['type'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'] ?? null)
            ->exists();

        if ($exists) {
            return back()->withErrors(['duplicate' => 'A budget for this period already exists'])->withInput();
        }

        $budget = BudgetPlan::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'year' => $validated['year'],
            'month' => $validated['month'] ?? null,
            'total_budget' => $validated['total_budget'],
            'notes' => $validated['notes'],
            'objectives' => $validated['objectives'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Create budget items if provided
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $index => $item) {
                if (!empty($item['name']) && isset($item['budgeted_amount'])) {
                    BudgetItem::create([
                        'budget_plan_id' => $budget->id,
                        'expense_category_id' => $item['expense_category_id'] ?? null,
                        'name' => $item['name'],
                        'description' => $item['description'] ?? null,
                        'budgeted_amount' => $item['budgeted_amount'],
                        'sort_order' => $index,
                        'status' => 'draft',
                    ]);
                }
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'budget_created',
            'description' => "Created {$validated['type']} budget plan: {$budget->name}",
            'metadata' => ['budget_id' => $budget->id],
        ]);

        return redirect()->route('finance.budgets.show', $budget->id)
            ->with('success', 'Budget plan created successfully.');
    }

    /**
     * Show budget details
     */
    public function showBudget(BudgetPlan $budget)
    {
        $budget->load(['items.category', 'items.expenses', 'createdBy', 'approvedBy']);

        // Calculate actual spending from expenses
        $expenses = Expense::where('budget_plan_id', $budget->id)
            ->where('status', 'paid')
            ->get();

        $totalSpent = $expenses->sum('amount');

        return view('staff.finance.budgets.show', compact('budget', 'expenses', 'totalSpent'));
    }

    /**
     * Show edit budget form
     */
    public function editBudget(BudgetPlan $budget)
    {
        if ($budget->status !== 'draft') {
            return redirect()->route('finance.budgets.show', $budget->id)
                ->with('warning', 'Only draft budgets can be edited.');
        }

        $budget->load('items');
        $years = BudgetPlan::getAvailableYears();
        $months = BudgetPlan::getAvailableMonths();
        $categories = ExpenseCategory::active()->get();

        return view('staff.finance.budgets.edit', compact('budget', 'years', 'months', 'categories'));
    }

    /**
     * Update budget plan
     */
    public function updateBudget(Request $request, BudgetPlan $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('warning', 'Only draft budgets can be edited.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_budget' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'objectives' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:budget_items,id',
            'items.*.expense_category_id' => 'nullable|exists:expense_categories,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.budgeted_amount' => 'required_with:items|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        $budget->update([
            'name' => $validated['name'],
            'total_budget' => $validated['total_budget'],
            'notes' => $validated['notes'],
            'objectives' => $validated['objectives'],
        ]);

        // Update budget items
        if (!empty($validated['items'])) {
            $existingIds = $budget->items->pluck('id')->toArray();
            $newIds = [];

            foreach ($validated['items'] as $index => $item) {
                if (!empty($item['name']) && isset($item['budgeted_amount'])) {
                    $budgetItem = BudgetItem::updateOrCreate(
                        ['id' => $item['id'] ?? null],
                        [
                            'budget_plan_id' => $budget->id,
                            'expense_category_id' => $item['expense_category_id'] ?? null,
                            'name' => $item['name'],
                            'description' => $item['description'] ?? null,
                            'budgeted_amount' => $item['budgeted_amount'],
                            'sort_order' => $index,
                            'status' => 'draft',
                        ]
                    );
                    $newIds[] = $budgetItem->id;
                }
            }

            // Delete removed items
            BudgetItem::where('budget_plan_id', $budget->id)
                ->whereNotIn('id', $newIds)
                ->delete();
        }

        return redirect()->route('finance.budgets.show', $budget->id)
            ->with('success', 'Budget plan updated successfully.');
    }

    /**
     * Activate budget plan
     */
    public function activateBudget(BudgetPlan $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('warning', 'Only draft budgets can be activated.');
        }

        $budget->update(['status' => 'active']);

        // Update all items to active
        $budget->items()->update(['status' => 'active']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'budget_activated',
            'description' => "Activated budget plan: {$budget->name}",
            'metadata' => ['budget_id' => $budget->id],
        ]);

        return back()->with('success', 'Budget activated successfully.');
    }

    /**
     * Close budget plan
     */
    public function closeBudget(BudgetPlan $budget)
    {
        if ($budget->status !== 'active') {
            return back()->with('warning', 'Only active budgets can be closed.');
        }

        $budget->update(['status' => 'closed']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'budget_closed',
            'description' => "Closed budget plan: {$budget->name}",
            'metadata' => ['budget_id' => $budget->id],
        ]);

        return back()->with('success', 'Budget closed successfully.');
    }

    /**
     * Delete budget plan
     */
    public function deleteBudget(BudgetPlan $budget)
    {
        if ($budget->status !== 'draft') {
            return back()->with('warning', 'Only draft budgets can be deleted.');
        }

        $budget->items()->delete();
        $budget->expenses()->delete();
        $budget->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'budget_deleted',
            'description' => "Deleted budget plan",
            'metadata' => ['budget_id' => $budget->id],
        ]);

        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget plan deleted successfully.');
    }

    // ==================== EXPENSES ====================

    /**
     * List all expenses
     */
    public function expenses(Request $request)
    {
        $query = Expense::with(['category', 'budgetPlan', 'createdBy']);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('expense_category_id', $request->category_id);
        }

        // Filter by budget
        if ($request->has('budget_id') && $request->budget_id) {
            $query->where('budget_plan_id', $request->budget_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);

        $categories = ExpenseCategory::active()->get();
        $budgets = BudgetPlan::where('status', 'active')->get();
        $statuses = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'paid' => 'Paid'];

        return view('staff.finance.expenses.index', compact('expenses', 'categories', 'budgets', 'statuses'));
    }

    /**
     * Show create expense form
     */
    public function createExpense(Request $request)
    {
        $categories = ExpenseCategory::active()->get();
        $budgets = BudgetPlan::where('status', 'active')->get();
        $paymentMethods = Expense::getPaymentMethods();

        // Pre-select budget if provided
        $selectedBudgetId = $request->budget_id;

        return view('staff.finance.expenses.create', compact(
            'categories', 'budgets', 'paymentMethods', 'selectedBudgetId'
        ));
    }

    /**
     * Store a new expense
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'budget_plan_id' => 'nullable|exists:budget_plans,id',
            'budget_item_id' => 'nullable|exists:budget_items,id',
            'amount' => 'required|numeric|min:0.01',
            'quantity' => 'nullable|numeric|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|in:cash,mpesa,bank_transfer,cheque,card,online',
            'receipt_number' => 'nullable|string',
            'vendor' => 'nullable|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['reference'] = Expense::generateReference();
        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();
        $validated['quantity'] = $validated['quantity'] ?? 1;

        $expense = Expense::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_created',
            'description' => "Created expense: {$expense->title} - " . number_format($expense->amount, 2),
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return redirect()->route('finance.expenses.show', $expense->id)
            ->with('success', 'Expense recorded successfully.');
    }

    /**
     * Show expense details
     */
    public function showExpense(Expense $expense)
    {
        $expense->load(['category', 'budgetPlan', 'budgetItem', 'createdBy', 'approvedBy']);

        return view('staff.finance.expenses.show', compact('expense'));
    }

    /**
     * Show edit expense form
     */
    public function editExpense(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->route('finance.expenses.show', $expense->id)
                ->with('warning', 'Only pending expenses can be edited.');
        }

        $categories = ExpenseCategory::active()->get();
        $budgets = BudgetPlan::where('status', 'active')->get();
        $paymentMethods = Expense::getPaymentMethods();

        return view('staff.finance.expenses.edit', compact(
            'expense', 'categories', 'budgets', 'paymentMethods'
        ));
    }

    /**
     * Update expense
     */
    public function updateExpense(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('warning', 'Only pending expenses can be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'budget_plan_id' => 'nullable|exists:budget_plans,id',
            'budget_item_id' => 'nullable|exists:budget_items,id',
            'amount' => 'required|numeric|min:0.01',
            'quantity' => 'nullable|numeric|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|in:cash,mpesa,bank_transfer,cheque,card,online',
            'receipt_number' => 'nullable|string',
            'vendor' => 'nullable|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['quantity'] = $validated['quantity'] ?? 1;

        $expense->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_updated',
            'description' => "Updated expense: {$expense->title}",
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return redirect()->route('finance.expenses.show', $expense->id)
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Approve expense
     */
    public function approveExpense(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('warning', 'Only pending expenses can be approved.');
        }

        $expense->approve(auth()->user());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_approved',
            'description' => "Approved expense: {$expense->title}",
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return back()->with('success', 'Expense approved successfully.');
    }

    /**
     * Reject expense
     */
    public function rejectExpense(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('warning', 'Only pending expenses can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $expense->reject(auth()->user());
        $expense->update(['notes' => ($expense->notes ?? '') . "\nRejection: " . $request->rejection_reason]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_rejected',
            'description' => "Rejected expense: {$expense->title}",
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return back()->with('success', 'Expense rejected.');
    }

    /**
     * Mark expense as paid
     */
    public function markExpensePaid(Request $request, Expense $expense)
    {
        if (!in_array($expense->status, ['pending', 'approved'])) {
            return back()->with('warning', 'Only pending or approved expenses can be marked as paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,mpesa,bank_transfer,cheque,card,online',
            'payment_date' => 'required|date',
        ]);

        $expense->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'expense_date' => $validated['payment_date'],
        ]);

        // Update budget spent amount
        if ($expense->budgetPlan) {
            $expense->budgetPlan->recalculateTotals();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_paid',
            'description' => "Marked expense as paid: {$expense->title}",
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return back()->with('success', 'Expense marked as paid.');
    }

    /**
     * Delete expense
     */
    public function deleteExpense(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('warning', 'Only pending expenses can be deleted.');
        }

        $expense->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'expense_deleted',
            'description' => "Deleted expense",
            'metadata' => ['expense_id' => $expense->id],
        ]);

        return redirect()->route('finance.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    // ==================== COMPARISON & REPORTS ====================

    /**
     * Budget vs Actual comparison
     */
    public function comparison(Request $request)
    {
        // Get active budgets with their items and expenses
        $budgets = BudgetPlan::with(['items.category', 'items.expenses' => function($q) {
            $q->where('status', 'paid');
        }])->whereIn('status', ['active', 'closed'])->get();

        $comparisonData = [];

        foreach ($budgets as $budget) {
            $items = [];
            $totalBudgeted = 0;
            $totalSpent = 0;

            foreach ($budget->items as $item) {
                $spent = $item->expenses->sum('amount');
                $totalBudgeted += $item->budgeted_amount;
                $totalSpent += $spent;

                $items[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category?->name,
                    'budgeted' => $item->budgeted_amount,
                    'spent' => $spent,
                    'balance' => $item->budgeted_amount - $spent,
                    'percentage' => $item->budgeted_amount > 0
                        ? round(($spent / $item->budgeted_amount) * 100, 1)
                        : 0,
                ];
            }

            $comparisonData[] = [
                'id' => $budget->id,
                'name' => $budget->name,
                'period' => $budget->getPeriodLabel(),
                'type' => $budget->type,
                'total_budget' => $budget->total_budget,
                'total_spent' => $totalSpent,
                'total_balance' => $budget->total_budget - $totalSpent,
                'spent_percentage' => $budget->total_budget > 0
                    ? round(($totalSpent / $budget->total_budget) * 100, 1)
                    : 0,
                'items' => $items,
            ];
        }

        // Summary stats
        $totalBudgeted = array_sum(array_column($comparisonData, 'total_budget'));
        $totalSpent = array_sum(array_column($comparisonData, 'total_spent'));
        $totalBalance = $totalBudgeted - $totalSpent;

        return view('staff.finance.budgets.comparison', compact('comparisonData', 'totalBudgeted', 'totalSpent', 'totalBalance'));
    }

    /**
     * Budget summary/dashboard
     */
    public function summary()
    {
        // Get current active monthly budget
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyBudget = BudgetPlan::where('type', 'monthly')
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->where('status', 'active')
            ->with('items.category')
            ->first();

        // Get current active yearly budget
        $yearlyBudget = BudgetPlan::where('type', 'yearly')
            ->where('year', $currentYear)
            ->where('status', 'active')
            ->with('items.category')
            ->first();

        // Recent expenses
        $recentExpenses = Expense::with('category')
            ->orderBy('expense_date', 'desc')
            ->take(10)
            ->get();

        // Pending approvals
        $pendingExpenses = Expense::pending()
            ->with('category')
            ->orderBy('expense_date', 'desc')
            ->take(5)
            ->get();

        // Category breakdown for current month
        $categoryTotals = Expense::whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->where('status', 'paid')
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        // Year-to-date expenses
        $ytdExpenses = Expense::whereYear('expense_date', $currentYear)
            ->where('status', 'paid')
            ->sum('amount');

        // Year-to-date budget
        $ytdBudget = BudgetPlan::where('type', 'yearly')
            ->where('year', $currentYear)
            ->where('status', 'active')
            ->value('total_budget') ?? 0;

        return view('staff.finance.budgets.summary', compact(
            'monthlyBudget',
            'yearlyBudget',
            'recentExpenses',
            'pendingExpenses',
            'categoryTotals',
            'ytdExpenses',
            'ytdBudget',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Expense report/analytics
     */
    public function expenseReport(Request $request)
    {
        $query = Expense::with('category');

        // Default: last 12 months
        $from = $request->date_from ?? now()->subMonths(12)->startOfMonth()->format('Y-m-d');
        $to = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');

        $query->whereBetween('expense_date', [$from, $to]);

        // Monthly trend
        $monthlyData = Expense::whereBetween('expense_date', [$from, $to])
            ->where('status', 'paid')
            ->selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // By category
        $byCategory = Expense::whereBetween('expense_date', [$from, $to])
            ->where('status', 'paid')
            ->selectRaw('expense_category_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        // By status
        $byStatus = Expense::selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        // Total stats
        $totalExpenses = $query->where('status', 'paid')->sum('amount');
        $totalPending = $query->where('status', 'pending')->sum('amount');
        $totalApproved = $query->where('status', 'approved')->sum('amount');

        return view('staff.finance.expenses.report', compact(
            'monthlyData', 'byCategory', 'byStatus',
            'totalExpenses', 'totalPending', 'totalApproved',
            'from', 'to'
        ));
    }
}
