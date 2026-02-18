<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $budgets = Budget::with('category')
            ->forMonth($month, $year)
            ->get();

        $totalBudgeted = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent');

        return view('budgets.index', compact('budgets', 'month', 'year', 'totalBudgeted', 'totalSpent'));
    }

    public function create(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $existingCategoryIds = Budget::forMonth($month, $year)->pluck('category_id');

        $categories = Category::expense()
            ->whereNotIn('id', $existingCategoryIds)
            ->orderBy('name')
            ->get();

        return view('budgets.create', compact('categories', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|integer|min:1',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        Budget::updateOrCreate(
            [
                'category_id' => $validated['category_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            ['amount' => $validated['amount']]
        );

        return redirect()->route('budgets.index', [
            'month' => $validated['month'],
            'year' => $validated['year'],
        ])->with('success', 'Orcamento definido!');
    }

    public function edit(Budget $budget)
    {
        $budget->load('category');

        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index', [
            'month' => $budget->month,
            'year' => $budget->year,
        ])->with('success', 'Orcamento atualizado!');
    }

    public function destroy(Budget $budget)
    {
        $month = $budget->month;
        $year = $budget->year;

        $budget->delete();

        return redirect()->route('budgets.index', [
            'month' => $month,
            'year' => $year,
        ])->with('success', 'Orcamento excluido!');
    }
}
