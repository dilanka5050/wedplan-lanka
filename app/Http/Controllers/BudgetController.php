<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $wedding    = $request->user()->wedding;
        $budgets    = $wedding->budgets()->with('category')->get();
        $categories = \App\Models\BudgetCategory::orderBy('sort_order')->get();
        $summary    = $wedding->budget_summary;
        $user       = $request->user();
        $budgetLimit   = $user->getLimit('budgets');  // null = unlimited (pro)
        $budgetCount   = $budgets->count();
        $limitReached  = $budgetLimit !== null && $budgetCount >= $budgetLimit;

        return view('budgets.index', compact('budgets', 'categories', 'summary', 'budgetLimit', 'budgetCount', 'limitReached'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'budget_category_id' => 'nullable|exists:budget_categories,id',
            'estimated_cost' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $request->user()->wedding->budgets()->create($validated);
        return redirect()->back()->with('success', 'Budget item added!');
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'item_name' => 'sometimes|string|max:255',
            'estimated_cost' => 'sometimes|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);
        return redirect()->back()->with('success', 'Budget updated!');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->back()->with('success', 'Budget item deleted!');
    }
}
