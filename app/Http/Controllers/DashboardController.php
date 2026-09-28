<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $totalIncome = Transaction::inMonth($month, $year)->income()->sum('amount');
        $totalExpense = Transaction::inMonth($month, $year)->expense()->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $recentTransactions = Transaction::with('category')
            ->inMonth($month, $year)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $budgetAlerts = Budget::with('category')
            ->forMonth($month, $year)
            ->get()
            ->filter(fn ($b) => $b->spent_percentage >= 80)
            ->values();

        // Build trend data inline
        $trendData = $this->buildTrendData($month, $year);

        // Build category breakdown inline
        $categoryData = $this->buildCategoryData($month, $year);

        return Inertia::render('Dashboard/Index', [
            'month' => $month,
            'year' => $year,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'recentTransactions' => $recentTransactions,
            'budgetAlerts' => $budgetAlerts,
            'trendData' => $trendData,
            'categoryData' => $categoryData,
            'header' => 'Dashboard'
        ]);
    }

    private function buildTrendData(int $month, int $year): array
    {
        $labels = [];
        $income = [];
        $expense = [];

        foreach ($this->lastSixMonths($month, $year) as $d) {
            $labels[] = $d->translatedFormat('M/y');
            $income[] = Transaction::inMonth($d->month, $d->year)->income()->sum('amount');
            $expense[] = Transaction::inMonth($d->month, $d->year)->expense()->sum('amount');
        }

        return compact('labels', 'income', 'expense');
    }

    /**
     * Os 6 meses que terminam no mês selecionado, do mais antigo para o mais recente.
     *
     * @return array<int, Carbon>
     */
    private function lastSixMonths(int $month, int $year): array
    {
        $end = Carbon::create($year, $month, 1);

        return array_map(fn ($i) => $end->copy()->subMonths($i), range(5, 0));
    }

    private function buildCategoryData(int $month, int $year): array
    {
        return Transaction::select('category_id', DB::raw('SUM(amount) as total'))
            ->inMonth($month, $year)
            ->expense()
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn ($t) => [
                'name' => $t->category->name,
                'total' => $t->total,
                'color' => $t->category->color,
            ])
            ->values()
            ->toArray();
    }

    public function summary(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        return response()->json([
            'income' => Transaction::inMonth($month, $year)->income()->sum('amount'),
            'expense' => Transaction::inMonth($month, $year)->expense()->sum('amount'),
        ]);
    }

    public function spendingTrend(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $data = collect();
        foreach ($this->lastSixMonths($month, $year) as $d) {
            $data->push([
                'label' => $d->translatedFormat('M/y'),
                'expense' => Transaction::inMonth($d->month, $d->year)->expense()->sum('amount'),
            ]);
        }

        return response()->json($data);
    }

    public function categoryBreakdown(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $data = Transaction::select('category_id', DB::raw('SUM(amount) as total'))
            ->inMonth($month, $year)
            ->expense()
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn ($t) => [
                'name' => $t->category->name,
                'total' => $t->total,
                'color' => $t->category->color,
            ]);

        return response()->json($data);
    }
}
