<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        $type = $request->get('type');

        $query = Transaction::with('category')
            ->inMonth($month, $year)
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $transactions = $query->get();

        $totalIncome = Transaction::inMonth($month, $year)->income()->sum('amount');
        $totalExpense = Transaction::inMonth($month, $year)->expense()->sum('amount');

        return Inertia::render('Transactions/Index',[
            'header' => "Dashboard",
            'transactions' => $transactions,
            'month' => $month,
            'year' => $year,
            'type' => $type,
            'totalIncome' => $totalIncome, 
            'totalExpense' => $totalExpense
        ]);
    }

    public function create()
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return Inertia::render('Transactions/Create', [
            'header' => 'Nova Transacao',
            'backUrl' => route('transactions.index'),
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date',
        ]);

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transacao adicionada!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('category');

        return Inertia::render('Transactions/Show', [
            'header' => $transaction->description,
            'backUrl' => route('transactions.index'),
            'transaction' => $transaction,
        ]);
    }

    public function edit(Transaction $transaction)
    {
        $expenseCategories = Category::expense()->orderBy('name')->get();
        $incomeCategories = Category::income()->orderBy('name')->get();

        return Inertia::render('Transactions/Edit', [
            'header'            => "Transação",
            'backUrl'           => route('transactions.index'),
            'transaction'       => $transaction,
            'expenseCategories' => $expenseCategories,
            'incomeCategories'  => $incomeCategories
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transacao atualizada!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transacao excluida!');
    }
}
